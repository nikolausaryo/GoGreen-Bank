@extends('layouts.dashboard')
@section('title', ($category->exists ? 'Ubah' : 'Tambah') . ' Jenis Sampah — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
@php $isEdit = $category->exists; @endphp

<a href="{{ route('karyawan.categories.index') }}" class="text-muted-2 fw-semibold small d-inline-flex align-items-center gap-1 mb-3">
    <i class="bi bi-arrow-left"></i> Kembali ke daftar
</a>

<h2 class="fw-800 mb-0">{{ $isEdit ? 'Ubah Jenis Sampah' : 'Tambah Jenis Sampah' }}</h2>
<p class="text-muted-2">{{ $isEdit ? 'Perbarui data jenis sampah beserta harganya.' : 'Lengkapi data jenis sampah baru beserta harganya.' }}</p>

<div class="gg-card p-4" style="max-width:640px">
    <form method="POST"
          action="{{ $isEdit ? route('karyawan.categories.update', $category) : route('karyawan.categories.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        {{-- Pratinjau + Nama --}}
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="price-icon" style="width:56px;height:56px;font-size:1.5rem">
                <i class="bi {{ old('icon', $category->icon ?: 'bi-recycle') }}" id="iconPreview"></i>
            </div>
            <div class="flex-grow-1">
                <label class="form-label fw-bold mb-1">Nama Jenis Sampah</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="form-control" placeholder="Contoh: PET Bersih" required>
            </div>
        </div>

        <div class="row g-3">
            {{-- Kategori --}}
            <div class="col-sm-6">
                <label class="form-label fw-bold mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $category->category) }}"
                       class="form-control" list="kategoriList" placeholder="Contoh: Plastik" required>
                <datalist id="kategoriList">
                    <option value="Plastik"><option value="Kaca"><option value="Logam">
                    <option value="Kertas"><option value="Lainnya">
                </datalist>
            </div>

            {{-- Satuan --}}
            <div class="col-sm-6">
                <label class="form-label fw-bold mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', $category->unit ?: 'kg') }}"
                       class="form-control" list="satuanList" placeholder="kg" required>
                <datalist id="satuanList">
                    <option value="kg"><option value="biji"><option value="liter">
                </datalist>
            </div>

            {{-- Harga --}}
            <div class="col-sm-6">
                <label class="form-label fw-bold mb-1">Harga per Satuan</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $category->price) }}"
                           class="form-control" min="0" step="100" placeholder="0" required>
                </div>
            </div>

            {{-- Ikon --}}
            <div class="col-sm-6">
                <label class="form-label fw-bold mb-1">Ikon <span class="text-muted-2 fw-normal small">(opsional)</span></label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                       class="form-control" list="iconList" placeholder="bi-recycle" id="iconInput">
                <datalist id="iconList">
                    <option value="bi-recycle"><option value="bi-droplet"><option value="bi-droplet-half">
                    <option value="bi-droplet-fill"><option value="bi-moisture"><option value="bi-cup-straw">
                    <option value="bi-cup"><option value="bi-tools"><option value="bi-newspaper">
                    <option value="bi-archive"><option value="bi-box-seam"><option value="bi-bag">
                </datalist>
                <div class="form-text">Nama ikon Bootstrap Icons. Kosongkan untuk memakai ikon default.</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-forest px-4">
                <i class="bi bi-check-lg me-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
            <a href="{{ route('karyawan.categories.index') }}" class="btn btn-outline-forest">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    var input = document.getElementById('iconInput');
    var preview = document.getElementById('iconPreview');
    if (input && preview) {
        input.addEventListener('input', function () {
            var val = input.value.trim() || 'bi-recycle';
            preview.className = 'bi ' + val;
        });
    }
})();
</script>
@endpush
@endsection