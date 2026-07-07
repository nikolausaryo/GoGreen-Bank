@extends('layouts.public')
@section('title', 'Misi Kami — GoGreen Bank')

@section('content')
<section class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="soft-card overflow-hidden" style="height:380px;padding:0">
                <video controls playsinline preload="metadata"
                       poster="{{ asset('img/misipembuka.jpeg') }}"
                       style="width:100%;height:100%;object-fit:cover;display:block;background:var(--container)">
                    <source src="{{ asset('video/misi.mp4') }}" type="video/mp4">
                    Maaf, browser Anda tidak mendukung pemutaran video.
                </video>
            </div>
        </div>
        <div class="col-lg-6">
            <span class="badge-soft mb-3 d-inline-flex align-items-center gap-2"><i class="bi bi-shield-check"></i> Misi Kami</span>
            <h2 class="section-title mb-3">Membangun Masa Depan Bebas Sampah Melalui Keuangan Sirkular</h2>
            <p class="text-muted-2">Di GoGreen Bank, kami percaya bahwa sampah hanyalah sumber daya yang berada di tempat yang salah. Kami telah membangun jembatan antara aksi lingkungan dan manfaat ekonomi. Dengan menganggap barang daur ulang sebagai deposit yang berharga, kami memberi insentif kepada masyarakat untuk berpartisipasi dalam praktik berkelanjutan.</p>
            <div class="row g-3 mt-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-2 fw-bold mb-1"><i class="bi bi-recycle text-green"></i> Pemulihan Sumber Daya</div>
                    <p class="text-muted-2 small mb-0">Mengembalikan plastik, kertas, dan logam ke dalam siklus produksi.</p>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-2 fw-bold mb-1"><i class="bi bi-wallet2 text-green"></i> Tabungan-Eco</div>
                    <p class="text-muted-2 small mb-0">Tabungan akumulasi untuk setiap kilogram sampah yang disetorkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5 text-center">
    <h2 class="section-title">Kegiatan Bank Sampah Go Green</h2>
    <p class="text-muted-2 mx-auto" style="max-width:34rem">Tidak hanya melakukan tabungan sampah, kami juga melakukan berbagai kegiatan dalam menjaga ekosistem lingkungan</p>
    <div class="row g-3 mt-2">
        @foreach (['Maggot' => 'maggot.jpg', 'Pemilahan' => 'pemilahan.jpg', 'Pirolisis' => 'pirolisis.jpg'] as $label => $foto)
        <div class="col-md-4">
            <div class="soft-card overflow-hidden position-relative" style="height:240px;padding:0">
                <img src="{{ asset('img/kegiatan/' . $foto) }}" alt="Kegiatan {{ $label }}"
                     style="width:100%;height:100%;object-fit:cover;display:block">
                <span class="badge-soft position-absolute" style="bottom:18px;left:50%;transform:translateX(-50%)">{{ $label }}</span>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="container pb-5">
    <div class="cta-band text-center p-5">
        <h2 class="display-hero mb-3">Siap untuk memberikan dampak?</h2>
        <p class="mb-4">Bergabunglah dengan 50.000+ kontributor eco hari ini.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-pill px-4 fw-bold text-green">Daftar Akun Anda</a>
    </div>
</section>
@endsection
