@extends('layouts.dashboard')
@section('title', 'Permintaan Kartu — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<h4 class="fw-800 mb-3">Permintaan Cetak Kartu</h4>

@forelse ($requests as $req)
<div class="gg-card p-3 mb-2 d-flex align-items-center">
    <div class="price-icon"><i class="bi bi-person-vcard"></i></div>
    <div class="ms-3">
        <div class="fw-800">{{ $req->user->name }}</div>
        <div class="text-muted-2 small"><span class="badge-soft"><i class="bi bi-qr-code"></i> {{ $req->user->member_id }}</span> · Diajukan {{ $req->created_at->format('d M Y, H:i') }}</div>
    </div>
    <a href="{{ route('karyawan.card.show', $req) }}" class="btn btn-green btn-sm ms-auto">Cetak Kartu</a>
</div>
@empty
<div class="gg-card p-4 text-center text-muted-2">Belum ada permintaan cetak kartu.</div>
@endforelse
@endsection