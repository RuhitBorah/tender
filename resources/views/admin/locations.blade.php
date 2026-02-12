<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
        <div class="max-w-[1300px] mx-auto px-1 sm:px-2">
            
            <div x-data="locationManager()" x-init="init()">
                <!-- Hero Header with Stats -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Location Management
                            </h1>
                            <p class="text-gray-600 mt-2">Manage districts and subdistricts for your organization</p>
                        </div>
                        <button 
                            @click="showAddDistrict = true" 
                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:shadow-xl transition-all flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add District
                        </button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 border border-indigo-200">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-indigo-700">Total Districts</p>
                                    <p class="text-3xl font-bold text-indigo-900" x-text="districts.length"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-700">Total Subdistricts</p>
                                    <p class="text-3xl font-bold text-purple-900" x-text="districts.reduce((acc, d) => acc + (d.subdistricts_count || 0), 0)"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-5 border border-teal-200">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-teal-700">Total Tenders</p>
                                    <p class="text-3xl font-bold text-teal-900" x-text="districts.reduce((acc, d) => acc + (d.tenders_count || 0), 0)"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add District Form (Inline) -->
                <div 
                    x-show="showAddDistrict" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white rounded-2xl shadow-lg border border-indigo-200 p-6 mb-6"
                    style="display: none;"
                >
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New District</h3>
                    <div class="flex gap-3">
                        <input 
                            type="text" 
                            x-model="newDistrict.name" 
                            placeholder="Enter district name" 
                            @keydown.enter="addDistrict()"
                            class="flex-1 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                        <button @click="addDistrict()" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition shadow-lg">
                            Save
                        </button>
                        <button @click="showAddDistrict = false; newDistrict.name = ''" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition">
                            Cancel
                        </button>
                    </div>
                </div>

                <!-- Districts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="district in districts" :key="district.id">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                            <!-- District Header -->
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                                <div x-show="editingDistrict?.id !== district.id">
                                    <h3 class="text-lg font-bold mb-2" x-text="district.name"></h3>
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-lg text-xs font-medium">
                                            <span x-text="district.subdistricts_count"></span> Sub
                                        </span>
                                        <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-lg text-xs font-medium">
                                            <span x-text="district.users_count"></span> Users
                                        </span>
                                        <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-lg text-xs font-medium">
                                            <span x-text="district.tenders_count"></span> Tend
                                        </span>
                                    </div>
                                </div>

                                <!-- Edit Mode -->
                                <div x-show="editingDistrict?.id === district.id">
                                    <input 
                                        type="text" 
                                        x-model="editingDistrict.name" 
                                        @keydown.enter="updateDistrict()"
                                        @keydown.escape="editingDistrict = null"
                                        class="w-full bg-white/20 backdrop-blur border-white/40 text-white placeholder-white/60 rounded-lg"
                                    >
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-1 mt-3">
                                    <template x-if="editingDistrict?.id === district.id">
                                        <div class="flex gap-1">
                                            <button @click="updateDistrict()" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition text-xs">
                                                Save
                                            </button>
                                            <button @click="editingDistrict = null" class="px-2 py-1 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition text-xs">
                                                Cancel
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="editingDistrict?.id !== district.id">
                                        <div class="flex gap-1 flex-wrap">
                                            <button 
                                                @click="fetchUsers('district', district.id, district.name)" 
                                                class="px-2 py-1 bg-white text-indigo-600 rounded-lg font-bold hover:bg-indigo-50 transition text-xs flex items-center gap-1"
                                                x-show="district.users_count > 0"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                Users
                                            </button>
                                            <a 
                                                :href="`/admin/tenders?district_id=${district.id}`" 
                                                target="_blank"
                                                x-show="district.tenders_count > 0"
                                                class="px-2 py-1 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition text-xs flex items-center gap-1"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                                View
                                            </a>
                                            <button @click="editingDistrict = {...district}" class="p-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button @click="deleteDistrict(district)" class="p-2 bg-white/20 hover:bg-red-600 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Subdistricts Section -->
                            <div class="p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Subdistricts</h4>
                                    <button 
                                        @click="selectDistrict(district); showAddSubdistrict = true" 
                                        class="px-2 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs font-medium transition"
                                    >
                                        + Add
                                    </button>
                                </div>

                                <!-- Add Subdistrict Form -->
                                <div 
                                    x-show="showAddSubdistrict && selectedDistrict?.id === district.id" 
                                    class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-4"
                                    style="display: none;"
                                >
                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            x-model="newSubdistrict.name" 
                                            placeholder="Subdistrict name" 
                                            @keydown.enter="addSubdistrict()"
                                            class="flex-1 border-purple-300 rounded-lg text-sm"
                                        >
                                        <button @click="addSubdistrict()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
                                            Save
                                        </button>
                                        <button @click="showAddSubdistrict = false; newSubdistrict.name = ''" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                                            Cancel
                                        </button>
                                    </div>
                                </div>

                                <!-- Subdistricts List -->
                                <div class="space-y-2">
                                    <template x-for="subdistrict in (district.subdistricts || [])" :key="subdistrict.id">
                                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-xl p-4">
                                            <div x-show="editingSubdistrict?.id !== subdistrict.id">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h5 class="font-semibold text-gray-900" x-text="subdistrict.name"></h5>
                                                        <div class="flex gap-3 mt-2 text-sm text-gray-600">
                                                            <span><span x-text="subdistrict.users_count"></span> Users</span>
                                                            <span><span x-text="subdistrict.tenders_count"></span> Tenders</span>
                                                        </div>
                                                        <div class="flex gap-2 mt-2">
                                                            <button 
                                                                @click="fetchUsers('subdistrict', subdistrict.id, subdistrict.name)"
                                                                x-show="subdistrict.users_count > 0"
                                                                class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-semibold text-sm"
                                                            >
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                                </svg>
                                                                Users
                                                            </button>
                                                            <a 
                                                            :href="`/admin/tenders?subdistrict_id=${subdistrict.id}`" 
                                                            target="_blank"
                                                            x-show="subdistrict.tenders_count > 0"
                                                            class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-800 font-semibold text-sm mt-2"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                            View Tenders
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-1 ml-2">
                                                        <button @click="editingSubdistrict = {...subdistrict}; selectedDistrict = district" class="p-1.5 hover:bg-teal-200 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <button @click="deleteSubdistrict(subdistrict)" class="p-1.5 hover:bg-red-100 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Mode -->
                                            <div x-show="editingSubdistrict?.id === subdistrict.id">
                                                <input 
                                                    type="text" 
                                                    x-model="editingSubdistrict.name" 
                                                    @keydown.enter="updateSubdistrict()"
                                                    @keydown.escape="editingSubdistrict = null"
                                                    class="w-full border-teal-300 rounded-lg"
                                                >
                                                <div class="flex gap-2 mt-2">
                                                    <button @click="updateSubdistrict()" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                                                        Save
                                                    </button>
                                                    <button @click="editingSubdistrict = null" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="!district.subdistricts || district.subdistricts.length === 0" class="text-center py-6 text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <p class="text-sm">No subdistricts yet</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="districts.length === 0" class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Districts Yet</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first district</p>
                    <button @click="showAddDistrict = true" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition">
                        Add First District
                    </button>
                </div>
                <!-- Users List Modal -->
                <div 
                    x-show="showUsersModal" 
                    @click="showUsersModal = false"
                    x-transition:enter="transition-opacity ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
                    style="display: none;"
                >
                    <div 
                        @click.stop
                        x-show="showUsersModal"
                        x-transition:enter="transform transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transform transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden"
                    >
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between shrink-0">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900" x-text="usersModalTitle"></h3>
                                <p class="text-sm text-gray-500" x-text="usersList.length + ' users found'"></p>
                            </div>
                            <button @click="showUsersModal = false" class="p-2 hover:bg-gray-200 rounded-lg transition text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 overflow-y-auto flex-1">
                            <template x-if="isLoadingUsers">
                                <div class="flex items-center justify-center py-12">
                                    <svg class="w-10 h-10 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </template>
                            
                            <template x-if="!isLoadingUsers && usersList.length === 0">
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">No users found</h3>
                                    <p class="text-gray-500 mt-1">There are no users associated with this location yet.</p>
                                </div>
                            </template>

                            <template x-if="!isLoadingUsers && usersList.length > 0">
                                <div class="space-y-3">
                                    <template x-for="user in usersList" :key="user.id">
                                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                                <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="user.name"></p>
                                                <p class="text-xs text-gray-500 truncate" x-text="user.email"></p>
                                                <template x-if="user.subdistrict">
                                                    <p class="text-xs text-indigo-600 mt-0.5 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        <span x-text="user.subdistrict.name"></span>
                                                    </p>
                                                </template>
                                            </div>
                                            <div class="shrink-0 flex items-center gap-2">
                                                 <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                                    :class="user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'"
                                                    x-text="user.role === 'admin' ? 'Admin' : 'User'">
                                                 </span>
                                                 <a :href="'mailto:' + user.email" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-white rounded-lg transition" title="Send Email">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                 </a>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Footer -->
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
                            <button @click="showUsersModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function locationManager() {
            return {
                districts: [],
                selectedDistrict: null,
                showAddDistrict: false,
                showAddSubdistrict: false,
                showUsersModal: false,
                usersList: [],
                usersModalTitle: '',
                isLoadingUsers: false,
                newDistrict: { name: '' },
                newSubdistrict: { name: '' },
                editingDistrict: null,
                editingSubdistrict: null,

                init() {
                    this.fetchDistricts();
                },

                fetchDistricts() {
                    fetch('/admin/api/districts', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.districts = data;
                        // Update selected district if it exists
                        if (this.selectedDistrict) {
                            this.selectedDistrict = this.districts.find(d => d.id === this.selectedDistrict.id);
                        }
                    });
                },

                fetchUsers(type, id, name) {
                    this.usersModalTitle = `Users in ${name}`;
                    this.usersList = [];
                    this.isLoadingUsers = true;
                    this.showUsersModal = true;

                    let url = '/admin/api/users?';
                    if (type === 'district') {
                        url += `district_id=${id}`;
                    } else {
                        url += `subdistrict_id=${id}`;
                    }

                    fetch(url, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Handle pagination response or raw array
                        this.usersList = data.data ? data.data : data; 
                        this.isLoadingUsers = false;
                    })
                    .catch(() => {
                        this.isLoadingUsers = false;
                        alert('Error fetching users');
                    });
                },

                selectDistrict(district) {
                    this.selectedDistrict = district;
                    this.editingSubdistrict = null;
                },

                addDistrict() {
                    if (!this.newDistrict.name.trim()) return;

                    fetch('/admin/api/districts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.newDistrict)
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.newDistrict.name = '';
                        this.showAddDistrict = false;
                        this.fetchDistricts();
                    })
                    .catch(() => alert('Error creating district'));
                },

                updateDistrict() {
                    if (!this.editingDistrict.name.trim()) return;

                    fetch(`/admin/api/districts/${this.editingDistrict.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ name: this.editingDistrict.name })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.editingDistrict = null;
                        this.fetchDistricts();
                    })
                    .catch(() => alert('Error updating district'));
                },

                deleteDistrict(district) {
                    if (!confirm(`Delete district "${district.name}"?`)) return;

                    fetch(`/admin/api/districts/${district.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => {
                        if (!res.ok) return res.json().then(err => { throw new Error(err.message); });
                        return res.json();
                    })
                    .then(() => {
                        if (this.selectedDistrict?.id === district.id) {
                            this.selectedDistrict = null;
                        }
                        this.fetchDistricts();
                    })
                    .catch(err => alert(err.message || 'Error deleting district'));
                },

                addSubdistrict() {
                    if (!this.newSubdistrict.name.trim() || !this.selectedDistrict) return;

                    fetch('/admin/api/subdistricts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            name: this.newSubdistrict.name,
                            district_id: this.selectedDistrict.id
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.newSubdistrict.name = '';
                        this.showAddSubdistrict = false;
                        this.fetchDistricts();
                    })
                    .catch(() => alert('Error creating subdistrict'));
                },

                updateSubdistrict() {
                    if (!this.editingSubdistrict.name.trim()) return;

                    fetch(`/admin/api/subdistricts/${this.editingSubdistrict.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            name: this.editingSubdistrict.name,
                            district_id: this.selectedDistrict.id
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed');
                        return res.json();
                    })
                    .then(() => {
                        this.editingSubdistrict = null;
                        this.fetchDistricts();
                    })
                    .catch(() => alert('Error updating subdistrict'));
                },

                deleteSubdistrict(subdistrict) {
                    if (!confirm(`Delete subdistrict "${subdistrict.name}"?`)) return;

                    fetch(`/admin/api/subdistricts/${subdistrict.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => {
                        if (!res.ok) return res.json().then(err => { throw new Error(err.message); });
                        return res.json();
                    })
                    .then(() => {
                        this.fetchDistricts();
                    })
                    .catch(err => alert(err.message || 'Error deleting subdistrict'));
                }
            };
        }
    </script>
</x-app-layout>
