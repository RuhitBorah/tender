@props(['maxWidth' => 'max-w-7xl'])

<nav x-data="{ open: false }" class="fixed top-6 left-1/2 -translate-x-1/2 w-[95%] {{ $maxWidth }} z-50 bg-white/80 supports-[backdrop-filter]:bg-white/60 backdrop-blur-xl rounded-2xl border border-gray-200/60 shadow-xl shadow-black/5 transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard', ['username' => \Illuminate\Support\Str::slug(Auth::user()->name)]) }}" class="flex items-center gap-3 group">
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-lg leading-none text-gray-900 tracking-wide font-sans">Tender<span class="text-indigo-600">Flow</span></span>
                            <span class="text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase">Enterprise</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:flex items-center">
                    @php
                        $navLinks = [];
                        if(Auth::user()->role === 'admin') {
                            $navLinks = [
                                ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                                ['route' => 'admin.tenders', 'label' => 'Tenders'],
                                ['route' => 'admin.users', 'label' => 'Users'],
                                ['route' => 'admin.locations', 'label' => 'Locations'],
                            ];
                        } else {
                            $username = \Illuminate\Support\Str::slug(Auth::user()->name);
                             $navLinks = [
                                ['route' => 'dashboard', 'params' => ['username' => $username], 'label' => 'Dashboard'],
                                ['route' => 'user.tenders', 'params' => ['username' => $username], 'label' => 'Tenders'],
                            ];
                        }
                    @endphp

                    @foreach($navLinks as $link)
                        <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                           class="relative px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300
                                  {{ request()->routeIs($link['route']) 
                                     ? 'text-indigo-700 bg-indigo-50 border border-indigo-100' 
                                     : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 border border-transparent' }}">
                           @if(request()->routeIs($link['route']))
                                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-indigo-600 rounded-full"></span>
                           @endif
                            {{ __($link['label']) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="56" contentClasses="py-1 bg-white border border-gray-100 text-gray-700 shadow-xl rounded-xl">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-3 pl-4 pr-1.5 py-1.5 rounded-full border border-gray-200 bg-gray-50/50 hover:bg-gray-100 hover:border-gray-300 transition-all duration-300">
                             <div class="flex flex-col items-end mr-1">
                                <span class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-gray-500 leading-none mt-0.5">Admin</span>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 p-[1px] shadow-sm">
                                <div class="h-full w-full rounded-full bg-white flex items-center justify-center">
                                     <span class="text-xs font-bold text-indigo-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Signed in as</p>
                            <p class="text-sm font-bold text-gray-900 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="p-1">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900 group flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 group flex items-center gap-2"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100 rounded-b-2xl shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-3">
           @php
                 $navLinks = [];
                if(Auth::user()->role === 'admin') {
                    $navLinks = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.tenders', 'label' => 'Tenders'],
                        ['route' => 'admin.users', 'label' => 'Users'],
                        ['route' => 'admin.locations', 'label' => 'Locations'],
                    ];
                } else {
                    $username = \Illuminate\Support\Str::slug(Auth::user()->name);
                     $navLinks = [
                        ['route' => 'dashboard', 'params' => ['username' => $username], 'label' => 'Dashboard'],
                        ['route' => 'user.tenders', 'params' => ['username' => $username], 'label' => 'Tenders'],
                    ];
                }
            @endphp

            @foreach($navLinks as $link)
                <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                   class="block px-4 py-3 rounded-xl text-base font-bold transition-all duration-200 my-1
                          {{ request()->routeIs($link['route']) 
                             ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' 
                             : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border border-transparent' }}">
                    {{ __($link['label']) }}
                </a>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
            <div class="px-5 flex items-center gap-4">
                 <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 font-sans">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-2 px-3">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 pl-4">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="rounded-xl text-red-600 hover:text-red-700 hover:bg-red-50 pl-4"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
