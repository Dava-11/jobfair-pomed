# Panduan Update Database - Menambahkan Kolom Logo Perusahaan

## Perubahan yang Dilakukan

### 1. Update Database Schema
File `database_update_logo.sql` berisi query untuk menambahkan kolom `logo` pada tabel `companies`.

### 2. Cara Menjalankan Update Database

#### Opsi 1: Menggunakan phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database `jobfair_polmed`
3. Klik tab "SQL"
4. Copy dan paste isi file `database_update_logo.sql`
5. Klik "Go" atau "Jalankan"

#### Opsi 2: Menggunakan Command Line MySQL
```bash
mysql -u root -p jobfair_polmed < database_update_logo.sql
```

#### Opsi 3: Manual Query
Jalankan query berikut di MySQL:
```sql
ALTER TABLE `companies` 
ADD COLUMN `logo` VARCHAR(255) NULL AFTER `description`;
```

### 3. Struktur Tabel Setelah Update

Tabel `companies` akan memiliki struktur:
- `company_id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `user_id` (INT, FOREIGN KEY)
- `company_name` (VARCHAR)
- `description` (TEXT)
- **`logo` (VARCHAR(255), NULL)** ← KOLOM BARU
- `created_at` (DATETIME)

### 4. Folder Upload

Pastikan folder `uploads/company_logos/` sudah dibuat dengan permission yang sesuai:
```bash
mkdir -p uploads/company_logos
chmod 777 uploads/company_logos
```

Atau buat secara manual di folder root project.

### 5. Fitur yang Telah Diupdate

1. **Halaman Edit Profil Perusahaan** (`dashboard/edit_profile.php`)
   - Form untuk edit nama perusahaan
   - Form untuk edit deskripsi perusahaan
   - Upload logo perusahaan (JPG, PNG, GIF)
   - Preview logo yang sudah ada

2. **Halaman Dashboard Perusahaan** (`dashboard/perusahaan.php`)
   - Tombol "Profil" di navbar untuk akses edit profil
   - Tombol Edit dan Hapus untuk setiap lowongan

3. **Halaman Daftar Lowongan** (`dashboard/mahasiswa.php`)
   - Logo perusahaan ditampilkan di setiap card lowongan
   - Fallback icon jika perusahaan belum upload logo

4. **Halaman Daftar Pelamar** (`dashboard/view_applicants.php`)
   - Tombol Edit dan Hapus untuk setiap lamaran
   - Kolom Aksi ditambahkan

5. **Halaman Edit/Hapus Lowongan**
   - `dashboard/edit_job.php` - Edit lowongan
   - `dashboard/delete_job.php` - Hapus lowongan dengan konfirmasi

6. **Halaman Edit/Hapus Lamaran**
   - `dashboard/edit_application.php` - Edit status lamaran
   - `dashboard/delete_application.php` - Hapus lamaran dengan konfirmasi

### 6. Tema Warna Baru

Semua halaman telah diupdate dengan spektrum warna:
- **DDC3C3** - Warna terang (secondary)
- **A376A2** - Warna medium (primary)
- **8D5F8C** - Warna gelap (accent)
- **6B3F69** - Warna sangat gelap (darker)

### 7. Troubleshooting

**Error: Unknown column 'logo'**
- Pastikan sudah menjalankan script `database_update_logo.sql`
- Cek apakah kolom sudah ada dengan query: `DESCRIBE companies;`

**Error: Duplicate column name**
- Kolom sudah ada, tidak perlu menjalankan update lagi

**Error: Permission denied saat upload logo**
- Pastikan folder `uploads/company_logos/` ada dan memiliki permission write (777 atau 755)

**Logo tidak muncul**
- Cek path file logo di database
- Pastikan file logo ada di folder `uploads/company_logos/`
- Cek permission folder uploads

