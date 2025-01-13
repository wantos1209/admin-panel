<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $fillable = ['userapk_id', 'subarea_id', 'nomor'];
    protected $table = 'pengiriman';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

     // Event: generate nomor saat create
     protected static function booted()
     {
         static::creating(function ($pengiriman) {
             // Ambil nomor terakhir yang ada di database (diurutkan berdasarkan nomor, desimal)
             $lastPengiriman = Pengiriman::latest('nomor')->first();
 
             // Generate nomor baru
             $newNomor = $pengiriman->generateNomor($lastPengiriman ? $lastPengiriman->nomor : null);
 
             // Assign nomor ke model
             $pengiriman->nomor = $newNomor;
         });
     }

     // Fungsi untuk generate nomor
    public function generateNomor($lastNumber)
    {
        $lastYear = $lastNumber ? substr($lastNumber, 1, 2) : null;
        $lastMonth = $lastNumber ? substr($lastNumber, 3, 2) : null;
        $lastSeq = $lastNumber ? (int)substr($lastNumber, 5, 3) : 0;

        $currentYear = date("y");
        $currentMonth = date("m");

        // Jika tahun dan bulan sama, increment sequence, jika tidak reset ke 1
        if ($lastYear == $currentYear && $lastMonth == $currentMonth) {
            $lastSeq++;
        } else {
            $lastSeq = 1;
        }

        // Generate nomor baru
        $newNumber = $currentYear . $currentMonth . str_pad($lastSeq, 3, '0', STR_PAD_LEFT);

        // Pastikan nomor unik
        while (Pengiriman::where('nomor', $newNumber)->exists()) {
            $lastSeq++;
            $newNumber = $currentYear . $currentMonth . str_pad($lastSeq, 3, '0', STR_PAD_LEFT);
        }

        return $newNumber;
    }

    public function pengirimandetails()
    {
        return $this->hasMany(Pengirimandetail::class, 'pengiriman_id', 'id');
    }

    public function subareas()
    {
        return $this->belongsTo(Subarea::class, 'subarea_id', 'id');
    }
}
