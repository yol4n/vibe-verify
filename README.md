# VibeVerify - Detektor Berita Palsu Berbasis AI

VibeVerify adalah aplikasi web sederhana yang membantu pengguna menganalisis apakah sebuah teks berita berpotensi hoax atau tidak menggunakan Gemini API.

Project ini dibuat untuk event #JuaraVibeCoding sebagai contoh pemanfaatan AI dalam membantu masyarakat melakukan verifikasi awal terhadap informasi yang beredar di internet dan media sosial.

## Latar Belakang

Penyebaran berita palsu atau hoax menjadi masalah serius karena dapat membuat masyarakat mudah percaya pada informasi yang belum terverifikasi. Banyak pengguna internet menerima berita dari media sosial tanpa mengecek sumber aslinya.

Karena itu, VibeVerify dibuat sebagai alat bantu sederhana untuk memberikan analisis awal terhadap teks berita. Aplikasi ini tidak menggantikan proses verifikasi resmi, tetapi membantu pengguna lebih berhati-hati sebelum mempercayai atau menyebarkan informasi.

## Fitur Utama

- Input teks berita
- Analisis berita menggunakan Gemini API
- Menampilkan status berita:
  - VALID
  - HOAX
  - POTENSI_HOAX
- Menampilkan skor risiko
- Menampilkan alasan hasil analisis
- Memberikan rekomendasi tindakan kepada pengguna
- Validasi input minimal 30 karakter
- Tampilan responsif dan sederhana
- Error handling jika server atau API gagal merespons

## Teknologi yang Digunakan

- Laravel 12
- PHP 8.2
- Vite
- TailwindCSS
- Gemini API
- JavaScript

## Cara Menjalankan Project

1. Clone repository

```bash
git clone link-repository-kamu
cd vibe-verify