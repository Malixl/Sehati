{{-- Footer Partial --}}
<footer class="bg-gray-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('img/Blue.svg') }}" alt="Sehati Logo" class="w-full h-full object-contain">
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
                    <li><a href="#tim" class="hover:text-blue-600 transition-colors">Tim</a></li>
                    <li><a href="#faq" class="hover:text-blue-600 transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Kontak</h4>
                <ul class="space-y-2.5 text-sm text-gray-500">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="https://wa.me/628124490785" target="_blank"
                            class="hover:text-green-600 transition-colors">
                            WhatsApp
                        </a>
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