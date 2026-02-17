<x-app-layout :padding="false">
    <div class="min-h-screen bg-slate-950 pb-12" x-data="locationManager()" x-init="init()">
        <x-toast-notification />

        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 pb-32 pt-32">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-8">
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight leading-tight">
                            Locations
                        </h1>
                        <p class="text-indigo-200 mt-2 text-lg">Manage districts and user access.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        <div class="relative group w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-indigo-200/70 group-focus-within:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                x-model="searchQuery" 
                                placeholder="Search districts..." 
                                class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:border-white/50 focus:ring-4 focus:ring-white/10 transition-all text-white placeholder:text-indigo-200/70 shadow-lg backdrop-blur-sm outline-none"
                            >
                        </div>

                        <button 
                            @click="showAddDistrict = true" 
                            class="px-6 py-3 bg-white text-indigo-900 font-bold rounded-xl hover:bg-indigo-50 focus:ring-4 focus:ring-white/20 transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add District
                        </button>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-lg hover:bg-white/15 transition-all group">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Total Districts</p>
                                <h4 class="text-4xl font-bold text-white leading-none" x-text="districts.length"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-lg hover:bg-white/15 transition-all group">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-emerald-300 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1">Total Users</p>
                                <h4 class="text-4xl font-bold text-white leading-none" x-text="districts.reduce((acc, d) => acc + (d.users_count || 0) + (d.subdistricts ? d.subdistricts.reduce((sAcc, s) => sAcc + (s.users_count || 0), 0) : 0), 0)"></h4>
                                <p class="text-[10px] text-emerald-200/70 mt-1">* Includes sub-district users</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-lg hover:bg-white/15 transition-all group">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-blue-300 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-200 uppercase tracking-widest mb-1">Tenders</p>
                                <h4 class="text-4xl font-bold text-white leading-none" x-text="districts.reduce((acc, d) => acc + (d.tenders_count || 0), 0)"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 -mt-6">
            
            <!-- Sync State -->
            <div x-show="isLoading" class="flex flex-col items-center justify-center py-24">
                <div class="w-12 h-12 border-4 border-slate-800 border-t-indigo-500 rounded-full animate-spin"></div>
                <p class="mt-8 text-xs font-bold text-slate-500 uppercase tracking-widest">Synchronizing Data...</p>
            </div>

                <!-- Content Grid -->
                <div 
                    x-show="!isLoading && districts.length > 0" 
                    class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8 mb-24"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <template x-for="district in filteredDistricts" :key="district.id">
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-lg hover:border-slate-600 transition-all duration-300 group overflow-hidden flex flex-col">
                            <!-- Card Header -->
                            <div class="p-8 border-b border-slate-800 bg-slate-800/20">
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <div class="flex-1 min-w-0">
                                        <div x-show="editingDistrict?.id !== district.id" class="flex items-center gap-3">
                                            <h3 class="text-xl font-bold text-white truncate" x-text="district.name"></h3>
                                            <button @click="editingDistrict = {...district}" class="p-2 text-slate-600 hover:text-white hover:bg-slate-800 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>

                                        <!-- Edit Input -->
                                        <div x-show="editingDistrict?.id === district.id" class="flex items-center gap-3">
                                            <input 
                                                type="text" 
                                                x-model="editingDistrict.name" 
                                                @keydown.enter="updateDistrict()"
                                                @keydown.escape="editingDistrict = null"
                                                class="w-full bg-slate-950 border border-indigo-500/50 text-white rounded-lg px-3 py-2 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all"
                                                autofocus
                                            >
                                            <button @click="updateDistrict()" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-lg shadow-indigo-900/20">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button 
                                            @click="deleteDistrict(district)" 
                                            class="p-2 text-slate-600 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                            title="Delete District"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center gap-5">
                                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-slate-500">
                                        <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                                        <span x-text="district.subdistricts_count"></span> Sub-districts
                                    </div>
                                    <button 
                                        @click="fetchUsers('district', district.id, district.name)"
                                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-indigo-400 hover:text-white hover:bg-slate-800 px-3 py-1.5 -ml-3 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span x-text="district.users_count"></span> Users
                                    </button>
                                </div>
                            </div>

                            <!-- Body & Sub-districts -->
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-widest">Sub-districts</h4>
                                    <button 
                                        @click="selectDistrict(district); showAddSubdistrict = true" 
                                        class="p-2 text-indigo-400 bg-slate-800 hover:bg-indigo-600 hover:text-white rounded-lg transition-colors"
                                        title="Add Sub-district"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>

                                <!-- Inline Add -->
                                <div x-show="showAddSubdistrict && selectedDistrict?.id === district.id" x-collapse>
                                    <div class="mb-6 flex gap-3">
                                        <input 
                                            type="text" 
                                            x-model="newSubdistrict.name" 
                                            placeholder="New sub-district name" 
                                            @keydown.enter="addSubdistrict()"
                                            class="flex-1 bg-slate-950 border border-slate-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none transition-all placeholder:text-slate-600"
                                            autofocus
                                        >
                                        <button @click="addSubdistrict()" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-lg shadow-indigo-900/20 text-sm font-semibold">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <!-- List -->
                                <ul class="space-y-1 flex-1">
                                    <template x-for="subdistrict in (district.subdistricts || [])" :key="subdistrict.id">
                                        <li class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-800/50 border border-transparent hover:border-slate-800 transition-all group/sub">
                                            <div class="flex-1 min-w-0 mr-4">
                                                <div x-show="editingSubdistrict?.id !== subdistrict.id" class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-slate-300 group-hover/sub:text-white transition-colors" x-text="subdistrict.name"></span>
                                                    <a 
                                                        :href="`/admin/${district.name}/${subdistrict.name}/tender`" 
                                                        target="_blank"
                                                        class="opacity-0 group-hover/sub:opacity-100 text-[10px] font-bold uppercase tracking-wider text-indigo-400 hover:text-indigo-300 transition-all bg-indigo-500/10 px-2 py-1 rounded"
                                                    >
                                                        Access
                                                    </a>
                                                </div>

                                                <div x-show="editingSubdistrict?.id === subdistrict.id" class="flex items-center gap-2">
                                                    <input 
                                                        type="text" 
                                                        x-model="editingSubdistrict.name" 
                                                        @keydown.enter="updateSubdistrict()"
                                                        @keydown.escape="editingSubdistrict = null"
                                                        class="flex-1 min-w-0 bg-slate-950 border border-indigo-500/50 text-white rounded px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-lg shadow-indigo-500/10 placeholder:text-slate-600"
                                                        placeholder="Sub-district name"
                                                        autofocus
                                                    >
                                                    <button 
                                                        @click="updateSubdistrict()" 
                                                        class="p-1.5 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-lg transition-all border border-transparent hover:border-emerald-400/50 shadow-lg shadow-emerald-500/20"
                                                        title="Save changes"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                    <button 
                                                        @click="editingSubdistrict = null" 
                                                        class="p-1.5 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all border border-transparent hover:border-rose-400/50 shadow-lg shadow-rose-500/20"
                                                        title="Cancel"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1 opacity-0 group-hover/sub:opacity-100 transition-all">
                                                <button @click="editingSubdistrict = {...subdistrict}; selectedDistrict = district" class="p-1.5 text-slate-500 hover:text-white transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <button @click="deleteSubdistrict(subdistrict)" class="p-1.5 text-slate-500 hover:text-rose-400 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <template x-if="!district.subdistricts || district.subdistricts.length === 0">
                                    <div class="text-center py-8">
                                        <p class="text-xs text-slate-700 font-medium uppercase tracking-wider">Empty District</p>
                                    </div>
                                </template>

                                <div class="mt-8 pt-5 border-t border-slate-800 flex justify-center">
                                    <a 
                                        :href="`/admin/${district.name}/tender`" 
                                        target="_blank"
                                        class="text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-indigo-400 transition-colors flex items-center gap-2"
                                    >
                                        View Tenders
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="!isLoading && districts.length === 0" class="text-center py-32 bg-slate-900/50 rounded-3xl border border-dashed border-slate-800">
                    <div class="w-24 h-24 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner border border-slate-800">
                        <svg class="w-10 h-10 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Districts Found</h3>
                    <p class="text-slate-500 mb-10 max-w-sm mx-auto">Create your first district to begin managing the location grid.</p>
                    <button 
                        @click="showAddDistrict = true" 
                        class="px-10 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 shadow-lg shadow-indigo-900/20 transition-all hover:-translate-y-1"
                    >
                        Create District
                    </button>
                </div>

                <!-- Add District Modal (Dark) -->
                <div 
                    x-show="showAddDistrict" 
                    @click="showAddDistrict = false"
                    class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    <div 
                        @click.stop
                        class="bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
                        x-show="showAddDistrict"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    >
                        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-800/50">
                            <h3 class="text-lg font-bold text-white">Add New District</h3>
                            <button @click="showAddDistrict = false" class="text-slate-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-8">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">District Name</label>
                            <input 
                                type="text" 
                                x-model="newDistrict.name" 
                                placeholder="e.g. Sector 7" 
                                @keydown.enter="addDistrict()"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-5 py-3 text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all placeholder:text-slate-600 mb-8"
                                autofocus
                            >
                            <div class="flex gap-4 justify-end">
                                <button @click="showAddDistrict = false" class="px-6 py-3 text-slate-400 font-semibold hover:text-white hover:bg-slate-800 rounded-xl transition-colors">Cancel</button>
                                <button @click="addDistrict()" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 shadow-lg shadow-indigo-900/20 transition-all">Create District</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Modal (Dark) -->
                <div 
                    x-show="showUsersModal" 
                    @click="showUsersModal = false"
                    class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                >
                    <div 
                        @click.stop
                        class="bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden"
                         x-show="showUsersModal"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                    >
                        <div class="p-8 border-b border-slate-800 flex justify-between items-center bg-slate-800/50">
                            <div>
                                <h3 class="text-2xl font-bold text-white tracking-tight" x-text="usersModalTitle"></h3>
                                <p class="text-sm font-medium text-slate-500 mt-1" x-text="usersList.length + ' registered users'"></p>
                            </div>
                            <button @click="showUsersModal = false" class="text-slate-500 hover:text-white transition-colors p-3 hover:bg-slate-800 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-8 bg-slate-900">
                            <template x-if="isLoadingUsers">
                                <div class="flex flex-col items-center justify-center py-20">
                                    <div class="w-10 h-10 border-4 border-slate-800 border-t-indigo-500 rounded-full animate-spin"></div>
                                </div>
                            </template>

                            <template x-if="!isLoadingUsers && usersList.length > 0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <template x-for="user in usersList" :key="user.id">
                                        <div class="flex items-center gap-5 p-5 rounded-xl border border-slate-800 hover:border-slate-600 hover:bg-slate-800/50 transition-all bg-slate-900/50 group">
                                            <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center text-indigo-400 font-bold border border-slate-700 group-hover:bg-indigo-500/20 group-hover:text-indigo-300 group-hover:border-indigo-500/30 transition-all">
                                                <span x-text="user.name.charAt(0)"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-white truncate" x-text="user.name"></h4>
                                                <p class="text-sm text-slate-500 truncate" x-text="user.email"></p>
                                            </div>
                                            <div class="flex flex-col gap-2 items-end">
                                                <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-bold rounded uppercase tracking-wider border border-slate-700" x-text="user.role"></span>
                                                <template x-if="user.subdistrict">
                                                    <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[10px] font-bold rounded uppercase tracking-wider border border-indigo-500/20" x-text="user.subdistrict.name"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="!isLoadingUsers && usersList.length === 0">
                                <div class="text-center py-20 text-slate-600">
                                    <p class="uppercase tracking-widest text-xs font-bold">No personnel data available</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div 
                    x-show="deleteModal.show" 
                    class="fixed inset-0 z-[60] flex items-center justify-center px-4"
                    role="dialog"
                    aria-modal="true"
                    style="display: none;"
                >
                    <!-- Backdrop -->
                    <div 
                        x-show="deleteModal.show"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"
                        @click="deleteModal.show = false"
                    ></div>

                    <!-- Modal Panel -->
                    <div 
                        x-show="deleteModal.show"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <div class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center text-rose-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </div>
                                <button @click="deleteModal.show = false" class="text-slate-500 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <h3 class="text-lg font-bold text-white mb-2" x-text="deleteModal.title"></h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-8" x-text="deleteModal.message"></p>

                            <div class="flex gap-3 justify-end">
                                <button 
                                    @click="deleteModal.show = false" 
                                    class="px-5 py-2.5 text-slate-400 font-semibold hover:text-white hover:bg-slate-800 rounded-xl transition-colors text-sm"
                                >
                                    Cancel
                                </button>
                                <button 
                                    @click="performDelete()" 
                                    class="px-5 py-2.5 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-500 shadow-lg shadow-rose-900/20 transition-all text-sm"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function locationManager() {
            return {
                districts: [], searchQuery: '', selectedDistrict: null,
                showAddDistrict: false, showAddSubdistrict: false, showUsersModal: false,
                usersList: [], usersModalTitle: '', isLoading: false, isLoadingUsers: false,
                toastShow: false, toastType: 'success', toastMessage: '',
                newDistrict: { name: '' }, newSubdistrict: { name: '' },
                editingDistrict: null, editingSubdistrict: null,

                init() { this.fetchDistricts(); },

                showToast(message, type = 'success') {
                    this.toastMessage = message; this.toastType = type; this.toastShow = true;
                    setTimeout(() => { this.toastShow = false; }, 4000);
                },

                get filteredDistricts() {
                    if (!this.searchQuery) return this.districts;
                    const query = this.searchQuery.toLowerCase();
                    return this.districts.filter(d => 
                        d.name.toLowerCase().includes(query) ||
                        (d.subdistricts && d.subdistricts.some(s => s.name.toLowerCase().includes(query)))
                    );
                },

                fetchDistricts(background = false) {
                    if (!background) this.isLoading = true;
                    fetch('/admin/api/districts', { headers: { 'Accept': 'application/json' } })
                    .then(res => res.json()).then(data => {
                        this.districts = data;
                        if (this.selectedDistrict) this.selectedDistrict = this.districts.find(d => d.id === this.selectedDistrict.id);
                    }).finally(() => { this.isLoading = false; });
                },

                fetchUsers(type, id, name) {
                    this.usersModalTitle = `Users: ${name}`; this.usersList = []; this.isLoadingUsers = true; this.showUsersModal = true;
                    fetch(`/admin/api/users?${type === 'district' ? 'district_id' : 'subdistrict_id'}=${id}`, { headers: { 'Accept': 'application/json' } })
                    .then(res => res.json()).then(data => {
                        this.usersList = data.data ? data.data : data; this.isLoadingUsers = false;
                    }).catch(() => { this.isLoadingUsers = false; alert('Connection Failed'); });
                },

                addDistrict() {
                    if (!this.newDistrict.name.trim()) return;
                    fetch('/admin/api/districts', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.newDistrict)
                    }).then(res => res.json()).then(() => {
                        this.newDistrict.name = ''; this.showAddDistrict = false; this.fetchDistricts(true);
                        this.showToast('District created.');
                    }).catch(() => this.showToast('Failed to create district', 'error'));
                },

                updateDistrict() {
                    if (!this.editingDistrict.name.trim()) return;
                    fetch(`/admin/api/districts/${this.editingDistrict.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ name: this.editingDistrict.name })
                    }).then(() => { this.editingDistrict = null; this.fetchDistricts(true); this.showToast('District updated.'); });
                },

                deleteDistrict(district) {
                    this.confirmDelete(
                        'Delete District', 
                        `Are you sure you want to permanently delete "${district.name}"? This action cannot be undone.`,
                        () => {
                            fetch(`/admin/api/districts/${district.id}`, {
                                method: 'DELETE',
                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(async (res) => {
                                if (!res.ok) {
                                    const data = await res.json();
                                    throw new Error(data.message || 'Deletion failed');
                                }
                                if (this.selectedDistrict?.id === district.id) this.selectedDistrict = null;
                                this.fetchDistricts(true);
                                this.showToast('District deleted.');
                            }).catch((err) => this.showToast(err.message, 'error'));
                        }
                    );
                },

                addSubdistrict() {
                    if (!this.newSubdistrict.name.trim() || !this.selectedDistrict) return;
                    fetch('/admin/api/subdistricts', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ name: this.newSubdistrict.name, district_id: this.selectedDistrict.id })
                    }).then(() => { this.newSubdistrict.name = ''; this.showAddSubdistrict = false; this.fetchDistricts(true); this.showToast('Sub-district added.'); });
                },

                updateSubdistrict() {
                    if (!this.editingSubdistrict || !this.editingSubdistrict.name.trim()) return;
                    fetch(`/admin/api/subdistricts/${this.editingSubdistrict.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ 
                            name: this.editingSubdistrict.name,
                            district_id: this.editingSubdistrict.district_id 
                        })
                    }).then(async (res) => {
                        if (!res.ok) {
                            const data = await res.json();
                            throw new Error(data.message || 'Validation failed');
                        }
                        return res.json();
                    }).then(() => { 
                        this.editingSubdistrict = null; 
                        this.fetchDistricts(true); 
                        this.showToast('Sub-district updated.'); 
                    }).catch((err) => {
                        console.error(err);
                        this.showToast(err.message || 'Failed to update sub-district.', 'error');
                    });
                },

                deleteSubdistrict(subdistrict) {
                    this.confirmDelete(
                        'Delete Sub-district',
                        `Are you sure you want to delete "${subdistrict.name}"?`,
                        () => {
                            fetch(`/admin/api/subdistricts/${subdistrict.id}`, {
                                method: 'DELETE',
                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(() => { this.fetchDistricts(true); this.showToast('Sub-district deleted.'); });
                        }
                    );
                },

                // Delete Modal Logic
                deleteModal: { show: false, title: '', message: '', action: null },

                confirmDelete(title, message, action) {
                    this.deleteModal = { show: true, title, message, action };
                },

                performDelete() {
                    if (this.deleteModal.action) {
                        this.deleteModal.action();
                    }
                    this.deleteModal.show = false;
                },

                selectDistrict(district) { this.selectedDistrict = district; this.editingSubdistrict = null; }
            };
        }
    </script>
</x-app-layout>
