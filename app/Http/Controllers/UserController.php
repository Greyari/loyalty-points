<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan semua data user, siap dipakai client-side pagination
     */
    public function index()
    {
        $users = User::all()->map(function ($u) {
            return [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
                'role'  => $u->role,
            ];
        });

        return view('user.user', compact('users'));
    }

    /**
     * Tambah data user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6',
                'role'     => 'required|string|max:255'
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!',
                'data'    => $user
            ]);

        } catch (ValidationException $e) {

            $allErrors = implode('<br>', $e->validator->errors()->all());

            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan user.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        try {

            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|min:6',
                'role'     => 'required|string|max:255'
            ]);

            $user = User::findOrFail($id);

            $user->name  = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate!',
                'data'    => $user
            ]);

        } catch (ValidationException $e) {

            $allErrors = implode('<br>', $e->validator->errors()->all());

            return response()->json([
                'success' => false,
                'message' => $allErrors,
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate user.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        try {

            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error saat menghapus user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
