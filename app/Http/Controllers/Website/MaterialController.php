<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    public function index()
    {
        return view('pages.material.index');
    }

    public function data()
    {
        $query = Material::select(['id', 'material_code', 'material_name', 'type', 'minimum_stock', 'stock', 'unit']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        return view('pages.material.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_code' => 'required|string|max:255',
            'material_name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'minimum_stock' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
        ]);

        $material = Material::create([
            'material_code' => $request->material_code,
            'material_name' => $request->material_name,
            'type' => $request->type,
            'minimum_stock' => $request->minimum_stock,
            'stock' => $request->stock,
            'unit' => $request->unit,
        ]);

        return redirect()->route('material.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        return view('pages.material.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'material_code' => 'required|string|max:255',
            'material_name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'minimum_stock' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
        ]);

        $material->material_code = $request->material_code;
        $material->material_name = $request->material_name;
        $material->type = $request->type;
        $material->minimum_stock = $request->minimum_stock;
        $material->stock = $request->stock;
        $material->unit = $request->unit;
        $material->save();

        return redirect()->route('material.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $material = Material::find($id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan.',
            ], 404);
        }

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material berhasil dihapus.',
        ]);
    }
}
