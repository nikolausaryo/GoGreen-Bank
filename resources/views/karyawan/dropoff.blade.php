@extends('layouts.dashboard')
@section('title', 'Drop Off — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge-soft"><i class="bi bi-broadcast"></i> Green-Hub Alpha · Terhubung IoT</span>
</div>

<h4 class="fw-800 mb-3">Laporan Drop-Off Masuk</h4>
@forelse ($reports as $r)
<div class="gg-card p-3 mb-2 d-flex align-items-start gap-3">
    <a href="{{ asset('storage/' . $r->photo_path) }}" target="_blank" class="flex-shrink-0">
        <img src="{{ asset('storage/' . $r->photo_path) }}" alt="Foto drop-off"
             style="width:84px;height:84px;object-fit:cover;border-radius:12px;border:1px solid #e9ecef">
    </a>
    <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="fw-800">{{ $r->user->name }}</span>
            <span class="badge-soft"><i class="bi bi-qr-code"></i> {{ $r->user->member_id }}</span>
        </div>
        <div class="text-muted-2 small mb-1">{{ $r->note ?: 'Tanpa keterangan.' }}</div>
        <div class="text-muted-2 small">{{ $r->created_at->format('d M Y, H:i') }}</div>
    </div>
    <div class="text-end flex-shrink-0">
        <span class="badge-status badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
        @if ($r->status === 'menunggu')
        <form method="POST" action="{{ route('karyawan.dropoff.verify', $r) }}" class="mt-2"
              data-confirm="Verifikasi laporan drop-off dari {{ $r->user->name }} ({{ $r->user->member_id }})?"
              data-confirm-title="Verifikasi Drop-Off"
              data-confirm-ok="Ya, Verifikasi">
            @csrf
            <button type="submit" class="btn btn-sm btn-forest">Verifikasi</button>
        </form>
        @endif
    </div>
</div>
@empty
<div class="gg-card p-4 text-center text-muted-2">Belum ada laporan drop-off masuk.</div>
@endforelse

<hr class="my-4">

<h4 class="fw-800 mb-3">Daftar Nasabah — Sesi Drop Off</h4>

@foreach ($nasabahs as $n)
<div class="gg-card p-3 mb-2 d-flex align-items-center" style="border-left:4px solid var(--green)">
    <div class="price-icon overflow-hidden">
        @if ($n->avatar)
            <img src="{{ asset('storage/' . $n->avatar) }}" alt="Foto {{ $n->name }}" style="width:100%;height:100%;object-fit:cover">
        @else
            <i class="bi bi-person-fill"></i>
        @endif
    </div>
    <div class="ms-3">
        <div class="fw-800">{{ $n->name }}</div>
        <div class="text-muted-2 small"><span class="badge-soft"><i class="bi bi-qr-code"></i> {{ $n->member_id }}</span> · Anggota sejak {{ $n->created_at->format('M Y') }}</div>
    </div>
    <div class="ms-auto text-end me-4 d-none d-md-block">
        <div class="text-muted-2 small">Total Kunjungan</div>
        <div class="fw-800 text-green">{{ $n->transactions_count }}</div>
    </div>
    <a href="{{ route('karyawan.input', ['user' => $n, 'from' => 'dropoff']) }}" class="btn btn-green btn-sm">Proses Setoran</a>
</div>
@endforeach

@endsection
