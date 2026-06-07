# Sistem Manajemen Perpustakaan - Laravel 12
 **Nama:** Eka Visi Kurnia
 
 **NIM:** 60324074

Aplikasi Sistem Manajemen Perpustakaan modern yang dibangun menggunakan **Laravel 12**, **Bootstrap 5**, dan **SweetAlert2**. Proyek ini mencakup arsitektur layout master, pengelolaan data buku & anggota, validasi tingkat lanjut, serta fitur operasional esensial perpustakaan.

---

## Fitur Utama

* **Blade Layouting Master**: Sistem templating yang terstruktur rapi memisahkan `layouts`, `partials`, dan komponen halaman anak (`home`, `buku`, `anggota`).
* **UI/UX Modern Enhancement**:
    * Konfirmasi hapus interaktif menggunakan **SweetAlert2**.
    * *Loading state* otomatis (efek spinner & disable button) saat submit form untuk mencegah *double submit*.
    * *Auto-hide flash messages* (alert sukses/gagal otomatis memudar setelah 5 detik).
* **Advanced Validation Rules** (Tugas 1):
    * *Custom Rule* format Kode Buku khusus (`BK-[KATEGORI]-[NOMOR]`, contoh: `BK-PROG-001`).
    * Validasi Bersyarat (*Conditional Validation*): Jika kategori adalah "Programming", bahasa buku wajib "Inggris". Jika tahun terbit < 2000, stok maksimal dibatasi hanya 5 buku.
    * Seluruh pesan error divalidasi penuh dalam **Bahasa Indonesia**.
* **Bulk Delete Operations** (Tugas 2): Fitur hapus masal banyak data buku sekaligus memanfaatkan checkbox *Select All* interaktif.
* **Export Data to CSV** (Tugas 3): Fitur mengunduh seluruh data koleksi buku ke dalam file spreadsheet format `.csv` secara langsung melalui *Data Stream*.

---

## Arsitektur Folder Views

```text
resources/
└── views/
    ├── anggota/
    │   └── index.blade.php
    ├── buku/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── layouts/
    │   ├── app.blade.php
    │   ├── footer.blade.php
    │   └── navbar.blade.php
    ├── partials/
    └── home.blade.php

## Cara Menjalankan Proyek Lokal
1. Clone repository ini atau download zip.
2. Pastikan XAMPP (Apache & MySQL) sudah aktif.
3. Jalankan migrasi database di terminal:
   ```bash
   php artisan migrate
4. Bukti Server Berjalan
![Server Terbuka](dokumentasi/Tugas%2012.1.png)
![Server Terbuka](dokumentasi/Tugas%2012.2.png)
