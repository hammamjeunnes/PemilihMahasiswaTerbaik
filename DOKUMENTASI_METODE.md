# Dokumentasi Metode Perhitungan

## Daftar Isi
1. [Pendahuluan](#pendahuluan)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Metode Weighted Product (WP)](#metode-weighted-product-wp)
   - [Konsep Dasar](#konsep-dasar-wp)
   - [Rumus dan Perhitungan](#rumus-dan-perhitungan-wp)
   - [Contoh Perhitungan](#contoh-perhitungan-wp)
4. [Metode Simple Additive Weighting (SAW)](#metode-simple-additive-weighting-saw)
   - [Konsep Dasar](#konsep-dasar-saw)
   - [Normalisasi Data](#normalisasi-data)
   - [Rumus dan Perhitungan](#rumus-dan-perhitungan-saw)
   - [Contoh Perhitungan](#contoh-perhitungan-saw)
5. [Integrasi WP dan SAW](#integrasi-wp-dan-saw)
6. [Implementasi dalam Kode](#implementasi-dalam-kode)
7. [Kesimpulan](#kesimpulan)

## Pendahuluan

Aplikasi "Selektor Mahasiswa Terbaik" adalah sebuah sistem pendukung keputusan (SPK) yang dirancang untuk membantu dalam proses pemilihan mahasiswa terbaik berdasarkan beberapa kriteria penilaian. Aplikasi ini mengimplementasikan dua metode utama:

1. **Weighted Product (WP)**: Digunakan untuk menentukan bobot masing-masing kriteria secara dinamis berdasarkan data yang ada.
2. **Simple Additive Weighting (SAW)**: Digunakan untuk melakukan perangkingan mahasiswa berdasarkan kriteria yang telah diberi bobot.

## Arsitektur Sistem

Aplikasi ini dibangun dengan arsitektur sebagai berikut:

- **Frontend**: HTML, CSS (Tailwind CSS), dan JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Metode**: WP untuk pembobotan dan SAW untuk perangkingan

## Metode Weighted Product (WP)

### Konsep Dasar WP

Weighted Product (WP) adalah salah satu metode dalam sistem pendukung keputusan yang digunakan untuk menentukan bobot kriteria berdasarkan preferensi. Metode ini menggunakan operasi perkalian untuk menghubungkan rating atribut yang dinilai berdasarkan bobot yang diberikan.

### Rumus dan Perhitungan WP

Langkah-langkah perhitungan WP:

1. **Menentukan Nilai Preferensi (Sᵢ)**
   
   Untuk setiap alternatif (mahasiswa), hitung nilai preferensi dengan rumus:
   
   ```
   Sᵢ = ∏(xᵢⱼ^wⱼ) untuk j = 1 sampai n
   ```
   
   Keterangan:
   - Sᵢ = Nilai preferensi alternatif ke-i
   - xᵢⱼ = Nilai kriteria ke-j untuk alternatif ke-i
   - wⱼ = Bobot kriteria ke-j
   - n = Jumlah kriteria

2. **Menghitung Nilai Preferensi Relatif (Vᵢ)**
   
   ```
   Vᵢ = Sᵢ / ∑Sᵢ
   ```
   
   Keterangan:
   - Vᵢ = Nilai preferensi relatif alternatif ke-i
   - m = Jumlah alternatif

### Contoh Perhitungan WP

Misalkan kita memiliki data 3 mahasiswa dengan 3 kriteria (IPK, Jumlah Organisasi, Jumlah Keterlambatan):

| Nama | IPK | Organisasi | Keterlambatan |
|------|-----|------------|---------------|
| A    | 3.8 | 2          | 1             |
| B    | 3.5 | 3          | 2             |
| C    | 3.9 | 1          | 0             |


1. Hitung nilai Sᵢ untuk setiap mahasiswa (asumsikan bobot awal sama = 1/3):
   - S_A = 3.8^(1/3) × 2^(1/3) × (1/1)^(1/3) ≈ 1.73
   - S_B = 3.5^(1/3) × 3^(1/3) × (1/2)^(1/3) ≈ 1.63
   - S_C = 3.9^(1/3) × 1^(1/3) × (1/0.1)^(1/3) ≈ 2.11

2. Hitung total S = 1.73 + 1.63 + 2.11 = 5.47

3. Hitung nilai Vᵢ:
   - V_A = 1.73 / 5.47 ≈ 0.316
   - V_B = 1.63 / 5.47 ≈ 0.298
   - V_C = 2.11 / 5.47 ≈ 0.386

Dari perhitungan di atas, mahasiswa C memiliki nilai preferensi tertinggi.

## Metode Simple Additive Weighting (SAW)

### Konsep Dasar SAW

Simple Additive Weighting (SAW) adalah metode yang sering digunakan untuk menyelesaikan masalah pengambilan keputusan multikriteria. Metode ini dikenal juga dengan metode penjumlahan terbobot.

### Normalisasi Data

Sebelum perhitungan SAW, data perlu dinormalisasi terlebih dahulu. Ada dua jenis kriteria:

1. **Benefit**: Semakin besar nilainya semakin baik (contoh: IPK, jumlah organisasi)
   ```
   rᵢⱼ = xᵢⱼ / max(xⱼ)
   ```

2. **Cost**: Semakin kecil nilainya semakin baik (contoh: jumlah keterlambatan)
   ```
   rᵢⱼ = min(xⱼ) / xᵢⱼ
   ```

### Rumus dan Perhitungan SAW

Setelah normalisasi, hitung nilai preferensi dengan rumus:

```
Vᵢ = ∑(wⱼ × rᵢⱼ) untuk j = 1 sampai n
```

Keterangan:
- Vᵢ = Nilai akhir alternatif ke-i
- wⱼ = Bobot kriteria ke-j (diambil dari hasil WP)
- rᵢⱼ = Nilai normalisasi kriteria ke-j untuk alternatif ke-i
- n = Jumlah kriteria

### Contoh Perhitungan SAW

Menggunakan data sebelumnya dan bobot dari WP (asumsikan bobot = [0.4, 0.3, 0.3]):

1. Normalisasi data:
   - IPK (benefit): dibagi dengan 3.9 (nilai maksimum)
   - Organisasi (benefit): dibagi dengan 3 (nilai maksimum)
   - Keterlambatan (cost): nilai minimum (0.1) dibagi nilai

   | Nama | IPK (norm) | Organisasi (norm) | Keterlambatan (norm) |
   |------|------------|-------------------|----------------------|
   | A    | 3.8/3.9≈0.97 | 2/3≈0.67          | 0.1/1=0.1            |
   | B    | 3.5/3.9≈0.90 | 3/3=1.0           | 0.1/2=0.05           |
   | C    | 3.9/3.9=1.0  | 1/3≈0.33          | 0.1/0.1=1.0          |


2. Hitung nilai Vᵢ dengan bobot [0.4, 0.3, 0.3]:
   - V_A = (0.4×0.97) + (0.3×0.67) + (0.3×0.1) = 0.388 + 0.201 + 0.03 = 0.619
   - V_B = (0.4×0.90) + (0.3×1.0) + (0.3×0.05) = 0.36 + 0.3 + 0.015 = 0.675
   - V_C = (0.4×1.0) + (0.3×0.33) + (0.3×1.0) = 0.4 + 0.099 + 0.3 = 0.799

3. Hasil perangkingan:
   1. C (0.799)
   2. B (0.675)
   3. A (0.619)

## Integrasi WP dan SAW

Dalam aplikasi ini, WP dan SAW diintegrasikan sebagai berikut:

1. **Tahap 1: Perhitungan Bobot dengan WP**
   - Sistem menghitung bobot untuk setiap kriteria berdasarkan data yang ada
   - Bobot ini merepresentasikan tingkat kepentingan relatif dari setiap kriteria

2. **Tahap 2: Perangkingan dengan SAW**
   - Menggunakan bobot dari WP sebagai input
   - Melakukan normalisasi data
   - Menghitung nilai akhir untuk setiap alternatif
   - Mengurutkan alternatif berdasarkan nilai akhir

## Implementasi dalam Kode

### 1. Fungsi Normalisasi Data

```php
function normalizeMatrix($data) {
    $normalized = [];
    $maxValues = [];
    $minValues = [];
    
    // Inisialisasi array untuk menyimpan nilai maksimum dan minimum
    $criteria = ['iq', 'eq', 'attitude', 'association', 'friendship'];
    foreach ($criteria as $criterion) {
        $maxValues[$criterion] = PHP_FLOAT_MIN;
        $minValues[$criterion] = PHP_FLOAT_MAX;
    }
    $minValues['effort_regulation'] = PHP_FLOAT_MAX;
    
    // Mencari nilai maksimum dan minimum untuk setiap kriteria
    foreach ($data as $row) {
        foreach ($criteria as $criterion) {
            $value = (float)$row[$criterion];
            if ($value > $maxValues[$criterion]) {
                $maxValues[$criterion] = $value;
            }
            if ($value < $minValues[$criterion]) {
                $minValues[$criterion] = $value;
            }
        }
        
        // Effort Regulation (cost criteria)
        $value = (float)$row['effort_regulation'];
        if ($value < $minValues['effort_regulation']) {
            $minValues['effort_regulation'] = $value;
        }
    }
    
    // Normalisasi data
    foreach ($data as $row) {
        $normalizedRow = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'nim' => $row['nim']
        ];
        
        // Normalisasi kriteria benefit (semakin besar semakin baik)
        foreach ($criteria as $criterion) {
            $value = (float)$row[$criterion];
            if ($maxValues[$criterion] == 0) {
                $normalizedRow[$criterion] = 0;
            } else {
                $normalizedRow[$criterion] = $value / $maxValues[$criterion];
            }
        }
        
        // Normalisasi kriteria cost (semakin kecil semakin baik)
        $value = (float)$row['effort_regulation'];
        if ($value == 0) {
            $normalizedRow['effort_regulation'] = 1; // Menghindari pembagian dengan nol
        } else {
            $normalizedRow['effort_regulation'] = $minValues['effort_regulation'] / $value;
        }
        
        $normalized[] = $normalizedRow;
    }
    
    return $normalized;
}
```

### 2. Fungsi Perhitungan Bobot WP

```php
function calculateWPWeights($data) {
    // Inisialisasi array untuk menyimpan hasil perkalian
    $products = [
        'iq' => 1.0,
        'eq' => 1.0,
        'attitude' => 1.0,
        'association' => 1.0,
        'friendship' => 1.0,
        'effort_regulation' => 1.0
    ];
    
    $n = count($data);
    
    if ($n == 0) {
        // Jika tidak ada data, kembalikan bobot yang sama
        return [
            'iq' => 1/6,
            'eq' => 1/6,
            'attitude' => 1/6,
            'association' => 1/6,
            'friendship' => 1/6,
            'effort_regulation' => 1/6
        ];
    }
    
    // Hitung perkalian untuk setiap kriteria
    foreach ($data as $row) {
        $products['iq'] *= $row['iq'] > 0 ? $row['iq'] : 0.01;
        $products['eq'] *= $row['eq'] > 0 ? $row['eq'] : 0.01;
        $products['attitude'] *= $row['attitude'] > 0 ? $row['attitude'] : 0.01;
        $products['association'] *= $row['association'] > 0 ? $row['association'] : 0.01;
        $products['friendship'] *= $row['friendship'] > 0 ? $row['friendship'] : 0.01;
        $products['effort_regulation'] *= $row['effort_regulation'] > 0 ? $row['effort_regulation'] : 0.01;
    }
    
    // Hitung akar pangkat n
    $sum = 0;
    $roots = [];
    foreach ($products as $criterion => $product) {
        $root = pow($product, 1/$n);
        $roots[$criterion] = $root;
        $sum += $root;
    }
    
    // Normalisasi bobot
    $weights = [];
    foreach ($roots as $criterion => $root) {
        $weights[$criterion] = $sum > 0 ? $root / $sum : 1/6;
    }
    
    return $weights;
}
```

### 3. Fungsi Perhitungan Peringkat SAW

```php
function calculateSAWRankings($normalizedData, $weights) {
    $rankings = [];
    
    foreach ($normalizedData as $row) {
        $score = 0;
        $score += $row['iq'] * $weights['iq'];
        $score += $row['eq'] * $weights['eq'];
        $score += $row['attitude'] * $weights['attitude'];
        $score += $row['association'] * $weights['association'];
        $score += $row['friendship'] * $weights['friendship'];
        $score += $row['effort_regulation'] * $weights['effort_regulation'];
        
        $rankings[] = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'nim' => $row['nim'],
            'score' => $score
        ];
    }
    
    // Urutkan berdasarkan skor tertinggi
    usort($rankings, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    return $rankings;
}
```

## Kesimpulan

Aplikasi "Selektor Mahasiswa Terbaik" ini menggabungkan dua metode dalam sistem pendukung keputusan, yaitu Weighted Product (WP) dan Simple Additive Weighting (SAW). 

1. **Weighted Product (WP)** digunakan untuk menentukan bobot setiap kriteria secara dinamis berdasarkan data yang ada. Metode ini mempertimbangkan distribusi data untuk menentukan seberapa penting setiap kriteria dalam pengambilan keputusan.

2. **Simple Additive Weighting (SAW)** digunakan untuk melakukan perangkingan mahasiswa berdasarkan kriteria yang telah diberi bobot. Metode ini memudahkan dalam membandingkan alternatif dengan kriteria yang berbeda-beda.

Keunggulan pendekatan ini adalah:
- Dinamis: Bobot kriteria dihitung otomatis berdasarkan data yang ada
- Objektif: Proses perhitungan yang sistematis mengurangi subjektivitas
- Fleksibel: Dapat menangani kriteria benefit dan cost
- Mudah dipahami: Metode yang digunakan relatif mudah dipahami dan dijelaskan

Dengan demikian, aplikasi ini dapat membantu dalam proses pengambilan keputusan yang lebih terstruktur dan objektif dalam memilih mahasiswa terbaik berdasarkan kriteria yang telah ditentukan.
