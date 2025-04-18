# KTP Extractor PHP

![License](https://img.shields.io/github/license/classyid/ktp-extractor-php)
![Stars](https://img.shields.io/github/stars/classyid/ktp-extractor-php?style=social)

<p align="center">
  <img src="https://blog.classy.id/upload/gambar_berita/a26fa049152aa65e02e9c00b28a84bce_20250418212044.png" alt="KTP Extractor Logo"/>
</p>

<p align="center">
  Aplikasi web PHP yang powerful untuk mengekstrak data dari Kartu Tanda Penduduk (KTP) Indonesia menggunakan Gemini AI.
</p>

## ✨ Fitur

- 📱 **Responsif** - UI menyesuaikan dengan sempurna di berbagai ukuran layar
- 🎨 **UI Modern** - Dibangun dengan Tailwind CSS dan Font Awesome
- 🔍 **Ekstraksi Cerdas** - Menggunakan API berbasis Gemini AI untuk akurasi tinggi
- 🖼️ **Drag & Drop** - Upload gambar KTP dengan mudah
- 📋 **Copy Data** - Salin data ekstraksi ke clipboard dengan satu klik
- 💾 **Export JSON** - Unduh data dalam format JSON untuk integrasi sistem lain
- ⚡ **Cepat & Ringan** - Dioptimalkan untuk kinerja yang cepat

## 🚀 Demo

<p align="center">
  <img src="https://blog.classy.id/upload/gambar_berita/a26fa049152aa65e02e9c00b28a84bce_20250418212044.png" alt="KTP Extractor Demo"/>
</p>

## 🔧 Instalasi

1. Clone repository ini:
   ```bash
   git clone https://github.com/classyid/ktp-extractor-php.git
   ```

2. Pindah ke direktori proyek:
   ```bash
   cd ktp-extractor-php
   ```

3. Konfigurasi endpoint API di file `index.php`:
   ```javascript
   const API_URL = 'https://script.google.com/macros/s/[ID_DEPLOYMENT]/exec';
   ```
   Ganti `[ID_DEPLOYMENT]` dengan ID deployment API Anda.


## 📝 Penggunaan

1. **Upload Gambar KTP**
   - Klik tombol upload atau seret gambar KTP ke area drop
   - Format yang didukung: JPG, JPEG, dan PNG
   - Ukuran maksimal: 5MB

2. **Proses Ekstraksi**
   - Klik tombol "Ekstrak Data KTP"
   - Tunggu beberapa saat sampai proses selesai

3. **Hasil Ekstraksi**
   - Data KTP akan ditampilkan secara terstruktur
   - Gunakan tombol "Salin Data" untuk menyalin ke clipboard
   - Gunakan tombol "Unduh JSON" untuk mengunduh data dalam format JSON

## 🔌 Integrasi API

Aplikasi ini menggunakan API Ekstraksi Data KTP yang dibangun dengan Google Apps Script dan Gemini AI. Lihat [dokumentasi API](./docs/API-DOCUMENTATION.md) untuk detail lebih lanjut.

## 📊 Struktur Data

Data yang diekstrak dari KTP meliputi:

- NIK
- Nama
- Tempat/Tanggal Lahir
- Jenis Kelamin
- Golongan Darah
- Alamat
- RT/RW
- Kel/Desa
- Kecamatan
- Agama
- Status Perkawinan
- Pekerjaan
- Kewarganegaraan
- Berlaku Hingga
- Dikeluarkan di

## 🛡️ Privasi & Keamanan

- Data KTP hanya diproses untuk tujuan ekstraksi dan tidak disimpan secara permanen
- Semua transfer data melalui API menggunakan HTTPS
- Aplikasi tidak menyimpan riwayat ekstraksi di server

## 🤝 Kontribusi

Kontribusi selalu diterima! Lihat [CONTRIBUTING.md](./CONTRIBUTING.md) untuk panduan berkontribusi.

## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat [LICENSE](./LICENSE) untuk informasi lebih lanjut.
