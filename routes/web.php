<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarCorreo;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AcudientesController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profesores', function () {
    if (Auth::guard('directivos')->check() || Cookie::get('remember_token')) {

        $userId = Cookie::get('remember_token');
        $user = App\Models\directivos::find($userId);

        if ($user) {
            Auth::guard('directivos')->login($user);
            return app(ProfesoresController::class)->show();
        }
    }

    return redirect()->route('login.form');
})->name('profesores.index');

Route::get('/estudiantes', function () {
    if (Auth::guard('directivos')->check() || Cookie::get('remember_token')) {

        $userId = Cookie::get('remember_token');
        $user = App\Models\directivos::find($userId);

        if ($user) {
            Auth::guard('directivos')->login($user);
            return app(EstudiantesController::class)->show();
        }
    }

    return redirect()->route('login.form');
})->name('estudiantes.index');

Route::get('/acudientes', function () {
    if (Auth::guard('directivos')->check() || Cookie::get('remember_token')) {

        $userId = Cookie::get('remember_token');
        $user = App\Models\directivos::find($userId);

        if ($user) {
            Auth::guard('directivos')->login($user);
            return app(AcudientesController::class)->index();
        }
    }

    return redirect()->route('login.form');
})->name('acudientes.index');


Route::post('/edit', [EstudiantesController::class, 'options']);

Route::post('/edit2', [MateriasController::class, 'asd']);

Route::post('/edit3', [ProfesoresController::class, 'options']);

Route::post('/edit4', [AcudientesController::class, 'show']);

Route::post('/update', [EstudiantesController::class, 'update']);

Route::post('/update2', [ProfesoresController::class, 'update']);

Route::post('/update4', [AcudientesController::class, 'update']);

Route::post('/delete', [EstudiantesController::class, 'destroy']);

Route::get('/matriculas', [EstudiantesController::class, 'create']);

Route::post('/nuevo-estudiante', [EstudiantesController::class, 'newEstudiante'])->name('newEstudiante');

Route::post('/enviar-datos', [EstudiantesController::class, 'storeDatos']);

Route::get('/addprofesor', [ProfesoresController::class, 'create']);

Route::get('/addmateria', [MateriasController::class, 'create']);

Route::post('/enviarprofesor', [ProfesoresController::class, 'store'])->name('newprofesor');

Route::post('/newMateria', [MateriasController::class, 'store']);

Route::get('/profesoresDeleted', [ProfesoresController::class, 'eliminados']);

Route::get('/EstudiantesDeleted', [EstudiantesController::class, 'eliminados']);

Route::get('/MateriasDeleted', [MateriasController::class, 'eliminados']);

Route::post('/updateMateria', [MateriasController::class, 'update']);


Route::get('/materias', function () {
    if (Auth::guard('estudiantes')->check() || Cookie::get('remember_token')) {

        $userId = Cookie::get('remember_token');
        $user = App\Models\estudiantes::find($userId);

        if ($user) {
            Auth::guard('estudiantes')->login($user);
            return app(MateriasController::class)->show();
        }
    }

    return redirect()->route('login.form');
})->name('materias.index');
