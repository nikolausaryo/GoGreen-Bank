@extends('layouts.dashboard')
@section('title', 'Scan QR Code — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="text-center">
    <div class="fw-bold">Pindai Kode QR Nasabah</div>
    <p class="text-muted-2 small">Arahkan kamera ke kartu QR Nasabah untuk memulai transaksi setoran.</p>
</div>

<div class="mx-auto" style="max-width:560px">
    <div class="soft-card d-flex align-items-center justify-content-center mb-3 position-relative" style="height:300px;background:var(--inverse)">
        <i class="bi bi-qr-code-scan text-white fs-1 opacity-50"></i>
        <div class="position-absolute" style="inset:40px;border:3px solid var(--green-bright);border-radius:16px"></div>
    </div>

    <div class="text-center text-muted-2 small mb-2">— ATAU MASUKKAN ID NASABAH SECARA MANUAL —</div>
    <form method="POST" action="{{ route('karyawan.scan') }}">
        @csrf
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
            <input name="member_id" class="form-control" placeholder="Contoh: GGB-9021" required>
            <button class="btn btn-green-soft px-4"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>
    <a href="{{ route('karyawan.dashboard') }}" class="d-block text-center mt-3 fw-bold" style="color:var(--danger)"><i class="bi bi-x-circle"></i> Batal & Kembali</a>

    <div class="row g-2 mt-4">
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-geo-alt text-green"></i><div class="small text-muted-2">Lokasi Akurat</div><div class="fw-bold small">Bank Sampah Go Green</div></div></div>
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-camera-video text-green"></i><div class="small text-muted-2">Koneksi Kamera</div><div class="fw-bold small">Stabil (HD)</div></div></div>
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-shield-check text-green"></i><div class="small text-muted-2">Mode Keamanan</div><div class="fw-bold small">Enkripsi Aktif</div></div></div>
    </div>
</div>
@endsection
