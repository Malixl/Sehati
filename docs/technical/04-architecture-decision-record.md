# Architecture Decision Records (ADR)

Dokumen ini mencatat semua keputusan arsitektur (Architecture Decision Records) utama dalam proyek SEHATI. Setiap keputusan dicatat dengan format standar untuk memberikan konteks kepada anggota tim (sekarang maupun di masa depan) mengenai *mengapa* suatu keputusan diambil.

---

## ADR-001: Pemilihan Frontend Stack

**Status:** Accepted
**Date:** Juli 2026

**Context:** 
Aplikasi SEHATI membutuhkan antarmuka pengguna (UI) yang modern, bersih, dan interaktif untuk form skrining yang panjang. Aplikasi ini harus ringan, mudah di-hosting di shared hosting, dan *maintainable* oleh tim kecil (peneliti/dosen). Awalnya sempat dipertimbangkan menggunakan Livewire atau stack berat seperti React/Vue. Dipertimbangkan juga DaisyUI untuk komponen.

**Decision:** 
Kita menetapkan *stack* berikut:
1. **Laravel Blade:** Sebagai templating engine utama.
2. **Tailwind CSS v4 (Raw):** Sebagai utility-first CSS framework.
3. **Flowbite:** Sebagai library komponen UI (pengganti DaisyUI) karena struktur *class*-nya tidak menyembunyikan Tailwind utility, memudahkan kustomisasi.
4. **Alpine.js:** Untuk menangani state interaktivitas di sisi klien (seperti step-by-step form) tanpa round-trip server yang tidak perlu.

**Consequences:** 
- (+) Sangat ringan dan proses build (Vite) sangat cepat.
- (+) Flowbite menyediakan UI siap pakai yang cocok dengan ekosistem Tailwind murni.
- (+) Alpine.js memberikan reaktivitas layaknya Vue/React namun dengan ukuran sekecil jQuery, sangat cocok disematkan di dalam Blade.
- (-) Semua logika bisnis kompleks tetap harus diselesaikan di sisi server (Backend).

---

## ADR-002: Server-Side Rendering untuk Shared Hosting

**Status:** Accepted
**Date:** Juli 2026

**Context:** 
Target *deployment* produksi untuk proyek penelitian ini adalah Hostinger Shared Hosting (atau cPanel sejenisnya). Shared hosting memiliki keterbatasan dalam menjalankan *daemon* (background worker) atau environment Node.js secara persisten.

**Decision:** 
Kita menggunakan pola **Full Server-Side Rendering (SSR)** menggunakan arsitektur monolitik Laravel standar (Blade). Kita secara sadar menghindari pola Single Page Application (SPA) seperti Inertia.js atau arsitektur *headless* terpisah.

**Consequences:** 
- (+) Proses *deployment* ke cPanel/hPanel sangat standar dan minim konfigurasi server tingkat lanjut (cukup mapping `public_html` ke folder `public` Laravel).
- (-) Tidak bisa menggunakan fitur Laravel yang mewajibkan daemon berjalan terus-menerus (seperti Laravel Reverb untuk WebSockets realtime atau Laravel Horizon untuk Redis Queueing). Jika butuh queue, kita akan menggunakan *database queue* yang di-trigger via cron job (Scheduler).

---

## ADR-003: Pendekatan Module-First Design

**Status:** Accepted
**Date:** Juli 2026

**Context:** 
Banyak aplikasi Laravel berkembang menjadi *spaghetti code* ketika logika bisnis (seseperti kalkulasi Rule Engine atau validasi skrining) ditumpuk di dalam Controller (dikenal sebagai *Fat Controller*). Proyek SEHATI diprediksi akan memiliki berbagai modul (Skrining, Master Data, Dashboard, Auth) yang saling terkait.

**Decision:** 
Kita menerapkan arsitektur **Module-First Design** dengan pemisahan *Layered Architecture*:
- **Controller:** Hanya bertugas menerima HTTP Request, memanggil form validation (FormRequest), memanggil Service yang tepat, dan mengembalikan HTTP Response (Blade view/JSON).
- **Service:** Tempat semua logika bisnis (Business Logic) berada. Contoh: `ScreeningService` akan memanggil rule engine.
- **Repository:** Tempat abstraksi query database tingkat lanjut jika diperlukan, agar Service tidak bercampur dengan query builder Eloquent yang rumit.

**Consequences:** 
- (+) Kode menjadi sangat terstruktur, bersih, dan mudah di-maintain.
- (+) *Reusability* tinggi (sebuah *Service* bisa dipanggil oleh Controller Web maupun Controller API/CLI di masa depan).
- (+) Tanggung jawab yang jelas (Single Responsibility Principle) untuk setiap kelas.
- (-) Penambahan fitur membutuhkan pembuatan lebih banyak file (Controller + Service + Repository) dibandingkan gaya *Fat Controller*. Namun ini di-offset (dikompensasi) oleh kemudahan *maintenance* jangka panjang.
