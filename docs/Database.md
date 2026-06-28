# Sehati - Database Schema

## Overview

Dokumentasi skema database untuk project Sehati.

## Tables (Planned)

### `respondents`

| Column     | Type      | Description    |
| ---------- | --------- | -------------- |
| id         | bigint    | Primary key    |
| name       | varchar   | Nama responden |
| age        | int       | Usia           |
| gender     | enum      | Jenis kelamin  |
| created_at | timestamp |                |
| updated_at | timestamp |                |

### `responses`

| Column        | Type      | Description       |
| ------------- | --------- | ----------------- |
| id            | bigint    | Primary key       |
| respondent_id | bigint    | FK ke respondents |
| question_key  | varchar   | ID pertanyaan     |
| answer        | text      | Jawaban           |
| created_at    | timestamp |                   |

### `results`

| Column        | Type      | Description            |
| ------------- | --------- | ---------------------- |
| id            | bigint    | Primary key            |
| respondent_id | bigint    | FK ke respondents      |
| score         | decimal   | Skor hasil  ddduining |
| category      | varchar   | Kategori hasil         |
| created_at    | timestamp |                        |

> **Note:** Skema ini masih draft dan akan disesuaikan saat development.
