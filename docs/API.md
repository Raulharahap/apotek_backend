# Remedis RME — API Documentation

> **Version:** 1.0  
> **Base URL:** `http://localhost/api/v1`  
> **Auth:** Laravel Sanctum (Bearer Token)

---

## Daftar Isi

1. [Arsitektur](#arsitektur)
2. [Quick Start](#quick-start)
3. [Autentikasi](#autentikasi)
4. [Roles & Otorisasi](#roles--otorisasi)
5. [Endpoints](#endpoints)
6. [SATUSEHAT Sync](#-satusehat-sync)
7. [Error Handling](#error-handling)
8. [Konfigurasi SATUSEHAT](#konfigurasi-satusehat)
9. [Testing](#testing)
10. [Referensi Implementasi](#referensi-implementasi)

---

## Arsitektur

```
Client (Frontend / Mobile / Postman)
        │
        ▼  REST API (JSON)
┌───────────────────────────┐
│      Laravel Remedis      │
│  ─────────────────────    │
│  Auth  : Sanctum Token    │
│  Authz : Gate (per role)  │
│  Valid : Form Validation  │
└───────────────────────────┘
        │                       │
        ▼                       ▼
  MySQL (Lokal)         SATUSEHAT API
  (Data RME)            api-satusehat.kemkes.go.id
                        Format: FHIR R4
```

**Dua lapisan utama:**
1. **Local API** — kelola data pasien, kunjungan, diagnosa, obat, billing
2. **SATUSEHAT Sync** — push data ke portal Kemenkes dalam format FHIR R4

---

## Quick Start

```bash
# 1. Migrate database
php artisan migrate

# 2. Seed user dummy (7 role)
php artisan db:seed --class=UserSeeder

# 3. Login → dapatkan token
POST /api/v1/auth/login
{ "email": "admin@remedis.id", "password": "password" }
```

### Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@remedis.id | password |
| Dokter | dr.sari@remedis.id | password |
| Perawat | perawat@remedis.id | password |
| Apoteker | apoteker@remedis.id | password |
| Kasir | kasir@remedis.id | password |
| Front Office | fo@remedis.id | password |
| Pimpinan | pimpinan@remedis.id | password |

---

## Autentikasi

Semua endpoint (kecuali login) memerlukan header:

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## Roles & Otorisasi

| Role | Deskripsi | Akses Utama |
|---|---|---|
| `admin` | Administrator sistem | Semua endpoint (Gate::before bypass) |
| `dokter` | Dokter/tenaga medis | Rekam medis, diagnosa, resep, observasi |
| `perawat` | Perawat | Antrian, vital signs, status bed |
| `apoteker` | Petugas farmasi | Resep, stok obat, dispensing |
| `kasir` | Petugas kasir | Invoice, pembayaran |
| `front_office` | Pendaftaran | Pasien, antrian, jadwal |
| `pimpinan` | Manajemen | Read-only semua data, laporan |

> **Catatan:** `admin` otomatis punya akses ke semua gate via `Gate::before()`.

---

## Endpoints

### 🔐 Auth

| Method | Endpoint | Deskripsi | Auth |
|---|---|---|---|
| POST | `/auth/login` | Login, mendapatkan token | Public |
| POST | `/auth/logout` | Revoke token aktif | ✅ |
| GET | `/auth/me` | Data user yang login | ✅ |

#### POST `/auth/login`
```json
// Request
{ "email": "admin@remedis.id", "password": "password" }

// Response 200
{
  "token": "1|abc123...",
  "user": { "id": 1, "name": "Admin", "email": "...", "role": "admin" }
}
```

---

### 👥 Pasien

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/pasien` | Daftar pasien (search, paginate) | Semua |
| POST | `/pasien` | Daftarkan pasien baru | admin, front_office, dokter |
| GET | `/pasien/{id}` | Detail pasien | Semua |
| PUT | `/pasien/{id}` | Update data pasien | admin, front_office, dokter |
| DELETE | `/pasien/{id}` | Hapus pasien (soft delete) | admin |
| GET | `/pasien/{id}/rekam` | Riwayat kunjungan pasien | Semua |

**Query params GET `/pasien`:** `?search=nama_atau_nik&per_page=15`

#### POST `/pasien` — Body
```json
{
  "nama": "Budi Santoso",
  "nik": "3302011234567890",
  "tgl_lahir": "1990-01-15",
  "jk": "Laki-Laki",
  "no_hp": "08123456789",
  "email": "budi@mail.com",
  "alamat_lengkap": "Jl. Merdeka No. 1, Karangasem, Laweyan",
  "kabupaten": "Surakarta",
  "provinsi": "Jawa Tengah",
  "kodepos": "57145",
  "cara_bayar": "BPJS",
  "no_bpjs": "0001234567890"
}
```

> `no_rm` di-generate otomatis oleh server.

---

### 🩺 Dokter

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/dokter` | Daftar dokter | Semua |
| POST | `/dokter` | Tambah dokter | admin |
| GET | `/dokter/{id}` | Detail dokter | Semua |
| PUT | `/dokter/{id}` | Update dokter | admin |
| DELETE | `/dokter/{id}` | Hapus dokter | admin |
| GET | `/dokter/{id}/jadwal` | Jadwal praktek dokter | Semua |

---

### 🏥 Poli

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/poli` | Daftar poli | Semua |
| POST | `/poli` | Tambah poli | admin |
| GET | `/poli/{id}` | Detail poli | Semua |
| PUT | `/poli/{id}` | Update poli | admin |

---

### 📋 Antrian

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/antrian` | Daftar antrian hari ini | Semua |
| POST | `/antrian` | Buat antrian baru | admin, front_office, perawat |
| GET | `/antrian/{id}` | Detail antrian | Semua |
| PUT | `/antrian/{id}/status` | Update status antrian | perawat, front_office |

**Query params:** `?tanggal=2024-03-05&poli_id=1&status=menunggu`

#### PUT `/antrian/{id}/status`
```json
{ "status": "dipanggil" }
// status: menunggu | dipanggil | dilayani | selesai | batal
```

> `no_antrian` di-generate otomatis: `{kode_poli}{urutan_hari}` (mis. `A001`).

---

### 📅 Jadwal Dokter

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/jadwal-dokter` | Semua jadwal | Semua |
| POST | `/jadwal-dokter` | Tambah jadwal | admin |
| PUT | `/jadwal-dokter/{id}` | Update jadwal | admin |
| DELETE | `/jadwal-dokter/{id}` | Hapus jadwal | admin |

---

### 📁 Rekam Medis (Encounter)

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/rekam` | Daftar kunjungan | Semua |
| POST | `/rekam` | Buka kunjungan baru | admin, dokter, perawat |
| GET | `/rekam/{id}` | Detail kunjungan | Semua |
| PUT | `/rekam/{id}` | Update kunjungan / tutup | dokter |
| GET | `/rekam/{id}/detail` | Full rekam + semua sub-resource | Semua |

**Query params GET `/rekam`:** `?pasien_id=&dokter_id=&poli_id=&tgl=&status=`

#### POST `/rekam` — Body
```json
{
  "pasien_id": 1,
  "dokter_id": 2,
  "poli_id": 1,
  "jenis_kunjungan": "rawat_jalan",
  "tgl_masuk": "2024-03-05 08:30:00",
  "keluhan": "Demam dan batuk 3 hari",
  "cara_bayar": "BPJS"
}
// jenis_kunjungan: rawat_jalan | rawat_inap | igd | homecare
```

> `no_rekam` dan `status_encounter = in-progress` di-set otomatis.

---

### 🔬 Diagnosa (FHIR Condition)

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/rekam/{id}/diagnosa` | Daftar diagnosa | Semua |
| POST | `/rekam/{id}/diagnosa` | Tambah diagnosa | dokter |
| DELETE | `/rekam/{id}/diagnosa/{diagId}` | Hapus diagnosa | dokter |

```json
// POST body
{
  "icd_code": "J06.9",
  "diagnosa": "Infeksi Saluran Pernafasan Akut",
  "jenis": "utama",
  "clinical_status": "active",
  "verification_status": "confirmed"
}
// jenis: utama | sekunder | komorbid | komplikasi
```

---

### 🔭 Observasi (FHIR Observation)

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/rekam/{id}/observasi` | Daftar observasi | Semua |
| POST | `/rekam/{id}/observasi` | Tambah observasi | dokter, perawat |

```json
// POST body — contoh tekanan darah
{
  "kategori": "vital-signs",
  "kode_loinc": "85354-9",
  "nama_observasi": "Tekanan Darah",
  "komponen": [
    { "kode_loinc": "8480-6", "nama": "Sistolik", "nilai": "120", "satuan": "mmHg" },
    { "kode_loinc": "8462-4", "nama": "Diastolik", "nilai": "80",  "satuan": "mmHg" }
  ],
  "efektif_datetime": "2024-03-05 08:45:00",
  "status": "final"
}
```

---

### 🧪 Prosedur (FHIR Procedure) & Alergi (FHIR AllergyIntolerance)

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/rekam/{id}/prosedur` | Daftar prosedur | Semua |
| POST | `/rekam/{id}/prosedur` | Tambah prosedur | dokter |
| GET | `/rekam/{id}/alergi` | Daftar alergi pasien | Semua |
| POST | `/rekam/{id}/alergi` | Catat alergi | dokter |

---

### 💊 Resep & Pengeluaran Obat (FHIR MedicationRequest)

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/rekam/{id}/obat` | Resep pada kunjungan | Semua |
| POST | `/rekam/{id}/obat` | Tambah resep | dokter |
| PUT | `/pengeluaran-obat/{id}/dispense` | Konfirmasi dispensing | apoteker |

> Harga dan subtotal diambil otomatis dari tabel `obat`. `biaya_obat` pada rekam diperbarui setiap ada penambahan resep.

---

### 🏥 Rawat Inap — Kamar & Bed

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/kamar` | Daftar kamar & status | Semua |
| POST | `/kamar` | Tambah kamar | admin |
| GET | `/kamar/{id}/beds` | Daftar bed di kamar | Semua |
| PUT | `/beds/{id}/assign` | Assign pasien ke bed | perawat, admin |
| PUT | `/beds/{id}/release` | Kosongkan bed | perawat, admin |

---

### 💉 Obat & Farmasi

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/obat` | Daftar obat | Semua |
| POST | `/obat` | Tambah obat | admin, apoteker |
| GET | `/obat/{id}` | Detail obat | Semua |
| PUT | `/obat/{id}` | Update obat | admin, apoteker |
| GET | `/stok-obat` | Daftar stok per batch | apoteker, admin |
| POST | `/stok-obat` | Tambah stok masuk | apoteker, admin |
| GET | `/stok-obat/expired` | Obat akan/sudah expired ≤30 hari | apoteker, admin |

---

### 💰 Billing & Invoice

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| POST | `/invoice` | Generate invoice dari rekam | kasir, admin |
| GET | `/invoice/{id}` | Detail invoice | kasir, admin, pimpinan |
| POST | `/invoice/{id}/bayar` | Proses pembayaran | kasir |

```json
// POST /invoice/{id}/bayar
{
  "cara_bayar": "tunai",
  "dibayar": 150000,
  "catatan": "Bayar tunai"
}
// cara_bayar: tunai | transfer | BPJS | asuransi | qris | debit | kredit
// Kembalian dihitung otomatis. Status: partial | lunas
```

---

### ⚙️ Master Data Lainnya

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/tindakan` | Daftar tindakan/prosedur | Semua |
| POST | `/tindakan` | Tambah tindakan | admin |
| GET | `/icd` | Cari kode ICD-10 | Semua |
| GET | `/settings` | Konfigurasi sistem | admin |
| PUT | `/settings/{key}` | Update konfigurasi | admin |
| GET | `/users` | Daftar user sistem | admin |
| POST | `/users` | Tambah user | admin |
| PUT | `/users/{id}` | Update user | admin |

**Query params GET `/icd`:** `?search=hipertensi`

---

## 🔄 SATUSEHAT Sync

Sync data lokal ke portal SATUSEHAT Kemenkes dalam format FHIR R4. Token OAuth2 diambil otomatis dari tabel `settings`.

> [!IMPORTANT]
> **Payload FHIR masih minimal.** Controller saat ini mengirim field-field dasar FHIR R4. Untuk produksi, beberapa field tambahan mungkin diwajibkan SATUSEHAT (mis. `meta.profile`, `identifier` system-specific, coding system tertentu per use case). Sesuaikan `SatusehatSyncController` saat mengintegrasikan use case spesifik dari Postman collection.

> [!TIP]
> **Alur UI yang direkomendasikan:** Lakukan operasi CRUD lokal terlebih dahulu, lalu panggil endpoint sync hanya jika respons lokal `2xx`.
> ```
> UI → POST /api/v1/rekam              → 201 Created (lokal)
>    → POST /api/v1/satusehat/sync/rekam/{id}  → push ke SATUSEHAT
>    ← { "success": true, "satusehat_id": "..." }
> ```
> Sync bisa dipanggil **on-demand** dari UI atau dijadikan **background job**.

| Method | Endpoint | FHIR Resource | Role |
|---|---|---|---|
| POST | `/satusehat/sync/pasien/{id}` | Patient | admin, dokter |
| POST | `/satusehat/sync/rekam/{id}` | Encounter | admin, dokter |
| POST | `/satusehat/sync/rekam/{id}/diagnosa` | Condition | admin, dokter |
| POST | `/satusehat/sync/rekam/{id}/observasi` | Observation | admin, dokter |
| POST | `/satusehat/sync/rekam/{id}/obat` | MedicationRequest | admin, dokter |
| POST | `/satusehat/sync/rekam/{id}/prosedur` | Procedure | admin, dokter |
| POST | `/satusehat/sync/pasien/{id}/alergi` | AllergyIntolerance | admin, dokter |
| GET | `/satusehat/status/{resource}/{id}` | Cek status sync | Semua |

> ⚠️ **Urutan sync yang benar:** Pasien → Rekam → Diagnosa/Observasi/Obat/Prosedur. Pasien harus memiliki `satusehat_id` sebelum Rekam bisa di-sync.

#### Response Sync Berhasil
```json
{
  "success": true,
  "satusehat_id": "abc-def-123",
  "resource": "Encounter",
  "status": 201,
  "message": "Berhasil disinkronkan ke SATUSEHAT"
}
```

#### Response Cek Status (`GET /satusehat/status/pasien/{id}`)
```json
{
  "resource": "pasien",
  "id": 1,
  "satusehat_id": "uuid-xxx",
  "synced": true
}
```

---

## Error Handling

### HTTP Status Codes

| Code | Arti |
|---|---|
| 200 | OK — Request berhasil |
| 201 | Created — Resource berhasil dibuat |
| 400 | Bad Request — Request tidak valid (mis. invoice sudah lunas) |
| 401 | Unauthorized — Token tidak ada/invalid |
| 403 | Forbidden — Role tidak punya akses |
| 404 | Not Found — Resource tidak ditemukan |
| 422 | Unprocessable — Validasi gagal |
| 500 | Server Error |

### Format Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nik": ["NIK sudah terdaftar."],
    "no_hp": ["Format nomor HP tidak valid."]
  }
}
```

---

## Konfigurasi SATUSEHAT

Disimpan di tabel `settings`, dapat diupdate via `PUT /api/v1/settings/{key}`:

| Key | Deskripsi |
|---|---|
| `satusehat.client_id` | Client ID dari portal SATUSEHAT |
| `satusehat.client_secret` | Client Secret |
| `satusehat.organization_id` | ID Organisasi faskes |
| `satusehat.base_url` | URL FHIR API (default: api-satusehat.kemkes.go.id/fhir-r4/v1) |
| `satusehat.auth_url` | URL token OAuth2 |

---

## Testing

```bash
# Jalankan semua test API
php artisan test --filter="AuthTest|PasienTest|RekamTest|AntrianTest"
```

### Hasil Test (21 passed)

```
PASS  Tests\Feature\Api\AntrianTest   ✓ 3 tests
PASS  Tests\Feature\Api\AuthTest      ✓ 6 tests
PASS  Tests\Feature\Api\PasienTest    ✓ 5 tests
PASS  Tests\Feature\Api\RekamTest     ✓ 6 tests

Tests: 21 passed (39 assertions) — Duration: ~1.9s
```

> Test menggunakan **SQLite in-memory** (`RefreshDatabase`) — tidak perlu konfigurasi database tambahan.  
> Test SATUSEHAT sync tidak disertakan (memerlukan credential nyata).

### Cakupan Test

| Test File | Yang Di-cover |
|---|---|
| `AuthTest` | Login, logout, me, token invalid |
| `PasienTest` | CRUD pasien, validasi NIK duplikat |
| `RekamTest` | Buka/update rekam, tambah diagnosa |
| `AntrianTest` | Buat antrian, update status, list hari ini |

> [!NOTE]
> **Belum ada test** untuk: `DokterController`, `PoliController`, `JadwalDokterController`, `ObatController`, `TindakanController`, `IcdController`, `ObservasiController`, `ProsedurController`, `AlergiController`, `PengeluaranObatController`, `StokObatController`, `InvoiceController`, `KamarController`, `BedController`, `UserController`, `SettingController`, dan `SatusehatSyncController`.

---

## Referensi Implementasi

### Controllers (17 total)

| Controller | Domain |
|---|---|
| `AuthController` | Login / logout / Sanctum token |
| `PasienController` | CRUD pasien + riwayat rekam |
| `DokterController` | CRUD dokter + jadwal |
| `PoliController` | CRUD poli |
| `ObatController` | CRUD obat |
| `TindakanController` | CRUD tindakan medis |
| `IcdController` | Search ICD-10 |
| `UserController` | Admin: user management |
| `RekamController` | Encounter CRUD + detail |
| `RekamDiagnosaController` | Diagnosa nested di rekam |
| `ObservasiController` | FHIR Observation nested |
| `ProsedurController` | FHIR Procedure nested |
| `AlergiController` | FHIR AllergyIntolerance nested |
| `PengeluaranObatController` | MedicationRequest + dispense |
| `AntrianController` | Queue CRUD + status flow |
| `JadwalDokterController` | Dokter schedule |
| `KamarController` + `BedController` | Rawat inap bed management |
| `StokObatController` | Pharmacy stock + expired |
| `InvoiceController` | Billing + payment |
| `SettingController` | System config key-value |
| `SatusehatSyncController` | Push ke SATUSEHAT API |

### Otorisasi

```php
// AppServiceProvider.php
Gate::before(fn($user) => $user->role === 'admin' ? true : null);
Gate::define('create-pasien', fn($u) => in_array($u->role, ['front_office','dokter','perawat']));
// ... dst
```

### Model Factories (untuk testing)

`PasienFactory` · `DokterFactory` · `PoliFactory` · `RekamFactory` · `AntrianFactory`
