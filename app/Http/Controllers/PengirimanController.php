<?php

namespace App\Http\Controllers;

use App\Exports\PengirimanDetailExport;
use App\Models\Pengiriman;
use App\Models\Pengirimandetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PengirimanController extends Controller
{
    // Menampilkan daftar pengiriman
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $pengirimans = Pengiriman::when($search, function ($query, $search) {
            return $query->where('userapk.username', 'like', '%' . $search . '%')
                ->orWhere('subarea.subarea_nama', 'like', '%' . $search . '%')
                ->orWhere('area.area_nama', 'like', '%' . $search . '%');
        })
        ->join('userapk', 'userapk.id', '=', 'pengiriman.userapk_id')
        ->join('subarea', 'subarea.id', '=', 'userapk.subarea_id')
        ->join('area', 'area.id', '=', 'subarea.area_id')
        ->select('pengiriman.*', 'area.area_nama', 'subarea.subarea_nama', 'userapk.username')
        ->orderBy('pengiriman.created_at', 'DESC')
        ->paginate(20); // Mengatur jumlah item per halaman

        return view('pengirimans.index', compact('pengirimans', 'search'));
    }

    // Form untuk membuat pengiriman baru
    public function create()
    {
        // return view('pengirimans.create');
    }

    // Menyimpan pengiriman baru
    public function store(Request $request)
    {
        // $request->validate([
        //     'pengiriman_nama' => 'required|unique:pengiriman',
        // ], [
        //     'pengiriman_nama.unique' => 'Nama Kota sudah digunakan, silakan pilih yang lain.',
        // ]);

        // Pengiriman::create([
        //     'pengiriman_nama' => strtoupper($request->pengiriman_nama)
        // ]);
        
        // return redirect()->route('pengirimans.index')->with('success', 'Pengiriman created successfully.');
    }

    // Menampilkan detail pengiriman
    public function show(Pengiriman $pengiriman)
    {
        // return view('pengirimans.show', compact('pengiriman'));
    }

    // Form untuk edit pengiriman
    public function edit(Pengiriman $pengiriman)
    {
        // return view('pengirimans.edit', compact('pengiriman'));
    }

    // Menyimpan perubahan pengiriman
    public function update(Request $request, Pengiriman $pengiriman)
    {
        // $request->validate([
        //     'pengiriman_nama' => 'required|unique:pengiriman,pengiriman_nama,' . $pengiriman->id,
        // ], [
        //     'pengiriman_nama.unique' => 'Nama Kota sudah digunakan, silakan pilih yang lain.',
        // ]);
        
        // $pengiriman->update([
        //     'pengiriman_nama' => strtoupper($request->pengiriman_nama),
        // ]);

        // return redirect()->route('pengirimans.index')->with('success', 'Pengiriman updated successfully.');
    }

    // Menghapus pengiriman
    public function destroy(Pengiriman $pengiriman)
    {
        $pengiriman->delete();
        return redirect()->route('pengirimans.index')->with('success', 'Pengiriman deleted successfully.');
    }

    public function getDetails($id)
    {
        // Ambil data dari tabel pengirimandetail berdasarkan pengiriman_id
        $details = PengirimanDetail::join('subarea', 'subarea.id', '=', 'pengirimandetail.subarea_id')
            ->select('pengirimandetail.*', 'subarea.subarea_nama')
            ->where('pengirimandetail.pengiriman_id', $id)
            ->get();

        // Cek apakah data ditemukan
        if ($details->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Kembalikan response JSON jika data ditemukan
        return response()->json([
            'success' => true,
            'details' => $details
        ]);
    }

    public function export($id)
    {
        $details = PengirimanDetail::join('subarea', 'subarea.id', '=', 'pengirimandetail.subarea_id')
            ->select('pengirimandetail.id', 'pengirimandetail.no_stt', 'subarea.subarea_nama')
            ->where('pengirimandetail.pengiriman_id', $id)
            ->get();

        if ($details->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        return Excel::download(new PengirimanDetailExport($details), 'pengiriman_detail.xlsx');
    }
}
