<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
     public function get_data()
    {
        $shift = Shift::select('id', 'name','start_time','end_time')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $shift
        ]);
    }
}
