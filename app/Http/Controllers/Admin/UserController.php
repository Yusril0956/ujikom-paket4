<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('pages.admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Set created_by
        $validated['created_by'] = auth()->id();

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Set updated_by
        $validated['updated_by'] = auth()->id();

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(User $user)
    {
        // Store the user name before deletion
        $userName = $user->name;

        // Set deleted_by before soft delete
        $user->update(['deleted_by' => auth()->id()]);

        // Soft delete
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$userName}' berhasil dihapus.");
    }

    /**
     * Restore a soft-deleted user (admin only)
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil dipulihkan.");
    }

    /**
     * List of trashed (deleted) users - for admin
     */
    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $users = $query->orderBy('deleted_at', 'desc')->paginate(15)->withQueryString();

        return view('pages.admin.users.trashed', compact('users'));
    }
}
