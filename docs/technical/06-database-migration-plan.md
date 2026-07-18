# Database Migration Plan & Data Dictionary

Dokumen ini berisi rincian teknis skema database (tipe data, indeks, dan batasan) yang akan digunakan untuk meng-generate file Migration di Laravel. Desain ini menggunakan pendekatan *Single-Tenant* (Aplikasi eksklusif untuk 1 Kecamatan/Puskesmas).

## 1. Table `users` (Admin & Super Admin)

| Column | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigint(unsigned) | PK, Auto Increment | |
| `name` | varchar(255) | Not Null | Nama lengkap admin |
| `email` | varchar(255) | Unique, Not Null | Email login |
| `password` | varchar(255) | Not Null | Hash password bcrypt |
| `role` | enum | Not Null | `['superadmin', 'admin_posyandu']` |
| `village_id` | bigint(unsigned) | Nullable, FK -> villages | Hanya diisi jika role = admin_posyandu |
| `remember_token` | varchar(100) | Nullable | |
| `created_at`, `updated_at` | timestamp | | Standard Laravel timestamps |

---

## 2. Table `villages` (Desa)

| Column | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigint(unsigned) | PK, Auto Increment | |
| `name` | varchar(100) | Not Null | Nama desa (misal: "Desa Tilango") |
| `created_at`, `updated_at` | timestamp | | Standard Laravel timestamps |

---

## 3. Table `health_posts` (Posyandu)

| Column | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigint(unsigned) | PK, Auto Increment | |
| `village_id` | bigint(unsigned) | Not Null, FK -> villages | Berelasi ke desa |
| `name` | varchar(100) | Not Null | Nama Posyandu |
| `created_at`, `updated_at` | timestamp | | Standard Laravel timestamps |

---

## 4. Table `respondents` (Data Masyarakat)

| Column | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigint(unsigned) | PK, Auto Increment | |
| `no_ktp` | varchar(16) | Unique, Not Null | NIK Masyarakat |
| `name` | varchar(150) | Not Null | Nama Lengkap Responden |
| `birthdate` | date | Not Null | Tanggal lahir |
| `gender` | enum | Not Null | `['L', 'P']` |
| `age` | tinyint(unsigned) | Not Null | Umur saat mendaftar |
| `blood_type` | enum | Nullable | `['A', 'B', 'AB', 'O', 'tidak_tahu']` |
| `village_id` | bigint(unsigned) | Nullable, FK -> villages | Relasi ke desa asal |
| `posyandu_id` | bigint(unsigned) | Nullable, FK -> health_posts | Relasi ke posyandu (opsional) |
| `created_at`, `updated_at` | timestamp | | Standard Laravel timestamps |

---

## 5. Table `screenings` (Riwayat Skrining & Kalkulasi Rule Engine)

| Column | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigint(unsigned) | PK, Auto Increment | |
| `respondent_id` | bigint(unsigned) | Not Null, FK -> respondents | |
| `screening_date` | date | Not Null | Indexing: untuk limit 2x/hari |
| **--- RAW INPUT ---** | | | *Jawaban Kuesioner (21 Variabel)* |
| `a_diabetes` s/d `c_buah_sayur` | boolean | Not Null, Default 0 | 13 Variabel (Bagian A, B, Faktor C) |
| `c_sistolik`, `c_diastolik` | smallint(unsigned) | Not Null | Input tensi darah |
| `c_gula`, `c_kolesterol_lab`, `c_asam_urat` | float(8,2) | Nullable | Input laboratorium (opsional) |
| `c_berat`, `c_tinggi`, `c_perut` | float(8,2) | Not Null | Antropometri |
| **--- GATEC OUTPUT ---** | | | *Kalkulasi Rule Engine (Cached)* |
| `dm_status` | varchar(100) | Not Null | Contoh: "Perlu Pemeriksaan Segera" |
| `dm_severity` | enum | Not Null | `['critical', 'high', 'moderate', 'info', 'good']` |
| `ht_status` | varchar(100) | Not Null | Contoh: "Hipertensi Derajat 2" |
| `ht_severity` | enum | Not Null | `['critical', 'high', 'moderate', 'info', 'good']` |
| `needs_urgent_referral` | boolean | Not Null, Default 0 | Flagging status gawat darurat |
| `needs_referral` | boolean | Not Null, Default 0 | Flagging butuh rujukan biasa |
| `created_at`, `updated_at` | timestamp | | Standard Laravel timestamps |

> **Catatan Indexing untuk Migration:**
> Kita akan menambahkan *Compound Index* pada migrasi tabel `screenings`: `$table->index(['respondent_id', 'screening_date']);`. Hal ini memastikan *query blocker* limit skrining harian (maks. 2x/hari) tereksekusi dengan cepat meskipun data tabel membengkak.
