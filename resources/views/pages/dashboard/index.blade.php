@extends('layouts.dashboard')

@section('title', 'Overview')

@section('content')

    <x-dashboard.page-header title="Dashboard Overview"
        subtitle="Selamat datang di SEHATI. Berikut ringkasan data skrining Anda bulan ini." :breadcrumb="['Overview' => null]" />

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Row 1 --}}
        <x-dashboard.stat-card title="Total Skrining" value="{{ number_format($kpi['total_skrining'], 0, ',', '.') }}">
            <x-slot name="icon"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg></x-slot>
        </x-dashboard.stat-card>

        <x-dashboard.stat-card title="Risiko Tinggi DM" value="{{ number_format($kpi['risiko_tinggi_dm'], 0, ',', '.') }}">
            <x-slot name="icon"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg></x-slot>
        </x-dashboard.stat-card>

        <x-dashboard.stat-card title="Risiko Tinggi HT" value="{{ number_format($kpi['risiko_tinggi_ht'], 0, ',', '.') }}">
            <x-slot name="icon"><svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg></x-slot>
        </x-dashboard.stat-card>

        <x-dashboard.stat-card title="Jumlah Fasilitas Kesehatan" value="{{ number_format($kpi['jumlah_posyandu'], 0, ',', '.') }}">
            <x-slot name="icon"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg></x-slot>
        </x-dashboard.stat-card>
    </div>

    {{-- Charts Section --}}
    {{-- Analytics Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Tren Skrining (6 Bulan Terakhir)</h3>
            <div id="chart-screening-trend" class="h-64 w-full"></div>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Total Skrining per Desa</h3>
            <div id="chart-screening-village" class="h-64 w-full"></div>
        </div>
    </div>

    {{-- Analytics Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Riwayat Penyakit Keluarga</h3>
            <div id="chart-family-disease" class="h-56 w-full flex justify-center"></div>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Riwayat Penyakit Pribadi</h3>
            <div id="chart-personal-disease" class="h-56 w-full flex justify-center"></div>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Distribusi Keparahan DM</h3>
            <div id="chart-severity-dm" class="h-56 w-full flex justify-center"></div>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Distribusi Keparahan HT</h3>
            <div id="chart-severity-ht" class="h-56 w-full flex justify-center"></div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Trend Line Chart
            const trendData = @json($screeningTrendChart);
            new ApexCharts(document.getElementById("chart-screening-trend"), {
                series: [{ name: "Total Skrining", data: Object.values(trendData) }],
                chart: { type: 'area', height: 250, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#1A56DB'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1, stops: [0, 90, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: { categories: Object.keys(trendData) }
            }).render();

            // Village Bar Chart
            const villageData = @json($screeningPerVillageChart);
            new ApexCharts(document.getElementById("chart-screening-village"), {
                series: [{ name: "Total Skrining", data: Object.values(villageData) }],
                chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#10B981'],
                plotOptions: { bar: { borderRadius: 4, horizontal: false, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                xaxis: { categories: Object.keys(villageData) }
            }).render();

            const severityColorMap = {
            'Risiko Rendah': '#10B981',   // Green
            'Risiko Sedang': '#FBBF24',   // Yellow
            'Risiko Tinggi': '#F97316',   // Orange
            'Kritis': '#EF4444',          // Red
            'Belum ada data': '#6B7280'   // Gray
        };

        const diseaseColorMap = {
            'Diabetes': '#3B82F6',        // Blue
            'Hipertensi': '#10B981',      // Green
            'Jantung': '#FBBF24',         // Yellow
            'Stroke': '#EF4444',          // Red
            'Asma': '#8B5CF6',            // Purple
            'Kolesterol': '#06B6D4',      // Cyan
            'Belum ada data': '#6B7280'   // Gray
        };

        function getSeverityColors(labels) {
            return labels.map(label => severityColorMap[label] || '#9CA3AF');
        }

        function getDiseaseColors(labels) {
            return labels.map(label => diseaseColorMap[label] || '#9CA3AF');
        }

        // Family Disease Pie Chart
        const familyData = @json($familyDiseaseChart);
        new ApexCharts(document.getElementById("chart-family-disease"), {
            series: Object.values(familyData),
            chart: { type: 'pie', height: 250, fontFamily: 'Inter, sans-serif' },
            labels: Object.keys(familyData),
            colors: getDiseaseColors(Object.keys(familyData)),
            legend: { position: 'bottom' }
        }).render();

        // Personal Disease Pie Chart
        const personalData = @json($personalDiseaseChart);
        new ApexCharts(document.getElementById("chart-personal-disease"), {
            series: Object.values(personalData),
            chart: { type: 'pie', height: 250, fontFamily: 'Inter, sans-serif' },
            labels: Object.keys(personalData),
            colors: getDiseaseColors(Object.keys(personalData)),
            legend: { position: 'bottom' }
        }).render();

        // Severity DM Donut Chart
        const dmData = @json($severityDmChart);
        new ApexCharts(document.getElementById("chart-severity-dm"), {
            series: Object.values(dmData),
            chart: { type: 'donut', height: 250, fontFamily: 'Inter, sans-serif' },
            labels: Object.keys(dmData),
            colors: getSeverityColors(Object.keys(dmData)),
            legend: { position: 'bottom' }
        }).render();

        // Severity HT Donut Chart
        const htData = @json($severityHtChart);
        new ApexCharts(document.getElementById("chart-severity-ht"), {
            series: Object.values(htData),
            chart: { type: 'donut', height: 250, fontFamily: 'Inter, sans-serif' },
            labels: Object.keys(htData),
            colors: getSeverityColors(Object.keys(htData)),
            legend: { position: 'bottom' }
        }).render();
    });
</script>
@endsection