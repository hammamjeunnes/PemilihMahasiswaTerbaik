# Pemilih Mahasiswa Terbaik

Sistem Pendukung Keputusan (SPK) untuk memilih mahasiswa terbaik menggunakan metode SAW (Simple Additive Weighting) dengan pembobotan dinamis menggunakan metode WP (Weighted Product).

## Fitur

- Input data mahasiswa dengan 6 kriteria penilaian
- Perhitungan peringkat menggunakan metode SAW
- Pembobotan dinamis menggunakan metode WP
- Antarmuka pengguna yang responsif dan mudah digunakan
- Tampilan peringkat mahasiswa
- Informasi bobot kriteria yang transparan

## Persyaratan Sistem

- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Web server (Apache/Nginx)
- Composer (untuk mengelola dependensi)

## Instalasi

1. Clone repositori ini:
   ```bash
   git clone [url-repositori]
   cd PemilihMahasiswaTerbaik
   ```

2. Buat database MySQL baru:
   ```sql
   CREATE DATABASE pemilih_mahasiswa_terbaik;
   ```

3. Konfigurasi koneksi database:
   - Buka file `config/database.php`
   - Sesuaikan konfigurasi database sesuai dengan pengaturan lokal Anda:
     ```php
     define('DB_SERVER', 'localhost');
     define('DB_USERNAME', 'username');
     define('DB_PASSWORD', 'password');
     define('DB_NAME', 'pemilih_mahasiswa_terbaik');
     ```

4. Aplikasi akan secara otomatis membuat tabel yang diperlukan dan mengimpor data awal dari file CSV saat pertama kali dijalankan.

5. Letakkan folder proyek di direktori web server Anda (misalnya, `htdocs` atau `www`).

## Penggunaan

1. Buka aplikasi di browser Anda (contoh: `http://localhost/PemilihMahasiswaTerbaik`)
2. Akan diarahkan ke halaman formulir untuk mengisi data mahasiswa
3. Setelah mengisi formulir, data akan disimpan dan dihitung peringkatnya
4. Untuk melihat peringkat, buka halaman "Peringkat"
5. Di halaman peringkat, Anda bisa melihat bobot kriteria dengan menekan tombol "Tampilkan Bobot"

## Struktur Proyek

```
PemilihMahasiswaTerbaik/
├── config/
│   └── database.php      # Konfigurasi database
├── css/                  # File CSS tambahan
├── includes/             # File include PHP
│   ├── header.php        # Header halaman
│   └── footer.php        # Footer halaman
├── js/                   # File JavaScript
├── pages/                # Halaman aplikasi
│   ├── form.php          # Formulir input mahasiswa
│   └── ranking.php       # Halaman peringkat
├── index.php             # File indeks (redirect ke halaman formulir)
└── README.md            # Dokumentasi ini
```

## Metode Perhitungan

### 1. Normalisasi Data
Setiap kriteria dinormalisasi menggunakan rumus:
- Kriteria benefit (semakin besar semakin baik): 
  ```
  nilai_normalisasi = nilai_aktual / nilai_maksimum
  ```
- Kriteria cost (semakin kecil semakin baik):
  ```
  nilai_normalisasi = nilai_minimum / nilai_aktual
  ```

### 2. Weighted Product (WP)
Digunakan untuk menghitung bobot kriteria secara dinamis:
1. Hitung perkalian nilai setiap kriteria
2. Hitung akar pangkat n dari hasil perkalian
3. Normalisasi bobot

### 3. Simple Additive Weighting (SAW)
Menghitung skor akhir dengan rumus:
```
skor_akhir = Σ(nilai_normalisasi * bobot_kriteria)
```

## Kriteria Penilaian

1. **IQ (Prestasi Akademik)**
   - Semakin tinggi IP, semakin baik

2. **EQ (Kegiatan Sosial)**
   - Semakin banyak kegiatan sosial, semakin baik

3. **Attitude (Sertifikat)**
   - Semakin banyak sertifikat, semakin baik

4. **Association (Organisasi)**
   - Semakin banyak organisasi, semakin baik

5. **Friendship (Interaksi Sosial)**
   - Semakin sering berinteraksi, semakin baik

6. **Effort Regulation (Keterlambatan Tugas)**
   - Semakin sedikit keterlambatan, semakin baik (kriteria cost)

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Special Thanks
M. Faris Al Hakim, S.Pd., M.Cs.
HAMMAM JITAPSARA (2304140050)
YUDHISTIRA LUCKY OKTAVIAN (2304140080)
AISYAH WILAVY ZAHRA (2304140068)
ARIF SATRIA TAMA (2304140063)
