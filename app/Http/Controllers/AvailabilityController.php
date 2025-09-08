<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $id)
    {

        $availability = Availability::query()
        ->join('users', 'users.id', '=', 'availabilities.id_user')
        ->where('users.id', $id)
        ->select(
            'availabilities.*',
            'users.first_name as first_name',
            'users.last_name as last_name',
        )
        ->get()
        ->map(function ($item) {
            $days = [
                0 => 'Dimanche',
                1 => 'Lundi',
                2 => 'Mardi',
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
            ];
            $item->day_name = $days[$item->day_of_week] ?? 'Jour inconnu';
            return $item;
        });

        $user = User::find($id);

        if ($request->routeIs('api.availability.index')) {
            return response()->json([
                'availabilities' => $availability,
                'user' => $user,
            ]);
        }

        
        return view('availability.index', [
            "availabilities" => $availability,
            "user" => $user,
        ]);

    }

        /**
     * Display a listing of the resource.
     */
    public function indexAll(Request $request)
    {
        $availability = Availability::query()
        ->join('users', 'users.id', '=', 'availabilities.id_user')
        ->select(
            'availabilities.*',
            'users.first_name as first_name',
            'users.last_name as last_name',
        )->get()
        ->map(function ($item) {
            $days = [
                0 => 'Dimanche',
                1 => 'Lundi',
                2 => 'Mardi',
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
            ];
            $item->day_name = $days[$item->day_of_week] ?? 'Jour inconnu';
            return $item;
        });


        if ($request->routeIs('api.availability.indexAll')) {
            return response()->json([
                'availabilities' => $availability,
            ]);
        }


        return view('availability.indexall', [
            "availabilities" => $availability
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
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $availability = new Availability();
        $availability->id_user = $request->input('id_user');
        $availability->day_of_week = $request->input('day_of_week');
        $availability->start_time = $request->input('start_time');
        $availability->end_time = $request->input('end_time');
        $availability->save();

        return response()->json(['message' => 'Disponibilité ajoutée avec succès.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Availability $availability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Availability $availability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:availabilities,id',
            'id_user' => 'required|exists:users,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $availability = Availability::find($request->input('id'));
        if (!$availability) {
            return response()->json(['error' => 'Disponibilité introuvable.'], 404);
        }
        $availability->id_user = $request->input('id_user');
        $availability->day_of_week = $request->input('day_of_week');
        $availability->start_time = $request->input('start_time');
        $availability->end_time = $request->input('end_time');
        $availability->save();

        return response()->json(['message' => 'Disponibilité mise à jour avec succès.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $availability = Availability::where('id', $request->input('id'))->first();
        if ($availability) {
            $availability->delete();
            return response()->json(['message' => 'Disponibilité supprimée avec succès.']);
        } else {
            return response()->json(['message' => 'Disponibilité introuvable.'], 404);
        }
    }
}
