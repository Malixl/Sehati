<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Fasilitas Kesehatan — Sehati</title>

    {{-- Inter Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #map {
            height: calc(100vh - 64px); /* Full height minus navbar */
            width: 100%;
        }
        .legend-box {
            background: white;
            padding: 10px 14px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.2);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            line-height: 1.8;
        }
        .legend-box i {
            width: 14px;
            height: 14px;
            display: inline-block;
            margin-right: 6px;
            border-radius: 50%;
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-1000">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Sehati</span>
                </a>

                <div class="flex items-center gap-3">
                    <span id="status-text" class="text-sm text-gray-500 hidden sm:inline">Memuat peta...</span>
                    <a href="javascript:history.back()" class="text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Full-page Map --}}
    <div id="map"></div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusText = document.getElementById('status-text');

            // Default view: Gorontalo
            const defaultLat = 0.5435;
            const defaultLon = 123.0568;

            // Base Layers
            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri &mdash; Esri, DeLorme, NAVTEQ'
            });

            // Initialize map with street map as default
            const map = L.map('map', {
                center: [defaultLat, defaultLon],
                zoom: 10,
                layers: [streetMap]
            });

            // Icons
            const blueIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });

            const rsIcon = L.icon({
                iconUrl: '/img/RumahSakit.png',
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                popupAnchor: [0, -36]
            });

            const pkIcon = L.icon({
                iconUrl: '/img/PuskesMas.png',
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                popupAnchor: [0, -36]
            });

            // Legend
            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function () {
                const div = L.DomUtil.create('div', 'legend-box');
                div.innerHTML = `
                    <strong>Legenda</strong><br>
                    <img src="/img/RumahSakit.png" style="width:18px;height:18px;vertical-align:middle;margin-right:6px;"> Rumah Sakit<br>
                    <img src="/img/PuskesMas.png" style="width:18px;height:18px;vertical-align:middle;margin-right:6px;"> Puskesmas<br>
                    <i style="background: #2563eb;"></i> Lokasi Anda
                `;
                return div;
            };
            legend.addTo(map);

            // Load GeoJSON data
            async function loadGeoJSON() {
                statusText.textContent = 'Memuat data faskes...';

                try {
                    let rsLayer = null;
                    let pkLayer = null;

                    // Load Rumah Sakit
                    const rsRes = await fetch('/geojson/RumahSakit.json');
                    if (rsRes.ok) {
                        const rsData = await rsRes.json();
                        rsLayer = L.geoJSON(rsData, {
                            pointToLayer: (feature, latlng) => L.marker(latlng, { icon: rsIcon }),
                            onEachFeature: (feature, layer) => {
                                const nama = feature.properties.Nama || 'Rumah Sakit';
                                const jenis = feature.properties.Jenis || 'Rumah Sakit';
                                const coords = feature.geometry.coordinates;
                                layer.bindPopup(`
                                    <div style="font-family: 'Inter', sans-serif; min-width: 180px;">
                                        <strong style="font-size: 14px;">${nama}</strong><br>
                                        <span style="color: #6b7280; font-size: 12px;">${jenis}</span><br>
                                        <a href="https://www.google.com/maps/dir/?api=1&destination=${coords[1]},${coords[0]}" target="_blank" style="color: #2563eb; font-size: 12px; text-decoration: underline; margin-top: 4px; display: inline-block;">📍 Rute via Google Maps</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    }

                    // Load Puskesmas
                    const pkRes = await fetch('/geojson/PusKesmas.json');
                    if (pkRes.ok) {
                        const pkData = await pkRes.json();
                        pkLayer = L.geoJSON(pkData, {
                            pointToLayer: (feature, latlng) => L.marker(latlng, { icon: pkIcon }),
                            onEachFeature: (feature, layer) => {
                                const nama = feature.properties.Nama || 'Puskesmas';
                                const jenis = feature.properties.Jenis || 'Puskesmas';
                                const coords = feature.geometry.coordinates;
                                layer.bindPopup(`
                                    <div style="font-family: 'Inter', sans-serif; min-width: 180px;">
                                        <strong style="font-size: 14px;">${nama}</strong><br>
                                        <span style="color: #6b7280; font-size: 12px;">${jenis}</span><br>
                                        <a href="https://www.google.com/maps/dir/?api=1&destination=${coords[1]},${coords[0]}" target="_blank" style="color: #2563eb; font-size: 12px; text-decoration: underline; margin-top: 4px; display: inline-block;">📍 Rute via Google Maps</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    }

                    // Layer Control: Base maps + Overlay toggles
                    const baseMaps = {
                        "🗺️ Peta Jalan": streetMap,
                        "🛰️ Satelit": satelliteMap
                    };

                    const overlays = {};
                    if (rsLayer) overlays["🏥 Rumah Sakit"] = rsLayer;
                    if (pkLayer) overlays["🏢 Puskesmas"] = pkLayer;

                    L.control.layers(baseMaps, overlays, {
                        collapsed: false,
                        position: 'topright'
                    }).addTo(map);

                    statusText.textContent = 'Data faskes berhasil dimuat ✓';
                    setTimeout(() => { statusText.style.opacity = '0'; }, 3000);

                } catch (error) {
                    console.error('Gagal memuat GeoJSON:', error);
                    statusText.textContent = '⚠ Gagal memuat data faskes';
                }
            }

            // Try to get user location
            if (navigator.geolocation) {
                statusText.textContent = 'Mencari lokasi Anda...';
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        map.setView([lat, lon], 13);

                        L.marker([lat, lon], { icon: blueIcon }).addTo(map)
                            .bindPopup('<strong>Lokasi Anda Saat Ini</strong>')
                            .openPopup();

                        loadGeoJSON();
                    },
                    (error) => {
                        console.warn('Geolocation ditolak, menggunakan default Gorontalo.');
                        statusText.textContent = 'Lokasi GPS tidak tersedia, menampilkan Gorontalo.';
                        loadGeoJSON();
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                loadGeoJSON();
            }
        });
    </script>

</body>
</html>
