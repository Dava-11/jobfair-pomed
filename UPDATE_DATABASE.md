# Panduan Update Database - Menambahkan Kolom Alasan

## Perubahan yang Dilakukan

### 1. Update Database Schema
File `database_update.sql` berisi query untuk menambahkan kolom `alasan` pada tabel `applications`.

### 2. Cara Menjalankan Update Database

#### Opsi 1: Menggunakan phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database `jobfair_polmed`
3. Klik tab "SQL"
4. Copy dan paste isi file `database_update.sql`
5. Klik "Go" atau "Jalankan"

#### Opsi 2: Menggunakan Command Line MySQL
```bash
mysql -u root -p jobfair_polmed < database_update.sql
```

#### Opsi 3: Manual Query
Jalankan query berikut di MySQL:
```sql
ALTER TABLE `applications` 
ADD COLUMN `alasan` TEXT NULL AFTER `cv_file`;
```

### 3. Struktur Tabel Setelah Update

Tabel `applications` akan memiliki struktur:
- `application_id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `job_id` (INT, FOREIGN KEY)
- `user_id` (INT, FOREIGN KEY)
- `cv_file` (VARCHAR)
- **`alasan` (TEXT, NULL)** ← KOLOM BARU
- `status` (VARCHAR)
- `applied_at` (DATETIME)

### 4. Catatan Penting

- Kolom `alasan` bertipe TEXT untuk menyimpan teks panjang
- NULL diizinkan untuk data lama yang belum memiliki alasan
- Jika ada error "Duplicate column name", berarti kolom sudah ada di database
- Untuk MySQL versi lama yang tidak support `IF NOT EXISTS`, gunakan query manual

### 5. Fitur yang Telah Diupdate

1. **Form Lamar Pekerjaan** (`dashboard/apply_job.php`)
   - Menambahkan textarea untuk "Alasan Melamar Pekerjaan"
   - Data alasan disimpan ke database saat submit

2. **Halaman Daftar Pelamar** (`dashboard/view_applicants.php`)
   - Menampilkan kolom "Alasan Melamar"
   - Tombol "Lihat Alasan" dengan modal popup untuk membaca alasan lengkap

3. **Query INSERT** 
   - Sudah diupdate untuk menyertakan kolom `alasan`:
   ```php
   INSERT INTO applications (job_id, user_id, cv_file, alasan) 
   VALUES ('$job_id', '$user_id', '$cv_file', '$alasan')
   ```

### 6. Troubleshooting

**Error: Unknown column 'alasan'**
- Pastikan sudah menjalankan script `database_update.sql`
- Cek apakah kolom sudah ada dengan query: `DESCRIBE applications;`

**Error: Duplicate column name**
- Kolom sudah ada, tidak perlu menjalankan update lagi
- Lanjutkan ke penggunaan aplikasi

**Data lama tidak memiliki alasan**
- Normal, kolom `alasan` diizinkan NULL
- Data baru akan memiliki alasan saat user mengisi form

