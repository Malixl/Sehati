{{-- Footer Partial --}}
<footer class="bg-gray-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">Sehati</span>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Sistem Skrining Kesehatan untuk deteksi dini Diabetes Mellitus dan Hipertensi.
                </p>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Tautan</h4>
                <ul class="space-y-2.5 text-sm text-gray-500">
                    <li><a href="/" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-blue-600 transition-colors">Tentang Skrining</a></li>
                    <li><a href="#faq" class="hover:text-blue-600 transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Kontak</h4>
                <ul class="space-y-2.5 text-sm text-gray-500">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>sehati@example.com</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="border-t border-gray-200 mt-10 pt-6">
            <p class="text-sm text-gray-500 text-center">
                &copy; {{ date('Y') }} Sehati. Dibuat untuk keperluan penelitian.
            </p>
        </div>
    </div>
</footer>
