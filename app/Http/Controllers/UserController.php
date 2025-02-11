<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'DESC')->get();

        return view('users.index', compact('users', 'search'));
    }

    // Form untuk membuat user baru
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Form untuk edit user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Menyimpan perubahan user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ], [
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function editPassword($id)
    {
        $currentUser = Auth::user();
        if($currentUser->id != $id) {
            abort(403, 'Unauthorized.');
        }
        
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        return view('users.changepassword', compact('user', 'id'));
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password_lama' => 'required', 
            'password' => 'nullable|confirmed|min:8',
        ]);
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        if (!Hash::check($request->password_lama, $user->password)) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('users.index')->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->back()->with('info', 'Tidak ada perubahan password yang dilakukan.');
    }
}
