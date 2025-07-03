<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionData;
use App\Models\QualityInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QualityInspectionController extends Controller
{
    public function entry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'production_data_id' => 'required|exists:production_datas,id',
            'judgement' => 'required|in:ok,ng',
            'corrective_action' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan inspection
        $inspection = QualityInspection::create($request->all());

        // Cek jika judgement = ng
        if ($request->judgement === 'ng') {
            $productionData = ProductionData::find($request->production_data_id);

            if ($productionData) {
                // Simpan nilai ok sebelumnya
                $previousOk = $productionData->ok;

                $ngValue = $productionData->plan_qty >= $productionData->ok
                    ? $productionData->plan_qty
                    : $productionData->ok;

                $productionData->update([
                    'ng' => $ngValue,
                    'ok' => 0,
                ]);

                // Update stock material jika ada material_id dan ok sebelumnya lebih dari 0
                if ($productionData->material_id && $previousOk > 0) {
                    $material = \App\Models\Material::find($productionData->material_id);

                    if ($material) {
                        $material->decrement('stock', $previousOk);
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Data inspeksi berhasil disimpan.',
        ], 200);
    }

    public function history(Request $request)
    {
        $inspections = QualityInspection::with('production_data', 'user', 'production_data.line')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inspections
        ]);
    }

    public function dashboardQuality()
    {
        $counts = QualityInspection::select('judgement', DB::raw('count(*) as total'))
            ->groupBy('judgement')
            ->pluck('total', 'judgement');

        return response()->json([
            'success' => true,
            'data' => [
                'ok' => $counts['OK'] ?? 0,
                'ng' => $counts['NG'] ?? 0,
            ]
        ]);
    }
}
