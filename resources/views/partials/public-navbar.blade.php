<nav class="gg-navbar">
    <div class="container d-flex align-items-center py-3">
        <a href="{{ route('landing') }}" class="gg-brand">
            <span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green"></span> GoGreen Bank
        </a>

        <div class="d-none d-lg-flex mx-auto gap-2">
            <a href="{{ route('landing') }}" class="gg-nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('misi') }}" class="gg-nav-link {{ request()->routeIs('misi') ? 'active' : '' }}">Misi Kami</a>
            <a href="{{ route('harga') }}" class="gg-nav-link {{ request()->routeIs('harga') ? 'active' : '' }}">Harga</a>
            <a href="{{ route('lokasi') }}" class="gg-nav-link {{ request()->routeIs('lokasi') ? 'active' : '' }}">Lokasi</a>
        </div>

        <div class="ms-auto ms-lg-0 d-flex gap-2">
            <a href="{{ route('register') }}" class="btn btn-green-soft btn-sm px-3">Register</a>
            <a href="{{ route('login') }}" class="btn btn-green btn-sm px-3">Login</a>
        </div>
    </div>
</nav>
