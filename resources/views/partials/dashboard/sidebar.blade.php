<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-20 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium mt-4">

            {{-- Dashboard --}}
            <li>
                <a href="/dashboard"
                    class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->is('dashboard') ? 'bg-blue-50' : '' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ request()->is('dashboard') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span
                        class="ms-3 {{ request()->is('dashboard') ? 'text-blue-700 font-semibold' : '' }}">Dashboard</span>
                </a>
            </li>

            {{-- Data Responden --}}
            <li>
                <a href="{{ route('dashboard.skrining.index') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('dashboard.skrining.*') ? 'bg-blue-50' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard.skrining.*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span
                        class="flex-1 ms-3 whitespace-nowrap {{ request()->routeIs('dashboard.skrining.*') ? 'text-blue-700 font-semibold' : '' }}">Data
                        Skrining</span>
                </a>
            </li>


            {{-- Hasil Capaian --}}
            <li>
                <a href="/dashboard/capaian"
                    class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->is('dashboard/capaian*') ? 'bg-blue-50' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->is('dashboard/capaian*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span
                        class="flex-1 ms-3 whitespace-nowrap {{ request()->is('dashboard/capaian*') ? 'text-blue-700 font-semibold' : '' }}">Hasil
                        Capaian</span>
                </a>
            </li>

        </ul>

        <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200">
            {{-- Data Faskes (Super Admin & Owner Only) --}}
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isOwner())
                <li>
                    <a href="/dashboard/posyandu"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->is('dashboard/posyandu*') ? 'bg-blue-50' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->is('dashboard/posyandu*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span
                            class="flex-1 ms-3 whitespace-nowrap {{ request()->is('dashboard/posyandu*') ? 'text-blue-700 font-semibold' : '' }}">Data
                            Faskes</span>
                    </a>
                </li>
            @endif

            {{-- Manajemen Periode (Super Admin Only) --}}
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isOwner())
                <li>
                    <a href="/dashboard/periode"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->is('dashboard/periode*') ? 'bg-blue-50' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->is('dashboard/periode*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-blue-600' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span
                            class="flex-1 ms-3 whitespace-nowrap {{ request()->is('dashboard/periode*') ? 'text-blue-700 font-semibold' : '' }}">Manajemen
                            Periode</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</aside>