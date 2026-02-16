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
                                <th class="px-4 py-3 w-10">#</th>
                                <th class="px-4 py-3 min-w-[120px]">Tender No</th>
                                <th class="px-4 py-3 min-w-[200px]">Title</th>
                                
                                @if(!isset($subdistrict))
                                    <th class="px-4 py-3 min-w-[150px]">Subdistrict</th>
                                @endif

                                <th class="px-4 py-3 min-w-[150px]">Department</th>
                                <th class="px-4 py-3 min-w-[120px]">Value</th>
                                <th class="px-4 py-3 min-w-[140px]">Publish Date</th>
                                <th class="px-4 py-3 min-w-[140px]">Closing Date</th>
                                <th class="px-4 py-3 min-w-[120px]">Status</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(row, index) in rows" :key="row._key">
                                <tr :class="{'bg-red-50': row.deleted, 'bg-yellow-50': row.isDirty && !row.deleted}" class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-500" x-text="index + 1"></td>
                                    
                                    <td class="p-0">
                                        <input type="text" x-model="row.tender_no" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>
                                    
                                    <td class="p-0">
                                        <input type="text" x-model="row.title" @input="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                    </td>

                                    @if(!isset($subdistrict))
                                        <td class="p-0">
                                            <select x-model="row.subdistrict_id" @change="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                                <option value="">- District Level -</option>
                                                @foreach($district->subdistricts as $sub)
                                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endif

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
                                        <select x-model="row.status" @change="markDirty(row)" class="w-full h-full px-4 py-2 border-none bg-transparent focus:ring-2 focus:ring-inset focus:ring-indigo-500" :disabled="row.deleted">
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                            <option value="awarded">Awarded</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </td>

                                    <td class="px-2 text-center">
                                        <button @click="toggleDelete(row)" class="text-gray-400 hover:text-red-600 transition">
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

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('excelGrid', () => ({
                    rows: [],
                    originalData: @json($tenders),
                    unsavedChanges: false,
                    saveUrl: "{{ isset($subdistrict) ? route('admin.locations.subdistrict.excel.update', [$district, $subdistrict]) : route('admin.locations.excel.update', $district) }}",

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
