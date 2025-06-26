<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\MaterialStock;
use Illuminate\Http\Request;

class MaterialStockController extends Controller
{
    public function getMaterialStockData(Request $request)
    {
        $stocks = MaterialStock::with('material')->get();

        return response()->json([
            'labels' => $stocks->pluck('material.material_name'), // Ambil nama material
            'stocks' => $stocks->pluck('qty'),                    // Ambil qty dari MaterialStock
        ]);
    }
    public function data()
    {
        $stocks = MaterialStock::with('material')->select('material_stocks.*');

        return datatables()->of($stocks)
            ->addIndexColumn()
            ->make(true);
    }
    public function destroy($id)
    {
        $stock = MaterialStock::findOrFail($id);
        $stock->delete();

        return response()->json(['success' => true, 'message' => 'Material stock berhasil dihapus.']);
    }
}
