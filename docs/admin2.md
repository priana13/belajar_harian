# Admin Inertia

Admin baru dibuka melalui `/admin2` dan login `/admin2/login`, menggunakan akun dengan jenis pengguna `Admin`. Panel Filament `/admin` tetap tersedia. Keduanya menggunakan database yang sama: perubahan data pada admin baru langsung terlihat pada admin lama.

Route terdaftar di `routes/admin2.php`. Katalog field, validasi, relasi, dan submenu ada di `config/admin2.php`. Tampilan React memakai entry point `resources/js/admin2.jsx` dan Blade `resources/views/admin2.blade.php`; stylesheet diadaptasi dari `themes/bisi_theme.html`. Halaman admin tidak merender komponen Filament/Livewire atau memuat Alpine. Dependensi lama tetap terpasang karena panel lama dan halaman peserta masih memakainya.

Fitur yang tersedia:

- Dashboard dengan statistik database, pertumbuhan peserta, status peserta, dan jadwal ujian mendatang.
- 28 modul pengelolaan data, termasuk peserta, materi/pertemuan/gambar, soal, roadmap, gelombang, angkatan/kelas/keanggotaan, jadwal, hasil/jawaban ujian, sertifikat peserta, kegiatan, absensi, dan kelompok.
- Pencarian, filter, sorting, pagination, tambah/edit/hapus, validasi server, pilihan relasi dengan pencarian, upload berkas, dan ekspor CSV.
- Konfigurasi dengan submenu umum/pengumuman/logo, kategori, jenis pengguna/ujian, struktur, banner, halaman informasi, dan template sertifikat.
- Relasi materi dan gelombang pada roadmap; roadmap dan kelompok pada peserta; anggota kelompok; soal jadwal ujian.
- Pembuatan angkatan beserta kelas, jadwal belajar/ujian, dan keanggotaan gelombang dalam satu transaksi. Materi harus mempunyai pertemuan dan frekuensi 1–6 materi per pekan.
- Pembuatan jadwal harian dari jadwal roadmap, dengan penolakan jika jadwal untuk materi/gelombang/roadmap sudah ada.
- Laporan peserta berdasarkan jumlah ujian dalam periode, ekspor CSV, dan link materi harian.

Untuk pengembangan jalankan `npm run dev`, atau `npm ci && npm run build` untuk menghasilkan aset produksi. Tidak ada migrasi database baru. Upload memakai disk `public`; akses berkas mengikuti konfigurasi storage aplikasi yang sudah ada.

Verifikasi backend pada database terisolasi:

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=Admin2Test
```

Aksi khusus panel lama seperti impersonasi, perubahan template sertifikat massal, ekspor Excel, dan duplikasi angkatan berikutnya belum disalin menjadi tombol khusus di admin2. Admin2 menyediakan form CRUD/relasi, ekspor CSV, dan pembuatan angkatan baru. Konten HTML lama tetap dapat diedit melalui textarea; editor WYSIWYG Filament tidak dimuat. Gunakan panel lama untuk aksi khusus tersebut selama masa transisi.
