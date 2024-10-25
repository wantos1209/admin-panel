<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subarea;
use App\Models\Userapk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserapkController extends Controller
{
    // Menampilkan daftar userapk
    public function index(Request $request)
    {
        $search = $request->input('search');

        $userapks = Userapk::when($search, function ($query, $search) {
             return $query->where('subarea.subarea_nama', 'like', '%' . $search . '%')
            ->orWhere('area.area_nama', 'like', '%' . $search . '%')
            ->orWhere('userapk.username', 'like', '%' . $search . '%');
        })
        ->join('subarea', 'subarea.id', '=', 'userapk.subarea_id')
        ->join('area', 'area.id', '=', 'subarea.area_id')
        ->select('userapk.*', 'subarea.subarea_nama', 'area.area_nama')
        ->orderBy('userapk.created_at', 'DESC')
        ->get();

        return view('userapks.index', compact('userapks', 'search'));
    }

    // Form untuk membuat userapk baru
    public function create()
    {
        $data_subarea = Subarea::join('area', 'area.id', '=', 'subarea.area_id')
        ->select('subarea.*', 'area.area_nama')
        ->orderBy('area.area_nama', 'DESC')
        ->orderBy('subarea.subarea_nama', 'DESC')
        ->get();
        return view('userapks.create', compact('data_subarea'));
    }

    // Menyimpan userapk baru
    public function store(Request $request)
    {
        $request->validate([
            'subarea_id' => 'required',
            'username' => 'required|unique:userapk',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        ]);

        Userapk::create([
            'subarea_id' => $request->subarea_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('userapks.index')->with('success', 'Userapk created successfully.');
    }

    // Menampilkan detail userapk
    public function show(Userapk $userapk)
    {
        return view('userapks.show', compact('userapk'));
    }

    // Form untuk edit userapk
    public function edit(Userapk $userapk)
    {
        $data_subarea = Subarea::join('area', 'area.id', '=', 'subarea.area_id')
        ->select('subarea.*', 'area.area_nama')
        ->orderBy('area.area_nama', 'DESC')
        ->orderBy('subarea.subarea_nama', 'DESC')
        ->get();
        return view('userapks.edit', compact('userapk', 'data_subarea'));
    }

    // Menyimpan perubahan userapk
    public function update(Request $request, Userapk $userapk)
    {
        $request->validate([
            'subarea_id' => 'required',
            'username' => 'required|unique:users,username,' . $userapk->id,
            'password' => 'nullable|min:6|confirmed',
        ], [
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        ]);
        
        $userapk->update([
            'subarea_id' => $request->subarea_id,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $userapk->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('userapks.index')->with('success', 'Userapk updated successfully.');
    }

    // Menghapus userapk
    public function destroy(Userapk $userapk)
    {
        $userapk->delete();
        return redirect()->route('userapks.index')->with('success', 'Userapk deleted successfully.');
    }
}
