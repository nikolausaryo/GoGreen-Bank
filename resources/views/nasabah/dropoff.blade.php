@extends('layouts.dashboard')
@section('title', 'Lapor Drop Off — GoGreen Bank')
@section('sidebar') @include('partials.nasabah-sidebar') @endsection

@section('content')
<h2 class="fw-800 mb-0">Lapor Drop Off</h2>
<p class="text-muted-2">Jangan lupa bukti foto dan kode nasabahnya ya.</p>

<div class="gg-card p-5">
    <div class="text-center">
        <div class="fw-bold">Foto sampah anda</div>
        <p class="text-muted-2 small">Arahkan kamera ke sampah yang akan disetorkan.</p>
    </div>

    <form method="POST" action="{{ route('nasabah.dropoff.store') }}" enctype="multipart/form-data" class="mx-auto" style="max-width:520px">
        @csrf
        <div class="soft-card d-flex flex-column align-items-center justify-content-center mb-3" style="height:260px;background:var(--inverse)">
            <i class="bi bi-camera text-white fs-1 opacity-50"></i>
            <span class="text-white-50 small mt-2">Pratinjau kamera / unggahan</span>
        </div>

        <div class="text-center text-muted-2 small mb-2">ATAU CARI GAMBAR DI GALERI</div>
        <input type="file" name="photo" accept="image/*" class="form-control mb-3" required>
        <input name="note" class="form-control mb-3" placeholder="Keterangan (opsional)">

        <button class="btn btn-green-soft w-100 py-2"><i class="bi bi-search"></i> Buka Berkas & Kirim</button>
        <a href="{{ route('nasabah.dashboard') }}" class="d-block text-center mt-3 fw-bold" style="color:var(--danger)"><i class="bi bi-x-circle"></i> Batal & Kembali</a>
    </form>
</div>
@endsection
