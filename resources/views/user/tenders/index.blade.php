<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('District Tenders') }}
            </h2>
            @if($user->district)
                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium border border-blue-100">
                    {{ $user->district->name }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full mx-auto px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tenders->count() > 0)
                        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-white" x-data="tenderEditor()">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50/95 backdrop-blur-sm z-10 border-b border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">Tender No</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Description</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Department</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Subdistrict</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">State</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Publish Date</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Closing Date</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Value (₹)</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Added On</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50/95 backdrop-blur-sm z-10 border-b border-gray-200 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.05)]">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($tenders as $tender)
                                        <tr class="hover:bg-indigo-50/30 transition-all duration-200 group relative" :class="{ 'bg-indigo-50/60': editingId === {{ $tender->id }} }">
                                            
                                            <!-- Tender No -->
                                            <td class="px-6 py-4 text-sm font-semibold text-indigo-600 whitespace-nowrap sticky left-0 bg-white group-hover:bg-indigo-50/30 transition-colors z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]" :class="{ 'bg-indigo-50/60': editingId === {{ $tender->id }} }">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="text" x-model="formData.tender_no" class="w-full text-sm px-2 py-1 border-indigo-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span x-text="tenders[{{ $tender->id }}].tender_no" class="font-mono tracking-tight"></span>
                                                </template>
                                            </td>

                                            <!-- Description (with Title tooltip) -->
                                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs" title="{{ $tender->title }}">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <div class="space-y-2">
                                                        <input type="text" x-model="formData.title" placeholder="Title" class="w-full text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                        <textarea x-model="formData.description" placeholder="Description" rows="2" class="w-full text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <div>
                                                        <div class="font-medium text-gray-900 line-clamp-1" x-text="tenders[{{ $tender->id }}].title"></div>
                                                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="tenders[{{ $tender->id }}].description || 'No description'"></div>
                                                    </div>
                                                </template>
                                            </td>

                                            <!-- Department -->
                                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                                 <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="text" x-model="formData.department" class="w-full text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span x-text="tenders[{{ $tender->id }}].department || '-'"></span>
                                                </template>
                                            </td>

                                            <!-- Subdistrict -->
                                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $tender->subdistrict ? $tender->subdistrict->name : 'Unassigned' }}
                                                </span>
                                            </td>

                                            <!-- State -->
                                            <td class="px-6 py-4 text-center">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="text" x-model="formData.state" class="w-20 text-center text-sm px-1 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span class="text-sm text-gray-500" x-text="tenders[{{ $tender->id }}].state"></span>
                                                </template>
                                            </td>

                                            <!-- Publish Date -->
                                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="date" x-model="formData.publish_date" class="w-full text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span x-text="formatDate(tenders[{{ $tender->id }}].publish_date)"></span>
                                                </template>
                                            </td>

                                            <!-- Closing Date -->
                                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                                 <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="date" x-model="formData.closing_date" class="w-full text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span :class="new Date(tenders[{{ $tender->id }}].closing_date) < new Date() ? 'text-red-600 font-medium' : 'text-gray-600'" x-text="formatDate(tenders[{{ $tender->id }}].closing_date)"></span>
                                                </template>
                                            </td>

                                            <!-- Value -->
                                            <td class="px-6 py-4 text-right text-sm whitespace-nowrap font-mono tracking-tight text-gray-900">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <input type="number" step="0.01" x-model="formData.value" class="w-32 text-right text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span x-text="Number(tenders[{{ $tender->id }}].value).toLocaleString('en-IN', {style: 'currency', currency: 'INR'})"></span>
                                                </template>
                                            </td>

                                            <!-- Status -->
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <select x-model="formData.status" class="w-28 text-sm px-2 py-1 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="draft">Draft</option>
                                                        <option value="active">Active</option>
                                                        <option value="closed">Closed</option>
                                                        <option value="awarded">Awarded</option>
                                                        <option value="archived">Archived</option>
                                                    </select>
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border"
                                                        :class="{
                                                            'bg-emerald-50 text-emerald-700 border-emerald-100': tenders[{{ $tender->id }}].status === 'active',
                                                            'bg-red-50 text-red-700 border-red-100': tenders[{{ $tender->id }}].status === 'closed',
                                                            'bg-amber-50 text-amber-700 border-amber-100': tenders[{{ $tender->id }}].status === 'draft',
                                                            'bg-blue-50 text-blue-700 border-blue-100': tenders[{{ $tender->id }}].status === 'awarded',
                                                            'bg-gray-50 text-gray-700 border-gray-100': !['active', 'closed', 'draft', 'awarded'].includes(tenders[{{ $tender->id }}].status)
                                                        }">
                                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5"
                                                            :class="{
                                                                'bg-emerald-500': tenders[{{ $tender->id }}].status === 'active',
                                                                'bg-red-500': tenders[{{ $tender->id }}].status === 'closed',
                                                                'bg-amber-500': tenders[{{ $tender->id }}].status === 'draft',
                                                                'bg-blue-500': tenders[{{ $tender->id }}].status === 'awarded',
                                                                'bg-gray-500': !['active', 'closed', 'draft', 'awarded'].includes(tenders[{{ $tender->id }}].status)
                                                            }"></span>
                                                        <span x-text="tenders[{{ $tender->id }}].status.charAt(0).toUpperCase() + tenders[{{ $tender->id }}].status.slice(1)"></span>
                                                    </span>
                                                </template>
                                            </td>

                                            <!-- Added On -->
                                            <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                                                <span x-text="formatDate(tenders[{{ $tender->id }}].created_at)"></span>
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-6 py-4 text-center whitespace-nowrap sticky right-0 bg-white group-hover:bg-indigo-50/30 transition-colors z-10 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.05)]" :class="{ 'bg-indigo-50/60': editingId === {{ $tender->id }} }">
                                                <template x-if="editingId === {{ $tender->id }}">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <button @click="saveTender({{ $tender->id }})" class="p-2 text-white bg-green-500 rounded-lg hover:bg-green-600 shadow-md shadow-green-200 transition-all hover:-translate-y-0.5" title="Save">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                        <button @click="cancelEdit()" class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600 shadow-md shadow-red-200 transition-all hover:-translate-y-0.5" title="Cancel">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== {{ $tender->id }}">
                                                    <button @click="editTender({{ $tender->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Tender">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                </template>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">
                            Showing {{ $tenders->count() }} records.
                        </div>

                        <script>
                            function tenderEditor() {
                                return {
                                    editingId: null,
                                    tenders: {!! $tenders->keyBy('id')->toJson() !!},
                                    formData: {},

                                    init() {
                                        // Process dates for JS compatibility
                                        Object.values(this.tenders).forEach(tender => {
                                            if (tender.publish_date) tender.publish_date = tender.publish_date.split('T')[0];
                                            if (tender.closing_date) tender.closing_date = tender.closing_date.split('T')[0];
                                        });
                                    },

                                    formatDate(dateStr) {
                                        if (!dateStr) return '-';
                                        const date = new Date(dateStr);
                                        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/ /g, '-');
                                    },

                                    editTender(id) {
                                        this.editingId = id;
                                        // Clone data to avoid direct mutation before save
                                        this.formData = { ...this.tenders[id] };
                                    },

                                    cancelEdit() {
                                        this.editingId = null;
                                        this.formData = {};
                                    },

                                    saveTender(id) {
                                        const username = "{{ \Illuminate\Support\Str::slug(Auth::user()->name) }}";
                                        
                                        axios.put(`/${username}/tenders/${id}`, this.formData)
                                            .then(response => {
                                                // Update local data
                                                this.tenders[id] = response.data.tender;
                                                // Handle date formats from response if needed
                                                let t = this.tenders[id];
                                                if (t.publish_date) t.publish_date = t.publish_date.split('T')[0];
                                                if (t.closing_date) t.closing_date = t.closing_date.split('T')[0];
                                                
                                                this.editingId = null;
                                                // Optional: Show success toast
                                            })
                                            .catch(error => {
                                                console.error('Update failed:', error);
                                                alert('Failed to update tender. Please check inputs.');
                                            });
                                    }
                                }
                            }
                        </script>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tenders found</h3>
                            <p class="mt-1 text-sm text-gray-500">Your district currently has no active tenders.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
