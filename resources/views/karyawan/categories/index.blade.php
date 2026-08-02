@extends('layouts.dashboard')
@section('title', 'Kelola Jenis Sampah — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
    <div>
        <h2 class="fw-800 mb-0">Kelola Jenis Sampah</h2>
        <p class="text-muted-2 mb-0">Atur data master jenis sampah beserta harga yang dipakai pada seluruh transaksi.</p>
    </div>
    <a href="{{ route('karyawan.categories.create') }}" class="btn btn-forest">
        <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Sampah
    </a>
</div>

<div class="gg-card p-0 mt-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr class="text-muted-2" style="font-size:.82rem">
                    <th class="ps-4 py-3">Jenis Sampah</th>
                    <th class="py-3">Kategori</th>
                    <th class="py-3 text-end">Harga</th>
                    <th class="py-3">Satuan</th>
                    <th class="py-3 text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $item)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="price-icon"><i class="bi {{ $item->icon }}"></i></div>
                            <div class="fw-bold">{{ $item->name }}</div>
                        </div>
                    </td>
                    <td class="py-3">{{ $item->category }}</td>
                    <td class="py-3 text-end text-green fw-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-3">/ {{ $item->unit }}</td>
                    <td class="py-3 text-end pe-4">
                        <a href="{{ route('karyawan.categories.edit', $item) }}"
                           class="btn btn-sm btn-outline-forest" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('karyawan.categories.destroy', $item) }}"
                              class="d-inline"
                              data-confirm="Hapus jenis sampah &quot;{{ $item->name }}&quot;? Tindakan ini tidak dapat dibatalkan."
                              data-confirm-title="Hapus Jenis Sampah"
                              data-confirm-ok="Ya, Hapus">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted-2 py-5">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:1.8rem"></i>
                        Belum ada jenis sampah. Klik <span class="fw-bold">Tambah Jenis Sampah</span> untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($categories->count())
<p class="text-muted-2 small mt-3 mb-0">Total {{ $categories->count() }} jenis sampah terdaftar.</p>
@endif
@endsection
