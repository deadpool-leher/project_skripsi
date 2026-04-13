<?php

namespace App\Http\Controllers;

use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $promos = Promo::where('aktif', 1)->get();

        return view('home', compact('promos'));
    }
}