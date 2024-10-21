<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userapk extends Model
{
    use HasFactory;

    protected $fillable = ['subarea_id', 'username', 'password'];
    protected $table = 'userapk';
}