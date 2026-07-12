<nav class="gg-navbar">
    <div class="container py-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('landing') }}" class="gg-brand">
                <span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green"></span> GoGreen Bank
            </a>

            {{-- Menu tengah (hanya desktop) --}}
            <div class="d-none d-lg-flex mx-auto gap-2">
                <a href="{{ route('landing') }}" class="gg-nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('misi') }}" class="gg-nav-link {{ request()->routeIs('misi') ? 'active' : '' }}">Misi Kami</a>
                <a href="{{ route('harga') }}" class="gg-nav-link {{ request()->routeIs('harga') ? 'active' : '' }}">Harga</a>
                <a href="{{ route('lokasi') }}" class="gg-nav-link {{ request()->routeIs('lokasi') ? 'active' : '' }}">Lokasi</a>
            </div>

            {{-- Tombol kanan (hanya desktop) --}}
            <div class="d-none d-lg-flex gap-2">
                <a href="{{ route('register') }}" class="btn btn-green-soft btn-sm px-3">Register</a>
                <a href="{{ route('login') }}" class="btn btn-green btn-sm px-3">Login</a>
            </div>

            {{-- Tombol hamburger (hanya HP) --}}
            <button class="btn btn-green-soft btn-sm ms-auto d-lg-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#ggNavMobile"
                    aria-controls="ggNavMobile" aria-expanded="false" aria-label="Buka menu">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>

        {{-- Menu HP yang muncul saat hamburger ditekan --}}
        <div class="collapse d-lg-none" id="ggNavMobile">
            <div class="d-flex flex-column gap-2 mt-3">
                <a href="{{ route('landing') }}" class="gg-nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('misi') }}" class="gg-nav-link {{ request()->routeIs('misi') ? 'active' : '' }}">Misi Kami</a>
                <a href="{{ route('harga') }}" class="gg-nav-link {{ request()->routeIs('harga') ? 'active' : '' }}">Harga</a>
                <a href="{{ route('lokasi') }}" class="gg-nav-link {{ request()->routeIs('lokasi') ? 'active' : '' }}">Lokasi</a>
                <hr class="my-2">
                <a href="{{ route('register') }}" class="btn btn-green-soft btn-sm">Register</a>
                <a href="{{ route('login') }}" class="btn btn-green btn-sm">Login</a>
            </div>
        </div>
    </div>
</nav>