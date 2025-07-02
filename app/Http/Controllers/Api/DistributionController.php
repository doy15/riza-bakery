<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Material;
use App\Models\MaterialStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'material_id' => 'required|exists:materials,id',
            'destination' => 'required',
            'quantity' => 'required',
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
            if ((float) $material->stock <  $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok material tidak mencukupi.',
                ], 400);
            }

            // Buat material stock
            // $stock = Distribution::create($request->all());
            $distribution = Distribution::create([
                'user_id' => $request->user_id,
                'material_id' => $request->material_id,
                'destination' => $request->destination,
                'quantity' => $request->quantity,
                'distribution_date' => now(),
            ]);

            $matrial_stock = MaterialStock::create([
                'material_id' => $material->id,
                'qty' => $request->quantity,
                'status' => 'out',
                'user_id' => $request->user_id,
            ]);

            // Kurangi stok
            $material->stock -= $request->quantity;
            $material->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Material Stock created & material stock updated',
                'data' => $distribution,
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
}
