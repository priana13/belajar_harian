# TaskProject — BISI Online (belajar_harian)

> Dokumen status & checklist kesiapan produksi.
> Terakhir dianalisa: **2 Agustus 2026** · Branch: `main` · Commit terakhir: `949a462` (3 Juli 2026)

---

## 1. Ringkasan Status

Aplikasi **sudah berjalan di produksi** dan fitur bisnis intinya (pendaftaran, angkatan, materi
harian, ujian/kuis, nilai, sertifikat, roadmap, group, absensi kegiatan, admin panel Filament)
sudah lengkap. Yang belum matang adalah **lapisan pendukung produksi**: keamanan, testing,
CI/CD, observability, dan dokumentasi.

**Estimasi kesiapan produksi keseluruhan: ± 72%**

| Area | Bobot | Progres | Catatan singkat |
|---|---:|---:|---|
| Fitur inti (domain belajar) | 30% | **90%** | Lengkap & dipakai user; sisa perbaikan bug kecil |
| Keamanan & otorisasi | 20% | **60%** | Ada celah otorisasi route & reset password |
| Kualitas kode / kebersihan | 15% | **55%** | Sisa `dd()`, kode mati, relasi rusak, kode terkomentar |
| Testing otomatis | 15% | **20%** | 46 test lulus, tapi hanya auth/home/profile |
| DevOps, CI/CD, deploy | 10% | **40%** | Ada Docker, belum ada CI, belum ada worker queue |
| Performa & observability | 5% | **50%** | Telescope ada, belum ada monitoring/error tracking |
| Dokumentasi | 5% | **25%** | README masih menyebut versi lama |

**Cara pakai dokumen ini:** centang `[x]` bila sudah selesai. Item ditulis berurutan dari yang
paling mendesak (P0) ke paling ringan (P3).

---

## 2. Yang SUDAH Selesai

### 2.1 Fitur & Domain
- [x] Autentikasi lengkap (login, register, forgot/reset password, verifikasi email, confirm password)
- [x] Login sosial via Laravel Socialite (`/auth/redirect`, `/auth/callback`)
- [x] Pendaftaran peserta + pendaftaran per-angkatan lewat kode (`/daftar/{kode_daftar}`)
- [x] Master data: Angkatan, Kelas, Gelombang, Group, Jenis User, Jenis Ujian, Kategori Materi, Kategori Kegiatan, Struktur, Provinsi/Kota
- [x] Modul Materi: Materi, Materi Detail, gambar materi, materi audio, materi video, link materi harian
- [x] Modul Roadmap: Roadmap, Roadmap Materi, Gelombang Roadmap, User Roadmap, Jadwal Roadmap
- [x] Modul Ujian/Kuis: Soal, Soal Ujian, Jadwal Ujian, halaman kuis, evaluasi kuis, daftar nilai, peringkat
- [x] Modul Sertifikat: Sertifikat, Sertifikat User, TTD ganda, background, status, upload, QR Code
- [x] Modul Absensi Kegiatan + Kegiatan
- [x] Admin panel Filament v3 (24 Resource, 5 Page kustom, 3 Widget, relation manager)
- [x] Halaman laporan: Keaktifan Peserta, Laporan Kegiatan, Rekap Belajar, Generate Page, Ujian per Angkatan
- [x] Export Excel (`maatwebsite/excel` + `pxlrbt/filament-excel`), export peserta tidak aktif
- [x] Kirim email massal (broadcast) via Job `JobKirimEmail`
- [x] CMS ringan: Page dinamis (`page/{slug}`), Banner, Setting
- [x] Impersonate admin (`stechstudio/filament-impersonate`) — *terpasang, lihat catatan §3.4*
- [x] Lokalisasi `lang/id` + `lang/en`

