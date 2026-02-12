<nav x-data="{ open: false, scanned: false }" 
     @scroll.window="scanned = (window.pageYOffset > 20) ? true : false"
     :class="{ 'bg-white/80 backdrop-blur-md shadow-sm': scanned, 'bg-white/50 backdrop-blur-sm': !scanned }"
     class="fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b border-gray-200/50">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <!-- Increased height for better proportions -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard', ['username' => \Illuminate\Support\Str::slug(Auth::user()->name)]) }}" class="group flex items-center gap-2">
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg group-hover:shadow-indigo-500/30 group-hover:scale-105 transition-all duration-300">
                             <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900 group-hover:text-indigo-600 transition-colors">Tender<span class="text-indigo-600">Flow</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
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
                           class="relative px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 ease-in-out
                                  {{ request()->routeIs($link['route']) 
                                     ? 'text-indigo-700 bg-indigo-50/80 shadow-sm ring-1 ring-indigo-200' 
                                     : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                            {{ __($link['label']) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 pl-3 pr-2 py-1.5 border border-gray-200 rounded-full text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-sm">
                            <span class="ml-1">{{ Auth::user()->name }}</span>
                            
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white text-xs font-bold ring-2 ring-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 mb-1">
                            <p class="text-sm leading-5 text-gray-500">Signed in as</p>
                            <p class="text-sm font-bold leading-5 text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-xl border-t border-gray-200 shadow-xl">
        <div class="pt-2 pb-3 space-y-1 px-2">
           @php
                // Re-use logic for brevity/consistency
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
                   class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out
                          {{ request()->routeIs($link['route']) 
                             ? 'border-indigo-400 text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700' 
                             : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                    {{ __($link['label']) }}
                </a>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50/50">
            <div class="px-4 flex items-center gap-3">
                 <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg hover:bg-white hover:shadow-sm">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-red-600 hover:bg-red-50 to-transparent rounded-lg"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
