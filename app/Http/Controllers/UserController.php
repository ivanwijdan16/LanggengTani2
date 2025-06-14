<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display the list of users
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // Show the form to create a new user
    public function create()
    {
        return view('user.create');
    }

    // Store a newly created user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'pegawai',  // Default role
        ]);

        return redirect()->route('user.index');
    }

    // Show the form to edit a user
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Update the specified user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('user.index');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}
