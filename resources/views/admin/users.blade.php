<x-app-layout :padding="false">
    <div x-data="userManagement()" x-init="init()" class="min-h-screen bg-gray-50 pb-12">
        
        <!-- Toast Notification -->
        <div 
            x-show="toastShow" 
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-24 right-6 z-[100] max-w-sm w-full bg-white border-l-4 shadow-lg rounded-r-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5"
            :class="toastType === 'success' ? 'border-green-500' : 'border-red-500'"
            style="display: none;"
        >
            <div class="flex-1 w-0 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <template x-if="toastType === 'success'">
                            <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="toastType === 'error'">
                            <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900" x-text="toastType === 'success' ? 'Success' : 'Error'"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="toastMessage"></p>
                    </div>
                </div>
            </div>
            <div class="flex border-l border-gray-200">
                <button @click="toastShow = false" class="w-full border border-transparent rounded-none rounded-r-lg p-4 flex items-center justify-center text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none">Close</button>
            </div>
        </div>

        <!-- Dark Gradient Hero Header -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 pb-32 pt-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white tracking-tight">User Management</h1>
                        <p class="mt-2 text-indigo-200 text-sm">Manage system access, roles, and permissions.</p>
                    </div>
                    <button 
                        @click="openModal()" 
                        class="bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm px-4 py-2.5 rounded-xl font-medium transition-all shadow-lg hover:shadow-xl flex items-center gap-2 group"
                    >
                        <div class="bg-indigo-500 rounded-lg p-1 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span>Add New User</span>
                    </button>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        <p class="text-indigo-200 font-medium text-sm">Total Users</p>
                        <p class="text-4xl font-bold text-white mt-2">{{ $stats['total_users'] }}</p>
                    </div>

                    <!-- Admins -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-purple-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-purple-200 font-medium text-sm">Administrators</p>
                        <p class="text-4xl font-bold text-white mt-2">{{ $stats['admin_users'] }}</p>
                    </div>

                    <!-- Regular Users -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:bg-white/15 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-blue-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-blue-200 font-medium text-sm">Regular Users</p>
                        <p class="text-4xl font-bold text-white mt-2">{{ $stats['regular_users'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Overlapping) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                
                <!-- Table Controls -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative max-w-sm w-full">
                        <!-- Hidden dummy input to catch autofill -->
                        <input type="email" name="email" autocomplete="email" style="position: absolute; left: -9999px;" tabindex="-1">
                        
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="user_search_query"
                            x-model="search" 
                            autocomplete="new-search"
                            readonly
                            onfocus="this.removeAttribute('readonly')"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-xl py-2.5" 
                            placeholder="Search users..."
                        >
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Filter Role:</span>
                        <select x-model="roleFilter" class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Access Scope</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="user in filteredUsers" :key="user.id">
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                    <span x-text="user.name.charAt(0)"></span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900" x-text="user.name"></div>
                                                <div class="text-sm text-gray-500" x-text="user.email"></div>
                                                <template x-if="user.deletion_requested_at">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                        Deletion Requested
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'">
                                            <span x-text="user.role === 'admin' ? 'Administrator' : 'Regular User'"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <template x-if="user.role === 'admin'">
                                                <div class="flex items-center gap-1.5 text-gray-500 italic">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Full System Access
                                                </div>
                                            </template>
                                            <template x-if="user.role !== 'admin'">
                                                <div>
                                                    <div x-show="user.district" class="flex items-center gap-1.5">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                        <span class="font-medium" x-text="user.district?.name"></span>
                                                    </div>
                                                    <div x-show="user.subdistrict" class="flex items-center gap-1.5 mt-0.5 ml-5 text-gray-500 text-xs">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                                        <span x-text="user.subdistrict?.name"></span>
                                                    </div>
                                                    <div x-show="!user.district && !user.subdistrict" class="text-red-400 text-xs flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                        Unassigned
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <template x-if="!user.deletion_requested_at">
                                                <div class="flex gap-3">
                                                    <button 
                                                        @click="editUser(user)" 
                                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors group"
                                                        title="Edit User"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <button 
                                                        @click="deleteUserSetup(user)" 
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors group"
                                                        title="Delete User"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            
                                            <template x-if="user.deletion_requested_at">
                                                <div class="flex gap-2">
                                                    <button 
                                                        @click="rejectDeletion(user)" 
                                                        class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition-colors"
                                                    >
                                                        Reject
                                                    </button>
                                                    <button 
                                                        @click="deleteUserSetup(user)" 
                                                        class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors shadow-sm"
                                                    >
                                                        Approve
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <!-- Empty State -->
                            <tr x-show="filteredUsers.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        <p class="text-base font-medium">No users found</p>
                                        <p class="text-sm mt-1">Try adjusting your search or filters</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between" x-show="pagination.total > 0">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium" x-text="pagination.from"></span> to <span class="font-medium" x-text="pagination.to"></span> of <span class="font-medium" x-text="pagination.total"></span> results
                    </div>
                    <div class="flex gap-2">
                        <button 
                            @click="loadPage(pagination.prev_page_url)" 
                            :disabled="!pagination.prev_page_url" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            Previous
                        </button>
                        <button 
                            @click="loadPage(pagination.next_page_url)" 
                            :disabled="!pagination.next_page_url" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="display: none;"
        >
            <div 
                x-show="showModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" 
                @click="closeModal()"
            ></div>

            <div 
                x-show="showModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                class="bg-white rounded-2xl shadow-2xl transform transition-all w-full max-w-lg overflow-hidden relative z-10"
            >
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white tracking-wide" x-text="isEditing ? 'Edit User' : 'Create New User'"></h3>
                    <button @click="closeModal()" class="text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-lg p-1.5">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                            <input type="text" x-model="form.name" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3" placeholder="John Doe">
                            <p class="text-red-500 text-xs mt-1 font-medium" x-text="errors.name"></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                            <input type="email" x-model="form.email" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3" placeholder="john@example.com">
                            <p class="text-red-500 text-xs mt-1 font-medium" x-text="errors.email"></p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1" x-text="isEditing ? 'New Password (Optional)' : 'Password'"></label>
                            <input type="password" x-model="form.password" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3" placeholder="••••••••">
                            <p class="text-red-500 text-xs mt-1 font-medium" x-text="errors.password"></p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    type="button" 
                                    @click="form.role = 'user'"
                                    class="border rounded-xl py-3 px-4 flex flex-col items-center justify-center transition-all"
                                    :class="form.role === 'user' ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                >
                                    <span class="text-sm font-bold" :class="form.role === 'user' ? 'text-indigo-700' : 'text-gray-600'">Regular User</span>
                                    <span class="text-xs mt-1 text-center" :class="form.role === 'user' ? 'text-indigo-500' : 'text-gray-400'">Limited access</span>
                                </button>
                                <button 
                                    type="button" 
                                    @click="form.role = 'admin'"
                                    class="border rounded-xl py-3 px-4 flex flex-col items-center justify-center transition-all"
                                    :class="form.role === 'admin' ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-500' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                >
                                    <span class="text-sm font-bold" :class="form.role === 'admin' ? 'text-purple-700' : 'text-gray-600'">Administrator</span>
                                    <span class="text-xs mt-1 text-center" :class="form.role === 'admin' ? 'text-purple-500' : 'text-gray-400'">Full access</span>
                                </button>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div x-show="form.role === 'user'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-gray-100 pt-4 mt-4">
                            <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                Location Access
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- District -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">District</label>
                                    <select x-model="form.district_id" @change="filterSubdistricts()" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                                        <option value="">Select District</option>
                                        <template x-for="district in locations" :key="district.id">
                                            <option :value="district.id" x-text="district.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Subdistrict -->
                                <div class="relative">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Subdistrict</label>
                                    <select 
                                        x-model="form.subdistrict_id" 
                                        :disabled="!form.district_id"
                                        class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 disabled:bg-gray-100 disabled:text-gray-400"
                                    >
                                        <option value="">None (District Office)</option>
                                        <template x-for="sub in filteredSubdistricts" :key="sub.id">
                                            <option :value="sub.id" x-text="sub.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 italic bg-blue-50 p-2 rounded-lg border border-blue-100 text-blue-800" x-show="form.district_id && !form.subdistrict_id">
                                User will have access to <span class="font-bold">District-level tenders only</span> (strict isolation).
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-row-reverse gap-3">
                        <button 
                            @click="saveUser()" 
                            type="button" 
                            class="inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-indigo-200 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-base font-semibold text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition-all transform hover:-translate-y-0.5"
                        >
                            <span x-text="isEditing ? 'Save Changes' : 'Create User'"></span>
                        </button>
                        <button 
                            @click="closeModal()" 
                            type="button" 
                            class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal (Red Gradient) -->
        <div 
            x-show="deleteConfirmOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="display: none;"
        >
            <div 
                x-show="deleteConfirmOpen"
                @click="deleteConfirmOpen = false; userToDelete = null"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            ></div>

            <div 
                x-show="deleteConfirmOpen"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md m-4 overflow-hidden relative z-10"
            >
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
                            <h3 class="text-lg font-bold text-white">Delete User</h3>
                            <p class="text-red-100 text-sm">Action cannot be undone</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Are you sure you want to permanently delete this user account? All access will be revoked immediately.
                    </p>
                    
                    <div x-show="userToDelete" class="mt-4 bg-red-50 border border-red-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600 font-bold">
                                <span x-text="userToDelete?.name?.charAt(0)"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-red-900" x-text="userToDelete?.name"></p>
                                <p class="text-xs text-red-600 mt-0.5 truncate" x-text="userToDelete?.email"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-5 flex items-center gap-3">
                    <button 
                        @click="deleteConfirmOpen = false; userToDelete = null;" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-sm"
                    >Cancel</button>
                    <button 
                        @click="confirmDelete()" 
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg shadow-red-200 hover:shadow-red-300 transition-all text-sm flex items-center justify-center gap-2"
                    >
                        Delete User
                    </button>
                </div>
            </div>
        </div>

        <!-- Reject Confirmation Modal (Amber Gradient) -->
        <div 
            x-show="rejectConfirmOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="display: none;"
        >
            <div 
                x-show="rejectConfirmOpen"
                @click="rejectConfirmOpen = false; userToReject = null"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            ></div>

            <div 
                x-show="rejectConfirmOpen"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md m-4 overflow-hidden relative z-10"
            >
                <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-red-500 px-6 py-5 relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Reject Deletion</h3>
                            <p class="text-orange-100 text-sm">User account will remain active</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Are you sure you want to reject this deletion request? The user will remain active and the request flag will be cleared.
                    </p>
                    
                    <div x-show="userToReject" class="mt-4 bg-orange-50 border border-orange-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 text-orange-600 font-bold">
                                <span x-text="userToReject?.name?.charAt(0)"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900" x-text="userToReject?.name"></p>
                                <p class="text-xs text-gray-500 mt-0.5 truncate" x-text="userToReject?.email"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-5 flex items-center gap-3">
                    <button 
                        @click="rejectConfirmOpen = false; userToReject = null;" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-sm"
                    >Cancel</button>
                    <button 
                        @click="confirmReject()" 
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg shadow-orange-200 hover:shadow-orange-300 transition-all text-sm flex items-center justify-center gap-2"
                    >
                        Reject Request
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function userManagement() {
            return {
                users: [],
                locations: [], 
                pagination: {},
                showModal: false,
                isEditing: false,
                filteredSubdistricts: [],
                
                // Search & Filter
                search: '',
                roleFilter: '',

                // Toast
                toastShow: false,
                toastMessage: '',
                toastType: 'success',

                // Delete
                deleteConfirmOpen: false,
                userToDelete: null,

                // Reject
                rejectConfirmOpen: false,
                userToReject: null,

                form: {
                    id: null,
                    name: '',
                    email: '',
                    password: '',
                    role: 'user',
                    district_id: '',
                    subdistrict_id: ''
                },
                errors: {},

                get filteredUsers() {
                    let result = this.users;

                    if (this.roleFilter) {
                        result = result.filter(u => u.role === this.roleFilter);
                    }

                    if (this.search) {
                        const q = this.search.toLowerCase();
                        result = result.filter(u => 
                            u.name.toLowerCase().includes(q) || 
                            u.email.toLowerCase().includes(q)
                        );
                    }

                    return result;
                },

                async init() {
                    await this.fetchLocations();
                    this.fetchUsers();
                },

                showToast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastShow = true;
                    setTimeout(() => this.toastShow = false, 3000);
                },

                fetchUsers(url = '/admin/api/users') {
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.users = data.data;
                            this.pagination = {
                                current_page: data.current_page,
                                from: data.from,
                                to: data.to,
                                total: data.total,
                                next_page_url: data.next_page_url,
                                prev_page_url: data.prev_page_url
                            };
                        });
                },

                fetchLocations() {
                    return fetch('/admin/api/locations')
                        .then(res => res.json())
                        .then(data => {
                            this.locations = data;
                        });
                },

                loadPage(url) {
                    if (url) this.fetchUsers(url);
                },

                resetForm() {
                    this.form = {
                        id: null,
                        name: '',
                        email: '',
                        password: '',
                        role: 'user',
                        district_id: '',
                        subdistrict_id: ''
                    };
                    this.errors = {};
                    this.filteredSubdistricts = [];
                },

                openModal() {
                    this.resetForm();
                    this.isEditing = false;
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },

                editUser(user) {
                    this.resetForm();
                    this.isEditing = true;
                    this.form = {
                        id: user.id,
                        name: user.name,
                        email: user.email,
                        role: user.role,
                        district_id: user.district_id || '',
                        subdistrict_id: user.subdistrict_id || '',
                        password: '' 
                    };
                    this.filterSubdistricts(); 
                    this.showModal = true;
                },

                filterSubdistricts() {
                    if (!this.form.district_id) {
                        this.filteredSubdistricts = [];
                        this.form.subdistrict_id = '';
                        return;
                    }
                    const district = this.locations.find(d => d.id == this.form.district_id);
                    this.filteredSubdistricts = district ? district.subdistricts : [];
                    
                    if (this.form.subdistrict_id) {
                         const exists = this.filteredSubdistricts.find(s => s.id == this.form.subdistrict_id);
                         if (!exists) this.form.subdistrict_id = '';
                    }
                },

                saveUser() {
                    this.errors = {};
                    const url = this.isEditing ? `/admin/api/users/${this.form.id}` : '/admin/api/users';
                    const method = this.isEditing ? 'PUT' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.form)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            if (res.status === 422) {
                                this.errors = data.errors || {};
                                for (const key in this.errors) {
                                    this.errors[key] = this.errors[key][0];
                                }
                            } else {
                                this.showToast(data.message || 'Error saving user', 'error');
                            }
                            throw new Error('Validation failed');
                        }
                        return data;
                    })
                    .then(data => {
                        this.showToast(this.isEditing ? 'User updated successfully' : 'User created successfully');
                        this.closeModal();
                        this.fetchUsers(); 
                    })
                    .catch(err => console.log(err));
                },

                deleteUserSetup(user) {
                    this.userToDelete = user;
                    this.deleteConfirmOpen = true;
                },

                confirmDelete() {
                    if (!this.userToDelete) return;
                    
                    fetch(`/admin/api/users/${this.userToDelete.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            this.showToast('User deleted successfully');
                            this.fetchUsers();
                            this.deleteConfirmOpen = false;
                            this.userToDelete = null;
                        } else {
                            this.showToast('Failed to delete user', 'error');
                        }
                    });
                },

                rejectDeletion(user) {
                    this.userToReject = user;
                    this.rejectConfirmOpen = true;
                },

                confirmReject() {
                    if (!this.userToReject) return;

                    fetch(`/admin/api/users/${this.userToReject.id}/reject-deletion`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            this.showToast('Deletion request rejected');
                            this.fetchUsers();
                            this.rejectConfirmOpen = false;
                            this.userToReject = null;
                        } else {
                            this.showToast('Failed to reject deletion', 'error');
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
