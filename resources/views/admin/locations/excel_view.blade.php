<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8" x-data="excelGrid()" x-init="init()">
        <x-toast-notification />
        
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                        <span>/</span>
                        <a href="{{ route('admin.locations') }}" class="hover:text-indigo-600">Locations</a>
                        <span>/</span>
                        @if(isset($subdistrict))
                            <span class="text-gray-900 font-semibold">{{ $district->name }} > {{ $subdistrict->name }}</span>
                        @else
                            <span class="text-gray-900 font-semibold">{{ $district->name }}</span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        @if(isset($subdistrict))
                            {{ $subdistrict->name }} Tenders
                        @else
                            {{ $district->name }} Tenders
                        @endif
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ isset($subdistrict) ? route('admin.locations.subdistrict.excel.export', [$district, $subdistrict]) : route('admin.locations.excel.export', $district) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <button @click="document.getElementById('importFile').click()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Excel
                    </button>
                    <form 
                        action="{{ isset($subdistrict) ? route('admin.locations.subdistrict.excel.import', [$district, $subdistrict]) : route('admin.locations.excel.import', $district) }}" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        class="hidden"
                    >
                        @csrf
                        <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Toolbar -->
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button @click="addRow()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Row
                        </button>
                        <span class="text-sm text-gray-500 ml-2" x-show="unsavedChanges">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full inline-block mr-1"></span> Unsaved changes
                        </span>
                    </div>
                    <button 
                        @click="saveChanges()" 
                        :disabled="!unsavedChanges"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-lg font-medium flex items-center gap-2 transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Changes
                    </button>
                </div>

                <!-- Spreadsheet Grid -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 font-medium border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 w-16 whitespace-nowrap">ID</th>
                                <th class="px-4 py-3 whitespace-nowrap">Tender No</th>
                                <th class="px-4 py-3 whitespace-nowrap">Title</th>
                                <th class="px-4 py-3 whitespace-nowrap">Description</th>
                                <th class="px-4 py-3 whitespace-nowrap">District Name</th>
                                
                                <th class="px-4 py-3 whitespace-nowrap">Subdistrict</th>

                                <th class="px-4 py-3 whitespace-nowrap">Department</th>
                                <th class="px-4 py-3 whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 whitespace-nowrap">Publish Date</th>
                                <th class="px-4 py-3 whitespace-nowrap">Closing Date</th>
                                <th class="px-4 py-3 whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(row, index) in rows" :key="row._key">
                                <tr :class="{'bg-red-50': row.deleted, 'bg-yellow-50': row.isDirty && !row.deleted}" class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-500" x-text="row.id ? row.id : 'Auto'"></td>
                                    
                                    <td class="p-0">
                                        <input type="text" x-model="row.tender_no" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>
                                    
                                    <td class="p-0">
                                        <input type="text" x-model="row.title" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    <td class="p-0">
                                        <input type="text" x-model="row.description" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    <td class="px-4 py-2 text-gray-500">
                                        {{ $district->name }}
                                    </td>

                                    <td class="p-0">
                                        @if(isset($subdistrict))
                                            <div class="px-4 py-2 text-gray-500">{{ $subdistrict->name }}</div>
                                        @else
                                            <select x-model="row.subdistrict_id" @change="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                                <option value="">- Select -</option>
                                                @foreach($district->subdistricts as $sub)
                                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>

                                    <td class="p-0">
                                        <input type="text" x-model="row.department" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    <td class="p-0">
                                        <input type="number" step="0.01" x-model="row.value" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    <td class="p-0">
                                        <input type="date" x-model="row.publish_date" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>
                                    
                                    <td class="p-0">
                                        <input type="date" x-model="row.closing_date" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    <td class="p-0">
                                        <select x-model="row.status" @change="markDirty(row)" class="w-full h-full pl-4 pr-8 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                            <option value="awarded">Awarded</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </td>

                                    <td class="px-2 text-center flex items-center justify-center space-x-2">
                                        <button @click="openPreview(row)" class="text-gray-400 hover:text-blue-600 transition" title="Preview">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <button @click="toggleDelete(row)" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                            <svg x-show="!row.deleted" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <svg x-show="row.deleted" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l5 5m0 0l-5 5m5-5l5-5m-5 5l5 5"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <div x-show="previewModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="previewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closePreview()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="previewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Tender Details
                                </h3>
                                <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tender No</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.tender_no || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.title || '-'"></div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.description || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">District</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $district->name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subdistrict</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="getPreviewSubdistrict(previewData.subdistrict_id)"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Department</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.department || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.value || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Publish Date</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.publish_date || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Closing Date</label>
                                        <div class="mt-1 text-sm text-gray-900" x-text="previewData.closing_date || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <div class="mt-1 text-sm text-gray-900 capitalize" x-text="previewData.status || '-'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="closePreview()">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('excelGrid', () => ({
                    rows: [],
                    originalData: @json($tenders),
                    unsavedChanges: false,
                    saveUrl: "{{ isset($subdistrict) ? route('admin.locations.subdistrict.excel.update', [$district, $subdistrict]) : route('admin.locations.excel.update', $district) }}",
                    subdistricts: @json($district->subdistricts),
                    previewModalOpen: false,
                    previewData: {},

                    init() {
                        // Initialize rows from tenders data
                        this.rows = this.originalData.map(tender => ({
                            ...tender,
                            _key: Math.random().toString(36).substr(2, 9),
                            isDirty: false,
                            deleted: false,
                            publish_date: tender.publish_date ? tender.publish_date.substring(0, 10) : '',
                            closing_date: tender.closing_date ? tender.closing_date.substring(0, 10) : ''
                        }));
                    },

                    addRow() {
                        this.rows.unshift({
                            _key: Math.random().toString(36).substr(2, 9),
                            id: null,
                            tender_no: '',
                            title: '',
                            description: '',
                            subdistrict_id: '{{ isset($subdistrict) ? $subdistrict->id : "" }}', // Auto-set subdistrict if in context
                            department: '',
                            value: '',
                            publish_date: '',
                            closing_date: '',
                            status: 'draft',
                            isDirty: true,
                            deleted: false
                        });
                        this.unsavedChanges = true;
                    },

                    markDirty(row) {
                        row.isDirty = true;
                        this.unsavedChanges = true;
                    },

                    toggleDelete(row) {
                        row.deleted = !row.deleted;
                        this.markDirty(row);
                    },

                    openPreview(row) {
                        this.previewData = { ...row };
                        this.previewModalOpen = true;
                    },

                    closePreview() {
                        this.previewModalOpen = false;
                        this.previewData = {};
                    },

                    getPreviewSubdistrict(id) {
                        if (!id) return '-';
                        const sub = this.subdistricts.find(s => s.id == id);
                        return sub ? sub.name : '-';
                    },

                    saveChanges() {
                        const changes = this.rows.filter(r => r.isDirty);
                        if (changes.length === 0) return;

                        fetch(this.saveUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ tenders: changes })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Failed to save');
                            return res.json();
                        })
                        .then(data => {
                            this.unsavedChanges = false;
                            // Reset dirty flags and reload
                             window.dispatchEvent(new CustomEvent('notify', { 
                                detail: { type: 'success', message: 'Saved successfully' }
                            }));
                            setTimeout(() => location.reload(), 1000);
                        })
                        .catch(err => {
                            alert('Error saving changes: ' + err.message);
                        });
                    }
                }));
            });
        </script>
    </div>
</x-app-layout>
