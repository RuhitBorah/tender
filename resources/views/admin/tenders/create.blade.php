<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.tenders') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Tenders
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Create New Tender</h1>
                        <p class="text-sm text-gray-500 mt-1">Add a new tender to the system</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.tenders.store') }}" class="p-6 space-y-6" x-data="{ districtId: '', subdistricts: [], allDistricts: {{ $districts->toJson() }} }">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Tender Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Department -->
                        <div>
                            <x-input-label for="department" :value="__('Department')" />
                            <x-text-input id="department" class="block mt-1 w-full" type="text" name="department" :value="old('department')" />
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <!-- State -->
                        <div>
                            <x-input-label for="state" :value="__('State')" />
                            <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state', 'Assam')" />
                            <x-input-error :messages="$errors->get('state')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- District -->
                        <div>
                            <x-input-label for="district_id" :value="__('District')" />
                            <select id="district_id" name="district_id" x-model="districtId" @change="subdistricts = allDistricts.find(d => d.id == districtId)?.subdistricts || []" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                        </div>

                        <!-- Subdistrict -->
                        <div>
                            <x-input-label for="subdistrict_id" :value="__('Subdistrict (Optional)')" />
                            <select id="subdistrict_id" name="subdistrict_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Subdistrict</option>
                                <template x-for="sub in subdistricts" :key="sub.id">
                                    <option :value="sub.id" x-text="sub.name"></option>
                                </template>
                            </select>
                            <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Value -->
                        <div>
                            <x-input-label for="value" :value="__('Value (₹)')" />
                            <x-text-input id="value" class="block mt-1 w-full" type="number" step="0.01" name="value" :value="old('value')" />
                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                        </div>

                        <!-- Publish Date -->
                        <div>
                            <x-input-label for="publish_date" :value="__('Publish Date')" />
                            <x-text-input id="publish_date" class="block mt-1 w-full" type="date" name="publish_date" :value="old('publish_date')" />
                            <x-input-error :messages="$errors->get('publish_date')" class="mt-2" />
                        </div>

                        <!-- Closing Date -->
                        <div>
                            <x-input-label for="closing_date" :value="__('Closing Date')" />
                            <x-text-input id="closing_date" class="block mt-1 w-full" type="date" name="closing_date" :value="old('closing_date')" />
                            <x-input-error :messages="$errors->get('closing_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="draft">Draft</option>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <x-primary-button class="ml-4">
                            {{ __('Create Tender') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
