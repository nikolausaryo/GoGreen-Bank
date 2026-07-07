@extends('layouts.dashboard')
@section('title', 'Drop Off — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge-soft"><i class="bi bi-broadcast"></i> Green-Hub Alpha · Terhubung IoT</span>
</div>
<h4 class="fw-800 mb-3">Daftar Nasabah — Sesi Drop Off</h4>

@foreach ($nasabahs as $n)
<div class="gg-card p-3 mb-2 d-flex align-items-center" style="border-left:4px solid var(--green)">
    <div class="price-icon"><i class="bi bi-person-fill"></i></div>
    <div class="ms-3">
        <div class="fw-800">{{ $n->name }}</div>
        <div class="text-muted-2 small">ID: {{ $n->member_id }} · Anggota sejak {{ $n->created_at->format('M Y') }}</div>
    </div>
    <div class="ms-auto text-end me-4 d-none d-md-block">
        <div class="text-muted-2 small">Total Kunjungan</div>
        <div class="fw-800 text-green">{{ $n->transactions_count }}</div>
    </div>
    <a href="{{ route('karyawan.input', ['user' => $n, 'from' => 'dropoff']) }}" class="btn btn-green btn-sm">Proses Setoran</a>
</div>
@endforeach

<div class="text-center text-muted-2 small my-4">— ATAU MASUKKAN ID NASABAH SECARA MANUAL —</div>
<form method="POST" action="{{ route('karyawan.scan') }}" class="mx-auto" style="max-width:520px">
    @csrf
    <input type="hidden" name="from" value="dropoff">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
        <input name="member_id" class="form-control" placeholder="Contoh: GGB-9021" required>
        <button class="btn btn-green-soft px-4"><i class="bi bi-search"></i> Cari</button>
    </div>
</form>
@endsection
