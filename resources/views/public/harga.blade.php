@extends('layouts.public')
@section('title', 'Katalog Harga Sampah — GoGreen Bank')

@section('content')
<section class="container py-5 text-center">
    <h2 class="section-title">Katalog Harga Sampah</h2>
    <p class="text-muted-2 mx-auto" style="max-width:34rem">Tarif pasar saat ini untuk barang daur ulang yang telah disortir. Harga diperbarui setiap hari berdasarkan pasar komoditas global.</p>

    <div class="row g-3 mt-2 text-start">
        @foreach ($all as $item)
        <div class="col-6 col-lg-3">
            <div class="price-card">
                <div class="price-icon mb-3"><i class="bi {{ $item->icon }}"></i></div>
                <div class="fw-bold">{{ $item->name }}</div>
                <div class="price-cat mb-3">Kategori {{ $item->category }}</div>
                <div><span class="price-amount">Rp {{ number_format($item->price, 0, ',', '.') }}</span> <span class="text-muted-2 small">/{{ $item->unit }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="container pb-5">
    <div class="cta-band text-center p-5">
        <h2 class="display-hero mb-3">Siap untuk memberikan dampak?</h2>
        <p class="mb-4">Bergabunglah dengan 50.000+ kontributor eco hari ini.<br>Pendaftaran memakan waktu kurang dari 2 menit.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-pill px-4 fw-bold text-green">Daftar Akun Anda</a>
    </div>
</section>
@endsection
