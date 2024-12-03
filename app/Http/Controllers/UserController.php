<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use PDO;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    //
    public function editProfile(Request $request)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::find(Auth::id());

            if ($user->profile_picture) {
                if (file_exists(public_path('uploads/' . $user->profile_picture))) {
                    unlink(public_path('uploads/' . $user->profile_picture));
                }
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);

            $user->profile_picture = $filename;
            $user->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile picture. Please try again.');
        }
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|string|min:8',
        ]);

        $user = User::find(Auth::id());
        $user->password = bcrypt($request->newPassword);
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function updateProfilePage(Request $request)
    {
        try {
            $request->validate([
                'height' => 'nullable|integer|max:250',
                'weight' => 'nullable|integer|max:600',
            ]);

            $user = User::find(Auth::id());
            $user->update([
               'height' => $request->input('height'),
               'weight' => $request->input('weight'),
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Profile update failed!');
        }
    }
}
