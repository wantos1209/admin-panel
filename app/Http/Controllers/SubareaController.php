<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SubareaController extends Controller
{
    // Menampilkan daftar subarea
    public function index(Request $request)
    {
        $search = $request->input('search');

        $subareas = Subarea::when($search, function ($query, $search) {
            return $query->where('subarea.subarea_nama', 'like', '%' . $search . '%')
            ->orWhere('area.area_nama', 'like', '%' . $search . '%');
        })
        ->join('area', 'area.id', '=', 'subarea.area_id')
        ->select('subarea.*', 'area.area_nama')
        ->orderBy('subarea.created_at', 'DESC')
        ->get();

        return view('subareas.index', compact('subareas', 'search'));
    }

    // Form untuk membuat subarea baru
    public function create()
    {
        $data_area = Area::get();
        return view('subareas.create', compact('data_area'));
    }

    // Menyimpan subarea baru
    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'subarea_nama' => 'required|unique:subarea',
        ], [
            'subarea_nama.unique' => 'Nama Kecamatan sudah digunakan, silakan pilih yang lain.',
        ]);

        Subarea::create([
            'area_id' => $request->area_id,
            'subarea_nama' => strtoupper($request->subarea_nama)
        ]);

        return redirect()->route('subareas.index')->with('success', 'Subarea created successfully.');
    }

    // Menampilkan detail subarea
    public function show(Subarea $subarea)
    {
        return view('subareas.show', compact('subarea'));
    }

    // Form untuk edit subarea
    public function edit(Subarea $subarea)
    {
        $data_area = Area::get();
        return view('subareas.edit', compact('subarea', 'data_area'));
    }

    // Menyimpan perubahan subarea
    public function update(Request $request, Subarea $subarea)
    {
        $request->validate([
            'area_id' => 'required',
            'subarea_nama' => 'required|unique:subarea,subarea_nama,' . $subarea->id,
        ], [
            'subarea_nama.unique' => 'Nama Kecamatan sudah digunakan, silakan pilih yang lain.',
        ]);
        
        $subarea->update([
            'area_id' => $request->area_id,
            'subarea_nama' => strtoupper($request->subarea_nama),
        ]);

        return redirect()->route('subareas.index')->with('success', 'Subarea updated successfully.');
    }

    // Menghapus subarea
    public function destroy(Subarea $subarea)
    {
        $subarea->delete();
        return redirect()->route('subareas.index')->with('success', 'Subarea deleted successfully.');
    }
}
