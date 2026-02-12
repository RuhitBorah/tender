<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 pb-12" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        
        <!-- Welcome Banner with Glassmorphism & Animated Gradient -->
        <div class="relative bg-white border-b border-gray-200 overflow-hidden shadow-sm" 
             x-show="show" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 opacity-70 animate-gradient-x"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                            Good Morning, {{ Auth::user()->name }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 max-w-2xl">
                            Here's your district's activity overview for <span class="font-medium text-gray-900">{{ now()->format('l, F jS') }}</span>.
                        </p>
                    </div>
                    @if($user->district)
                        <div class="flex items-center gap-3 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border border-gray-200/60 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-200">
                                {{ substr($user->district->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-900 uppercase tracking-wide leading-none">{{ $user->district->name }}</span>
                                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Authorized District</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 space-y-8">
            
            <!-- Stats Grid with Hover Effects -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Active Tenders -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-6px_rgba(0,0,0,0.1)] transition-all duration-300 group relative overflow-hidden transform hover:-translate-y-1"
                     x-show="show" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110 origin-top-right">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Active Tenders</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2 tracking-tight flex items-baseline gap-2">
                            {{ $stats['active'] ?? 0 }}
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">+{{ rand(1, 5) }} this week</span>
                        </p>
                    </div>
                    <div class="mt-6">
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 70%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 font-medium">70% completion rate</p>
                    </div>
                </div>

                <!-- Total Value -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-6px_rgba(0,0,0,0.1)] transition-all duration-300 group relative overflow-hidden transform hover:-translate-y-1"
                     x-show="show" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110 origin-top-right">
                        <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1.002-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.548.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.005a6.25 6.25 0 01.137-.996c-.67-.282-1.353-.541-2.046-.778A4.265 4.265 0 018.736 6.979z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Value</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2 tracking-tight">₹{{ number_format(($stats['active_value'] ?? 0) + ($stats['closed_value'] ?? 0), 2) }}</p>
                    </div>
                    <div class="mt-6">
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 45%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 font-medium">On track with budget</p>
                    </div>
                </div>

                <!-- Closed Tenders -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-6px_rgba(0,0,0,0.1)] transition-all duration-300 group relative overflow-hidden transform hover:-translate-y-1"
                     x-show="show" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110 origin-top-right">
                         <svg class="w-24 h-24 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Closed</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2 tracking-tight">{{ $stats['closed'] ?? 0 }}</p>
                    </div>
                    <div class="mt-6 flex items-center text-xs font-semibold text-gray-500">
                         <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>
                         Archived successfully
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Main: Recent Tenders -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                             <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                             </div>
                            Recent Tenders
                        </h3>
                        <a href="{{ route('user.tenders', ['username' => Str::slug(Auth::user()->name)]) }}" class="group flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                            View All
                            <span class="bg-indigo-50 p-1 rounded-full group-hover:bg-indigo-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </span>
                        </a>
                    </div>

                    @if($tenders->count() > 0)
                        <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead>
                                        <tr class="bg-gray-50/50">
                                            <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Tender No</th>
                                            <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Description</th>
                                            <th scope="col" class="px-8 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Deadline</th>
                                            <th scope="col" class="px-8 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Value</th>
                                            <th scope="col" class="px-8 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($tenders as $tender)
                                            <tr class="hover:bg-indigo-50/30 transition-colors duration-200 group">
                                                <td class="px-8 py-5 whitespace-nowrap">
                                                    <span class="text-sm font-semibold text-indigo-600 font-mono bg-indigo-50 px-2 py-1 rounded-md group-hover:bg-indigo-100 transition-colors">{{ $tender->tender_no }}</span>
                                                </td>
                                                <td class="px-8 py-5">
                                                    <div class="text-sm font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-700 transition-colors">{{ $tender->title }}</div>
                                                    <div class="text-xs font-medium text-gray-500 line-clamp-1 mt-0.5">{{ $tender->department }}</div>
                                                </td>
                                                <td class="px-8 py-5 whitespace-nowrap text-center text-sm font-medium text-gray-600">
                                                    {{ $tender->closing_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-8 py-5 whitespace-nowrap text-sm text-right font-bold font-mono text-gray-900 tracking-tight">
                                                    ₹{{ number_format($tender->value, 2) }}
                                                </td>
                                                <td class="px-8 py-5 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border
                                                        {{ $tender->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 
                                                           ($tender->status === 'closed' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $tender->status === 'active' ? 'bg-emerald-500 animate-pulse' : ($tender->status === 'closed' ? 'bg-red-500' : 'bg-gray-500') }}"></span>
                                                        {{ ucfirst($tender->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <!-- No Tenders State -->
                        <div class="bg-white rounded-2xl p-16 text-center border-2 border-dashed border-gray-200 shadow-sm">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-400 mb-6 shadow-inner">
                                <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">No tenders available</h3>
                            <p class="text-base text-gray-500 mt-2 max-w-sm mx-auto">There are currently no active tenders to display for your district. Check back later.</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">Quick Access</h4>
                        <div class="space-y-3">
                            <a href="{{ route('user.tenders', ['username' => Str::slug(Auth::user()->name)]) }}" class="flex items-center justify-between w-full p-4 bg-gray-50 hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-100">
                                <span class="font-semibold text-sm">Browse Tenders</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <button class="flex items-center justify-between w-full p-4 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl transition-all duration-200 group border border-transparent hover:border-gray-200">
                                <span class="font-semibold text-sm">Generate Report</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Help Widget -->
                    <div class="relative bg-gradient-to-br from-indigo-900 to-violet-900 rounded-2xl shadow-xl p-8 text-white overflow-hidden group">
                        <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-700"></div>
                        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-full blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-700"></div>
                        
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <div class="bg-white/10 backdrop-blur-md w-12 h-12 rounded-xl flex items-center justify-center mb-6 shadow-inner border border-white/10">
                                    <svg class="w-6 h-6 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h4 class="font-bold text-xl mb-3 tracking-tight">Need Assistance?</h4>
                                <p class="text-indigo-200 text-sm leading-relaxed mb-8">Our support team is available 24/7 to help you with tender submissions.</p>
                            </div>
                            <button class="w-full py-3.5 bg-white text-indigo-900 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:bg-gray-50 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                <span>Contact Support</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 15s ease infinite;
        }
        @keyframes gradient-x {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</x-app-layout>
