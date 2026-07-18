# Folder Structure Standard

Aplikasi SEHATI menggunakan pendekatan arsitektur berlapis (Layered Architecture).

## Direktori Utama `app/`

```text
app/
├── Http/
│   ├── Controllers/   # Hanya menerima request dan memanggil Service
│   ├── Middleware/    # Filter Auth & Role
│   └── Requests/      # Form Request Validation
├── Models/            # Eloquent ORM & Relasi
├── Services/          # [CORE] Bisnis Logic (e.g., ScreeningService)
├── Repositories/      # [CORE] Akses Database (Query yang kompleks)
├── Rules/             # Custom Validation Rules
├── View/
│   └── Components/    # Blade Components
└── Utils/             # Helper / Rule Engine class (GATEC)
```

**Aturan Emas:**
1. **Controller tidak boleh berisi *business logic***. Controller hanya memanggil `Service`.
2. **Service tidak boleh berisi query Eloquent yang rumit**. Gunakan `Model` scopes atau `Repository`.
