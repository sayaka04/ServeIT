<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // only admin users
        $query->where('is_admin', true);

        // search by name or email (or anything you like)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // order and paginate
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admins.index', compact('users'));
    }



    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admins.index')->with('success', 'User created.');
    }

    /**
     * Display a single user.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        // Paginate actions taken by this admin, latest first
        $adminActions = $user->adminActionsTaken()
            ->with(['admin', 'target'])
            ->orderByDesc('created_at')
            ->paginate(5); // Adjust per-page as needed

        return view('admins.show', compact('user', 'adminActions'));
    }

    public function toggleDisable(User $user)
    {
        $user->is_disabled = !$user->is_disabled;
        $user->save();

        return redirect()->back()->with('status', 'User ' . ($user->is_disabled ? 'disabled' : 'enabled') . ' successfully.');
    }




    /**
     * Show the form for editing a user.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admins.edit', compact('user'));
    }

    /**
     * Update a user in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admins.index')->with('success', 'User updated.');
    }

    /**
     * Delete a user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admins.index')->with('success', 'User deleted.');
    }
}
