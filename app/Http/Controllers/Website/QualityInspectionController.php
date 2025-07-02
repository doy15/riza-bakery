<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\QualityInspection;
use Illuminate\Http\Request;

class QualityInspectionController extends Controller
{

    public function getData()
    {
        $data = QualityInspection::with('user')->latest();

        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function destroy($id)
    {
        $inspection = QualityInspection::find($id);
        if (!$inspection) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $inspection->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

}
