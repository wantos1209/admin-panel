<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Userapk extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['subarea_id', 'username', 'password'];
    protected $table = 'userapk';
}
