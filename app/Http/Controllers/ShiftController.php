<?php

namespace App\Http\Controllers;
use App\Models\Shift;
use Illuminate\Support\Facades\Validator;
use App\Models\Assignment;
use App\Models\Branch;
use App\Models\User;
use App\Http\Resources\ShiftResource;

use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
        $validation = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id',
            'id_branch' => 'required|exists:branches,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time'
        ],
        [
            'id_branch.required' => 'Veuillez entrer une succursale.',
            'id_branch.exists' => 'La succursale sélectionnée n\'existe pas.',
            'start_time.required' => 'La date et l\'heure de début sont obligatoires.',
            'end_time.required' => 'La date et l\'heure de fin sont obligatoires.',
            'end_time.after' => 'La date et l\'heure de fin doivent être après la date et l\'heure de début.',
        ]);
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        }

        $contenuFormulaire = $validation->validated();

        $availability = $this->fetchAvailability($contenuFormulaire['id_user'], $contenuFormulaire['id_branch'], $contenuFormulaire['date'], $contenuFormulaire['start_time'] . ":00", $contenuFormulaire['end_time']. ":00");
        if (!$availability['available']) {
            $errorMessage = '';
            switch ($availability['reason']) {
                case 'userNotFound':
                    $errorMessage = 'L\'employé est introuvable.';
                    break;
                case 'overlap':
                    $errorMessage = 'Il y a un chevauchement avec un quart assigné à cet employé.';
                    break;
                case 'availability':
                    $errorMessage = 'Le quart ne correspond pas aux disponibilités de l\'employé.';
                    break;
                case 'timeOff':
                    $errorMessage = 'Un congé empêche l\'employé d\'être assigné à ce quart.';
                    break;
                default:
                    $errorMessage = 'Une erreur inconnue est survenue.';
                    break;
            }
            return back()->withErrors(['erreur' => $errorMessage])->withInput();
        }

        $shift = new Shift();
        $shift->id_branch = $contenuFormulaire['id_branch'];
        $shift->date = $contenuFormulaire['date'];
        $shift->start_time = $contenuFormulaire['start_time'];
        $shift->end_time = $contenuFormulaire['end_time'];
        $shift->save();

        $assignment = new Assignment();
        $assignment->id_user = $contenuFormulaire['id_user'];
        $assignment->id_shift = $shift->id;
        $assignment->assigned_at = now();
        $assignment->save();

        return redirect()->route('shifts.schedule', ['branch' => $contenuFormulaire['id_branch'], 'week' => $contenuFormulaire['date']])->with('succes', 'Le shift a été créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $shift = Shift::find($id);


        if ($request->routeIs('shiftAPI.show')) {
            if (empty($shift)){
                return response()->json(['ERREUR' => 'Le shift demandé est introuvable.'], 400);
            }
            return response()->json(new ShiftResource($shift), 200);
        } else {
            // Show non-api
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
         $validation = Validator::make($request->all(), [
            'id_shift' => 'required|exists:shifts,id',
            'id_branch' => 'required|exists:branches,id',
            'date' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time'
        ],
        [
            'id_user.required' => 'Veuillez entrer un employé.',
            'start_time.required' => 'La date et l\'heure de début sont obligatoires.',
            'end_time.required' => 'La date et l\'heure de fin sont obligatoires.',
            'end_time.after' => 'La date et l\'heure de fin doivent être après la date et l\'heure de début.',
        ]);
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        }
        $contenuFormulaire = $validation->validated();

        $availability = $this->fetchAvailability($contenuFormulaire['id_user'], $contenuFormulaire['id_branch'], $contenuFormulaire['date'], $contenuFormulaire['start_time'] . ":00", $contenuFormulaire['end_time'] . ":00", $contenuFormulaire['id_shift']);
        if (!$availability['available']) {
            $errorMessage = '';
            switch ($availability['reason']) {
                case 'userNotFound':
                    $errorMessage = 'L\'employé est introuvable.';
                    break;
                case 'overlap':
                    $errorMessage = 'Il y a un chevauchement avec un quart assigné à cet employé.';
                    break;
                case 'availability':
                    $errorMessage = 'Le quart ne correspond pas aux disponibilités de l\'employé.';
                    break;
                case 'timeOff':
                    $errorMessage = 'Un congé empêche l\'employé d\'être assigné à ce quart.';
                    break;
                default:
                    $errorMessage = 'Une erreur inconnue est survenue.';
                    break;
            }
            return back()->withErrors(['erreur' => $errorMessage])->withInput();
        }

        $shift = Shift::findOrFail($request->id_shift);
        $shift->start_time = $contenuFormulaire['start_time'];
        $shift->end_time = $contenuFormulaire['end_time'];
        $shift->save();


        if($contenuFormulaire['id_user'] != $shift->mostRecentAssignment->id_user) {
            $assignment = new Assignment();
            $assignment->id_user = $contenuFormulaire['id_user'];
            $assignment->id_shift = $shift->id;
            $assignment->assigned_at = now();
            $assignment->save();
        }


        return redirect()->route('shifts.schedule', ['branch' => $contenuFormulaire['id_branch'], 'week' => $contenuFormulaire['date']])
        ->with('succes', 'Le shift ' . $contenuFormulaire['id_shift'] . ' a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //On supprime le shift
        $shift = Shift::findOrFail($request->id_shift);
        $assignments = Assignment::where('id_shift', $shift->id)->get();
        if($assignments) {
            foreach ($assignments as $assignment) {
                $assignment->delete();
            }
        }

        $shift->delete();

        return redirect()->route('shifts.schedule', ['branch' => $request->id_branch, 'week' => $request->date])->with('succes', 'Le shift a été supprimé avec succès.');
    }

    public function scheduleByWeek(Request $request, $id_branch, $week)
    {
        //On parse la date de $week
        try {
            $weekDate = new \DateTime($week);
        } catch (\Exception $e) {
            $weekDate = new \DateTime(); // Default to current date if invalid
        }

        if ($weekDate->format('w') == 0) {
            $startOfWeek = $weekDate;
        } else {
            $startOfWeek = $weekDate->modify('last sunday');
        }
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days')->setTime(23, 59, 59);

        $branch = Branch::find($id_branch);
        if (!$branch or !$branch->is_actif) {
            $branch = Branch::where('is_actif', 1)->first();
        }

        $shifts = Shift::where('id_branch', $branch->id)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();


        $users = User::where('active', 1)->get();



        return view('schedule.schedule', [
            'shifts' => $shifts,
            'branch' => $branch,
            'week' => $startOfWeek->format('Y-m-d'),
            'branches' => Branch::where('is_actif', 1)->get(),
            'users' => $users,
        ]);
    }
    
    public function scheduleByWeekByUserAll(Request $request, $week, $user)
    {
        //Cette fonction est appelée par l'API pour afficher l'horaire d'un employé par semaine
        $validation = Validator::make(['week' => $week, 'user' => $user], [
            'week' => 'required|date',
            'user' => 'required|exists:users,id'
        ],
        [
            'week.required' => 'Veuillez entrer une date.',
            'week.date' => 'La date entrée n\'est pas valide.',
            'user.required' => 'Veuillez entrer un employé.',
            'user.exists' => 'L\'employé sélectionné n\'existe pas.',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $contenuFormulaire = $validation->validated();
        $week = $contenuFormulaire['week'];
        $user = $contenuFormulaire['user'];

        if((new \DateTime($week))->format('w') == 0) {
            $startOfWeek = (new \DateTime($week));
        } else {
            $startOfWeek = (new \DateTime($week))->modify('last sunday');
        }
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days')->setTime(23, 59, 59);

        $shifts = Shift::whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);

        $myShifts = [];
        foreach ($shifts->get() as $shift) {
            if($shift->mostRecentAssignment->id_user == $user) {
                array_push($myShifts, $shift);
            }
        }
        return response()->json(['data' => $myShifts], 200);
    }

    public function scheduleByWeekByUser(Request $request, $id_branch, $week, $user)
    {
        //Cette fonction est appelée par l'API pour afficher l'horaire d'un employé par semaine
        $validation = Validator::make(['id_branch' => $id_branch, 'week' => $week, 'user' => $user], [
            'id_branch' => 'required|exists:branches,id',
            'week' => 'required|date',
            'user' => 'required|exists:users,id'
        ],
        [
            'id_branch.required' => 'Veuillez entrer une succursale.',
            'id_branch.exists' => 'La succursale sélectionnée n\'existe pas.',
            'week.required' => 'Veuillez entrer une date.',
            'week.date' => 'La date entrée n\'est pas valide.',
            'user.required' => 'Veuillez entrer un employé.',
            'user.exists' => 'L\'employé sélectionné n\'existe pas.',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $contenuFormulaire = $validation->validated();
        $id_branch = $contenuFormulaire['id_branch'];
        $week = $contenuFormulaire['week'];
        $user = $contenuFormulaire['user'];

        if((new \DateTime($week))->format('w') == 0) {
            $startOfWeek = (new \DateTime($week));
        } else {
            $startOfWeek = (new \DateTime($week))->modify('last sunday');
        }
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days')->setTime(23, 59, 59);

        $shifts = Shift::where('id_branch', $id_branch)
            ->where('id_branch', $id_branch)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);

        $myShifts = [];
        foreach ($shifts->get() as $shift) {
            if($shift->mostRecentAssignment->id_user == $user) {
                array_push($myShifts, $shift);
            }
        }


        return response()->json(['data' => $myShifts], 200);
    }

    //Check si un employé particulier est disponible pour un shift
    public function checkAvailability(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_branch = $request->input('id_branch');
        //id_shift peut être null si on veut vérifier la disponibilité d'un employé pour un shift qui n'existe pas encore
        //S'il n'est pas null, ça veut dire qu'on modifie le shift. Dans ce cas, on ne considère pas le shift pour l'overlap
        $id_shift = $request->input('id_shift');
        $date = $request->input('date');
        $startTime = $request->input('startTime');
        $endTime = $request->input('endTime');

        $date = (new \DateTime($date))->format('Y-m-d');
        $startTime = substr($startTime, 0, 2) . ':' . substr($startTime, 2, 2) . ':00';
        $endTime = substr($endTime, 0, 2) . ':' . substr($endTime, 2, 2) . ':00';

        $availability = $this->fetchAvailability($id_user, $id_branch, $date, $startTime, $endTime, $id_shift);
        if ($availability['available']) {
            return response()->json($availability, 200);
        } else {
            return response()->json($availability, 400);
        }
    }

    //Pour la vérification dans le backend, si on bypass la vérification dans le frontend (call d'API)
    private function fetchAvailability($id_user, $id_branch, $date, $startTime, $endTime, $id_shift=null)
    {
        $date = (new \DateTime($date))->format('Y-m-d');
        //$startTime = substr($startTime, 0, 2) . ':' . substr($startTime, 2, 2);
        //$endTime = substr($endTime, 0, 2) . ':' . substr($endTime, 2, 2);

        $user = User::find($id_user);
        if (!$user) {
            return ['available' => false, 'reason' => 'userNotFound'];
        }

        $shifts = Shift::where('id_branch', $id_branch)
        ->where('date', $date)
        ->whereHas('mostRecentAssignment', function ($query) use ($id_user) {
            $query->where('id_user', $id_user);
        })->get();

        if ($shifts->isNotEmpty()) {
            foreach ($shifts as $shift) {
                if($startTime < $shift->end_time && $endTime > $shift->start_time && $shift->id != $id_shift){
                    return ['available' => false, 'reason' => 'overlap'];
                }
            }
        }

        #Check si les disponibilités de l'employé sont compatibles
        $dayOfWeek = (new \DateTime($date))->format('w');
        $availability = $user->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        if (!$availability || $startTime < $availability->start_time || $endTime > $availability->end_time) {
            return['available' => false, 'reason' => 'availability', 'day' => $dayOfWeek, 'start_time' => $availability->start_time ?? null, 'end_time' => $availability->end_time ?? null];
        }

        #Filtrer les employés qui ont des demandes de congé acceptées
        $timeOffRequests = $user->acceptedTimeOffRequests()
            ->where('date_start', '<=', $date)
            ->where('date_end', '>=', $date)
            ->get();
        if (!$timeOffRequests->isEmpty()) {
            foreach ($timeOffRequests as $timeOffRequest) {
                if ($startTime < $timeOffRequest->end_time && $endTime > $timeOffRequest->start_time) {
                    return ['available' => false, 'reason' => 'timeOff'];
                }
            }
        }

        return ['available' => true];
    }

    public function getFutureShifts(Request $request, $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ],
        [
            'id.required' => 'Veuillez entrer un employé.',
            'id.exists' => 'L\'employé sélectionné n\'existe pas.',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $contenuFormulaire = $validation->validated();
        $id = $contenuFormulaire['id'];

        $shifts = Shift::where('date', '>=', now());

        $myShifts = [];
        foreach ($shifts->get() as $shift) {
            if($shift->mostRecentAssignment->id_user == $id) {
                array_push($myShifts, $shift);
            }
        }

        return response()->json(['data' => $myShifts], 200);
    }

    public function getNextShift(Request $request, $id) {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ],
        [
            'id.required' => 'Veuillez entrer un employé.',
            'id.exists' => 'L\'employé sélectionné n\'existe pas.',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $contenuFormulaire = $validation->validated();
        $id = $contenuFormulaire['id'];

        $shift = Shift::where('date', '>=', now())->orderBy('date', 'asc')->first();

        return response()->json(['shift' => $shift], 200);
    }

    public function getFutureShiftsNoRequests(Request $request, $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ],
        [
            'id.required' => 'Veuillez entrer un employé.',
            'id.exists' => 'L\'employé sélectionné n\'existe pas.',
        ]);
        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }
        $contenuFormulaire = $validation->validated();
        $id = $contenuFormulaire['id'];

        $shifts = Shift::where('date', '>=', now())->get();
        $myShifts = [];
        foreach ($shifts as $shift) {
            $assignment = $shift->mostRecentAssignment;
            if($assignment->id_user == $id) {
                $hasReplacementRequest = false;
                $replacementRequests = $assignment->replacementRequests;
                if(!$replacementRequests->isEmpty()) {
                    foreach ($replacementRequests as $replacementRequest) {
                        if ($replacementRequest->replacementRequestType->name == 'En attente') {
                            $hasReplacementRequest = true;
                            break;
                        }
                    }
                }
                if (!$hasReplacementRequest) {
                    array_push($myShifts, [
                        'id' => $shift->id,
                        'date' => $shift->date,
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                        'id_branch' => $shift->branch->id,
                    ]);
                }
            }
        }


        return response()->json(['data' => $myShifts], 200);
    }
}
