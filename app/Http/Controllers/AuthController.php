<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showSignin(){
        return view('signin');
    }

    public function showSignup(){
        return view('signup');
    }
    public function signin(Request $request)
    {
        // Validate the form data
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $rememberme = $request->has('remember');

        if (Auth::attempt(['email' => $validate['email'], 'password' => $validate['password']], $rememberme)) {
            return redirect()->intended('/');
        } else {
            return back()->withErrors(['email' => 'User Email or Password is Wrong']);
        }
    }

    public function signup(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect('/');
    }


    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }
}
