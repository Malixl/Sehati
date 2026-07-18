<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Data Skrining</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #FFFF00; /* Yellow background */
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .text-format {
            mso-number-format: "\@"; /* Force Excel to treat as text */
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Skrining</th>
                <th>Periode Skrining</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Umur</th>
                <th>Jenis Kelamin</th>
                <th>Sistolik</th>
                <th>Diastolik</th>
                <th>Gula Darah</th>
                <th>Kolesterol</th>
                <th>Asam Urat</th>
                <th>Status DM</th>
                <th>Status HT</th>
                <th>Rekomendasi</th>
                <th>Status Tindakan</th>
                <th>Fasilitas Kesehatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($screenings as $index => $s)
                @php
                    $r = $s->respondent;
                    $age = $r && $r->birthdate ? \Carbon\Carbon::parse($r->birthdate)->age : '-';
                    $gender = $r && $r->gender == 'L' ? 'Laki-laki' : 'Perempuan';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $s->screeningPeriod->name ?? 'Tanpa Periode' }}</td>
                    <td class="text-format">{{ $r->nik ?? '-' }}</td>
                    <td class="text-left">{{ $r->fullname ?? '-' }}</td>
                    <td>{{ $age }}</td>
                    <td>{{ $gender }}</td>
                    <td>{{ $s->c_sistolik }}</td>
                    <td>{{ $s->c_diastolik }}</td>
                    <td>{{ $s->c_gula }}</td>
                    <td>{{ $s->c_kolesterol }}</td>
                    <td>{{ $s->c_asam_urat }}</td>
                    <td>{{ $s->dm_status }}</td>
                    <td>{{ $s->ht_status }}</td>
                    <td>{{ $s->recommendation_level }}</td>
                    <td>{{ $s->action_status == 'unhandled' ? 'Belum' : ($s->action_status == 'in_progress' ? 'Diproses' : 'Selesai') }}</td>
                    <td>{{ $r->healthPost->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
