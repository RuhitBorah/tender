<div 
    x-show="toastShow" 
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="translate-y-[-100%] opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-[-100%] opacity-0"
    class="fixed top-6 right-6 z-[200] max-w-sm w-full"
    style="display: none;"
>
    <div 
        class="rounded-2xl shadow-2xl border px-5 py-4 flex items-start gap-4 backdrop-blur-md"
        :class="toastType === 'success' ? 'bg-white/90 border-green-100' : 'bg-white/90 border-red-100'"
    >
        <!-- Icon -->
        <div 
            class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center shadow-lg"
            :class="toastType === 'success' ? 'bg-gradient-to-br from-green-500 to-emerald-600 shadow-green-200' : 'bg-gradient-to-br from-red-500 to-rose-600 shadow-red-200'"
        >
            <svg x-show="toastType === 'success'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="toastType === 'error'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <!-- Content -->
        <div class="flex-1 pt-0.5">
            <p 
                class="text-sm font-bold"
                :class="toastType === 'success' ? 'text-green-900' : 'text-red-900'"
                x-text="toastType === 'success' ? 'Success' : 'Error'"
            ></p>
            <p 
                class="text-xs mt-1 leading-relaxed"
                :class="toastType === 'success' ? 'text-green-700' : 'text-red-700'"
                x-text="toastMessage"
            ></p>
        </div>
        <!-- Close -->
        <button @click="toastShow = false" class="flex-shrink-0 p-1 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
