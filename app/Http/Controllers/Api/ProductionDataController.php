<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionData;
use App\Models\Shift;
use App\Models\User;
use App\Models\Line;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionDataController extends Controller
{

    public function select(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'line_id' => 'required|integer',
            'shift_id' => 'required|integer',
        ]);

        $productiondata = ProductionData::where('date', $request->date)
            ->where('line_id', $request->line_id)
            ->where('shift_id', $request->shift_id)
            ->exists(); // true or false

        return response()->json([
            'success' => true,
            'data' => $productiondata
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
                'target' => 3600 * $line->cycle_time,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
