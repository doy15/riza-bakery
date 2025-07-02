<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Line;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LineController extends Controller
{
    public function index()
    {
        return view('pages.line.index');
    }

    public function data()
    {
        $query = Line::select(['lines.id', 'line_code', 'line_name', 'cycle_time', 'target', 'materials.material_name'])->join('materials', 'lines.material_id', '=', 'materials.id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $materials = Material::select('materials.*')
                                ->where('type', 'finish_good')                        
                                ->orderBy('material_name')->get();

        return view('pages.line.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'line_code' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'cycle_time' => 'required|integer|min:1',
            'target' => 'required|integer|min:1',
        ]);

        $line = Line::create([
            'line_code' => $request->line_code,
            'line_name' => $request->line_name,
            'cycle_time' => $request->cycle_time,
            'target' => $request->target,
            'material_id' => $request->material_id,
        ]);

        return redirect()->route('line.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit(Request $request, $id)
    {
        $line = Line::findOrFail($id);
        $materials = Material::select('materials.*')
                                ->where('type', 'finish_good')                        
                                ->orderBy('material_name')->get();

        return view('pages.line.edit', compact('line', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $line = Line::findOrFail($id);

        $request->validate([
            'line_code' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'cycle_time' => 'required|integer|min:1',
            'target' => 'required|integer|min:1',
        ]);

        $line->line_code = $request->line_code;
        $line->line_name = $request->line_name;
        $line->cycle_time = $request->cycle_time;
        $line->target = $request->target;
        $line->material_id = $request->material_id;
        $line->save();

        return redirect()->route('line.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json([
                'success' => false,
                'message' => 'Line tidak ditemukan.',
            ], 404);
        }

        $line->delete();

        return response()->json([
            'success' => true,
            'message' => 'Line berhasil dihapus.',
        ]);
    }
}
