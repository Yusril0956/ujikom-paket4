<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(5)->withQueryString();

        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->isPetugas()) {
            session()->flash('info', 'Petugas hanya dapat membuat user dengan role Anggota.');
        }
        
        return view('pages.admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $authUser = auth()->user();

        if ($authUser->isPetugas() && in_array($validated['role'], ['admin', 'petugas'])) {
            return redirect()->back()
                ->with('error', 'Petugas hanya dapat membuat user dengan role Anggota.');
        }

        if ($request->hasFile('avatar')) {
            if ($request->input('user_id')) {
                $oldUser = User::find($request->input('user_id'));
                if ($oldUser && $oldUser->avatar) {
                    try {
                        Storage::disk('public')->delete($oldUser->avatar);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete avatar: ' . $e->getMessage());
                    }
                }
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_by'] = auth()->id();

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $authUser = auth()->user();
        $validated = $request->validated();

        if ($authUser->isPetugas() && in_array($user->role, ['admin', 'petugas'])) {
            return redirect()->back()
                ->with('error', 'Petugas tidak dapat mengedit user dengan role Admin atau Petugas.');
        }

        if ($authUser->isPetugas() && !empty($validated['password'])) {
            return redirect()->back()
                ->with('error', 'Petugas tidak dapat mengubah password user. Hubungi admin.');
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['updated_by'] = auth()->id();

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->isPetugas() && in_array($user->role, ['admin', 'petugas'])) {
            return redirect()->back()
                ->with('error', 'Petugas tidak dapat menghapus user dengan role Admin atau Petugas.');
        }

        $userName = $user->name;

        $user->update(['deleted_by' => auth()->id()]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$userName}' berhasil dihapus.");
    }

    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $users = $query->paginate(5)->withQueryString();

        return view('pages.admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $authUser = auth()->user();
        $user = User::withTrashed()->findOrFail($id);

        if ($authUser->isPetugas() && in_array($user->role, ['admin', 'petugas'])) {
            return redirect()->back()
                ->with('error', 'Petugas tidak dapat memulihkan user dengan role Admin atau Petugas.');
        }

        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota '{$user->name}' berhasil dipulihkan.");
    }

}
