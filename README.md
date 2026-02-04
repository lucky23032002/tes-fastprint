# Tes Junior Programmer - FastPrint Indonesia

Aplikasi Dashboard Manajemen Produk yang dibangun menggunakan **CodeIgniter 3** dan **PostgreSQL**. Aplikasi ini memiliki fitur sinkronisasi data dari API eksternal dengan otentikasi dinamis, manajemen inventaris (CRUD), serta antarmuka responsive.

## 📋 Fitur Utama

Sesuai dengan persyaratan tes, aplikasi ini mencakup fitur berikut:

1.  **Sync API Otomatis**:
    * Mengambil data dari API FastPrint.
    * **Keamanan Dinamis**: Algoritma otomatis untuk generate Username (`C` + Jam Server) dan Password (MD5 Tanggal) sesuai waktu server request.
    * **Cookie Handling**: Menangani sesi cookie API.
    * **Upsert Logic**: Mencegah duplikasi data saat sinkronisasi berulang.
2.  **CRUD Produk**:
    * **Create**: Form tambah produk dengan validasi server-side.
    * **Read**: Menampilkan daftar produk dengan **Filter** (Status/Kategori) dan **Sorting** (Harga/Nama) real-time.
    * **Update**: Edit data produk.
    * **Delete**: Hapus produk dengan konfirmasi Modal Pop-up.
3.  **Validasi Input**:
    * Nama Produk wajib diisi.
    * Harga wajib berupa angka.
4.  **Database Relasional**:
    * Menggunakan **PostgreSQL**.
    * Tabel: `produk`, `kategori`, dan `status`.
5.  **UI/UX Modern**:
    * **Admin Dashboard Style** dengan Sidebar.
    * **Responsive Design** (Mobile Friendly) menggunakan Bootstrap 5 & FontAwesome.

## 🛠️ Teknologi yang Digunakan

* **Backend**: CodeIgniter 3
* **Database**: PostgreSQL 18
* **Frontend**: Bootstrap 5, Vanilla JS (AJAX-free Filtering), FontAwesome 6

## 🗄️ Struktur Database


Aplikasi menggunakan 3 tabel yang saling berelasi:

1.  **`kategori`**: (`id_kategori` [PK], `nama_kategori`)
2.  **`status`**: (`id_status` [PK], `nama_status`)
3.  **`produk`**: 
    * `id_produk` [PK]
    * `nama_produk`
    * `harga`
    * `kategori_id` [FK -> kategori]
    * `status_id` [FK -> status]

## 🚀 Cara Instalasi

### 1. Prasyarat
* Web Server (Apache/Nginx) dengan PHP 7.4 - 8.1.
* PostgreSQL Server.
* Ekstensi PHP `pgsql` atau `pdo_pgsql` aktif.

### 2. Instalasi Database
Buat database baru di pgAdmin bernama `tes_fastprint`, lalu jalankan query berikut:

```sql
-- Tabel Kategori
CREATE TABLE kategori (
    id_kategori SERIAL PRIMARY KEY,
    nama_kategori VARCHAR(255) UNIQUE NOT NULL
);

-- Tabel Status
CREATE TABLE status (
    id_status SERIAL PRIMARY KEY,
    nama_status VARCHAR(255) UNIQUE NOT NULL
);

-- Tabel Produk
CREATE TABLE produk (
    id_produk SERIAL PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    harga NUMERIC(15, 2) DEFAULT 0,
    kategori_id INTEGER REFERENCES kategori(id_kategori),
    status_id INTEGER REFERENCES status(id_status)
);

3. Konfigurasi Project
Clone repository ini ke folder htdocs atau www.

Buka application/config/database.php, sesuaikan kredensial PostgreSQL:
