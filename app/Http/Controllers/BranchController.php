<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // For the api route
        if($request->routeIs('indexApi')){
            $branches = Branch::where('is_actif', true)->get();
            return response()->json($branches);
        }

        $branches = Branch::all();
        return view('branches.index', ['branches' => $branches]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        {
            $validation = Validator::make($request->all(), [
                // Validation rules
                'name' => 'required|string|max:255',
                'civic_no' => 'required|numeric',
                'road' => 'required|string|max:255',
                'city' => 'required|string|max:255',
            ], [
                // Custom error messages
                'name.required' => 'Veuillez entrer un nom pour la succursale.',
                'civic_no.required' => 'Veuillez spécifier un numéro civique.',
                'road.required' => 'Veuillez entrer le nom de la rue.',
                'city.required' => 'Veuillez entrer la ville.',
            ]);
        
            if ($validation->fails()) {
                return back()->withErrors($validation->errors())->withInput();
            } else {
                $branch = new Branch();
                $branch->name = $request->name;
                $branch->civic_no = $request->civic_no;
                $branch->road = $request->road;
                $branch->city = $request->city;
        
                if ($branch->save()) {
                    session()->flash('succes', 'La succursale a été ajoutée avec succès.');
                } else {
                    session()->flash('error', 'L\'ajout de la succursale a échoué.');
                }
        
                return redirect()->route('branches.index');
            } 
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(request()->routeIs('showApi')){
            $branch = Branch::findOrFail($id);
            return response()->json($branch);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $validation = Validator::make($request->all(), [
            // Validation rules
            'name' => 'required|string|max:255',
            'civic_no' => 'required|numeric',
            'road' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ], [
            // Custom error messages
            'name.required' => 'Veuillez entrer un nom pour la succursale.',
            'civic_no.required' => 'Veuillez spécifier un numéro civique.',
            'road.required' => 'Veuillez entrer le nom de la rue.',
            'city.required' => 'Veuillez entrer la ville.',
        ]);
        
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        } else {
            $branch = Branch::find($id); // Assuming you pass $id from route or form hidden field
        
            if (!$branch) {
                session()->flash('error', 'Succursale introuvable.');
                return redirect()->route('branches.index');
            }
        
            $branch->name = $request->name;
            $branch->civic_no = $request->civic_no;
            $branch->road = $request->road;
            $branch->city = $request->city;
        
            if ($branch->save()) {
                session()->flash('succes', 'La modification de la succursale a bien fonctionné.');
            } else {
                session()->flash('error', 'La modification de la succursale n\'a pas fonctionné.');
            }
        
            return redirect()->route('branches.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        try{
            $branch->delete();
            session()->flash('succes', 'La succursale a été supprimée avec succès.');
        }
        catch (\Exception $e) {
            $branch->is_actif = false;
            $branch->save();
            session()->flash('alerte', 'La succursale est en cours d\'utilisation et a été désactivée au lieu d\'etre supprimé.');
        }

        return redirect()->route('branches.index');

    }
}
