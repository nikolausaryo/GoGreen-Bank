{{--
    Widget Asisten / Panduan Halaman (statis)
    -----------------------------------------
    Menggantikan tombol recycle mengambang. Isinya menyesuaikan halaman
    secara otomatis berdasarkan nama route. Untuk mengubah teks panduan,
    cukup edit array $guides di bawah ini — tidak perlu menyentuh view lain.
--}}
@php
    $route = Route::currentRouteName();

    $guides = [
        /* ---------- Publik ---------- */
        'landing' => [
            'title' => 'Beranda',
            'intro' => 'Halo! Saya asisten GoGreen. Berikut panduan untuk halaman ini:',
            'steps' => [
                'Jelajahi menu di atas: Beranda, Misi Kami, Harga, dan Lokasi.',
                'Lihat ringkasan dampak lingkungan dan katalog harga sampah unggulan.',
                'Klik "Register" untuk menjadi nasabah, atau "Login" bila sudah punya akun.',
            ],
        ],
        'misi' => [
            'title' => 'Misi Kami',
            'intro' => 'Ingin tahu lebih dalam soal GoGreen Bank?',
            'steps' => [
                'Baca misi & kegiatan bank sampah: Maggot, Pemilahan, dan Pirolisis.',
                'Pahami konsep Tabungan-Eco: tiap kilogram sampah menjadi saldo.',
                'Tertarik bergabung? Klik "Daftar Akun Anda" di bagian bawah.',
            ],
        ],
        'harga' => [
            'title' => 'Katalog Harga',
            'intro' => 'Cek nilai jual sampah Anda di sini.',
            'steps' => [
                'Lihat harga tiap jenis sampah (per kg, biji, atau liter).',
                'Harga dikelompokkan per kategori: Plastik, Logam, Kertas, dan lainnya.',
                'Sudah tahu nilainya? Daftar jadi nasabah untuk mulai menabung.',
            ],
        ],
        'lokasi' => [
            'title' => 'Lokasi',
            'intro' => 'Mau berkunjung? Ini panduannya:',
            'steps' => [
                'Lihat alamat dan jam buka Bank Sampah Go Green pada peta.',
                'Klik "Petunjuk Arah" untuk membuka rute di Google Maps.',
                'Datang sesuai jadwal buka yang tertera di kartu lokasi.',
            ],
        ],

        /* ---------- Autentikasi ---------- */
        'login' => [
            'title' => 'Masuk',
            'intro' => 'Cara masuk ke portal GoGreen:',
            'steps' => [
                'Pilih peran: Nasabah atau Karyawan lewat tombol di atas form.',
                'Masukkan email / ID pengguna dan kata sandi Anda.',
                'Belum punya akun? Klik "Buat Akun" (khusus nasabah).',
                'Coba akun demo: sarah@gogreen.bank — kata sandi: password',
            ],
        ],
        'register' => [
            'title' => 'Pendaftaran Akun',
            'intro' => 'Yuk, daftar jadi nasabah dalam hitungan menit:',
            'steps' => [
                'Isi data diri: nama, email, nomor HP, kata sandi, dan alamat.',
                'Klik "Daftar" — ID nasabah (GGB-xxxx) dibuat otomatis untuk Anda.',
                'Selesai! Anda langsung masuk ke Dashboard Nasabah.',
            ],
        ],

        /* ---------- Nasabah ---------- */
        'nasabah.dashboard' => [
            'title' => 'Dashboard Nasabah',
            'intro' => 'Selamat datang! Berikut cara memakai dashboard Anda:',
            'steps' => [
                'Cek saldo tabungan pada kartu hijau di kiri atas.',
                'Klik "Ambil" untuk menarik saldo ke rekening bank atau e-wallet.',
                'Lihat riwayat transaksi, dan gunakan akses cepat setor di bawah.',
            ],
        ],
        'nasabah.katalog' => [
            'title' => 'Katalog Harga',
            'intro' => 'Pantau harga sampah sebelum menyetor.',
            'steps' => [
                'Lihat harga terkini semua jenis sampah di halaman ini.',
                'Jadikan acuan untuk memilah sampah yang paling bernilai.',
            ],
        ],
        'nasabah.qr' => [
            'title' => 'Kode QR Nasabah',
            'intro' => 'QR ini adalah identitas setoran Anda.',
            'steps' => [
                'Tunjukkan QR Code ini kepada petugas saat menyetor sampah.',
                'Petugas memindainya untuk mencatat setoran ke akun Anda.',
                'Bila QR gagal dipindai, sebutkan ID nasabah secara manual.',
            ],
        ],
        'nasabah.dropoff' => [
            'title' => 'Lapor Drop-off',
            'intro' => 'Menyetor tanpa bertemu petugas? Lapor di sini:',
            'steps' => [
                'Foto sampah yang Anda tinggalkan di lokasi (drop-off mandiri).',
                'Klik "Buka Berkas" untuk mengunggah foto dari galeri.',
                'Kirim laporan — petugas akan memverifikasi setoran Anda.',
            ],
        ],

        /* ---------- Karyawan ---------- */
        'karyawan.dashboard' => [
            'title' => 'Ringkasan Karyawan',
            'intro' => 'Panduan operasional harian petugas:',
            'steps' => [
                'Pantau metrik harian: total diproses, target, dan tugas tertunda.',
                'Lihat Log Verifikasi setoran serta Informasi Penarikan terbaru.',
                'Mulai layani nasabah lewat menu Scan QR Code atau Drop Off.',
            ],
        ],
        'karyawan.scan' => [
            'title' => 'Scan QR Code',
            'intro' => 'Cara memulai sesi setoran nasabah:',
            'steps' => [
                'Arahkan kamera ke QR Code nasabah untuk memulai transaksi.',
                'Atau ketik ID nasabah (GGB-xxxx) pada kolom manual lalu klik "Cari".',
                'Anda akan diarahkan ke halaman input penimbangan.',
            ],
        ],
        'karyawan.dropoff' => [
            'title' => 'Drop Off',
            'intro' => 'Mengelola antrean setoran drop-off:',
            'steps' => [
                'Pilih nasabah dari daftar sesi drop-off yang sedang aktif.',
                'Atau masukkan ID nasabah manual bila tidak muncul di daftar.',
                'Lanjutkan ke halaman input penimbangan sampahnya.',
            ],
        ],
        'karyawan.input' => [
            'title' => 'Input Penimbangan',
            'intro' => 'Langkah mencatat setoran sampah:',
            'steps' => [
                'Klik kartu jenis sampah yang sesuai pada grid kategori.',
                'Masukkan berat sampah — total kredit dihitung otomatis.',
                'Klik "Simpan": setoran tercatat dan saldo nasabah langsung bertambah.',
            ],
        ],
    ];

    $default = [
        'title' => 'Panduan',
        'intro' => 'Halo! Saya asisten GoGreen, siap memandu Anda.',
        'steps' => [
            'Gunakan menu navigasi untuk berpindah antar halaman.',
            'Klik ikon ini kapan saja untuk melihat petunjuk halaman.',
        ],
    ];

    $g = $guides[$route] ?? $default;
