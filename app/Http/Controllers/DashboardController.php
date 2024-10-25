<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    // Menampilkan daftar area
    public function index(Request $request)
    {
        return view('dashboard.index');
    }

}
