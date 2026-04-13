<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        $promos = \App\Models\Promo::all();
        return view('admin-promo', compact('promos'));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $promo->judul = $request->judul;
        $promo->diskon = $request->diskon;
        $promo->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('promo', 'public');
            $promo->gambar = $path;
        }

        $promo->save();

        return redirect()->back();
    }
}