Order API – Task 1

Project ini merupakan implementasi API sederhana untuk membuat order dengan multiple items, sekaligus menangani race condition pada pengurangan stock.

Features
- Buat produk
- Buat order dengan multiple items
- Validasi stock sebelum pembelian
- Otomatis mengurangi stock
- Generate nomor order (no_order)
- Menggunakan UUID sebagai identifier publik
- Mencegah overselling (race condition safe)

Concurrency Handling
Untuk mencegah race condition (overselling), digunakan:

1. Database Transaction
Semua proses dibungkus dalam transaction agar operasi basis data diproses secara utuh.

2. Pessimistic Locking
Digunakan untuk mengunci row product saat proses order berlangsung.

Dengan pendekatan ini:
- Tidak ada stock minus
- Tidak ada double order pada stock terbatas

Testing
Functional test dibuat untuk memastikan behavior sistem:

Covered Test Cases
- Create order success (multi item)
- Race condition prevention (no overselling)

Race Condition Test
Dilakukan dengan mensimulasikan dua request berurutan pada stock terbatas.

Hasil yang diinginkan:
- Hanya 1 request yang berhasil
- Stock tidak menjadi negatif

Hidden Item Game - Task 2
Program berbasis Command-Line Interface (CLI) untuk mensimulasikan pencarian koordinat item tersembunyi di dalam matriks grid berukuran 6x8 dengan halangan.

Spesifikasi Pergerakan
Menggunakan koordinat baris dan kolom yang dimulai dari indeks [4][1] (Posisi awal X). Pemain bergerak dengan urutan parameter:
- Utara / Up (A): Mengurangi indeks baris.
- Timur / East (B): Menambah indeks kolom.
- Selatan / South (C): Menambah indeks baris.
Jika rute menabrak dinding (#), pergerakan digagalkan. Jika berhasil mencapai titik aman (.), koordinat akhir akan ditampilkan beserta representasi visual grid yang menandai posisi item dengan simbol $.

Tech Stack
- Laravel
- MySQL
- PHPUnit

How to Run
- git clone https://github.com/akbarardi/flash-sale-service.git
- cd flash-sale-service
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan serve

Run Test
- php artisan test

Run Hidden Item Game
php artisan game:search {A} {B} {C}