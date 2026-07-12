@extends('layouts.dashboard')
@section('title', 'Kode QR Nasabah — GoGreen Bank')
@section('sidebar') @include('partials.nasabah-sidebar') @endsection

@section('content')
<h2 class="fw-800 mb-0">Kode QR Nasabah</h2>
<p class="text-muted-2">Jaga kode QR anda, jangan sampai orang lain mengetahuinya.</p>

<div class="gg-card p-4 p-md-5 text-center">
    <div class="fw-bold">Serahkan Kode QR Ke Operator</div>
    <p class="text-muted-2 small">Perlihatkan kode QR anda bila ingin melakukan setoran sampah.</p>

    <div class="qr-box my-4 mx-auto" style="max-width:280px;width:100%">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($user->member_id) }}" alt="QR {{ $user->member_id }}" style="width:100%;height:auto;display:block">
    </div>

    <div class="text-muted-2 small mb-2">ID NASABAH MANUAL</div>
    <div class="mx-auto" style="max-width:420px">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
            <input class="form-control text-center" value="{{ $user->member_id }}" readonly>
        </div>
    </div>

    <a href="{{ route('nasabah.dashboard') }}" class="d-inline-block mt-4 fw-bold" style="color:var(--danger)"><i class="bi bi-x-circle"></i> Batal & Kembali</a>
</div>
@endsection
