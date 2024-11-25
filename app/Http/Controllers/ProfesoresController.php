<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\profesores;
use App\Models\directivos;
use App\Models\materia;
use App\Models\curso;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;



class ProfesoresController extends Controller
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
        return view('add-profesor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
   // Validar los datos del formulario

        // Crear el profesor en la base de datos
        $profesor = new profesores();
        $profesor->tipodoc = $request->input('tipodoc');
        $profesor->doc = $request->input('doc');
        $profesor->nombre = $request->input('nombre');
        $profesor->direccion = $request->input('direccion');
        $profesor->telefono = $request->input('telefono');
        $profesor->email = $request->input('correo');
        $profesor->password = $request->input('password');  // Encriptamos la contraseña
        $profesor->fechaCon = $request->input('fechaNac');

        // Subir la imagen si existe
        if ($request->hasFile('picture')) {
            $imagen = $request->file('picture');
            $profesor->imagen = file_get_contents($imagen->getRealPath()); // Guardar como BLOB
            
        } 

        // Guardar el nuevo profesor
        $profesor->save();
        return redirect('profesores');

        // Redirigir a una página o mostrar un mensaje de éxito
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

        $usuarios = profesores::with(['materias', 'cursos'])->where('estado', 1)->get();

        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('indexProfesores', compact('usuarios', 'nameDir'));
        
        /* return view('indexEstudiantes', ['usuarios' => $usuarios]); */
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
        $profesorId = $_REQUEST['updateButton'];
        $materiasSeleccionadas = $request->input('materias', []);
        $materiasProf = materia::where('profesor_id', $profesorId)->get();

        $profesor = profesores::findOrFail($profesorId);
        $profesor->tipodoc = $request->input('tipodoc');
        $profesor->doc = $request->input('doc');
        $profesor->nombre = $request->input('nombre');
        $profesor->direccion = $request->input('direccion');
        $profesor->telefono = $request->input('telefono');
        $profesor->email = $request->input('correo');
        $profesor->fechaCon = $request->input('fechaNac');
        
        $cursoSeleccionado = $request->input('curso');
        if ($cursoSeleccionado) {
            $curso = Curso::where('nombre', $cursoSeleccionado)->first();
            if ($curso) {
                $curso->profesor_id = $profesor->id;
                $curso->save();
            }
        }

        if ($request->hasFile('picture')) {
            $imageData = file_get_contents($request->file('picture')->getRealPath());
            $profesor->imagen = $imageData;
        }

        $profesor->save();

        $materiasProf = Materia::where('profesor_id', $profesor->id)->get();
        
        foreach ($materiasProf as $materia) {
            if (!in_array($materia->id, $materiasSeleccionadas)) {
                $materia->profesor_id = null;
                $materia->save();
            }
        }

        foreach ($materiasSeleccionadas as $materiaId) {
            $materia = Materia::find($materiaId);
            if ($materia && $materia->profesor_id !== $profesor->id) {
                $materia->profesor_id = $profesor->id;
                $materia->save();
            }
        }

        return redirect('/profesores');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function options(Request $request) {   

    // Bloque para editar
    if ($request->has('editButton')) {

        $id = $request->input('editButton');

        $data = profesores::find($id)->load(['materias', 'cursos'])->toArray();
        $cursos = curso::all()->toArray();
        $materias = materia::where('estado', 1)->get()->toArray();
        $materiasProf = materia::where('profesor_id', $id)->get()->toArray();

        $data['fechaCon'] = Carbon::parse($data['fechaCon'])->format('Y-m-d');

        if (isset($data['imagen']) && !empty($data['imagen'])) {
            $data['imagen'] = 'data:image/jpeg;base64,' . base64_encode($data['imagen']);
        } else {
            $data['imagen'] = '../assets/img/avatars/1.png';
        }

        return view('setting-profesores', compact('data', 'cursos', 'materias', 'materiasProf'));

    } else if ($request->has('deleteButton')) {

        $id = $request->input('deleteButton');
        $profesor = profesores::find($id);

        if ($profesor) {
            $profesor->estado = 2;
            $profesor->save();

        }

        return redirect()->route('profesores.index')->with('error', 'Profesor no encontrado.');
        
    }else if ($request->has('restore')) {
        $id = $request->input('restore');
        $profesor = profesores::find($id);

        if ($profesor) {
            $profesor->estado = 1;
            $profesor->save();
        }

        return redirect()->route('profesores.index')->with('error', 'Profesor no encontrado.');
    }
}
    

    public function eliminados(){
        $usuarios = profesores::with(['materias', 'cursos'])
        ->where('estado', 2)  // Filtra los usuarios cuyo estado sea igual a 2
        ->get();
        $correoDir = Cookie::get('remember_email');

        $directivo = Directivos::where('email', $correoDir)->first();

        if ($directivo) {

            $directivoId = $directivo->id;
            
        }

        $dataDir = directivos::find($directivoId)->toArray();

        $nameDir = $dataDir['nombre'];

        return view('profesores-deleted', compact('usuarios', 'nameDir'));
    }


}
