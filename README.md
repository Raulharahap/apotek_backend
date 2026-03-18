silakan install extension markdown preview di vs code supaya lebih enak membaca file `.md`

# rme-dev
RME (Rekam Medis Elektronik) adalah aplikasi sistem informasi kesehatan yang dirancang untuk membantu fasilitas pelayanan kesehatan dalam mengelola data pasien secara digital, terintegrasi, dan terstruktur.

Aplikasi ini mendukung proses pencatatan rekam medis, pengelolaan kunjungan pasien, pencatatan diagnosis dan tindakan, serta pelaporan kesehatan secara efisien dan akurat.
Tujuan utama pengembangan aplikasi ini adalah:
- Meningkatkan efisiensi pelayanan kesehatan
- Mengurangi pencatatan manual berbasis kertas
- Menjamin keamanan dan integritas data pasien
- Mendukung pelaporan kesehatan yang cepat dan akurat

Clone repository

git clone https://github.com/username/rme-dev.git
cd rme-dev


Install dependency
- composer install
- cp .env.example .env
- php artisan key:generate


Edit file .env, sesuaikan:

DB_DATABASE=remedis
DB_USERNAME=root
DB_PASSWORD=

Jalankan migration
# Migrate database
php artisan migrate
# Seed users (7 roles)
php artisan db:seed --class=UserSeeder
# Jalankan test (kalau penasaran, untuk memastikan work aja)
php artisan test --filter="AuthTest|PasienTest|RekamTest|AntrianTest"


Jalankan server
php artisan serve


Buka di browser:
http://127.0.0.1:8000 atau http://remedis.test (sesuai .env kalian)


