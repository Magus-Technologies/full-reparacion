<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);
    
        // Primero verificamos si el usuario existe
        $user = User::where('username', $request->username)->first();
    
        if ($user) {
            // Si el usuario existe, intentamos autenticar
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
    
                if ($request->wantsJson()) {
                    return response()->json([
                        'redirect' => '/admin'
                    ]);
                }
    
                return redirect()->intended('/admin');
            }
    
            // Si la autenticación falla, significa que la contraseña es incorrecta
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => [
                        'password' => 'La contraseña es incorrecta'
                    ]
                ], 422);
            }
        }
    
        // Si el usuario no existe o no queremos revelar información específica
        if ($request->wantsJson()) {
            return response()->json([
                'errors' => [
                    'credentials' => 'Las credenciales proporcionadas no coinciden con el registro'
                ]
            ], 422);
        }
    
        return back()->withErrors([
            'credentials' => 'Las credenciales proporcionadas no coinciden con el registro'
        ]);
    }
    
    

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}