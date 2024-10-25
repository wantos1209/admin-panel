<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AreaController extends Controller
{
    // Menampilkan daftar area
    public function index(Request $request)
    {
        $search = $request->input('search');

        $areas = Area::when($search, function ($query, $search) {
            return $query->where('area_nama', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'DESC')->get();

        return view('areas.index', compact('areas', 'search'));
    }

    // Form untuk membuat area baru
    public function create()
    {
        return view('areas.create');
    }

    // Menyimpan area baru
    public function store(Request $request)
    {
        $request->validate([
            'area_nama' => 'required|unique:area',
        ], [
            'area_nama.unique' => 'Nama Kota sudah digunakan, silakan pilih yang lain.',
        ]);

        Area::create([
            'area_nama' => strtoupper($request->area_nama)
        ]);
        
        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    // Menampilkan detail area
    public function show(Area $area)
    {
        return view('areas.show', compact('area'));
    }

    // Form untuk edit area
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    // Menyimpan perubahan area
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'area_nama' => 'required|unique:area,area_nama,' . $area->id,
        ], [
            'area_nama.unique' => 'Nama Kota sudah digunakan, silakan pilih yang lain.',
        ]);
        
        $area->update([
            'area_nama' => strtoupper($request->area_nama),
        ]);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    // Menghapus area
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
