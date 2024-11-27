<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    //
    public function editProfile(Request $request){
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());

        if ($user->profile_picture) {
            Storage::delete('public/images/' . $user->profile_picture);
        }

        $originalName = $request->file('profile_picture')->getClientOriginalName(); // Get the original file name
        $path = $request->file('profile_picture')->storeAs('images', $originalName, 'public');

        $user->profile_picture = $path;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully!');

    }
}
