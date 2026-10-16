<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan kelola pengguna & role (Khusus Super Admin).
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah akun pengguna / admin baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun pengguna / admin baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        auth()->user()->logActivity('create_user', "Membuat pengguna baru: {$user->email} dengan role {$user->role}");

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Form edit akun pengguna.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data akun atau role pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => 'nullable|string|min:8',
        ]);

        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        auth()->user()->logActivity('update_user', "Memperbarui hak akses pengguna {$user->email} menjadi role {$user->role}");

        return redirect()->route('admin.users.index')->with('success', "Hak akses untuk {$user->name} berhasil diperbarui!");
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $email = $user->email;
        $user->delete();

        auth()->user()->logActivity('delete_user', "Menghapus akun pengguna: {$email}");

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus!');
    }
}
