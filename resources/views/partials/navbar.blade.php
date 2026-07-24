{{-- Navbar Partial --}}
<div x-data="{ timModalOpen: false }" class="sticky top-0 z-50">
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('img/Blue.svg') }}" alt="Sehati Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl font-bold text-gray-900">Sehati</span>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="/"
                        class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                        Beranda
                    </a>
                    <a href="/#tentang"
                        class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                        Tentang
                    </a>
                    <a href="/#pedoman"
                        class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                        Pedoman
                    </a>
                    <a href="/#tim"
                        class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                        Tim
                    </a>
                    <a href="{{ route('screening.history') }}"
                        class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('screening.history') ? 'text-blue-700 bg-blue-50' : '' }}">
                        Riwayat
                    </a>
                </div>

                {{-- CTA + Mobile Toggle --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="hidden md:inline-flex items-center justify-center py-2 px-4 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg transition-all duration-300">
                        Login
                    </a>
                    {{-- Mobile menu button --}}
                    <button data-collapse-toggle="navbar-mobile" type="button"
                        class="md:hidden inline-flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        aria-controls="navbar-mobile" aria-expanded="false">
                        <span class="sr-only">Menu</span>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div class="hidden md:hidden pb-4" id="navbar-mobile">
                <div class="flex flex-col gap-1 pt-2 border-t border-gray-100">
                    <a href="/"
                        class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Beranda</a>
                    <a href="/#tentang"
                        class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Tentang</a>
                    <a href="/#pedoman"
                        class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Pedoman</a>
                    <a href="/#tim"
                        class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Tim</a>
                    <a href="{{ route('screening.history') }}"
                        class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 {{ request()->routeIs('screening.history') ? 'text-blue-700 bg-blue-50' : '' }}">Riwayat</a>
                    <a href="{{ route('login') }}"
                        class="mt-4 text-center text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                        Login Petugas
                    </a>
                    <a href="/consent"
                        class="mt-2 text-center text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg px-4 py-2.5 transition-colors shadow-sm">
                        Mulai Skrining
                    </a>
                </div>
            </div>
        </div>
    </nav>
</div>