<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.user.index');
    }

    public function data()
    {
        $query = User::select(['id', 'nik', 'name', 'role', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|min:1|max:10|unique:users,nik',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'role' => 'required',
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return view('pages.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|min:1|max:10|unique:users,nik,' . $user->id,
            'name' => 'required|string|max:255',
            'role' => 'required',
            'password' => 'nullable|string|min:6',
        ]);

        $user->nik = $request->nik;
        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
