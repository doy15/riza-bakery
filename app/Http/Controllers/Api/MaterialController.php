<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialStock;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function get_data()
    {
        $materials = Material::select('materials.*')->orderBy('material_name')->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }
    public function get_data_raw()
    {
        $material = Material::select('materials.*')
            ->where('type', 'raw') // tambahkan nilai 'finish_good'
            ->get();

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }
    public function get_data_finish_good()
    {
        $material = Material::select('materials.*')
            ->where('type', 'finish_good') // tambahkan nilai 'finish_good'
            ->get();

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $material = Material::find($request->material_id);
        $material->stock += $request->stock;
        $material->save();

        $material_stock = MaterialStock::create([
            'material_id' => $material->id,
            'qty' => $request->stock,
            'type' => $request->type,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Material berhasil ditambahkan.',
            'data' => $material,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'stock' => 'required|numeric|min:0',
        ]);

        $material = Material::find($request->material_id);

        if (!$material) {
            \Log::error('Material not found', ['id' => $request->material_id]);
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan.',
            ], 404);
        }

        $material->stock = $request->stock;
        $material->save();

        \Log::info('Stock updated', ['id' => $material->id, 'stock' => $material->stock]);

        return response()->json([
            'success' => true,
            'message' => 'Stok material berhasil diperbarui.',
            'data' => $material,
        ]);
    }

    public function history(Request $request)
    {
        $materialStocks = MaterialStock::with('material', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materialStocks
        ]);
    }
}
