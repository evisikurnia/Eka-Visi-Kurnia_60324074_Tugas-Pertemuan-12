# Sistem Manajemen Perpustakaan - Modul Anggota
 **Nama:** Eka Visi Kurnia
 
 **NIM:** 60324074

Proyek ini adalah implementasi sistem manajemen perpustakaan berbasis web menggunakan **Laravel 12** dan **PHP 8.2**. Dokumentasi ini berfokus pada pengerjaan **Praktikum 4 & 5 (Form Request, Validation, CRUD)** serta penyelesaian **Tugas Akhir Modul Anggota** (Auto-Generate Code, Export Excel, dan Advanced Search/Filter).

---

## Fitur Utama

### 1. Manajemen Anggota (CRUD Lengkap)
* **Create**: Pendaftaran anggota baru dengan validasi berlapis menggunakan *Form Request*.
* **Read**: Menampilkan daftar anggota, ringkasan statistik (Total, Aktif, Nonaktif), serta halaman detail tiap anggota.
* **Update**: Memperbarui data anggota dengan validasi adaptif (mengabaikan pengecekan *unique* untuk ID data yang sedang di-edit).
* **Delete**: Penghapusan data anggota yang aman berbasis penanganan eksepsi (`try-catch`) dan konfirmasi aksi.

### 2. Fitur Tugas Khusus
* **Auto-Generate Kode Anggota (Tugas 1 - 30%)**: Sistem secara otomatis mengenerate kode dengan format `AGT-[TAHUN]-[NOMOR_URUT]` (Contoh: `AGT-2026-001`) saat form pendaftaran dibuka, dikunci dengan atribut `readonly`.
* **Export Data ke Excel (Tugas 2 - 40%)**: Fitur mengunduh seluruh data anggota secara instan ke format `.xlsx` menggunakan package `maatwebsite/excel`.
* **Advanced Search & Filter (Tugas 3 - 30%)**: Pencarian dinamis multi-variabel berdasarkan kombinasi kata kunci (Nama/Email/Telepon), Jenis Kelamin, Status, dan Jenis Pekerjaan.

---

## 💻 Tech Stack & Kebutuhan Sistem

* **Framework**: Laravel 12.x
* **Bahasa Pemrograman**: PHP 8.2.x
* **Ekstensi PHP**: `gd` (Wajib diaktifkan untuk fitur Export Excel)
* **Database**: MySQL / MariaDB
* **UI/UX Library**: Bootstrap 5, Bootstrap Icons, Flatpickr (Date Picker)

---

## Arsitektur Folder Views

```text
resources/views/
├── anggota/
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── index.blade.php
│   └── show.blade.php
├── buku/
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── index.blade.php
│   └── show.blade.php
├── components/
│   └── buku-card.blade.php
└── layouts/
    ├── app.blade.php
    └── footer.blade.php
```
---

## Cara Menjalankan Proyek Lokal

1. Clone repository ini atau download zip.
2. Pastikan XAMPP (Apache & MySQL) sudah aktif.
3. Jalankan migrasi database di terminal:
   ```bash
   php artisan migrate
4. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Bukti server berjalan
![Server Terbuka](dokumentasi/Tugas%2013.png)

---

## Dokumentasi Tugas 13
* **Form Tambah Anggota & Auto-Generate Code**
Kode anggota otomatis dibuat secara berurutan oleh sistem berdasarkan tahun pendaftaran saat ini.
<img src="dokumentasi/Tugas 13.1.png" width="100%" />
* **Fitur Export Excel Berhasil**
Data berhasil ditarik dan diunduh ke dalam berkas spreadsheet (.xlsx).
<img src="dokumentasi/Tugas 13.2.png" width="100%" />
* **Halaman Utama & Fitur Advanced Search**
Form pencarian multi-kolom memudahkan pencarian data anggota secara spesifik beserta pembaruan kartu statistik secara real-time.
<img src="dokumentasi/Tugas 13.3.png" width="100%" />