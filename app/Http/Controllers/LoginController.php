<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login.index', [
            'title' => 'login',
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'regex:/^[a-zA-Z0-9#]+$/'],
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->with('loginError', 'Log in failed!');
    }

    public function logout()
    {

        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        request()->session()->forget('pin_validated');

        return redirect('/login');
    }
}
