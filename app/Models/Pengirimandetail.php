<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengirimandetail extends Model
{
    use HasFactory;

    protected $fillable = ['pengiriman_id', 'subarea_id', 'no_stt'];
    protected $table = 'pengirimandetail';
}
