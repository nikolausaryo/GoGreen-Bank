@extends('layouts.dashboard')
@section('title', 'Pengaturan — GoGreen Bank')
@section('sidebar') @include(Auth::user()->isKaryawan() ? 'partials.karyawan-sidebar' : 'partials.nasabah-sidebar') @endsection

@section('content')
<h4 class="fw-800 mb-1">Pengaturan Profil</h4>
<p class="text-muted-2 small mb-4">Perbarui nama dan foto profil akun Anda.</p>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="gg-card p-4">
            @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="settings-avatar">
                        <img id="avatarPreview"
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/logo.png') }}"
                             alt="Foto profil">
                    </div>
                    <div>
                        <label class="btn btn-green-soft btn-sm mb-1">
                            <i class="bi bi-camera"></i> Pilih Foto
                            <input type="file" name="avatar" accept="image/*" id="avatarInput" hidden>
                        </label>
                        <div class="text-muted-2" style="font-size:.75rem">Format JPG/PNG, maksimal 2 MB.</div>
                    </div>
                </div>

                <label class="form-label small fw-semibold">Nama</label>
                <input type="text" name="name" class="form-control form-control-lg mb-3"
                       value="{{ old('name', $user->name) }}" required>

                <label class="form-label small fw-semibold">Email</label>
                <input type="text" class="form-control mb-4" value="{{ $user->email }}" disabled>

                <button type="submit" class="btn btn-forest w-100 py-3 fw-bold">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

@if (session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" style="border:0;border-radius:20px">
            <div class="mx-auto mb-3" style="width:64px;height:64px;background:var(--green-soft);border-radius:50%;display:grid;place-items:center">
                <i class="bi bi-check-lg text-green" style="font-size:2rem"></i>
            </div>
            <h5 class="fw-800 mb-1">Berhasil!</h5>
            <p class="text-muted-2 small">{{ session('success') }}</p>
            <button class="btn btn-forest w-100 py-2 fw-bold" data-bs-dismiss="modal">Selesai</button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<style>
    .settings-avatar { width:88px; height:88px; border-radius:50%; overflow:hidden; border:1px solid var(--line); flex-shrink:0; background:var(--green-soft); }
    .settings-avatar img { width:100%; height:100%; object-fit:cover; display:block; }
</style>
<script>
    const inp  = document.getElementById('avatarInput');
    const prev = document.getElementById('avatarPreview');
    if (inp) inp.addEventListener('change', e => {
        const f = e.target.files[0];
        if (f) prev.src = URL.createObjectURL(f);
    });
    @if (session('success'))
    new bootstrap.Modal(document.getElementById('successModal')).show();
    @endif
</script>
@endpush
@endsection