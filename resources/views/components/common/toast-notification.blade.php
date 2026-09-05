@if(session('success') || $errors->any())
    <style>
        @keyframes toast-progress {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .toast-motion,
            .toast-progress {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>

    <div class="pointer-events-none fixed right-4 top-20 z-[99998] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 sm:right-6 sm:top-20 xl:top-24" aria-live="polite" aria-atomic="true">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="toast-motion transition ease-out duration-500" x-transition:enter-start="translate-x-8 scale-95 opacity-0" x-transition:enter-end="translate-x-0 scale-100 opacity-100" x-transition:leave="toast-motion transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-x-8 opacity-0" x-init="setTimeout(() => show = false, 8000)" class="pointer-events-auto relative flex items-start gap-3 overflow-hidden rounded-xl border border-green-200 border-l-4 border-l-green-500 bg-white px-4 py-3.5 text-sm text-green-900 shadow-xl shadow-gray-900/15 ring-1 ring-black/5 dark:border-green-800 dark:border-l-green-400 dark:bg-gray-900 dark:text-green-100 dark:ring-white/10" role="status">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">Success</p>
                    <p class="mt-0.5 leading-5 text-green-800/80 dark:text-green-200/80">{{ session('success') }}</p>
                </div>
                <button type="button" @click="show = false" class="shrink-0 rounded-md p-1 text-green-500 transition hover:bg-green-50 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 dark:hover:bg-green-900/40 dark:hover:text-green-300" aria-label="Dismiss notification">
                    <span class="text-lg leading-none" aria-hidden="true">&times;</span>
                </button>
                <span class="toast-progress pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-left bg-green-500/70" style="animation: toast-progress 8s linear forwards"></span>
            </div>
        @endif

        @foreach($errors->all() as $error)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="toast-motion transition ease-out duration-500" x-transition:enter-start="translate-x-8 scale-95 opacity-0" x-transition:enter-end="translate-x-0 scale-100 opacity-100" x-transition:leave="toast-motion transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-x-8 opacity-0" x-init="setTimeout(() => show = false, 10000)" class="pointer-events-auto relative flex items-start gap-3 overflow-hidden rounded-xl border border-red-200 border-l-4 border-l-red-500 bg-white px-4 py-3.5 text-sm text-red-900 shadow-xl shadow-gray-900/15 ring-1 ring-black/5 dark:border-red-800 dark:border-l-red-400 dark:bg-gray-900 dark:text-red-100 dark:ring-white/10" role="alert">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-300" aria-hidden="true">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-4a1 1 0 10-2 0v4zm1-8a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">Please check your details</p>
                    <p class="mt-0.5 leading-5 text-red-800/80 dark:text-red-200/80">{{ $error }}</p>
                </div>
                <button type="button" @click="show = false" class="shrink-0 rounded-md p-1 text-red-500 transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 dark:hover:bg-red-900/40 dark:hover:text-red-300" aria-label="Dismiss notification">
                    <span class="text-lg leading-none" aria-hidden="true">&times;</span>
                </button>
                <span class="toast-progress pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-left bg-red-500/70" style="animation: toast-progress 10s linear forwards"></span>
            </div>
        @endforeach
    </div>
@endif
