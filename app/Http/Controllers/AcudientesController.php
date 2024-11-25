<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\acudientes;
use App\Models\directivos;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;


class AcudientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $acudientes = acudientes::with(['estudiantes' => function ($query) {
            $query->where('estado', 1);
        }])->where('estado', 1)->get()->toArray();

        $correoDir = Cookie::get('remember_email');

        $directivo = directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];
        

        return view('indexAcudientes', compact('acudientes', 'nameDir'));

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
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->input('editButton');

        $acudiente = acudientes::find($id)->load(['estudiantes'])->toArray();


        if (isset($acudiente['imagen']) && !empty($acudiente['imagen'])) {
            $acudiente['imagen'] = 'data:image/jpeg;base64,' . base64_encode($acudiente['imagen']);
        } else {
            $acudiente['imagen'] = '../assets/img/avatars/default_acudiente.png';
        }

        if (isset($acudiente['estudiantes']) && is_array($acudiente['estudiantes'])) {
            foreach ($acudiente['estudiantes'] as &$estudiante) {
                if (isset($estudiante['imagen']) && !empty($estudiante['imagen'])) {
                    $estudiante['imagen'] = 'data:image/jpeg;base64,' . base64_encode($estudiante['imagen']);
                } else {
                    $estudiante['imagen'] = '../assets/img/avatars/default_estudiante.png';
                }
            }
        }

        return view('settings-acudiente', compact('acudiente'));


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

    $acudiente = acudientes::find($id);

    $acudiente->doc = $request->input('doc');
    $acudiente->nombre = $request->input('nombre');
    $acudiente->direccion = $request->input('direccion');
    $acudiente->telefono = $request->input('telefono');
    $acudiente->email = $request->input('email');
    $acudiente->password = $request->input('contrasena');

    if ($request->hasFile('picture')) {
        $imageData = file_get_contents($request->file('picture')->getRealPath());
        $acudiente->imagen = $imageData;
    }

    $acudiente->save();
    
    return redirect()->route('acudientes.index'); 
    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
