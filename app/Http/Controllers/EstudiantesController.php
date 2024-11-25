<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\estudiantes;
use App\Models\curso;
use App\Models\directivos;
use App\Models\acudientes;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class EstudiantesController extends Controller
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

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        
        $cursos = Curso::all()->toArray();

        return view('indexMatriculas', compact('nameDir','cursos'));
    }

    public function newEstudiante(Request $request){
        
        $tipodoc = $request->input('tipodoc');
    $doc = $request->input('doc');
    $nombre = $request->input('nombre');
    $direccion = $request->input('direccion');
    $telefono = $request->input('telefono');
    $correo = $request->input('correo');
    $fechaNac = $request->input('fechaNac');
    $curso = $request->input('curso');
    $contrasena = bcrypt($request->input('contrasena'));

    $tipodocAcu = $request->input('tipodocAcu');
    $docAcud = $request->input('docAcud');
    $nombreAcud = $request->input('nombreAcud');
    $direccionAcud = $request->input('direccionAcud');
    $telefonoAcud = $request->input('telefonoAcud');
    $correoAcud = $request->input('correoAcud');
    $contrasenaAcud = bcrypt($request->input('contrasenaAcud'));

    if ($request->hasFile('picture')) {
        $imageEstudiantePath = $request->file('picture')->store('public/imagenes');
    } else {
        
        $imageEstudiantePath = 'default_estudiante.jpg';
    }

    if ($request->hasFile('pictureAcud')) {
        $imageAcudientePath = $request->file('pictureAcud')->store('public/imagenes');
    } else {

        $imageAcudientePath = 'default_acudiente.jpg';
    }


    $estudiante = new estudiantes();
    $estudiante->tipodoc = $tipodoc;
    $estudiante->doc = $doc;
    $estudiante->nombre = $nombre;
    $estudiante->direccion = $direccion;
    $estudiante->telefono = $telefono;
    $estudiante->email = $correo;
    $estudiante->fechanac = $fechaNac;
    $estudiante->curso_id = $curso;
    $estudiante->password = $contrasena;
    $estudiante->imagen = $imageEstudiantePath;

    $acudiente = new acudientes();
    $acudiente->doc = $docAcud;
    $acudiente->nombre = $nombreAcud;
    $acudiente->direccion = $direccionAcud;
    $acudiente->telefono = $telefonoAcud;
    $acudiente->email = $correoAcud;
    $acudiente->password = $contrasenaAcud;
    $acudiente->imagen = $imageAcudientePath;
    $acudiente->save();


    $estudiante->acudiente_id = $acudiente->id;
    $estudiante->save();
        
        return redirect('estudiantes');

    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $estudiantes = estudiantes::with(['acudientes','cursos'])->where('estado', 1)->get()->toArray();

        $cursos = Curso::all()->toArray();

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('indexEstudiantes', compact('estudiantes','cursos','nameDir'));

        /* dd($estudiantes); */
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

        $validatedData = $request->validate([
            'tipodoc' => 'required|string|max:30',
            'doc' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:120',
            'telefono' => 'required|string|max:30',
            'correo' => 'required|string|email|max:80',
            'fechaNac' => 'required|date',
            'curso' => 'required|string',
            'picture' => 'nullable|image|max:2048', 
        ]);

        $curso = curso::where('nombre', $request->input('curso'))->first();

        if (!$curso) {
            return redirect()->back()->withErrors(['curso' => 'El curso seleccionado no existe.']);
        }

        $estudiante = estudiantes::findOrFail($id);

        $estudiante->tipodoc = $request->input('tipodoc');
        $estudiante->doc = $request->input('doc');
        $estudiante->nombre = $request->input('nombre');
        $estudiante->direccion = $request->input('direccion');
        $estudiante->telefono = $request->input('telefono');
        $estudiante->email = $request->input('correo');
        $estudiante->fechanac = $request->input('fechaNac');
        $estudiante->curso_id = $curso->id;

        if ($request->hasFile('picture')) {
            $imageData = file_get_contents($request->file('picture')->getRealPath());
            $estudiante->imagen = $imageData;
        }

        $estudiante->save();

        return redirect()->route('estudiantes.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->input('deleteButton');

        $registro = estudiantes::find($id);

        $registro->delete();

        return redirect()->route('estudiantes.index');

    }

    public function options(Request $request) { 

    
        if ($request->has('editButton')) {

            $id = $request->input('editButton');
            
            $data = estudiantes::find($id)->load(['matriculas', 'acudientes', 'cursos'])->toArray();

            $cursos = Curso::all()->toArray();
            
            $data['fechanac'] = Carbon::parse($data['fechanac'])->format('Y-m-d');
            
            if (isset($data['matriculas'][0])) {
                $data['matriculas'][0]['created_at'] = Carbon::parse($data['matriculas'][0]['created_at'])->format('Y-m-d');
            }
            
            if (isset($data['imagen']) && !empty($data['imagen'])) {
                $data['imagen'] = 'data:image/jpeg;base64,' . base64_encode($data['imagen']);
            } else {
                $data['imagen'] = '../assets/img/avatars/1.png';
            }

            if (isset($data['acudientes']['imagen']) && !empty($data['acudientes']['imagen'])) {
                $data['acudientes']['imagen'] = 'data:image/jpeg;base64,' . base64_encode($data['acudientes']['imagen']);
            } else {
                $data['acudientes']['imagen'] = '../assets/img/avatars/default_acudiente.png';
            }
            
    
            return view('indexSettings', compact('data', 'cursos'));

            /* dd($data); */

        }else if ($request->has('deleteButton')) {
            $id = $request->input('deleteButton');
            $estudiante = estudiantes::find($id);
            
            if ($estudiante) {
                $estudiante->estado = 2;
                $estudiante->save();
                
                $estudiante2 = estudiantes::find($id)->toArray();

                $acudiente = $estudiante2['acudiente_id'];
    
                $acudienteConMasEstudiantes = estudiantes::where('acudiente_id', $acudiente)
                                                        ->where('estado', 1)
                                                        ->count();
                
                if ($acudienteConMasEstudiantes === 0) {
                    $acudienteData = acudientes::find($acudiente);
                    $acudienteData->estado = 2;
                    $acudienteData->save();
                }

            }
            
        return redirect('estudiantes');

    }else if ($request->has('restore')) {
            $id = $request->input('restore');
            $estudiante = estudiantes::find($id);
            $acudiente_id = $estudiante->acudiente_id;
            
            if ($estudiante) {
                $estudiante->estado = 1;
                $estudiante->save();

                $acudiente = acudientes::find($acudiente_id);

                if ($acudiente) {
                    if ($acudiente->estado == 2) {
                        $acudiente->estado = 1;
                        $acudiente->save();
                    }
                }


            }
            
        return redirect('estudiantes');
    }
    }

    public function filter(Request $request){

        $tipodoc = $request->input('tipoDocumento');

        $curso = $request->input('curso');

        if (!empty($tipodoc) && empty($curso)) {
            
        }

    }

    public function storeDatos(Request $request){

        $tipodoc = $request->input('tipodoc');
        $doc = $request->input('doc');
        $nombre = $request->input('nombre');
        $direccion = $request->input('direccion');
        $telefono = $request->input('telefono');
        $correo = $request->input('correo');
        $fechaNac = $request->input('fechaNac');
        $curso = $request->input('curso');
        $contrasena = $request->input('contrasena');
        $nombreAcud = $request->input('nombreAcud');


        $response = [
            'status' => 'success',
            'message' => 'Datos recibidos correctamente',
            'data' => [
                'tipodoc' => $tipodoc,
                'doc' => $doc,
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'correo' => $correo,
                'fechaNac' => $fechaNac,
                'curso' => $curso,
                'contrasena' => $contrasena,
                'nombreAcud' => $nombreAcud
            ]
        ];

        return response()->json($response);
    }


    public function eliminados(){

        $estudiantes = estudiantes::with(['acudientes','cursos'])->where('estado', 2)->get()->toArray();

        $cursos = Curso::all()->toArray();

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('estudiantes-deleted', compact('estudiantes','cursos','nameDir'));
    }


}
