<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the role of a user.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,nastavnik,student'
        ]);

        // Prevent user from changing their own role
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Ne možete promijeniti svoju vlastitu ulogu.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Uloga korisnika je uspješno promijenjena.');
    }
}
