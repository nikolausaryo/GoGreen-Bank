@extends('layouts.dashboard')
@section('title', 'Input Setoran — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge-soft"><i class="bi bi-broadcast"></i> Green-Hub Alpha · Terhubung IoT</span>
</div>

{{-- Profil nasabah yang dilayani --}}
<div class="gg-card p-3 mb-3 d-flex align-items-center" style="border-left:4px solid var(--green)">
    <div class="price-icon"><i class="bi bi-person-fill"></i></div>
    <div class="ms-3">
        <div class="fw-800 fs-5">{{ $user->name }}</div>
        <div class="text-muted-2 small">ID: {{ $user->member_id }} · Anggota sejak {{ $user->created_at->format('M Y') }}</div>
    </div>
    <div class="ms-auto text-end">
        <div class="text-muted-2 small">Status Sesi</div>
        <div class="fw-bold text-green"><i class="bi bi-circle-fill" style="font-size:.5rem"></i> Aktif Melayani</div>
    </div>
</div>

<div class="row g-3">
    {{-- Menu penimbangan (kiri) --}}
    <div class="col-lg-8">
        <div class="gg-card p-4">
            <h5 class="fw-800 mb-0">Menu Penimbangan Otomatis</h5>
            <p class="text-muted-2 small">Pilih kategori sampah — berat akan terbaca otomatis dari timbangan, lalu klik "Tambah ke Ringkasan".</p>

            <div class="waste-grid">
                @foreach ($categories as $c)
                <div class="waste-item" data-id="{{ $c->id }}" data-price="{{ $c->price }}" data-unit="{{ $c->unit }}" data-name="{{ $c->name }}">
                    <div class="ic"><i class="bi {{ $c->icon }}"></i></div>
                    <div class="nm">{{ $c->name }}</div>
                    <div class="pr">{{ $c->price }}/{{ $c->unit }}</div>
                </div>
                @endforeach
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Berat (kg, liter, biji)</label>
                    <input type="number" step="0.01" id="weight" class="form-control form-control-lg fw-bold" value="0.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Total Kredit (Rupiah)</label>
                    <input type="text" id="credit" class="form-control form-control-lg fw-bold text-green" value="0" readonly>
                </div>
            </div>

            <button type="button" id="addBtn" class="btn btn-forest w-100 py-3 mt-3 fw-bold" disabled>
                <i class="bi bi-plus-lg"></i> Tambah ke Ringkasan
            </button>
        </div>
    </div>

    {{-- Ringkasan Setoran (kanan) --}}
    <div class="col-lg-4">
        <form method="POST" action="{{ route('karyawan.input.store') }}" id="depositForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="method" value="{{ request('from') === 'dropoff' ? 'drop_off' : 'scan_qr' }}">
            <div id="itemsHolder"></div>

            <div class="gg-card p-4">
                <h5 class="fw-800 mb-3">Ringkasan Setoran</h5>

                <div id="cartEmpty" class="soft-card p-3 mb-3 text-center text-muted-2 small">
                    Belum ada sampah ditambahkan.<br>Pilih kategori lalu klik "Tambah ke Ringkasan".
                </div>

                <div id="cartList" class="mb-3"></div>

                <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
                    <span class="fw-semibold">Total Kredit</span>
                    <span class="fw-800 text-green fs-5" id="cartTotal">Rp 0</span>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-wallet2 text-green"></i>
                    <div>
                        <div class="text-muted-2 small">Saldo Tabungan Saat Ini</div>
                        <div class="fw-bold">Rp {{ number_format($user->balance, 0, ',', '.') }}</div>
                    </div>
                </div>

                <button type="submit" id="saveBtn" class="btn btn-forest w-100 py-3 fw-bold" disabled>
                    <i class="bi bi-safe2"></i> Masukkan ke Tabungan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Popup Berhasil --}}
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
<script>
    const items      = document.querySelectorAll('.waste-item');
    const weightEl   = document.getElementById('weight');
    const creditEl   = document.getElementById('credit');
    const addBtn     = document.getElementById('addBtn');
    const saveBtn    = document.getElementById('saveBtn');
    const cartList   = document.getElementById('cartList');
    const cartEmpty  = document.getElementById('cartEmpty');
    const cartTotal  = document.getElementById('cartTotal');
    const itemsHolder= document.getElementById('itemsHolder');

    const rupiah = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
    let selected = null;
    let cart = [];
    let animTimer = null;

    function recalcCredit() {
        const w = parseFloat(weightEl.value) || 0;
        const total = selected ? w * selected.price : 0;
        creditEl.value = Math.round(total).toLocaleString('id-ID');
        addBtn.disabled = !(selected && w > 0);
    }

    // Simulasi timbangan IoT: berat bergerak otomatis saat kategori dipilih
    function simulateScale(unit) {
        if (animTimer) clearInterval(animTimer);
        const target = unit === 'biji'
            ? Math.floor(Math.random() * 5) + 1
            : Math.round((Math.random() * 14 + 1) * 100) / 100;
        const steps = 25;
        let i = 0;
        animTimer = setInterval(() => {
            i++;
            const val = target * (i / steps);
            weightEl.value = unit === 'biji' ? Math.round(val) : val.toFixed(2);
            recalcCredit();
            if (i >= steps) {
                clearInterval(animTimer);
                weightEl.value = unit === 'biji' ? target : target.toFixed(2);
                recalcCredit();
            }
        }, 20);
    }

    items.forEach(el => el.addEventListener('click', () => {
        items.forEach(x => x.classList.remove('selected'));
        el.classList.add('selected');
        selected = {
            id: el.dataset.id,
            price: parseFloat(el.dataset.price),
            unit: el.dataset.unit,
            name: el.dataset.name,
        };
        simulateScale(selected.unit);
    }));

    weightEl.addEventListener('input', recalcCredit);

    // "Tambah ke Ringkasan" -> tampung dulu (belum masuk database)
    addBtn.addEventListener('click', () => {
        const w = parseFloat(weightEl.value) || 0;
        if (!selected || w <= 0) return;
        cart.push({
            id: selected.id, name: selected.name, unit: selected.unit,
            weight: w, amount: Math.round(w * selected.price),
        });
        renderCart();
        items.forEach(x => x.classList.remove('selected'));
        selected = null;
        weightEl.value = '0.00';
        creditEl.value = '0';
        addBtn.disabled = true;
    });

    function renderCart() {
        cartList.innerHTML = '';
        itemsHolder.innerHTML = '';
        let total = 0;
        cart.forEach((it, idx) => {
            total += it.amount;
            const row = document.createElement('div');
            row.className = 'd-flex justify-content-between align-items-center soft-card p-2 mb-2';
            row.innerHTML =
                '<div><div class="fw-semibold small">' + it.name + '</div>' +
                '<div class="text-muted-2" style="font-size:.75rem">' + it.weight + ' ' + it.unit + '</div></div>' +
                '<div class="text-end"><div class="fw-bold text-green small">' + rupiah(it.amount) + '</div>' +
                '<button type="button" class="btn btn-sm text-danger p-0" data-idx="' + idx + '" style="font-size:.72rem">Hapus</button></div>';
            cartList.appendChild(row);
            itemsHolder.insertAdjacentHTML('beforeend',
                '<input type="hidden" name="items[' + idx + '][waste_category_id]" value="' + it.id + '">' +
                '<input type="hidden" name="items[' + idx + '][weight]" value="' + it.weight + '">');
        });
        cartTotal.textContent = rupiah(total);
        cartEmpty.style.display = cart.length ? 'none' : 'block';
        saveBtn.disabled = cart.length === 0;

        cartList.querySelectorAll('button[data-idx]').forEach(b =>
            b.addEventListener('click', () => {
                cart.splice(parseInt(b.dataset.idx), 1);
                renderCart();
            }));
    }

    @if (session('success'))
    new bootstrap.Modal(document.getElementById('successModal')).show();
    @endif
</script>
@endpush
@endsection