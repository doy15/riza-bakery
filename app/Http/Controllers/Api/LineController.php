<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function get_data()
    {
        $line = Line::select('id', 'line_code','line_name','cycle_time','target')->orderBy('line_name')->get();

        return response()->json([
            'success' => true,
            'data' => $line
        ]);
    }
}
