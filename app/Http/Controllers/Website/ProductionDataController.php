<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\ProductionData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductionDataController extends Controller
{
    public function getEfficiencyPerLine()
    {
        $lines = Line::all();
        $data = [];

        foreach ($lines as $line) {
            $hours = [];
            $oks = [];
            $plans = [];

            $now = now()->format('Y-m-d');

            for ($h = 0; $h < 24; $h++) {
                $start = Carbon::parse("{$now} {$h}:00:00");
                $end = $start->copy()->addHour();

                $production = ProductionData::where('line_id', $line->id)
                    ->whereBetween('production_start', [$start, $end])
                    ->first();

                $ok = $production?->ok ?? 0;
                $plan = $production?->plan_qty ?? 0;

                $hours[] = $start->format('H:i');
                $oks[] = $ok;
                $plans[] = $plan;
            }

            $data[] = [
                'line_name' => $line->line_name,
                'hours' => $hours,
                'ok_values' => $oks,
                'plan_values' => $plans
            ];
        }

        return response()->json($data);
    }
}
