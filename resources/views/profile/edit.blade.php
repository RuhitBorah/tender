<x-app-layout>
    <!-- Header removed -->

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <aside class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                        <nav class="space-y-1">
                            <a href="#profile-info" class="flex items-center px-4 py-3 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg group transition-colors">
                                <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile Information
                            </a>

                            <a href="#update-password" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg group transition-colors">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Password
                            </a>

                            @if(auth()->user()->role !== 'admin')
                            <a href="#delete-account" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-700 rounded-lg group transition-colors">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Account
                            </a>
                            @endif
                        </nav>
                    </div>
                </aside>

                <!-- tailored Content Area -->
                <main class="flex-1 space-y-8">
                    <!-- Profile Information Section -->
                    <section id="profile-info" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden scroll-mt-20">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                                <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </section>

                    <!-- Update Password Section -->
                    <section id="update-password" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden scroll-mt-20">
                         <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
                            <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </section>

                    <!-- Delete Account Section -->
                    @if(auth()->user()->role !== 'admin')
                    <section id="delete-account" class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden scroll-mt-20">
                         <div class="px-6 py-4 border-b border-red-50 bg-red-50/30">
                            <h2 class="text-lg font-semibold text-red-700">Delete Account</h2>
                            <p class="text-sm text-red-500">Permanently remove your account and all of its data.</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </section>
                    @endif
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
