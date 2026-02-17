<x-app-layout :padding="false">
    <div class="min-h-screen bg-gray-50 pb-12" x-data="dashboardManager()" x-init="init()">
        
        <x-toast-notification />
        <x-create-tender-modal :districts="$districts" />
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 pb-32 pt-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white tracking-tight">Dashboard Overview</h1>
                        <p class="mt-2 text-indigo-200 text-sm">Welcome back, Admin. Here's what's happening today.</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="createOpen = true" 
                           class="bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm px-4 py-2.5 rounded-xl font-medium transition-all shadow-lg hover:shadow-xl flex items-center gap-2 group">
                            <div class="bg-indigo-500 rounded-lg p-1 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span>Create Tender</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        <p class="text-indigo-200 font-medium text-sm">Total Users</p>
                        <p class="text-4xl font-bold text-white mt-2">{{ $stats['total_users'] }}</p>
                        <div class="mt-4 flex items-center gap-2 text-xs text-indigo-200">
                            <span class="bg-white/20 px-2 py-0.5 rounded">{{ $stats['active_tenders'] }} Active Tenders</span>
                        </div>
                    </div>

                    <!-- Active Tenders -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-green-200 font-medium text-sm">Total Tenders</p>
                        <p class="text-4xl font-bold text-white mt-2">{{ $stats['total_tenders'] }}</p>
                        <div class="mt-4 flex items-center gap-2 text-xs text-green-200">
                            <span class="bg-green-500/20 px-2 py-0.5 rounded text-green-100">{{ $stats['active_tenders'] }} Active</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded">{{ $stats['draft_tenders'] }} Drafts</span>
                        </div>
                    </div>

                    <!-- Coverage -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-purple-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-purple-200 font-medium text-sm">Geographic Coverage</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <p class="text-4xl font-bold text-white">{{ $stats['total_districts'] }}</p>
                            <span class="text-purple-200 text-sm">Districts</span>
                        </div>
                        <div class="mt-4 text-xs text-purple-200">
                            Across {{ $stats['total_states'] }} States
                        </div>
                    </div>

                    <!-- Subdistricts -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-orange-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-orange-200 font-medium text-sm">Local Units</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <p class="text-4xl font-bold text-white">{{ $stats['total_subdistricts'] }}</p>
                            <span class="text-orange-200 text-sm">Subdistricts</span>
                        </div>
                        <div class="mt-4 text-xs text-orange-200">
                            Active Zones
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Recent Tenders -->
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Recent Tenders</h3>
                                <p class="text-sm text-gray-500">Latest tender activities</p>
                            </div>
                            <a href="{{ route('admin.tenders') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline">View All</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($recentTenders as $tender)
                                <div class="p-4 hover:bg-gray-50 transition-colors group">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $tender->title }}</h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wide
                                                    {{ $tender->status === 'active' ? 'bg-green-100 text-green-800' : ($tender->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $tender->status }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 line-clamp-1 mb-2">{{ $tender->description }}</p>
                                            
                                            <div class="flex items-center gap-3">
                                                @if($tender->district)
                                                    <span class="inline-flex items-center gap-1 text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                        {{ $tender->district->name }}
                                                    </span>
                                                @endif
                                                <span class="text-[10px] text-gray-400 font-medium">{{ $tender->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="#" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors block">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">No tenders found</h3>
                                    <p class="mt-1 text-xs text-gray-500">Get started by creating a new tender.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- District Insights -->
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Top Districts by Volume</h3>
                        <div class="space-y-4">
                            @foreach($tendersByDistrict as $district)
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $district->name }}</span>
                                        <span class="text-xs font-semibold text-gray-500">{{ $district->tenders_count }} Tenders</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-500" 
                                             style="width: {{ min(100, ($district->tenders_count / max(1, $stats['total_tenders'])) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('admin.users') }}" class="group flex items-center p-3 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md hover:shadow-indigo-100 transition-all bg-white">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">Manage Users</p>
                                    <p class="text-xs text-gray-500">Add, edit, or remove users</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.locations') }}" class="group flex items-center p-3 rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-md hover:shadow-purple-100 transition-all bg-white">
                                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-purple-600">Locations</p>
                                    <p class="text-xs text-gray-500">Manage districts & zones</p>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="group flex items-center p-3 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all bg-white">
                                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Settings</p>
                                    <p class="text-xs text-gray-500">Security & profile</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-900">Newest Users</h3>
                            <a href="{{ route('admin.users') }}" class="text-xs font-medium text-gray-500 hover:text-gray-900">View All</a>
                        </div>
                        <div>
                            @forelse($recentUsers as $user)
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium uppercase tracking-wide {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $user->role === 'admin' ? 'ADM' : 'USR' }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-gray-500">No users found</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function dashboardManager() {
            return {
                createOpen: false,
                toastShow: false,
                toastMessage: '',
                toastType: 'success',
                districts: @json($districts),
                newTender: {
                    tender_no: '',
                    title: '',
                    description: '',
                    department: '',
                    state: 'Assam',
                    district_id: '',
                    subdistrict_id: '',
                    publish_date: '',
                    closing_date: '',
                    value: '',
                    status: 'draft'
                },

                init() {
                    // Initialize if needed
                },

                submitCreate() {
                    // Validation: If district has subdistricts, subdistrict must be selected
                    if (this.newTender.district_id) {
                        const selectedDistrict = this.districts.find(d => d.id == this.newTender.district_id);
                        if (selectedDistrict && selectedDistrict.subdistricts && selectedDistrict.subdistricts.length > 0) {
                            if (!this.newTender.subdistrict_id) {
                                this.showToast('Please select a subdistrict.', 'error');
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
                        this.showToast('Tender created successfully!', 'success');
                        this.createOpen = false;
                        // Reset form
                        this.newTender = {
                            tender_no: '', title: '', description: '', department: '', state: 'Assam',
                            district_id: '', subdistrict_id: '', publish_date: '', closing_date: '', value: '', status: 'draft'
                        };
                        // Refresh page after a delay to show the new tender in the "Recent Tenders" list
                        setTimeout(() => window.location.reload(), 1500);
                    })
                    .catch(error => {
                        this.showToast('Failed to create tender.', 'error');
                    });
                },

                showToast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastShow = true;
                    setTimeout(() => { this.toastShow = false; }, 3500);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
