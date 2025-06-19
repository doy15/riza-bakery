<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Line;
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
        $query = Line::select(['id', 'line_code', 'line_name', 'cycle_time', 'target']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        return view('pages.line.create');
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
        ]);

        return redirect()->route('line.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit(Request $request, $id)
    {
        $line = Line::findOrFail($id);

        return view('pages.line.edit', compact('line'));
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
