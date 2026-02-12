<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 py-8" x-data="adminTendersManager()" x-init="init()">
        <x-toast-notification />
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with Stats -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tenders Management</h1>
                    <p class="text-gray-600 mt-1">Manage all tenders across all districts</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button 
                        @click="filterOpen = true" 
                        class="bg-indigo-600 text-white px-4 py-2.5 rounded-lg shadow-md hover:bg-indigo-700 transition-all flex items-center gap-2"
                        title="Open Filters"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="font-medium">Filters</span>
                    </button>

                    <button 
                        @click="statsOpen = true" 
                        class="bg-gradient-to-br from-blue-600 to-purple-600 text-white px-4 py-2.5 rounded-lg shadow-md hover:from-blue-700 hover:to-purple-700 transition-all flex items-center gap-2"
                        title="View Statistics"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">Statistics</span>
                    </button>

                    <button 
                        @click="createOpen = true" 
                        class="bg-gradient-to-br from-green-600 to-emerald-600 text-white px-4 py-2.5 rounded-lg shadow-md hover:from-green-700 hover:to-emerald-700 transition-all flex items-center gap-2"
                        title="Create New Tender"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="font-medium">Create Tender</span>
                    </button>
                </div>
            </div>

            <!-- Filter Popup Backdrop -->
            <div 
                x-show="filterOpen" 
                @click="filterOpen = false"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center"
                style="display: none;"
            >
                <!-- Filter Popup Modal -->
                <div 
                    @click.stop
                    x-show="filterOpen"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4"
                >
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-indigo-100 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Filter Tenders</h2>
                            </div>
                            <button @click="filterOpen = false" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Filter Options in Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Search -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search
                                </label>
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    placeholder="Search tenders..."
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                            </div>

                            <!-- District -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    District
                                </label>
                                <select 
                                    x-model="filters.district_id" 
                                    @change="filters.subdistrict_id = ''; fetchTenders()"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                    <option value="">All Districts</option>
                                    <template x-for="district in districts" :key="district.id">
                                        <option :value="district.id" x-text="district.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Subdistrict -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    Subdistrict
                                </label>
                                <select 
                                    x-model="filters.subdistrict_id" 
                                    @change="fetchTenders()"
                                    :disabled="!filters.district_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                                >
                                    <option value="">All Subdistricts</option>
                                    <template x-for="subdistrict in currentSubdistricts" :key="subdistrict.id">
                                        <option :value="subdistrict.id" x-text="subdistrict.name"></option>
                                    </template>
                                </select>
                                <p x-show="!filters.district_id" class="text-xs text-gray-500 mt-1">Select a district first</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                    Status
                                </label>
                                <select 
                                    x-model="filters.status" 
                                    @change="fetchTenders()"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                    <option value="">All Statuses</option>
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <!-- Department -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Department
                                </label>
                                <select 
                                    x-model="filters.department" 
                                    @change="fetchTenders()"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                    <option value="">All Departments</option>
                                    <option value="Public Works">Public Works</option>
                                    <option value="Health">Health</option>
                                    <option value="Education">Education</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Agriculture">Agriculture</option>
                                    <option value="Rural Development">Rural Development</option>
                                </select>
                            </div>

                            <!-- Publish Date Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Publish Date Range
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input 
                                        type="date" 
                                        x-model="filters.publish_from" 
                                        @change="fetchTenders()"
                                        placeholder="From"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                    <input 
                                        type="date" 
                                        x-model="filters.publish_to" 
                                        @change="fetchTenders()"
                                        placeholder="To"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                </div>
                            </div>

                            <!-- Closing Date Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Closing Date Range
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input 
                                        type="date" 
                                        x-model="filters.closing_from" 
                                        @change="fetchTenders()"
                                        placeholder="From"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                    <input 
                                        type="date" 
                                        x-model="filters.closing_to" 
                                        @change="fetchTenders()"
                                        placeholder="To"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                </div>
                            </div>

                            <!-- Value Range -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Value Range (₹)
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input 
                                        type="number" 
                                        x-model="filters.value_min" 
                                        @change="fetchTenders()"
                                        placeholder="Minimum Value"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                    <input 
                                        type="number" 
                                        x-model="filters.value_max" 
                                        @change="fetchTenders()"
                                        placeholder="Maximum Value"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">
                            <button 
                                @click="clearFilters()" 
                                class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                            >
                                Clear All
                            </button>
                            <button 
                                @click="filterOpen = false" 
                                class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-lg hover:shadow-xl"
                            >
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Popup -->
            <div 
                x-show="statsOpen" 
                @click="statsOpen = false"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center"
                style="display: none;"
            >
                <div 
                    @click.stop
                    x-show="statsOpen"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden m-4"
                >
                    <!-- Dark Gradient Hero Header -->
                    <div class="bg-gradient-to-br from-slate-800 via-indigo-900 to-purple-900 px-8 py-7 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full"></div>
                        <div class="absolute top-4 right-20 w-16 h-16 bg-white/5 rounded-full"></div>
                        
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p class="text-indigo-200 text-sm font-medium tracking-wide uppercase">Overview</p>
                                <h2 class="text-2xl font-bold text-white mt-1">Tender Statistics</h2>
                            </div>
                            <button @click="statsOpen = false" class="text-white/60 hover:text-white p-2 hover:bg-white/10 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="relative mt-5 flex items-end gap-3">
                            <span class="text-6xl font-extrabold text-white tracking-tight" x-text="pagination.total || 0"></span>
                            <span class="text-indigo-200 text-lg font-medium mb-2">Total Tenders</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-8 py-6 overflow-y-auto" style="max-height: calc(90vh - 200px);">

                        <!-- Status Breakdown -->
                        <div class="mb-8">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Status Breakdown</h3>
                            
                            <!-- Color Bar -->
                            <div class="h-3 rounded-full bg-gray-100 overflow-hidden flex mb-5">
                                <div class="bg-emerald-500 transition-all duration-700 rounded-l-full" :style="'width: ' + (pagination.total > 0 ? ((stats.active / pagination.total) * 100) : 0) + '%'"></div>
                                <div class="bg-amber-400 transition-all duration-700" :style="'width: ' + (pagination.total > 0 ? ((stats.draft / pagination.total) * 100) : 0) + '%'"></div>
                                <div class="bg-rose-500 transition-all duration-700 rounded-r-full" :style="'width: ' + (pagination.total > 0 ? ((stats.closed / pagination.total) * 100) : 0) + '%'"></div>
                            </div>

                            <!-- Status Cards -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100 hover:shadow-md hover:border-emerald-200 transition-all">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Active</span>
                                    </div>
                                    <p class="text-3xl font-extrabold text-emerald-900" x-text="stats.active || 0"></p>
                                    <p class="text-xs text-emerald-500 mt-1 font-medium" x-text="pagination.total > 0 ? Math.round((stats.active / pagination.total) * 100) + '% of total' : '0%'"></p>
                                </div>
                                <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 hover:shadow-md hover:border-amber-200 transition-all">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-2.5 h-2.5 bg-amber-400 rounded-full"></div>
                                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Draft</span>
                                    </div>
                                    <p class="text-3xl font-extrabold text-amber-900" x-text="stats.draft || 0"></p>
                                    <p class="text-xs text-amber-500 mt-1 font-medium" x-text="pagination.total > 0 ? Math.round((stats.draft / pagination.total) * 100) + '% of total' : '0%'"></p>
                                </div>
                                <div class="bg-rose-50 rounded-xl p-4 border border-rose-100 hover:shadow-md hover:border-rose-200 transition-all">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-2.5 h-2.5 bg-rose-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-rose-700 uppercase tracking-wide">Closed</span>
                                    </div>
                                    <p class="text-3xl font-extrabold text-rose-900" x-text="stats.closed || 0"></p>
                                    <p class="text-xs text-rose-500 mt-1 font-medium" x-text="pagination.total > 0 ? Math.round((stats.closed / pagination.total) * 100) + '% of total' : '0%'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 mb-6"></div>

                        <!-- Location Coverage -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Location Coverage</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-4 rounded-xl bg-gradient-to-b from-slate-50 to-white border border-slate-100 hover:shadow-md transition-all">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-3xl font-extrabold text-gray-900">{{ \App\Models\Tender::whereNotNull('state')->where('state', '!=', '')->distinct('state')->count('state') }}</p>
                                    <p class="text-xs text-gray-500 font-medium mt-1">States</p>
                                </div>
                                <div class="text-center p-4 rounded-xl bg-gradient-to-b from-slate-50 to-white border border-slate-100 hover:shadow-md transition-all">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-3xl font-extrabold text-gray-900" x-text="districts.length || 0"></p>
                                    <p class="text-xs text-gray-500 font-medium mt-1">Districts</p>
                                </div>
                                <div class="text-center p-4 rounded-xl bg-gradient-to-b from-slate-50 to-white border border-slate-100 hover:shadow-md transition-all">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-3xl font-extrabold text-gray-900" x-text="districts.reduce((sum, d) => sum + (d.subdistricts ? d.subdistricts.length : 0), 0)"></p>
                                    <p class="text-xs text-gray-500 font-medium mt-1">Subdistricts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <x-create-tender-modal :districts="$districts" />

            <!-- Tenders Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tender No</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Title</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Department</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">State</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Publish Date</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Closing Date</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Value (₹)</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <template x-for="tender in tenders" :key="tender.id">
                                <tr class="hover:bg-gray-50 transition-colors" :class="{ 'bg-yellow-50': editingId === tender.id }">
                                    <!-- ID -->
                                    <td class="px-3 py-3 text-sm text-gray-900" x-text="tender.id"></td>
                                    
                                    <!-- Tender No -->
                                    <td class="px-3 py-3 text-sm">
                                        <span x-show="editingId !== tender.id" class="font-mono text-indigo-600" x-text="tender.tender_no"></span>
                                        <input x-show="editingId === tender.id" type="text" x-model="tender.tender_no" class="w-24 px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Title -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm font-medium text-gray-900" x-text="tender.title"></span>
                                        <input x-show="editingId === tender.id" type="text" x-model="tender.title" class="w-full px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Description -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm text-gray-600" x-text="tender.description"></span>
                                        <textarea x-show="editingId === tender.id" x-model="tender.description" rows="2" class="w-full px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500"></textarea>
                                    </td>
                                    
                                    <!-- Department -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm text-gray-600" x-text="tender.department || '-'"></span>
                                        <input x-show="editingId === tender.id" type="text" x-model="tender.department" class="w-full px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- State -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm text-gray-600" x-text="tender.state || '-'"></span>
                                        <input x-show="editingId === tender.id" type="text" x-model="tender.state" class="w-24 px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Publish Date -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm text-gray-600" x-text="formatDate(tender.publish_date)"></span>
                                        <input x-show="editingId === tender.id" type="date" x-model="tender.publish_date" class="w-full px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Closing Date -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm text-gray-600" x-text="formatDate(tender.closing_date)"></span>
                                        <input x-show="editingId === tender.id" type="date" x-model="tender.closing_date" class="w-full px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Value -->
                                    <td class="px-3 py-3">
                                        <span x-show="editingId !== tender.id" class="text-sm font-medium text-gray-900" x-text="formatCurrency(tender.value)"></span>
                                        <input x-show="editingId === tender.id" type="number" step="0.01" x-model="tender.value" class="w-32 px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span 
                                            x-show="editingId !== tender.id"
                                            class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm"
                                            :class="{
                                                'bg-green-100 text-green-800 ring-1 ring-green-600/20': tender.status === 'active',
                                                'bg-gray-100 text-gray-800 ring-1 ring-gray-600/20': tender.status === 'draft',
                                                'bg-red-100 text-red-800 ring-1 ring-red-600/20': tender.status === 'closed'
                                            }"
                                            x-text="tender.status.toUpperCase()"
                                        ></span>
                                        <select x-show="editingId === tender.id" x-model="tender.status" class="px-2 py-1 text-sm border-yellow-300 rounded focus:ring-2 focus:ring-yellow-500">
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div x-show="editingId !== tender.id" class="flex items-center gap-1">
                                            <button @click="startEdit(tender)" class="text-indigo-600 hover:text-indigo-700 p-1" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button @click="deleteTender(tender)" class="text-red-600 hover:text-red-700 p-1" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="editingId === tender.id" class="flex items-center gap-2">
                                            <button @click="saveEdit(tender)" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                                Save
                                            </button>
                                            <button @click="cancelEdit()" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="tenders.length === 0">
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p>No tenders found</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4" x-show="pagination.total > 0">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold text-gray-900" x-text="pagination.from"></span> to 
                            <span class="font-semibold text-gray-900" x-text="pagination.to"></span> of 
                            <span class="font-semibold text-gray-900" x-text="pagination.total"></span> tenders
                        </div>
                        <div class="flex gap-2">
                            <button 
                                @click="loadPage(pagination.prev_page_url)" 
                                :disabled="!pagination.prev_page_url" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </button>
                            <button 
                                @click="loadPage(pagination.next_page_url)" 
                                :disabled="!pagination.next_page_url" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition"
                            >
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div 
                x-show="deleteConfirmOpen" 
                @click="deleteConfirmOpen = false; deleteTarget = null;"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center"
                style="display: none;"
            >
                <div 
                    @click.stop
                    x-show="deleteConfirmOpen"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-md m-4 overflow-hidden"
                >
                    <!-- Red Gradient Header -->
                    <div class="bg-gradient-to-br from-red-500 via-rose-500 to-pink-600 px-6 py-5 relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white/10 rounded-full"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Delete Tender</h3>
                                <p class="text-red-100 text-sm">This action cannot be undone</p>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5">
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Are you sure you want to permanently delete this tender? All associated data will be removed.
                        </p>
                        
                        <!-- Tender Info Card -->
                        <div x-show="deleteTarget" class="mt-4 bg-red-50 border border-red-100 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-red-900" x-text="deleteTarget?.tender_no"></p>
                                    <p class="text-xs text-red-600 mt-0.5 truncate" x-text="deleteTarget?.title"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 pb-5 flex items-center gap-3">
                        <button 
                            @click="deleteConfirmOpen = false; deleteTarget = null;" 
                            class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-sm"
                        >Cancel</button>
                        <button 
                            @click="confirmDelete()" 
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg shadow-red-200 hover:shadow-red-300 transition-all text-sm flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function adminTendersManager() {
            return {
                tenders: [],
                districts: [],
                pagination: {},
                stats: { draft: 0, active: 0, closed: 0 },
                search: '',
                filters: {
                    district_id: '',
                    subdistrict_id: '',
                    status: '',
                    department: '',
                    publish_from: '',
                    publish_to: '',
                    closing_from: '',
                    closing_to: '',
                    value_min: '',
                    value_max: ''
                },
                editingId: null,
                originalData: null,
                filterOpen: false,
                statsOpen: false,
                createOpen: false,
                toastShow: false,
                toastMessage: '',
                toastType: 'success',
                deleteConfirmOpen: false,
                deleteTarget: null,
                newTender: {
                    tender_no: '',
                    title: '',
                    description: '',
                    department: '',
                    state: '',
                    district_id: '',
                    subdistrict_id: '',
                    publish_date: '',
                    closing_date: '',
                    value: '',
                    status: 'draft'
                },

                init() {
                    // Read URL parameters and pre-fill filters
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('district_id')) {
                        this.filters.district_id = urlParams.get('district_id');
                    }
                    if (urlParams.has('subdistrict_id')) {
                        this.filters.subdistrict_id = urlParams.get('subdistrict_id');
                    }
                    if (urlParams.has('status')) {
                        this.filters.status = urlParams.get('status');
                    }
                    
                    this.fetchDistricts();
                    this.fetchTenders();
                    this.$watch('search', () => this.fetchTenders());
                },

                clearFilters() {
                    this.search = '';
                    this.filters.district_id = '';
                    this.filters.subdistrict_id = '';
                    this.filters.status = '';
                    this.filters.department = '';
                    this.filters.publish_from = '';
                    this.filters.publish_to = '';
                    this.filters.closing_from = '';
                    this.filters.closing_to = '';
                    this.filters.value_min = '';
                    this.filters.value_max = '';
                    this.fetchTenders();
                },

                get currentSubdistricts() {
                    if (!this.filters.district_id) return [];
                    const district = this.districts.find(d => d.id == this.filters.district_id);
                    return district?.subdistricts || [];
                },

                fetchDistricts() {
                    fetch('/admin/api/districts', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.districts = data;
                    });
                },

                fetchTenders(url = '/admin/api/tenders') {
                    let params = new URLSearchParams();
                    if (this.search) params.append('search', this.search);
                    if (this.filters.district_id) params.append('district_id', this.filters.district_id);
                    if (this.filters.subdistrict_id) params.append('subdistrict_id', this.filters.subdistrict_id);
                    if (this.filters.status) params.append('status', this.filters.status);
                    if (this.filters.department) params.append('department', this.filters.department);
                    if (this.filters.publish_from) params.append('publish_from', this.filters.publish_from);
                    if (this.filters.publish_to) params.append('publish_to', this.filters.publish_to);
                    if (this.filters.closing_from) params.append('closing_from', this.filters.closing_from);
                    if (this.filters.closing_to) params.append('closing_to', this.filters.closing_to);
                    if (this.filters.value_min) params.append('value_min', this.filters.value_min);
                    if (this.filters.value_max) params.append('value_max', this.filters.value_max);

                    const finalUrl = url + (params.toString() ? '?' + params.toString() : '');
                    
                    fetch(finalUrl, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.tenders = data.data;
                        this.pagination = {
                            current_page: data.current_page,
                            from: data.from,
                            to: data.to,
                            total: data.total,
                            next_page_url: data.next_page_url,
                            prev_page_url: data.prev_page_url
                        };
                        this.calculateStats();
                    });
                },

                calculateStats() {
                    this.stats.draft = this.tenders.filter(t => t.status === 'draft').length;
                    this.stats.active = this.tenders.filter(t => t.status === 'active').length;
                    this.stats.closed = this.tenders.filter(t => t.status === 'closed').length;
                },

                submitCreate() {
                    // Validation: If district has subdistricts, subdistrict must be selected
                    if (this.newTender.district_id) {
                        const selectedDistrict = this.districts.find(d => d.id == this.newTender.district_id);
                        if (selectedDistrict && selectedDistrict.subdistricts && selectedDistrict.subdistricts.length > 0) {
                            if (!this.newTender.subdistrict_id) {
                                this.showToast('Please select a subdistrict. This district has subdistricts, so you must choose one.', 'error');
                                return;
                            }
                        }
                    }

                    fetch('/admin/api/tenders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.newTender)
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Reset form
                        this.newTender = {
                            tender_no: '',
                            title: '',
                            description: '',
                            department: '',
                            state: '',
                            district_id: '',
                            subdistrict_id: '',
                            publish_date: '',
                            closing_date: '',
                            value: '',
                            status: 'draft'
                        };
                        
                        // Close modal and refresh list
                        this.createOpen = false;
                        this.fetchTenders();
                        
                        this.showToast('Tender created successfully!', 'success');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showToast('Failed to create tender. Please try again.', 'error');
                    });
                },

                showToast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastShow = true;
                    setTimeout(() => { this.toastShow = false; }, 3500);
                },

                loadPage(url) {
                    if (url) this.fetchTenders(url);
                },

                formatDate(date) {
                    if (!date) return '-';
                    return new Date(date).toLocaleDateString('en-IN', { 
                        day: '2-digit', 
                        month: 'short', 
                        year: 'numeric' 
                    });
                },

                formatCurrency(value) {
                    if (!value) return '-';
                    return '₹ ' + parseFloat(value).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                startEdit(tender) {
                    this.editingId = tender.id;
                    this.originalData = JSON.parse(JSON.stringify(tender));
                },

                cancelEdit() {
                    if (this.editingId && this.originalData) {
                        const tender = this.tenders.find(t => t.id === this.editingId);
                        if (tender) {
                            Object.assign(tender, this.originalData);
                        }
                    }
                    this.editingId = null;
                    this.originalData = null;
                },

                saveEdit(tender) {
                    fetch(`/admin/api/tenders/${tender.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            tender_no: tender.tender_no,
                            title: tender.title,
                            description: tender.description,
                            department: tender.department,
                            state: tender.state,
                            publish_date: tender.publish_date,
                            closing_date: tender.closing_date,
                            value: tender.value,
                            status: tender.status
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.editingId = null;
                        this.originalData = null;
                        this.fetchTenders();
                    })
                    .catch(() => {
                        this.showToast('Error updating tender', 'error');
                        this.cancelEdit();
                    });
                },

                deleteTender(tender) {
                    this.deleteTarget = tender;
                    this.deleteConfirmOpen = true;
                },

                confirmDelete() {
                    if (!this.deleteTarget) return;
                    const id = this.deleteTarget.id;
                    this.deleteConfirmOpen = false;
                    this.deleteTarget = null;

                    fetch(`/admin/api/tenders/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.showToast('Tender deleted successfully', 'success');
                        this.fetchTenders();
                    })
                    .catch(() => this.showToast('Error deleting tender', 'error'));
                }
            };
        }
    </script>
</x-app-layout>
