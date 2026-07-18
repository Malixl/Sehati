<nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button @click="sidebarOpen = !sidebarOpen" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="/dashboard" class="flex ms-2 md:me-24">
                    <svg class="h-8 w-8 text-blue-600 me-2" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    <span
                        class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-blue-900">SEHATI</span>
                </a>
            </div>

            <div class="flex items-center">

                {{-- Notifications --}}
                <div class="relative ms-3" x-data="{ notificationOpen: false }">
                    <button type="button" @click="notificationOpen = !notificationOpen" @click.outside="notificationOpen = false" class="relative p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                        <span class="sr-only">View notifications</span>
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -right-1">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </div>
                        @endif
                    </button>
                    
                    {{-- Notifications Dropdown --}}
                    <div x-show="notificationOpen" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="z-50 absolute right-0 top-10 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-80 max-h-96 overflow-y-auto"
                        x-cloak>
                        <div class="px-4 py-3 bg-gray-50 rounded-t-lg sticky top-0 border-b">
                            <p class="text-sm font-semibold text-gray-900">Notifikasi</p>
                        </div>
                        <ul class="py-1 divide-y divide-gray-100" role="none">
                            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                <li>
                                    <a href="{{ route('dashboard.notifications.markAsRead', $notification->id) }}" class="flex px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-70' : 'bg-blue-50' }}">
                                        <div class="w-full">
                                            <div class="text-sm text-gray-900 font-medium mb-1">{{ $notification->data['title'] ?? 'Pemberitahuan Baru' }}</div>
                                            <div class="text-xs text-gray-500 mb-2">{{ $notification->data['message'] ?? '' }}</div>
                                            <div class="text-xs text-blue-600">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li>
                                    <div class="px-4 py-6 text-center text-sm text-gray-500">
                                        Tidak ada notifikasi.
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="flex items-center ms-3 relative" x-data="{ userMenuOpen: false }">
                    <div>
                        <button type="button" @click="userMenuOpen = !userMenuOpen"
                            @click.outside="userMenuOpen = false"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
                            aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold uppercase">
                                {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
                            </div>
                        </button>
                    </div>

                    {{-- Dropdown --}}
                    <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="z-50 absolute right-0 top-10 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-48"
                        x-cloak>
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm font-medium text-gray-900" role="none">
                                {{ Auth::user()->name ?? 'Super Admin' }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate" role="none">
                                {{ Auth::user()->email ?? 'admin@sehati.com' }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="/dashboard/profil"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Profil Anda</a>
                            </li>
                            <!-- <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Pengaturan</a>
                            </li> -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                        role="menuitem">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>