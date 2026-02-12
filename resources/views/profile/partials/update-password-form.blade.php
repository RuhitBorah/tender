<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 gap-6 max-w-xl">
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="mb-1" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg sm:text-sm" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="mb-1" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg sm:text-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="mb-1" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg sm:text-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 border-0 shadow-sm rounded-lg px-4 py-2">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-500"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
