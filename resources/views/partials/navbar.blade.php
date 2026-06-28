{{-- Navbar Partial --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">Sehati</span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="/"
                    class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                    Beranda
                </a>
                <a href="#tentang"
                    class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                    Tentang
                </a>
                <a href="#faq"
                    class="px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-colors">
                    FAQ
                </a>
            </div>

            {{-- CTA + Mobile Toggle --}}
            <div class="flex items-center gap-3">
                <!-- <a href="/consent" class="hidden md:inline-flex justify-center items-center py-2 px-4 text-sm font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                    Mulai Skrining
                </a> -->

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
                <a href="#tentang"
                    class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">Tentang</a>
                <a href="#faq" class="px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">FAQ</a>
                <a href="/consent"
                    class="mt-2 text-center text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg px-4 py-2.5 transition-colors">
                    Mulai Skrining
                </a>
            </div>
        </div>
    </div>
</nav>