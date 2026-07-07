@extends('layouts.dashboard')
@section('title', 'Katalog Harga — GoGreen Bank')
@section('sidebar') @include('partials.nasabah-sidebar') @endsection

@section('content')
<h2 class="fw-800 mb-0">Katalog Harga</h2>
<p class="text-muted-2">Pantau terus katalog harga setiap harinya.</p>

<div class="gg-card p-4">
    <div class="row g-2">
        @foreach ($items as $item)
        <div class="col-md-6">
            <div class="price-row">
                <div class="price-icon"><i class="bi {{ $item->icon }}"></i></div>
                <div class="fw-bold">{{ $item->name }}</div>
                <div class="ms-auto text-green fw-800">Rp {{ number_format($item->price, 0, ',', '.') }} <span class="text-muted-2 fw-normal small">/ {{ $item->unit }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
