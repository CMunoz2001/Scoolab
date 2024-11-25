<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Models\estudiantes;
use App\Models\profesores;
use App\Models\acudientes;
use App\Models\directivos;


class LoginController extends Controller
{

    public function showLoginForm()
    {

        if (Auth::check()) {
            return redirect()->route('estudiantes'); 
        }else{
            return view('login');
        }
    }

        public function login(Request $request){


            $credentials = $request->only('email', 'password');
    
    $directivo = Directivos::where('email', $credentials['email'])
                           ->where('password', $credentials['password'])
                           ->first();
    
    if ($directivo) {
        // Autenticar al usuario
        Auth::login($directivo);
    
        // Crear cookie de sesión (siempre)
        Cookie::queue('remember_token', $directivo->id, 120);
    
        Cookie::queue('dataEmail', $credentials['email'], 30);
    
        // Validar si la cookie de recordatorio ya existe antes de crearla
        if ($request->has('remember')) {
            if (!Cookie::has('remember_email')) {
                Cookie::queue('remember_email', $credentials['email'], 30 * 24 * 60);
            }
            if (!Cookie::has('remember_password')) {
                Cookie::queue('remember_password', $credentials['password'], 30 * 24 * 60);
            }
        }
    
        return redirect()->intended('estudiantes');
    }
    
    return back()->withErrors([
        'email' => 'Las credenciales son incorrectas.',
    ]);
        }
    
        public function logout() {
            Auth::guard('estudiantes')->logout();
        
            Cookie::queue(Cookie::forget('remember_token'));
            
            Cookie::queue(Cookie::forget('dataEmail'));
    
            
            return redirect()->route('login.form');
        }
}