### 2.2 Teknis
- [x] Upgrade ke Laravel 11.54 + Livewire 3 + Filament 3
- [x] 80 migration, urut & konsisten
- [x] 23 Policy untuk resource admin
- [x] `User` implement `FilamentUser::canAccessPanel()` (hanya `jenis_user = Admin`)
- [x] Sanctum untuk API mobile (± 25 endpoint terlindungi `auth:sanctum`)
- [x] API Resource layer (18 class) untuk response mobile
- [x] Docker: `Dockerfile` (PHP 8.3-FPM), `compose.yml`, nginx conf, `php.ini`
- [x] Isolasi DB testing (`bisionline_test` di `phpunit.xml`) — DB produksi aman
- [x] Suite test dasar hijau: **46 test / 101 assertion, lulus semua**
- [x] Telescope terpasang + filter non-local hanya exception

---

## 3. Yang BELUM Selesai

### 3.1 🔴 P0 — Bug / Risiko Aktif di Produksi

- [ ] **`dd('oke')` masih ada di kode produksi** — [ListAngkatans.php:25](app/Filament/Resources/AngkatanResource/Pages/ListAngkatans.php#L25). Method `buatAngkatanBerikutnya()` akan menghentikan request begitu dipanggil. Selesaikan fiturnya atau hapus methodnya.
- [ ] **Relasi `roadmaps()` fatal error** — [User.php:139](app/Models/User.php#L139) memakai return type `BelongsToMany` tapi `Illuminate\Database\Eloquent\Relations\BelongsToMany` **tidak di-import**. Setiap pemanggilan `$user->roadmaps()` melempar `Error: Class "App\Models\BelongsToMany" not found`.
- [ ] **Relasi `kelompok()` menunjuk model yang tidak ada** — [User.php:98](app/Models/User.php#L98) referensi `Kelompok::class`, file `app/Models/Kelompok.php` tidak ada. Hapus relasi mati ini atau buat modelnya.
- [ ] **`AdminMiddleware` crash untuk guest** — [AdminMiddleware.php:18](app/Http/Middleware/AdminMiddleware.php#L18) memanggil `auth()->user()->jenis_user` tanpa cek null. Guest yang membuka `/email/pesan` dapat **500**, bukan redirect login. Tambahkan `auth` sebelum `admin` di [web.php:104](routes/web.php#L104) dan guard null di middleware.
- [ ] **Route `/koreksi-nilai` terbuka untuk semua user login** — [web.php:50](routes/web.php#L50). Endpoint ini menulis ulang predikat **seluruh** ujian akhir. Pindahkan ke grup `admin` atau jadikan artisan command.
- [ ] **Duplikasi provider** — [config/app.php:198-199](config/app.php#L198-L199) mendaftarkan `AdminPanelProvider::class` dua kali. Hapus satu baris.

### 3.2 🔴 P0 — Keamanan

- [ ] **Reset password rentan Host Header Injection** — [User.php:117](app/Models/User.php#L117) membangun URL dari `$_SERVER['HTTP_HOST']` dan memaksa skema `http://`. Ganti dengan `route('password.reset', $token)` / `config('app.url')` agar link selalu HTTPS dan tidak bisa dibajak.
- [ ] **`EmailController::kirim()` tanpa validasi** — [EmailController.php:19-35](app/Http/Controllers/EmailController.php#L19-L35): validasi masih dikomentari, `$request->recipients` dipakai langsung di `whereIn`, attachment diupload tanpa batas ukuran/tipe, dan opsi `all` mengambil `User::all()` ke memori. Aktifkan kembali validasinya.
- [ ] **Mass assignment terbuka luas** — 28 dari 34 model memakai `protected $guarded = []`, termasuk `User` ([User.php:33](app/Models/User.php#L33)) yang punya kolom sensitif `jenis_user_id`, `nip`, `kode_user`. Audit minimal untuk model yang menerima input user langsung.
- [ ] Audit ulang seluruh route publik/`auth` untuk otorisasi kepemilikan data (mis. `/sertifikat/{code}`, `/daftar-nilai/{kode_ujian}`, `/link_materi/{code}` — pastikan user A tidak bisa membaca data user B hanya dengan menebak kode).
- [ ] Pastikan `.env` produksi: `APP_ENV=production`, `APP_DEBUG=false`, `TELESCOPE_ENABLED=false`. (`.env` lokal saat ini `APP_ENV=local` + `APP_DEBUG=true` — konfirmasi server produksi tidak begitu.)
- [ ] Tambahkan rate limiting pada login, register, dan endpoint API yang menulis data.
- [ ] Tambahkan `AuthServiceProvider::$policies` eksplisit atau pastikan auto-discovery policy benar-benar aktif untuk semua model.
- [ ] Buat `GroupUserPolicy` — satu-satunya Filament Resource yang belum punya policy.

### 3.3 🟠 P1 — Testing

- [ ] Naikkan cakupan test. Saat ini hanya **3 area** yang diuji (Auth, HomeNew, Profile) dari puluhan modul. `tests/Unit` hanya berisi `ExampleTest`.
- [ ] Test alur ujian/kuis: mulai ujian → jawab soal → hitung nilai → predikat → nilai akhir angkatan.
- [ ] Test alur materi harian: jadwal belajar, status baca, link materi, materi berikutnya.
- [ ] Test alur sertifikat: generate, upload, sertifikat user, daftar nilai, QR code.
- [ ] Test alur pendaftaran angkatan (kuota, tanggal pendaftaran, `is_umum`, kode daftar).
- [ ] Test roadmap & jadwal roadmap (modul terbaru, paling belum teruji).
- [ ] Test endpoint API mobile (± 25 route) — belum ada satupun test.
- [ ] Test policy/otorisasi admin panel (peserta tidak boleh masuk `/admin`).
- [ ] Tambahkan factory selain `UserFactory` (baru 1 dari 34 model) agar test lebih mudah ditulis.

### 3.4 🟠 P1 — Infrastruktur & Deploy

- [ ] **Belum ada CI/CD** — tidak ada `.github/workflows`. Minimal: jalankan `php artisan test` + `pint --test` tiap push/PR.
- [ ] **Queue worker** — `QUEUE_CONNECTION=sync` di `.env`. `JobKirimEmail` dan mailable `ShouldQueue` berarti kirim email massal memblokir request HTTP sampai selesai. Pindahkan ke `database`/`redis` + jalankan `queue:work` sebagai service (supervisor/systemd).
- [ ] **Scheduler** — [Kernel.php:18](app/Console/Kernel.php#L18) hanya berisi `sanctum:prune-expired`. Pastikan cron `schedule:run` terpasang di server, dan pertimbangkan menjadwalkan pembuatan jadwal belajar/pengiriman materi harian yang selama ini manual.
- [ ] Dokumentasikan prosedur deploy (migrate, `config:cache`, `route:cache`, `view:cache`, `storage:link`, restart worker).
- [ ] Strategi backup DB + file `storage/app/public` (sertifikat, TTD, banner, materi) — belum terlihat.
- [ ] Sinkronkan `.env.example` dengan `.env`: `.env.example` belum punya `CAN_LOGOUT`; `.env` belum punya `LIVEWIRE_UPLOAD_MAX_TIME`, `TRUSTED_PROXIES`, `MYSQL_ROOT_PASSWORD`.
- [ ] Keputusan fitur impersonate: package terpasang tapi `->impersonate()` **dikomentari** di [AdminPanelProvider.php:31](app/Providers/Filament/AdminPanelProvider.php#L31). Aktifkan (dengan pembatasan role) atau hapus dependensinya.
- [ ] Error tracking produksi (Sentry/Flare) & alerting — belum ada.

### 3.5 🟡 P2 — Kebersihan Kode

- [ ] Hapus `CobaController` — [CobaController.php](app/Http/Controllers/CobaController.php) hanya `return $request->all()`, tidak terpakai di route manapun.
- [ ] Bersihkan blok kode terkomentar yang besar, terutama [RegisteredUserController.php:110-175](app/Http/Controllers/Auth/RegisteredUserController.php#L110) (± 60 baris logika jadwal belajar yang dimatikan) dan `GeneratePage.php`.
- [ ] Bersihkan sisa `// dd(...)` di ± 10 file (`GeneratePage.php`, `HookAngkatan.php`, `AngkatanUserRelationManager.php`, `Pendaftaran.php`, `KeaktifanPeserta.php`, dll).
- [ ] **Halaman Peringkat**: guard `abort(403, 'masih dalam perbaikan')` dikomentari di [Peringkat.php:21](app/Http/Livewire/Kuis/Peringkat.php#L21) dengan catatan "sementara di offkan dulu" — tentukan statusnya. Render-nya juga rawan null (`$user->ujian->last()->soal_ujian` tanpa cek data kosong).
- [ ] Angka ajaib tersebar: `jenis_user_id = 2` untuk peserta ([User.php scopePeserta](app/Models/User.php)), hari ujian `[5,10,15]` / `[6,12,18,24,30,36]` di `GeneratePage.php` & `HookAngkatan.php`. Pindahkan ke enum/config.
- [ ] Seeder produksi bercampur seeder perbaikan data sekali-pakai (`FixPredikatSeeder`, `DeduplicatedSertificateSeeder`, `KoreksiJadwalBelajar`, `PerbaikiFilderAudioSeeder`, dst). Pisahkan ke folder `database/seeders/Maintenance` atau ubah jadi artisan command.
- [ ] Jalankan `./vendor/bin/pint` untuk menyeragamkan gaya kode (indentasi & spasi masih campur).
- [ ] Konsistensi lokasi Livewire: ada `App\Http\Livewire\Materi\HalamanMateriVideo` **dan** `App\Http\Livewire\MateriVideo\HalamanMateriVideo` (duplikat nama kelas, berbeda namespace) — pastikan tidak ada yang mati.

### 3.6 🟡 P2 — Performa

- [ ] Audit N+1 query pada halaman berat: Rekap Belajar, Keaktifan Peserta, Peringkat, Generate Page. Gunakan Telescope/`preventLazyLoading` di lokal.
- [ ] `CACHE_DRIVER=file` & `SESSION_DRIVER=file` — Redis sudah tersedia (`predis` terpasang), pertimbangkan pindah untuk produksi.
- [ ] `User::all()` di `EmailController` dan pola serupa — ganti dengan `chunk()`/`lazy()`.
- [ ] Indeks database untuk kolom yang sering difilter (`angkatan_id`, `user_id`, `tanggal`, `materi_detail_id`) — verifikasi sudah ada.

### 3.7 🟢 P3 — Dokumentasi

- [ ] **README usang** — masih menulis Laravel 10 / Livewire 2 / Filament 2, padahal sudah Laravel 11 / Livewire 3 / Filament 3.
- [ ] Belum ada `CLAUDE.md` / panduan kontributor (cara setup lokal, seeding, menjalankan test).
- [ ] Belum ada dokumentasi API mobile (25 endpoint tanpa spesifikasi/Postman collection).
- [ ] Belum ada ERD / diagram alur domain (angkatan → jadwal belajar → materi → ujian → sertifikat).
- [ ] Belum ada panduan admin (SOP membuat angkatan baru, generate jadwal, terbitkan sertifikat).

---

## 4. Urutan Pengerjaan yang Disarankan

1. **Sprint 1 — Stabilkan produksi (P0):** §3.1 seluruhnya + §3.2 tiga item teratas. Semua ringkas, dampaknya langsung.
2. **Sprint 2 — Amankan (P0/P1):** sisa §3.2, lalu CI di §3.4 supaya perbaikan berikutnya terjaga.
3. **Sprint 3 — Jaring pengaman (P1):** test alur ujian → materi → sertifikat (§3.3) + queue worker & scheduler (§3.4).
4. **Sprint 4 — Rapikan (P2/P3):** §3.5, §3.6, §3.7.

---

## 5. Catatan Verifikasi

- `php artisan test` → **46 passed (101 assertions)**, durasi 7,45 detik, per 2 Agustus 2026.
- Analisa dilakukan lewat pembacaan kode statis; item ditandai lokasi `file:baris` agar mudah diverifikasi ulang.
- Kondisi `.env` **server produksi** tidak dapat diperiksa dari sini — item terkait `.env` di §3.2 perlu dikonfirmasi manual di server.
