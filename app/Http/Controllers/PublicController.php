<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;

class PublicController extends Controller
{
    public function landing()
    {
        // 4 item unggulan untuk preview di beranda
        $featured = WasteCategory::whereIn('name', ['PET Bersih', 'Kertas HVS', 'Alumunium', 'Botol Kaca'])->get();
        return view('public.landing', compact('featured'));
    }

    public function misi()
    {
        return view('public.misi');
    }

    public function harga()
    {
        $categories = WasteCategory::orderBy('id')->get()->groupBy('category');
        $all = WasteCategory::orderBy('id')->get();
        return view('public.harga', compact('categories', 'all'));
    }

    public function lokasi()
    {
        return view('public.lokasi');
    }
}
