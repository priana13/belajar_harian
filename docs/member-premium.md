# Member Premium

Pengguna membuka menu tiga kotak → **Member Premium** (`/premium`).

## Pengaturan admin

Di Admin Panel, grup **Member Premium** menyediakan:

- **Paket Premium**: nama, durasi dalam bulan (1–120), harga total paket dalam rupiah, instruksi transfer, dan status tersedia. Buat paket terpisah untuk setiap pilihan durasi/harga. Harga dan durasi pesanan lama tidak ikut berubah ketika paket diedit.
- **Langganan & Pembayaran**: periksa referensi transfer dan cocokkan pembayaran dengan mutasi rekening, lalu pilih **Verifikasi & aktifkan**. Pesanan belum memberikan akses sebelum diverifikasi. Pesanan yang tidak valid dapat ditolak.
- **Ebook Materi**: pilih materi dan unggah PDF, maksimum 50 MB. File disimpan privat dan hanya diunduh melalui endpoint yang memeriksa masa aktif Premium.
- **Buku Gratis**: satu buku per materi. Matikan ketersediaan jika buku belum siap dikirim. Materi buku tidak dapat diganti setelah dibuat.
- **Klaim & Pengiriman Buku**: tentukan ongkir dan instruksi transfer. Setelah pengguna mengirim referensi pembayaran ongkir, cocokkan transfer dan pilih **Verifikasi ongkir & kirim**, kemudian isi kurir dan nomor resi.

## Aturan langganan dan akses

Pembayaran transfer manual; tidak ada penagihan otomatis. Durasi kalender dimulai ketika admin menyetujui pembayaran. Perpanjangan dimulai setelah masa aktif sebelumnya berakhir dan menggunakan penambahan bulan tanpa melampaui akhir bulan.

Jenis pengguna berubah menjadi **Member Premium** setelah pembayaran disetujui; jenis Admin tetap dipertahankan. Akses ditentukan oleh masa aktif langganan, bukan hanya jenis pengguna. Saat masa aktif berakhir, akses Premium berhenti otomatis tanpa memerlukan cron. Jenis pengguna tetap tercatat Member Premium untuk riwayat; memilih jenis ini secara manual tidak memberikan masa aktif.

Admin dapat membuka katalog video dan ebook. Modul video tetap mengikuti jadwal belajar pengguna, termasuk jadwal pribadi, gelombang, dan grup. Materi harian yang sudah ada tetap menggunakan alurnya sendiri.

## Klaim buku

Satu buku per materi dapat diklaim satu kali per akun, termasuk setelah perpanjangan. Beberapa buku dapat digabung dalam satu klaim/pengiriman. Pengguna harus mempunyai langganan aktif, mengisi nama penerima, nomor HP, alamat lengkap, kota/kabupaten, provinsi, kode pos, dan menyetujui pembayaran ongkir.

Buku yang sudah diajukan tidak dapat diklaim ulang. Pembayaran ongkir dan pemantauan pengiriman tetap dapat dilanjutkan dari halaman Premium setelah langganan berakhir.

## Database dan verifikasi

Migrasi: `database/migrations/2026_10_14_100000_create_premium_membership_tables.php`.

Untuk lingkungan lain:

```sh
php artisan migrate --path=database/migrations/2026_10_14_100000_create_premium_membership_tables.php
npm run build
```

Pengujian terkait memakai SQLite terisolasi sehingga tidak mereset database aplikasi:

```sh
vendor/bin/phpunit tests/Feature/PremiumMembershipTest.php tests/Feature/MateriVideoProgressTest.php tests/Feature/FrontNavigationTest.php tests/Unit/MateriVideoTest.php
```
