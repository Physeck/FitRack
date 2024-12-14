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
            return redirect()->intended('/');
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

        return redirect('/');
    }

    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }

    protected function authenticated(Request $request, $user)
    {
        if (session()->has('temp_height') && session()->has('temp_weight') && session()->has('temp_gender')) {
            $user->height = session('temp_height');
            $user->weight = session('temp_weight');
            $user->gender = session('temp_gender');
            $user->save();

            // Clear the temp data
            session()->forget(['temp_height', 'temp_weight', 'temp_gender']);

            // Redirect to intended page
            $redirect = session('intended_redirect', 'gymplanning');
            session()->forget('intended_redirect');
            return redirect()->route($redirect);
        }

        return redirect()->intended('gymplanning');
    }
}
