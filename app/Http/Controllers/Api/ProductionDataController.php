<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\ProductionData;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionDataController extends Controller
{

    public function select(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'shift_id' => 'required|integer',
        ]);

        $lines = Line::all();

        $result = $lines->map(function ($line) use ($request) {
            $exists = ProductionData::where('date', $request->date)
                ->where('shift_id', $request->shift_id)
                ->where('line_id', $line->id)
                ->exists();

            return [
                'id' => $line->id,
                'line_code' => $line->line_code,
                'line_name' => $line->line_name,
                'exists' => $exists,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function generate(Request $request)
    {
        $shift = Shift::where('id', $request->shift_id)->first();
        $line = Line::where('id', $request->line_id)->first();
        $user = User::where('id', $request->user_id)->first();

        $production = ProductionData::where('shift_id', $request->shift_id)
            ->where('line_id', $request->line_id)
            ->where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->get();

        if ($production->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Sudah generate',
            ]);
        }

        $start_time = Carbon::createFromFormat('H:i:s', $shift->start_time);
        $end_time = Carbon::createFromFormat('H:i:s', $shift->end_time);

        if ($end_time->lessThan($start_time)) {
            // Jika shift lewat tengah malam, tambahkan 1 hari pada end_time
            $end_time->addDay();
        }

        $duration = $start_time->diffInHours($end_time);
        $int_duration = (int) $duration;

        for ($i = 0; $i < $int_duration; $i++) {
            $start = $start_time->copy()->addHours($i);
            $end = $start->copy()->addHour();

            $productiondata = ProductionData::create([
                'user_id' => $request->user_id,
                'line_id' => $request->line_id,
                'shift_id' => $request->shift_id,
                'date' => $request->date,
                'production_start' => $start->format('H:i:s'),
                'production_end' => $end->format('H:i:s'),
                'cctime' =>  $line->cycle_time,
                'material_id' => $request->material_id,
                'plan_qty' =>3600 / $line->cycle_time,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }



    public function entry(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:production_datas,id',
            'ok' => 'nullable|integer',
            'ng' => 'nullable|integer',
            'note' => 'nullable|string',
            'material_stocks' => 'required|array',
            'material_stocks.*.material_id' => 'required|exists:materials,id',
            'material_stocks.*.user_id' => 'required|exists:users,id',
            'material_stocks.*.line_id' => 'required|exists:lines,id',
            'material_stocks.*.qty' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update Production Data
            $production = ProductionData::findOrFail($request->id);
            $production->update([
                'ok' => $request->ok,
                'ng' => $request->ng,
                'notes' => $request->note,
            ]);

            // Simpan material_stocks dan kurangi stok material jika status 'out'
            foreach ($request->material_stocks as $stock) {
                MaterialStock::create([
                    'material_id' => $stock['material_id'],
                    'user_id' => $stock['user_id'],
                    'line_id' => $production['line_id'],
                    'production_data_id' => $production->id,
                    'qty' => $stock['qty'],
                    'status' => "out",
                ]);


                $material = Material::find($stock['material_id']);
                if ($material) {
                    $material->stock = max(0, $material->stock - $stock['qty']);
                    $material->save();
                }
            }

            // ✅ Tambah stok material finish_good jika `ok` terisi
            if (!is_null($request->ok) && $production->material_id) {
                $material = Material::find($production->material_id);
                if ($material && strtolower($material->type) === 'finish_good') {
                    $material->stock += $request->ok;
                    $material->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data produksi dan stok material berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function get_data(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'line_id' => 'required|exists:lines,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $data = ProductionData::where('date', $request->date)
            ->where('shift_id', $request->shift_id)
            ->where('line_id', $request->line_id)
            ->with(['line', 'shift', 'user', 'material']) // jika kamu pakai relasi
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan untuk tanggal, shift, dan line tersebut.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditemukan.',
            'data' => $data
        ]);
    }
}
