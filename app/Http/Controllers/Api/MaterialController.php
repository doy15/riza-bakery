<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function get_data()
    {
        $materials = Material::select('materials.*')->orderBy('material_name')->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }
    public function get_data_raw()
    {
        $material = Material::select('materials.*')
            ->where('type', 'raw') // tambahkan nilai 'finish_good'
            ->get();

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }
      public function get_data_finish_good()
    {
        $material = Material::select('materials.*')
            ->where('type', 'finish_good') // tambahkan nilai 'finish_good'
            ->get();

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }
}
