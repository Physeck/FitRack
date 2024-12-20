<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignin()
    {
        return view('signin');
    }

    public function showSignup()
    {
        return view('signup');
    }
    public function signin(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $rememberme = $request->has('remember');

        if (Auth::attempt(['email' => $validate['email'], 'password' => $validate['password']], $rememberme)) {
            $user = Auth::user();

            // Check for temp session data and apply it
            if (session()->has('temp_height') && session()->has('temp_weight') && session()->has('temp_gender')) {
                $user->height = session('temp_height');
                $user->weight = session('temp_weight');
                $user->gender = session('temp_gender');
                $user->save();

                // Clear temp session data
                session()->forget(['temp_height', 'temp_weight', 'temp_gender']);
            }
            // Redirect to the intended route or default to home
            $redirect = session('intended_redirect', '/');
            session()->forget('intended_redirect'); // Clear the intended redirect
            return redirect($redirect);
        } else {
            return back()->withErrors(['email' => 'User Email or Password is Wrong']);
        }
    }

    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'height' => 0,
            'weight' => 0,
            'gender' => 'Male',
        ]);

        Auth::login($user);

        if (session()->has('temp_height') && session()->has('temp_weight') && session()->has('temp_gender')) {
            $user->height = session('temp_height');
            $user->weight = session('temp_weight');
            $user->gender = session('temp_gender');
            $user->save();

            // Clear temp session data
            session()->forget(['temp_height', 'temp_weight', 'temp_gender']);
        }

        // Redirect to the intended route or default to home
        $redirect = session('intended_redirect', '/');
        session()->forget('intended_redirect'); // Clear the intended redirect
        return redirect($redirect);
    }

    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }

}