@endphp

<div class="gg-chatbot" id="ggChatbot">
    {{-- Panel panduan --}}
    <div class="gg-chatbot-panel" role="dialog" aria-label="Panduan halaman">
        <div class="gg-chatbot-head">
            <div class="av"><i class="bi bi-chat-dots-fill"></i></div>
            <div>
                <div class="t">Asisten GoGreen</div>
                <div class="s">Panduan penggunaan halaman</div>
            </div>
            <button type="button" class="x" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="gg-chatbot-body">
            <div class="gg-bubble">
                <span class="pg">{{ $g['title'] }}</span>
                {{ $g['intro'] }}
            </div>
            <ol class="gg-steps">
                @foreach ($g['steps'] as $i => $step)
                    <li><span class="n">{{ $i + 1 }}</span><span>{{ $step }}</span></li>
                @endforeach
            </ol>
        </div>

        <div class="gg-chatbot-foot">
            Butuh bantuan lain? Hubungi petugas Bank Sampah Go Green.
        </div>
    </div>

    {{-- Tombol pemicu --}}
    <button type="button" class="gg-chatbot-toggle" aria-label="Buka panduan halaman">
        <span class="dot"></span>
        <i class="bi bi-chat-dots-fill open-ic"></i>
        <i class="bi bi-x-lg close-ic"></i>
    </button>
</div>

<script>
(function () {
    var wrap = document.getElementById('ggChatbot');
    if (!wrap) return;
    var toggle = wrap.querySelector('.gg-chatbot-toggle');
    var closeX = wrap.querySelector('.gg-chatbot-head .x');

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        wrap.classList.toggle('open');
    });
    if (closeX) closeX.addEventListener('click', function () { wrap.classList.remove('open'); });

    // tutup saat klik di luar atau tekan Escape
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) wrap.classList.remove('open');
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') wrap.classList.remove('open');
    });
})();
</script>
