@extends('layouts.public')
@section('title', 'GoGreen Bank — Perbankan yang mengubah sampah menjadi berkah')

@section('content')
{{-- Hero --}}
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h1 class="display-hero mb-3">Perbankan yang mengubah <span class="text-green">sampah Anda</span> menjadi berkah.</h1>
                <p class="text-muted-2 mb-4" style="max-width:30rem">Bergabunglah dengan ekonomi sirkular. Kami menyediakan platform bagi rumah tangga dan bisnis untuk memonetisasi barang daur ulang sambil memantau dampak lingkungan positif mereka.</p>
                <a href="{{ route('register') }}" class="btn btn-green btn-pill px-4 py-2">Mulai Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="col-lg-6">
                <div class="gg-card p-4 ms-lg-auto" style="max-width:320px">
                    <div class="d-flex align-items-center gap-2 mb-2 text-green fw-bold"><i class="bi bi-graph-up-arrow"></i> Dampak Real-time</div>
                    <div class="text-muted-2 small">Total Pengurangan CO2</div>
                    <div class="fw-800 text-green" style="font-size:2rem">14.2 Ton</div>
                    <div class="small text-muted-2 mt-2 pt-2 border-top">Target Bulanan: 75% tercapai</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat strip --}}
    <div class="stat-strip">
        <div class="container py-4">
            <div class="row text-center g-3">
                <div class="col-6 col-md-3"><div class="num">42.8k</div><div class="lbl">Ton Sampah Teralihkan</div></div>
                <div class="col-6 col-md-3"><div class="num">12.5k</div><div class="lbl">Pohon Diselamatkan</div></div>
                <div class="col-6 col-md-3"><div class="num">8.2k</div><div class="lbl">CO2 Dinetralkan</div></div>
                <div class="col-6 col-md-3"><div class="num">$1.4M</div><div class="lbl">Imbalan Diberikan</div></div>
            </div>
        </div>
    </div>
</section>

{{-- Katalog preview --}}
<section class="container py-5 text-center">
    <h2 class="section-title">Katalog Harga Sampah</h2>
    <p class="text-muted-2 mx-auto" style="max-width:34rem">Tarif pasar saat ini untuk barang daur ulang yang telah disortir. Harga diperbarui setiap hari berdasarkan pasar komoditas global.</p>
    <div class="row g-3 mt-2 text-start">
        @foreach ($featured as $item)
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
    <a href="{{ route('harga') }}" class="btn btn-forest btn-pill mt-4 px-4">Lihat Daftar Harga Lengkap</a>
</section>

{{-- Organisasi & Komunitas --}}
<section class="container py-5 text-center">
    <h2 class="section-title">Organisasi dan Komunitas</h2>
    <p class="text-muted-2 mx-auto" style="max-width:34rem">Kami tidak bergerak sendiri. Beberapa organisasi dan komunitas ini pernah bekerja sama dengan Bank Sampah Go Green.</p>

    @php
        $organisasi = [
            ['logo' => 'org1.png', 'nama' => 'Organisasi 1', 'ig' => 'https://www.instagram.com/getplastic_id?igsh=MTAxbTh5NTM0MTU4NA=='],
            ['logo' => 'org2.png', 'nama' => 'Organisasi 2', 'ig' => 'https://www.instagram.com/getthefest?igsh=Z2VwZGNwZmVrYXgz'],
            ['logo' => 'org3.png', 'nama' => 'Organisasi 3', 'ig' => 'https://www.instagram.com/magobox.id?igsh=MTYycDlvdDRvdDRiNg=='],
            ['logo' => 'org4.png', 'nama' => 'Organisasi 4', 'ig' => 'https://www.instagram.com/askaranusantaraorg?igsh=M291ZWZhejVsbm8z'],
            ['logo' => 'org5.png', 'nama' => 'Organisasi 5', 'ig' => 'https://www.instagram.com/rapel_id?igsh=MXJybWljNGV2eGp3Ng=='],
        ];
    @endphp

    <div class="d-flex flex-wrap justify-content-center justify-content-md-between align-items-center gap-3 mt-4">
        @foreach ($organisasi as $o)
        <a href="{{ $o['ig'] }}" target="_blank" rel="noopener" class="org-logo" title="{{ $o['nama'] }} — Instagram">
            <img src="{{ asset('img/organisasi/' . $o['logo']) }}" alt="{{ $o['nama'] }}">
        </a>
        @endforeach
    </div>
</section>

{{-- 3 langkah --}}
<section class="container py-5 text-center">
    <h2 class="section-title">Mulai Perjalanan Anda dalam 3 Langkah Sederhana</h2>
    <p class="text-muted-2 mx-auto" style="max-width:34rem">Bergabung dalam gerakan ini sangat mudah. Platform digital kami memandu Anda dari pengumpulan hingga imbalan.</p>
    <div class="row g-3 mt-2 text-start">
        @php $steps = [
            ['01','bi-collection','Sortir & Kumpulkan','Kategorikan sampah Anda menjadi kertas, plastik, dan logam menggunakan panduan mudah kami.'],
            ['02','bi-truck','Setor di Hub','Antar langsung kantong yang sudah disortir ke lokasi bank sampah.'],
            ['03','bi-cash-coin','Tukarkan Imbalan','Terima Eco-Credit secara instan di dompet GoGreen Bank Anda, dapat digunakan di berbagai mitra ritel.'],
        ]; @endphp
        @foreach ($steps as $s)
        <div class="col-md-4">
            <div class="gg-card p-4 h-100 position-relative">
                <div class="position-absolute top-0 end-0 m-3 fw-800 text-muted-2" style="font-size:2rem;opacity:.15">{{ $s[0] }}</div>
                <div class="price-icon mb-3"><i class="bi {{ $s[1] }}"></i></div>
                <div class="fw-bold mb-2">{{ $s[2] }}</div>
                <p class="text-muted-2 small mb-0">{{ $s[3] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Galeri kegiatan (geser otomatis) --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Galeri Kegiatan</h2>
            <p class="text-muted-2 mx-auto" style="max-width:34rem">Momen-momen kegiatan Bank Sampah Go Green bersama masyarakat.</p>
        </div>
        <div class="gg-marquee">
            <div class="gg-marquee-track">
                @php $galeri = ['Alb1.jpg','Alb2.jpg','Alb3.jpg','Alb4.jpg','Alb5.jpg','Alb6.jpg','Alb7.jpg']; @endphp
                @foreach ($galeri as $g)
                    <div class="gg-album-item"><img src="{{ asset('img/galeri/' . $g) }}" alt="Galeri kegiatan"></div>
                @endforeach
                {{-- duplikat set untuk animasi loop yang mulus --}}
                @foreach ($galeri as $g)
                    <div class="gg-album-item" aria-hidden="true"><img src="{{ asset('img/galeri/' . $g) }}" alt=""></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="container pb-5">
    <div class="cta-band text-center p-5">
        <h2 class="display-hero mb-3">Siap untuk memberikan dampak?</h2>
        <p class="mb-4">Bergabunglah dengan 50.000+ kontributor eco hari ini.<br>Pendaftaran memakan waktu kurang dari 2 menit.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-pill px-4 fw-bold text-green">Daftar Akun Anda</a>
    </div>
</section>
@endsection
