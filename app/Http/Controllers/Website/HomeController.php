<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialStock;
use Illuminate\Http\Request;
use view;

class HomeController extends Controller
{
    public function index()
    {
        $countRaw = Material::where('type', 'raw')->count();
        $countFinishGood = Material::where('type', 'finish_good')->count();
         $userRole = auth()->user()->role;

        return view('index', compact('countRaw', 'countFinishGood','userRole'));
    }

    public function getMaterialData(Request $request)
    {
        $type = $request->query('type');

        $materials = Material::select('material_name', 'stock', 'minimum_stock', 'type')
            ->when($type, fn($q) => $q->where('type', $type))
            ->get();

        $countRaw = Material::where('type', 'raw')->count();
        $countFinishGood = Material::where('type', 'finish_good')->count();

        return response()->json([
            'labels' => $materials->pluck('material_name'),
            'stocks' => $materials->pluck('stock'),
            'minStocks' => $materials->pluck('minimum_stock'),
            'countRaw' => $countRaw,
            'countFinishGood' => $countFinishGood,
        ]);
    }
}
