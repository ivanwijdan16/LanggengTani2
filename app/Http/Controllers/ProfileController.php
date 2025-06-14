<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Show the profile form
    public function edit()
    {
        return view('profile.edit');
    }

    // Update the profile (email and password)
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update email if it's provided
        if ($request->email != $user->email) {
            $user->email = $request->email;
        }

        if ($request->name != $user->name) {
            $user->name = $request->name;
        }

        // Update password if provided
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
