<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class MaterialStockController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Ambil material
            $material = Material::findOrFail($request->material_id);

            // Cek ketersediaan stok (pakai float agar mendukung double)
            if ((float) $material->stock < (float) $request->qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok material tidak mencukupi.',
                ], 400);
            }

            // Buat material stock
            $stock = MaterialStock::create($request->all());

            // Kurangi stok
            $material->stock -= $request->qty;
            $material->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Material Stock created & material stock updated',
                'data' => $stock,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
     public function get_data()
    {
        $materials = Material::select('id', 'material_name')->orderBy('material_name')->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }
}
