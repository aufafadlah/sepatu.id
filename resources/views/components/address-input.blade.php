@props([
    'label' => 'Alamat Lengkap Pengiriman',
    'name' => 'shipping_address',
    'value' => '',
    'placeholder' => 'Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos...'
])

@php
    $uniqueId = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
@endphp

@push('styles')
    <!-- Leaflet CSS Modern & Ringan -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* FIX KRUSIAL: Mencegah bentrok CSS Tailwind yang merusak gambar tile peta */
        .leaflet-container img,
        .leaflet-tile,
        .leaflet-pane img {
            max-width: none !important;
            max-height: none !important;
        }
        .leaflet-container {
            font-family: inherit;
            border-radius: 1rem;
            z-index: 10;
        }
        .address-marker-pin {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.35));
            animation: bouncePin 0.35s ease-out;
        }
        @keyframes bouncePin {
            0% { transform: translateY(-10px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
    </style>
@endpush

<div class="space-y-3" id="address-container-{{ $uniqueId }}">
    <!-- Label Header -->
    <div class="flex items-center justify-between">
        <label for="shipping_address-{{ $uniqueId }}" class="block uppercase tracking-wider text-neutral-900 font-extrabold text-xs">
            {{ $label }} <span class="text-rose-500">*</span>
        </label>
        
        <!-- Tombol Link Pintas: Buka di Google Maps Langsung -->
        <a id="btn-open-gmaps-{{ $uniqueId }}"
           href="https://www.google.com/maps/search/?api=1&query=-6.200000,106.816666"
           target="_blank"
           rel="noopener noreferrer"
           title="Buka titik ini di aplikasi/web Google Maps"
           class="text-[11px] font-bold text-neutral-600 hover:text-orange-600 flex items-center gap-1 transition bg-neutral-100 hover:bg-neutral-200/70 px-2 py-0.5 rounded-md">
            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <span>Buka di Google Maps &nearr;</span>
        </a>
    </div>

    <!-- Search Box & Lokasi Saya -->
    <div class="space-y-1 relative">
        <div class="flex gap-2">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       id="search-input-{{ $uniqueId }}"
                       placeholder="Cari jalan, komplek, daerah, atau kelurahan..."
                       autocomplete="off"
                       class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-neutral-300 focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 text-xs font-medium bg-neutral-50/70 placeholder:text-neutral-400 transition">
                
                <!-- Loading Spinner -->
                <div id="search-spinner-{{ $uniqueId }}" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Tombol GPS Lokasi Saya -->
            <button type="button"
                    id="btn-geolocation-{{ $uniqueId }}"
                    title="Gunakan lokasi saya saat ini"
                    class="px-3.5 py-2.5 bg-white hover:bg-neutral-100 text-neutral-800 rounded-xl border border-neutral-300 font-bold text-xs flex items-center gap-1.5 transition shrink-0 shadow-2xs">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="hidden sm:inline">Lokasi Saya</span>
            </button>
        </div>

        <!-- Dropdown Hasil Pencarian -->
        <div id="search-results-{{ $uniqueId }}"
             class="hidden absolute z-30 left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-xl border border-neutral-200 max-h-56 overflow-y-auto divide-y divide-neutral-100 text-xs">
        </div>
    </div>

    <!-- Kotak Tampilan Peta Interaktif (Leaflet OSM) -->
    <div class="relative rounded-2xl overflow-hidden border border-neutral-300 shadow-xs bg-neutral-100">
        <!-- Container Peta dengan Tinggi Pasti 260px -->
        <div id="map-{{ $uniqueId }}" class="w-full" style="height: 260px; min-height: 260px; width: 100%;"></div>
        
        <!-- Status Bar Bawah Peta -->
        <div class="bg-neutral-50 px-3 py-2 border-t border-neutral-200 flex items-center justify-between text-[11px] text-neutral-600">
            <span class="flex items-center gap-1.5 min-w-0">
                <svg class="w-3.5 h-3.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span id="map-status-{{ $uniqueId }}" class="truncate">Geser pin atau klik peta untuk sesuaikan titik antar.</span>
            </span>
            <span class="hidden sm:inline text-neutral-400 font-medium shrink-0 ml-2">OpenStreetMap</span>
        </div>
    </div>

    <!-- Textarea Alamat Lengkap -->
    <div>
        <label for="shipping_address-{{ $uniqueId }}" class="block text-[11px] font-bold text-neutral-600 mb-1">
            Detail Alamat Lengkap &amp; Patokan <span class="text-neutral-400 font-normal">(bisa diedit/ditambahkan nomor rumah, blok, dll):</span>
        </label>
        <textarea id="shipping_address-{{ $uniqueId }}"
                  name="{{ $name }}"
                  rows="3"
                  required
                  placeholder="{{ $placeholder }}"
                  class="w-full rounded-xl border-neutral-300 focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 p-3 text-xs font-medium leading-relaxed bg-[#fcfcfc] transition">{{ old($name, $value) }}</textarea>

        @error($name)
            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
    <!-- Leaflet JS CDN Stabil -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        (function() {
            function initAddressMap_{{ $uniqueId }}() {
                const mapContainer = document.getElementById('map-{{ $uniqueId }}');
                const searchInput = document.getElementById('search-input-{{ $uniqueId }}');
                const searchResults = document.getElementById('search-results-{{ $uniqueId }}');
                const searchSpinner = document.getElementById('search-spinner-{{ $uniqueId }}');
                const textarea = document.getElementById('shipping_address-{{ $uniqueId }}');
                const btnGeo = document.getElementById('btn-geolocation-{{ $uniqueId }}');
                const btnOpenGmaps = document.getElementById('btn-open-gmaps-{{ $uniqueId }}');
                const statusText = document.getElementById('map-status-{{ $uniqueId }}');

                if (!mapContainer || !textarea) return;

                // Cek ketersediaan Leaflet
                if (typeof L === 'undefined') {
                    // Fallback load script jika belum siap
                    const script = document.createElement('script');
                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    script.onload = () => setTimeout(initAddressMap_{{ $uniqueId }}, 50);
                    document.head.appendChild(script);
                    return;
                }

                // Koordinat default (Pusat Jakarta / Monas)
                let currentLat = -6.200000;
                let currentLng = 106.816666;
                const defaultZoom = 14;

                // Inisialisasi peta Leaflet
                const map = L.map('map-{{ $uniqueId }}', {
                    scrollWheelZoom: false
                }).setView([currentLat, currentLng], defaultZoom);

                // Layer Tile OpenStreetMap (Gratis & Akurat)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                // Marker Pin Kustom Sepatu.id
                const pinSvg = `
                    <div class="address-marker-pin flex items-center justify-center relative">
                        <svg class="w-8 h-8 text-neutral-900" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.54 22.351A24.25 24.25 0 014.25 9.75a7.75 7.75 0 0115.5 0c0 5.568-4.484 10.37-8.21 12.601zm.46-9.601a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <div class="absolute w-2 h-2 bg-orange-500 rounded-full" style="top: 8px;"></div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: pinSvg,
                    className: '',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                const marker = L.marker([currentLat, currentLng], {
                    draggable: true,
                    icon: customIcon
                }).addTo(map);

                // Update link tombol "Buka di Google Maps"
                function updateGmapsLink(lat, lng) {
                    if (btnOpenGmaps) {
                        btnOpenGmaps.href = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
                    }
                }
                updateGmapsLink(currentLat, currentLng);

                // Helper Reverse Geocoding (Koordinat -> Nama Alamat Lengkap)
                let reverseTimeout = null;
                function reverseGeocode(lat, lng) {
                    currentLat = lat;
                    currentLng = lng;
                    updateGmapsLink(lat, lng);

                    if (statusText) statusText.textContent = 'Mendeteksi nama alamat...';
                    if (searchSpinner) searchSpinner.classList.remove('hidden');

                    clearTimeout(reverseTimeout);
                    reverseTimeout = setTimeout(function() {
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
                            .then(res => res.json())
                            .then(data => {
                                if (searchSpinner) searchSpinner.classList.add('hidden');
                                if (data && data.display_name) {
                                    textarea.value = data.display_name;
                                    if (searchInput) searchInput.value = data.display_name.split(',').slice(0, 3).join(',');
                                    if (statusText) statusText.textContent = 'Titik dipilih: ' + data.display_name.substring(0, 50) + '...';
                                } else {
                                    if (statusText) statusText.textContent = `Titik koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                                }
                            })
                            .catch(() => {
                                if (searchSpinner) searchSpinner.classList.add('hidden');
                                if (statusText) statusText.textContent = `Titik koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                            });
                    }, 350);
                }

                // Event saat marker digeser (dragend)
                marker.on('dragend', function() {
                    const pos = marker.getLatLng();
                    map.panTo(pos);
                    reverseGeocode(pos.lat, pos.lng);
                });

                // Event saat peta diklik
                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    reverseGeocode(e.latlng.lat, e.latlng.lng);
                });

                // Fitur Geolocation / "Lokasi Saya"
                if (btnGeo) {
                    btnGeo.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!navigator.geolocation) {
                            alert('Peramban Anda tidak mendukung sensor GPS.');
                            return;
                        }

                        if (statusText) statusText.textContent = 'Mencari sinyal GPS perangkat...';
                        if (searchSpinner) searchSpinner.classList.remove('hidden');

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                map.setView([lat, lng], 16);
                                marker.setLatLng([lat, lng]);
                                reverseGeocode(lat, lng);
                            },
                            function() {
                                if (searchSpinner) searchSpinner.classList.add('hidden');
                                if (statusText) statusText.textContent = 'Izin akses lokasi ditolak oleh peramban.';
                                alert('Gagal mendeteksi lokasi otomatis. Pastikan izin akses lokasi peramban diaktifkan.');
                            },
                            { timeout: 10000, enableHighAccuracy: true }
                        );
                    });
                }

                // Fitur Pencarian Tempat & Autocomplete
                if (searchInput && searchResults) {
                    let searchTimeout = null;

                    searchInput.addEventListener('input', function() {
                        const query = searchInput.value.trim();
                        clearTimeout(searchTimeout);

                        if (query.length < 3) {
                            searchResults.innerHTML = '';
                            searchResults.classList.add('hidden');
                            return;
                        }

                        if (searchSpinner) searchSpinner.classList.remove('hidden');

                        searchTimeout = setTimeout(function() {
                            fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&limit=5&q=${encodeURIComponent(query)}`)
                                .then(res => res.json())
                                .then(results => {
                                    if (searchSpinner) searchSpinner.classList.add('hidden');
                                    searchResults.innerHTML = '';

                                    if (!results || results.length === 0) {
                                        searchResults.innerHTML = `
                                            <div class="p-3 text-neutral-400 text-center font-medium">
                                                Tidak menemukan lokasi "${query}"
                                            </div>
                                        `;
                                        searchResults.classList.remove('hidden');
                                        return;
                                    }

                                    results.forEach(item => {
                                        const btn = document.createElement('button');
                                        btn.type = 'button';
                                        btn.className = 'w-full text-left p-3 hover:bg-neutral-50 transition flex items-start gap-2.5 focus:outline-none';
                                        btn.innerHTML = `
                                            <svg class="w-4 h-4 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div class="flex-grow min-w-0">
                                                <p class="font-bold text-neutral-900 truncate">${item.display_name.split(',')[0]}</p>
                                                <p class="text-[10px] text-neutral-500 line-clamp-1">${item.display_name}</p>
                                            </div>
                                        `;

                                        btn.addEventListener('click', function() {
                                            const lat = parseFloat(item.lat);
                                            const lng = parseFloat(item.lon);

                                            map.setView([lat, lng], 16);
                                            marker.setLatLng([lat, lng]);
                                            textarea.value = item.display_name;
                                            searchInput.value = item.display_name.split(',').slice(0, 3).join(',');
                                            searchResults.classList.add('hidden');
                                            updateGmapsLink(lat, lng);
                                            if (statusText) statusText.textContent = 'Lokasi dipilih: ' + item.display_name.substring(0, 50) + '...';
                                        });

                                        searchResults.appendChild(btn);
                                    });

                                    searchResults.classList.remove('hidden');
                                })
                                .catch(() => {
                                    if (searchSpinner) searchSpinner.classList.add('hidden');
                                });
                        }, 400);
                    });

                    document.addEventListener('click', function(e) {
                        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                            searchResults.classList.add('hidden');
                        }
                    });
                }

                // Invalidate size agar tile peta selalu tampil utuh, tidak abu-abu / glitch
                [100, 300, 700, 1500].forEach(delay => setTimeout(() => map.invalidateSize(), delay));
                window.addEventListener('resize', () => map.invalidateSize());

                // Intersection Observer untuk memastikan invalidateSize saat kontainer terlihat
                if ('IntersectionObserver' in window) {
                    const obs = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                map.invalidateSize();
                            }
                        });
                    }, { threshold: 0.1 });
                    obs.observe(mapContainer);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAddressMap_{{ $uniqueId }});
            } else {
                initAddressMap_{{ $uniqueId }}();
            }
        })();
    </script>
@endpush