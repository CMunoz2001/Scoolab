<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\materia;
use App\Models\directivos;
use App\Models\profesores;
use Illuminate\Support\Facades\Cookie;

class MateriasController extends Controller
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

        $profesores = profesores::all()->toArray();

        return view('add-materia', compact('profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $materia = materia::create([
            'nombre' => $request->input('nombre'),
            'profesor_id' => $request->input('profesor')
        ]);

        return redirect('materias');

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

        $materias = materia::with(['profesores'])->where('estado', 1)->get()->toArray();

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('indexMaterias', compact('materias','nameDir'));

        /* dd($materias); */
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
    public function update(Request $request)
    {
        $id = $request->input('updateButton');

        $materia = materia::find($id);

        $materia->nombre = $request->input('nombre');
        $materia->save();

        return redirect('materias');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function asd(Request $request){

        if ($request->has('editButton')) {

            $id = $request->input('editButton');

            $materia = materia::find($id)->toArray();

            return view('settings-materias', compact('materia'));
        }

        if ($request->has('deleteButton')) {
            $id = $request->input('deleteButton');
            $materia = materia::find($id);

        if ($materia) {
            $materia->estado = 2;
            $materia->save();
        }

            return redirect('materias');

        }

        if ($request->has('restore')) {
            $id = $request->input('restore');
            $materia = materia::find($id);

        if ($materia) {
            $materia->estado = 1;
            $materia->save();
        }

        return redirect('materias');

        }

    }

    public function eliminados(){

        $materias = materia::with(['profesores'])->where('estado', 2)->get()->toArray();

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('materias-deleted', compact('materias','nameDir'));

    }
    

}
