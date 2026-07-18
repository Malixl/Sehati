# System Overview — SEHATI

## Project Status
**Version:** 1.0 (Research Phase)

## Technical Stack
- **Framework:** Laravel 12
- **Architecture Style:** Modular Monolith (Module-First Design)
- **Design Pattern:** MVC + Service Layer + Repository Pattern
- **Rendering:** Laravel Blade (Server-Side Rendering)
- **Frontend CSS:** Tailwind CSS v4 (Raw) + Flowbite
- **Frontend JS:** Alpine.js
- **Database:** MySQL
- **Authentication:** Laravel Breeze / Custom Guard
- **Deployment:** Hostinger Shared Hosting

## Target Users
1. **Masyarakat:** Melakukan skrining mandiri
2. **Admin Posyandu (Tingkat Desa):** Memasukkan data masyarakat, melihat hasil desa
3. **Super Admin (Tingkat Kecamatan):** Melihat dashboard kumulatif 5 PTM

## High Level Data Flow
[Masyarakat/Admin] -> [Blade View] -> [Controller] -> [Service Layer] -> [Rule Engine GATEC] -> [Repository] -> [MySQL Database]
