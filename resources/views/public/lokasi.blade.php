@extends('layouts.public')
@section('title', 'Lokasi — GoGreen Bank')

@section('content')
<section class="container py-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <h2 class="section-title mb-3">Lokasi Go Green Bank</h2>
            <p class="text-muted-2">Ubah Sampah Jadi Cuan, Gak Pakai Ribet! Cek Canggihnya Bank Sampah Kami di Sini.</p>

            {{-- Kartu lokasi: klik untuk mengganti peta di kanan --}}
            <div class="gg-card p-3 mb-3 loc-card active"
                 data-map="https://www.google.com/maps?q=Bank+Sampah+Go+Green+Jl+Menur+Cupuwatu+Purwomartani+Kalasan&output=embed">
                <div class="fw-bold">Bank Sampah Go Green</div>
                <div class="text-muted-2 small">Jl. Menur, Cupuwatu II, Purwomartani, Kalasan, 55571</div>
                <div class="small mt-1"><i class="bi bi-clock text-green"></i> Buka Minggu Ke-3, jam 10:00 - 16:00
                    <a href="https://www.google.com/maps/dir/?api=1&destination=Bank+Sampah+Go+Green+Jl+Menur+Cupuwatu+Purwomartani+Kalasan"
                       target="_blank" rel="noopener" onclick="event.stopPropagation()"
                       class="text-green fw-semibold ms-2">Petunjuk Arah</a>
                </div>
            </div>
            <div class="gg-card p-3 loc-card"
                 data-map="https://www.google.com/maps?q=Pengolahan+Sampah+Plastik+Pirolisis+Jl+Kenanga+Cupuwatu+Purwomartani+Kalasan&output=embed">
                <div class="fw-bold">Pengolahan Sampah Plastik Pirolisis</div>
                <div class="text-muted-2 small">Jl. Kenanga, Cupuwatu II, Purwomartani, Kalasan, 55571</div>
                <div class="small mt-1"><i class="bi bi-clock text-green"></i> Buka jam 08:00 - 16:00
                    <a href="https://www.google.com/maps/dir/?api=1&destination=Pengolahan+Sampah+Plastik+Pirolisis+Jl+Kenanga+Cupuwatu+Purwomartani+Kalasan"
                       target="_blank" rel="noopener" onclick="event.stopPropagation()"
                       class="text-green fw-semibold ms-2">Petunjuk Arah</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            {{-- Embed Google Maps lokasi --}}
            <iframe id="locMap"
                class="w-100 gg-card"
                style="height:420px;border:0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps?q=Bank+Sampah+Go+Green+Jl+Menur+Cupuwatu+Purwomartani+Kalasan&output=embed">
            </iframe>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="cta-band text-center p-5">
        <h2 class="display-hero mb-3">Siap untuk memberikan dampak?</h2>
        <p class="mb-4">Bergabunglah dengan 50.000+ kontributor eco hari ini.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-pill px-4 fw-bold text-green">Daftar Akun Anda</a>
    </div>
</section>

<style>
    .loc-card { cursor: pointer; border-left: 4px solid transparent; transition: border-color .15s, background .15s; }
    .loc-card:hover { background: var(--surface); }
    .loc-card.active { border-left: 4px solid var(--green); }
</style>
<script>
(function () {
    var cards = document.querySelectorAll('.loc-card');
    var map = document.getElementById('locMap');
    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            cards.forEach(function (c) { c.classList.remove('active'); });
            card.classList.add('active');
            if (map && card.dataset.map) map.src = card.dataset.map;
        });
    });
})();
</script>
@endsection
