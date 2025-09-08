<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeOffRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\TimeOffRequestType;

class TimeOffRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('timeOffRequestAPI.index')) {
            $timeOffRequests = TimeOffRequest::all();
            return response()->json($timeOffRequests);
        } else if ($request->routeIs('timeoff.index')) {
            return view('timeoff/timeoffs', [
                'timeoffs' => TimeOffRequest::All()
            ]);
        }
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
        $validation = Validator::make($request->all(), [
            // Règles de validation
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'hour_start' => 'required|date_format:H:i',
            'hour_end' => 'required|date_format:H:i',
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:time_off_request_types,id',
        ], [
            // Messages personnalisés
            'date_start.required' => 'La date de début est obligatoire.',
            'date_start.date' => 'La date de début doit être une date valide.',
            'date_end.required' => 'La date de fin est obligatoire.',
            'date_end.date' => 'La date de fin doit être une date valide.',
            'date_end.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'hour_start.required' => 'L\’heure de début est obligatoire.',
            'hour_start.date_format' => 'L\’heure de début doit être au format HH:MM.',
            'hour_end.required' => 'L\’heure de fin est obligatoire.',
            'hour_end.date_format' => 'L\’heure de fin doit être au format HH:MM.',
            'user_id.required' => 'L\’utilisateur est requis.',
            'user_id.exists' => 'L\’utilisateur sélectionné n’existe pas.',
            'type_id.required' => 'Le type de congé est requis.',
            'type_id.exists' => 'Le type de congé sélectionné n’existe pas.',
        ]);

        // Règle personnalisée : l’heure de fin doit être après l’heure de début si les dates sont identiques
        if (
            !$validation->fails() &&
            $request->input('date_start') === $request->input('date_end') &&
            $request->input('hour_end') <= $request->input('hour_start')
        ) {
            $validation->after(function ($validator) {
                $validator->errors()->add('hour_end', 'L\’heure de fin doit être après l\’heure de début si la date est la même.');
            });
        }

        // Vérifie les erreurs

        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }else{
            $requestConge = new TimeOffRequest();
            $requestConge->date_start = $request->date_start;
            $requestConge->date_end = $request->date_end;
            $requestConge->hour_start = $request->hour_start;
            $requestConge->hour_end = $request->hour_end;
            $requestConge->user_id = $request->user_id;
            $requestConge->type_id = $request->type_id;
            $requestConge->save();
            return response()->json(['REUSSITE' => 'La demande de conge a été ajouté.'], 200);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $timeOffRequests = TimeOffRequest::findOrFail($id);
        return response()->json($timeOffRequests);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            // Règles de validation
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'hour_start' => 'required|date_format:H:i',
            'hour_end' => 'required|date_format:H:i',
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:time_off_request_types,id',
        ], [
            // Messages personnalisés
            'date_start.required' => 'La date de début est obligatoire.',
            'date_start.date' => 'La date de début doit être une date valide.',
            'date_end.required' => 'La date de fin est obligatoire.',
            'date_end.date' => 'La date de fin doit être une date valide.',
            'date_end.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'hour_start.required' => 'L\’heure de début est obligatoire.',
            'hour_start.date_format' => 'L\’heure de début doit être au format HH:MM.',
            'hour_end.required' => 'L\’heure de fin est obligatoire.',
            'hour_end.date_format' => 'L\’heure de fin doit être au format HH:MM.',
            'user_id.required' => 'L\’utilisateur est requis.',
            'user_id.exists' => 'L\’utilisateur sélectionné n’existe pas.',
            'type_id.required' => 'Le type de congé est requis.',
            'type_id.exists' => 'Le type de congé sélectionné n’existe pas.',
        ]);

        // Règle personnalisée : l’heure de fin doit être après l’heure de début si les dates sont identiques
        if (
            !$validation->fails() &&
            $request->input('date_start') === $request->input('date_end') &&
            $request->input('hour_end') <= $request->input('hour_start')
        ) {
            $validation->after(function ($validator) {
                $validator->errors()->add('hour_end', 'L\’heure de fin doit être après l\’heure de début si la date est la même.');
            });
        }

        // Vérifie les erreurs

        if ($validation->fails()) {
            return response()->json(['ERREUR' => $validation->errors()], 400);
        }else{
            $requestConge =  TimeOffRequest::findOrFail($id);
            $requestConge->date_start = $request->date_start;
            $requestConge->date_end = $request->date_end;
            $requestConge->hour_start = $request->hour_start;
            $requestConge->hour_end = $request->hour_end;
            $requestConge->user_id = $request->user_id;
            $requestConge->type_id = $request->type_id;
            $requestConge->save();
            return response()->json(['REUSSITE' => 'La demande de conge a été modifie.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    if (TimeOffRequest::destroy($id))
        return response()->json(['REUSSITE' => 'La demande de conge a été supprime.'], 200);

    return response()->json(['REUSSITE' => 'La demande de conge n\'a pas été supprime.'], 400);
    }

    public function updateStatus(Request $request, TimeOffRequest $timeoff) {
        $request->validate([
            'status' => 'required|string|in:Approuvée,Refusée,Annulée,Expirée',
        ]);

        $type = TimeOffRequestType::where('name', $request->status)->firstOrFail();
        $timeoff->type_id = $type->id;
        $timeoff->save();

        return response()->json(['status' => $request->status]);
    }

public function getByUser($user_id) {
    $requests = TimeOffRequest::where('user_id', $user_id)->get();

    $formatted = $requests->map(function ($r) {
        return [
            'id' => $r->id,
            'date_start' => $r->date_start,
            'date_end' => $r->date_end,
            'hour_start' => \Carbon\Carbon::parse($r->hour_start)->format('H:i'),
            'hour_end' => \Carbon\Carbon::parse($r->hour_end)->format('H:i'),
            'user_id' => $r->user_id,
            'type_id' => $r->type_id
        ];
    });

    return response()->json([
        'data' => $formatted
    ]);
}

}


