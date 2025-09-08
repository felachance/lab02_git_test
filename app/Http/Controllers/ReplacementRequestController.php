<?php

namespace App\Http\Controllers;

use App\Models\ReplacementRequest;
use App\Models\ReplacementRequestType;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shift;

function validate_status(string $status) {
    if (isset($params['status'])) {
        $replacementRequestType = ReplacementRequestType::where('name', $params['status'])->first();
        if ($replacementRequestType) {
            return true;
        } else {
            return false;
        }
    }

    return false;
}


class ReplacementRequestController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->routeIs('api.replacements.index')) {
            $params = $request->query();
            $filters = [];
            if (isset($params['status'])) {
                if(validate_status($params['status'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid status provided.',
                    ], 400);
                }
                $replacementRequestType = ReplacementRequestType::where('name', $params['status'])->first();
                if ($replacementRequestType) {
                    $filters['id_replacement_request_type'] = $replacementRequestType->id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid status provided.',
                    ], 400);
                }
            }

            $replacementRequests = ReplacementRequest::where($filters)
                ->join('replacement_request_types', 'replacement_requests.id_replacement_request_type', '=', 'replacement_request_types.id')
                ->select('replacement_requests.*',
                         'replacement_request_types.description as status_description',
                         'replacement_request_types.name as status')
                ->orderBy('created_at', 'desc')
                ->get();


            if($request->wantsJson()) {
                $jsonData = [];
                foreach ($replacementRequests as $replacementRequest) {
                    $assignment = $replacementRequest->assignment;
                    $user = $assignment->user;
                    $shift = $assignment->shift;
                    array_push($jsonData, [
                        'id' => $replacementRequest->id,
                        'description' => $replacementRequest->description,
                        'status' => $replacementRequest->status,
                        'status_description' => $replacementRequest->status_description,
                        'assignment' => $assignment,
                    ]);

                }
                return response()->json([
                    'success' => true,
                    'data' => $jsonData,
                ]);
            }

            return response()->view('replacements.replacements', [
                'replacements' => $replacementRequests,
            ]);
        }

        $replacementRequests = ReplacementRequest::join('replacement_request_types', 'replacement_requests.id_replacement_request_type', '=', 'replacement_request_types.id')
            ->select('replacement_requests.*',
                'replacement_request_types.description as status_description',
                'replacement_request_types.name as status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('replacements.index', [
            'replacements' => $replacementRequests,
        ]);
    }

    public function indexWithoutUser(Request $request, int $id)
    {
        $replacementRequests = ReplacementRequest::whereHas('replacementRequestType', function ($query) {
            $query->where('name', '=', 'En attente');
        })->get();


        $replacementRequestsWithoutUser = [];
        foreach ($replacementRequests as $replacementRequest) {
            $assignment = $replacementRequest->assignment;
            $user = $assignment->user;
            $shift = $assignment->shift;
            if ($assignment->user->id != $id) {
                array_push($replacementRequestsWithoutUser, [
                        'id' => $replacementRequest->id,
                        'description' => $replacementRequest->description,
                        'status' => $replacementRequest->status,
                        'status_description' => $replacementRequest->status_description,
                        'assignment' => $assignment,
                    ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $replacementRequestsWithoutUser,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'id_shift' => 'required|integer|exists:shifts,id',
        ]);

        $validatedData = $validator->validated();

        try {
            $shift = Shift::findOrFail($validatedData['id_shift']);

            $mostRecentAssignment = $shift->mostRecentAssignment;
            $replacementRequests = $mostRecentAssignment->replacementRequests;
            if($replacementRequests->isNotEmpty()) {
                foreach ($replacementRequests as $replacementRequest) {
                    if ($replacementRequest->id_replacement_request_type == 1) {
                        return response()->json([
                            'success' => false,
                            'message' => 'A replacement request already exists for this shift.',
                        ], 400);
                    }
                }
            }

            $replacementRequest = ReplacementRequest::create([
                'description' => $validatedData['description'] ?? null,
                'id_replacement_request_type' => 1,
                'id_assignment' => $mostRecentAssignment->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Replacement request created successfully.',
                'data' => $replacementRequest,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the replacement request. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $replacementRequest = ReplacementRequest::query()
            ->where('replacement_requests.id', $id)
            ->join('replacement_request_types', 'replacement_requests.id_replacement_request_type', '=', 'replacement_request_types.id')
            ->join('assignment', 'replacement_requests.id_assignment', '=', 'assignment.id')
            ->join('users', 'assignment.id_user', '=', 'users.id')
            ->select(
                'replacement_requests.*',
                'replacement_request_types.description as status_description',
                'replacement_request_types.name as status',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'assignment.id_shift'
            )
            ->first();

        if (!$replacementRequest) {
            return redirect()->route('replacements.index')
                ->with('error', 'Remplacement introuvable.');
        }

        $recentAssignment = Assignment::where('id_shift', $replacementRequest->id_shift)
            ->orderByDesc('assigned_at')
            ->first();

        $recentUser = null;
        if ($recentAssignment) {
            $recentUser = User::where('id', $recentAssignment->id_user)
                ->select('first_name', 'last_name')
                ->first();
        }

        return view('replacements.show', [
            'replacement' => $replacementRequest,
            'recentUser' => $recentUser,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReplacementRequest $replacementRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $replacementRequest = ReplacementRequest::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_assignment' => 'required|integer|exists:assignment,id',
        ]);

        try {
            $replacementRequest->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'assignment_id' => $validatedData['assignment_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Replacement request updated successfully.',
                'data' => $replacementRequest,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the replacement request. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $message = [];
        try {
            ReplacementRequest::destroy($id);
            $message = ['success' => 'Demande de remplacement supprimée avec succès.'];
        } catch (\Exception $e) {
            $message = ['error' => 'Une erreur est survenue lors de la suppression de la demande de remplacement. Veuillez réessayer.'];
        }
        return redirect()->route('replacements.index')->with($message);
    }

    public function getReplacementRequestsByUser(Request $request, int $userId)
    {
        $validation = Validator::make(['id' => $userId], [
            'id' => 'required|exists:users,id',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $assignments = Assignment::where('id_user', $userId)->pluck('id');
        $replacementRequests = ReplacementRequest::with('assignment')
            ->with('replacementRequestType')
            ->whereIn('id_assignment', $assignments)
            ->get();

        $jsonData = [];
        foreach ($replacementRequests as $replacementRequest) {
            $assignment = $replacementRequest->assignment;
            $shift = $assignment->shift;
            array_push($jsonData, [
                'id' => $replacementRequest->id,
                'description' => $replacementRequest->description,
                'status' => $replacementRequest->replacementRequestType->name,
                'date'=> $shift->date,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $jsonData,
        ]);
    }

    public function accept(Request $request, int $id, int $userId)
    {
        $validation = Validator::make(['id' => $id, 'userId' => $userId], [
            'id' => 'required|exists:replacement_requests,id',
            'userId' => 'required|exists:users,id',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }

        $replacementRequest = ReplacementRequest::findOrFail($id);
        if($replacementRequest->id_replacement_request_type != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Replacement request is not in a state that can be accepted.',
            ], 400);
        }
        $replacementRequest->id_replacement_request_type = 2; // Acceptée
        $replacementRequest->save();

        $assignment = new Assignment();
        $assignment->id_user = $userId;
        $assignment->id_shift = $replacementRequest->assignment->id_shift;
        $assignment->assigned_at = now();
        $assignment->save();

        return response()->json([
            'success' => true,
            'message' => 'Replacement request accepted successfully.',
            'data' => $assignment,
        ]);
    }

    public function cancel(Request $request, int $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:replacement_requests,id',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }

        $replacementRequest = ReplacementRequest::findOrFail($id);
        $replacementRequest->id_replacement_request_type = 4; // Annulée
        $replacementRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Replacement request canceled successfully.',
        ]);
    }

    public function getReplacementsEnAttente(Request $request, int $id) {
        $replacementRequests = ReplacementRequest::whereHas('replacementRequestType', function ($query) {
            $query->where('name', 'En attente');
        })->get();

        $replacementRequestsWithoutUser = [];
        foreach ($replacementRequests as $replacementRequest) {
            $assignment = $replacementRequest->assignment;
            if ($assignment->user->id != $id) {
                array_push($replacementRequestsWithoutUser, [
                        'id' => $replacementRequest->id,
                        'description' => $replacementRequest->description,
                        'status' => $replacementRequest->status,
                        'status_description' => $replacementRequest->status_description,
                        'assignment' => $assignment,
                    ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $replacementRequestsWithoutUser,
        ]);
    }
}
