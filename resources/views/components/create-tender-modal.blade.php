@props(['districts'])

<!-- Create Tender Popup -->
<div 
    x-show="createOpen" 
    @click="createOpen = false"
    x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-center justify-center p-4"
    style="display: none;"
>
    <div 
        @click.stop
        x-show="createOpen"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl"
    >
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-green-100 to-emerald-100 p-2 rounded-xl shadow-sm">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Create New Tender</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Fill in the missing details</p>
                    </div>
                </div>
                <button @click="createOpen = false" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Create Form -->
            <form @submit.prevent="submitCreate()">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tender No -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tender No *</label>
                        <input 
                            type="text" 
                            x-model="newTender.tender_no"
                            required
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all placeholder:text-gray-400 py-1.5 px-3 text-sm"
                            placeholder="e.g., TND-2024-001"
                        >
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status *</label>
                        <select 
                            x-model="newTender.status"
                            required
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all py-1.5 px-3 text-sm"
                        >
                            <option value="draft">Draft</option>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Title *</label>
                        <input 
                            type="text" 
                            x-model="newTender.title"
                            required
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all placeholder:text-gray-400 py-1.5 px-3 text-sm"
                            placeholder="Enter tender title"
                        >
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Description *</label>
                        <textarea 
                            x-model="newTender.description"
                            required
                            rows="2"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all placeholder:text-gray-400 resize-none py-1.5 px-3 text-sm"
                            placeholder="Enter tender description"
                        ></textarea>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Department</label>
                        <select 
                            x-model="newTender.department"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all py-1.5 px-3 text-sm"
                        >
                            <option value="">Select Department</option>
                            <option value="Public Works">Public Works</option>
                            <option value="Health">Health</option>
                            <option value="Education">Education</option>
                            <option value="Transport">Transport</option>
                            <option value="Agriculture">Agriculture</option>
                            <option value="Rural Development">Rural Development</option>
                        </select>
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">State</label>
                        <input 
                            type="text" 
                            x-model="newTender.state"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all placeholder:text-gray-400 py-1.5 px-3 text-sm"
                            placeholder="Enter state"
                        >
                    </div>

                    <!-- District -->
                    <div x-data="{ districtDropdownOpen: false, districtSearch: '' }" class="relative">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">District *</label>
                        <button 
                            type="button"
                            @click="districtDropdownOpen = !districtDropdownOpen"
                            class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent py-1.5 px-3 bg-white text-left flex items-center justify-between transition-all"
                        >
                            <span class="text-sm" x-text="newTender.district_id ? districts.find(d => d.id == newTender.district_id)?.name : 'Select District'" :class="newTender.district_id ? 'text-gray-900' : 'text-gray-400'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': districtDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div 
                            x-show="districtDropdownOpen" 
                            @click.outside="districtDropdownOpen = false"
                            class="absolute z-[110] mt-1 w-full bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden py-1"
                            style="display: none;"
                        >
                            <div class="px-3 py-1.5 border-b border-gray-50">
                                <input type="text" x-model="districtSearch" placeholder="Search..." class="w-full border-gray-200 rounded-lg text-xs focus:ring-green-500 py-1 px-2">
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="district in districts.filter(d => !districtSearch || d.name.toLowerCase().includes(districtSearch.toLowerCase()))" :key="district.id">
                                    <button 
                                        type="button"
                                        @click="newTender.district_id = district.id; newTender.subdistrict_id = ''; districtDropdownOpen = false; districtSearch = ''"
                                        class="w-full px-3 py-1.5 text-left text-xs hover:bg-green-50 transition-colors"
                                        :class="newTender.district_id == district.id ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-700'"
                                    >
                                        <span x-text="district.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Subdistrict -->
                    <div x-data="{ subDistDropdownOpen: false, subDistSearch: '' }" class="relative">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Subdistrict</label>
                        <button 
                            type="button"
                            @click="if(newTender.district_id) subDistDropdownOpen = !subDistDropdownOpen"
                            class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent py-1.5 px-3 bg-white text-left flex items-center justify-between transition-all"
                            :class="!newTender.district_id ? 'bg-gray-50 cursor-not-allowed opacity-60' : ''"
                        >
                            <span class="text-sm" x-text="newTender.subdistrict_id ? (districts.find(d => d.id == newTender.district_id)?.subdistricts || []).find(s => s.id == newTender.subdistrict_id)?.name : 'Select Subdistrict'" :class="newTender.subdistrict_id ? 'text-gray-900' : 'text-gray-400'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': subDistDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div 
                            x-show="subDistDropdownOpen" 
                            @click.outside="subDistDropdownOpen = false"
                            class="absolute z-[110] mt-1 w-full bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden py-1"
                            style="display: none;"
                        >
                            <div class="px-3 py-1.5 border-b border-gray-50">
                                <input type="text" x-model="subDistSearch" placeholder="Search..." class="w-full border-gray-200 rounded-lg text-xs focus:ring-green-500 py-1 px-2">
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="subdistrict in (districts.find(d => d.id == newTender.district_id)?.subdistricts || []).filter(s => !subDistSearch || s.name.toLowerCase().includes(subDistSearch.toLowerCase()))" :key="subdistrict.id">
                                    <button 
                                        type="button"
                                        @click="newTender.subdistrict_id = subdistrict.id; subDistDropdownOpen = false; subDistSearch = ''"
                                        class="w-full px-3 py-1.5 text-left text-xs hover:bg-green-50 transition-colors"
                                        :class="newTender.subdistrict_id == subdistrict.id ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-700'"
                                    >
                                        <span x-text="subdistrict.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Publish Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Publish Date</label>
                        <input 
                            type="date" 
                            x-model="newTender.publish_date"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all py-1.5 px-3 text-sm"
                        >
                    </div>

                    <!-- Closing Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Closing Date</label>
                        <input 
                            type="date" 
                            x-model="newTender.closing_date"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all py-1.5 px-3 text-sm"
                        >
                    </div>

                    <!-- Value -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tender Value (₹)</label>
                        <input 
                            type="number" 
                            x-model="newTender.value"
                            min="0"
                            step="0.01"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all placeholder:text-gray-400 py-1.5 px-3 text-sm"
                            placeholder="Enter tender value"
                        >
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-5 border-t border-gray-100 flex gap-3">
                    <button 
                        type="button"
                        @click="createOpen = false" 
                        class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-gray-700 font-bold text-sm hover:bg-gray-50 transition-all active:scale-[0.98]"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold text-sm hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-green-200 active:scale-[0.98]"
                    >
                        Create Tender
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
