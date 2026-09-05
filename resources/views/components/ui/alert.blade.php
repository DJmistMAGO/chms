{{-- resources/views/components/alert.blade.php --}}

@props([
    'variant' => 'info',
    'title' => '',
    'message' => '',
    'showLink' => false,
    'linkHref' => '#',
    'linkText' => 'Learn more',
    'dismissible' => true,
    'autoDismiss' => false,
    'duration' => 5000,
])

@php
    $variantClasses = [
        'success' => [
            'container' => 'border border-green-200 bg-white dark:border-green-500/40 dark:bg-gray-800 border-t-4 border-t-green-500',
            'icon' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-500/20',
        ],
        'error' => [
            'container' => 'border border-red-200 bg-white dark:border-red-500/40 dark:bg-gray-800 border-t-4 border-t-red-500',
            'icon' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-500/20',
        ],
        'warning' => [
            'container' => 'border border-yellow-200 bg-white dark:border-yellow-500/40 dark:bg-gray-800 border-t-4 border-t-yellow-500',
            'icon' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-500/20',
        ],
        'info' => [
            'container' => 'border border-blue-200 bg-white dark:border-blue-500/40 dark:bg-gray-800 border-t-4 border-t-blue-500',
            'icon' => 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-500/20',
        ],
    ];

    $icons = [
        'success' => '<svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"></path></svg>',
        'error' => '<svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.6501 12.0001C3.6501 7.38852 7.38852 3.6501 12.0001 3.6501C16.6117 3.6501 20.3501 7.38852 20.3501 12.0001C20.3501 16.6117 16.6117 20.3501 12.0001 20.3501C7.38852 20.3501 3.6501 16.6117 3.6501 12.0001ZM10.9992 7.52517C10.9992 8.07746 11.4469 8.52517 11.9992 8.52517H12.0002C12.5525 8.52517 13.0002 8.07746 13.0002 7.52517C13.0002 6.97289 12.5525 6.52517 12.0002 6.52517H11.9992C11.4469 6.52517 10.9992 6.97289 10.9992 7.52517ZM12.0002 17.3715C11.586 17.3715 11.2502 17.0357 11.2502 16.6215V10.945C11.2502 10.5308 11.586 10.195 12.0002 10.195C12.4144 10.195 12.7502 10.5308 12.7502 10.945V16.6215C12.7502 17.0357 12.4144 17.3715 12.0002 17.3715Z"></path></svg>',
        'warning' => '<svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.3499 12.0004C20.3499 16.612 16.6115 20.3504 11.9999 20.3504C7.38832 20.3504 3.6499 16.612 3.6499 12.0004C3.6499 7.38881 7.38833 3.65039 11.9999 3.65039C16.6115 3.65039 20.3499 7.38881 20.3499 12.0004ZM13.0008 16.4753C13.0008 15.923 12.5531 15.4753 12.0008 15.4753L11.9998 15.4753C11.4475 15.4753 10.9998 15.923 10.9998 16.4753C10.9998 17.0276 11.4475 17.4753 11.9998 17.4753L12.0008 17.4753C12.5531 17.4753 13.0008 17.0276 13.0008 16.4753ZM11.9998 6.62898C12.414 6.62898 12.7498 6.96476 12.7498 7.37898L12.7498 13.0555C12.7498 13.4697 12.414 13.8055 11.9998 13.8055C11.5856 13.8055 11.2498 13.4697 11.2498 13.0555L11.2498 7.37898C11.2498 6.96476 11.5856 6.62898 11.9998 6.62898Z"></path></svg>',
        'info' => '<svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.6501 11.9996C3.6501 7.38803 7.38852 3.64961 12.0001 3.64961C16.6117 3.64961 20.3501 7.38803 20.3501 11.9996C20.3501 16.6112 16.6117 20.3496 12.0001 20.3496C7.38852 20.3496 3.6501 16.6112 3.6501 11.9996ZM10.9992 7.52468C10.9992 8.07697 11.4469 8.52468 11.9992 8.52468H12.0002C12.5525 8.52468 13.0002 8.07697 13.0002 7.52468C13.0002 6.9724 12.5525 6.52468 12.0002 6.52468H11.9992C11.4469 6.52468 10.9992 6.9724 10.9992 7.52468ZM12.0002 17.371C11.586 17.371 11.2502 17.0352 11.2502 16.621V10.9445C11.2502 10.5303 11.586 10.1945 12.0002 10.1945C12.4144 10.1945 12.7502 10.5303 12.7502 10.9445V16.621C12.7502 17.0352 12.4144 17.371 12.0002 17.371Z"></path></svg>',
    ];

    $containerClass = $variantClasses[$variant]['container'] ?? $variantClasses['info']['container'];
    $iconClass = $variantClasses[$variant]['icon'] ?? $variantClasses['info']['icon'];
    $icon = $icons[$variant] ?? $icons['info'];
@endphp

<div
    x-data="{
        show: true,
        init() {
            if ({{ $autoDismiss ? 'true' : 'false' }}) {
                setTimeout(() => this.show = false, {{ $duration }});
            }
        }
    }"
    x-show="show"
    class="fixed inset-0 z-[99999] flex items-center justify-center p-4 overflow-y-auto"
    role="dialog"
    aria-modal="true"
>
    {{-- Darkened Overlay Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
    ></div>

    {{-- Centered SweetAlert-style Card --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="relative z-10 w-full max-w-md transform rounded-xl p-6 text-center shadow-xl {{ $containerClass }}"
    >
        {{-- Centered Icon Badge --}}
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full {{ $iconClass }}">
            {!! $icon !!}
        </div>

        {{-- Content --}}
        @if($title)
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $title }}
            </h3>
        @endif

        @if($message)
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                {{ $message }}
            </p>
        @endif

        @if($showLink)
            <div class="mb-4">
                <a
                    href="{{ $linkHref }}"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline"
                >
                    {{ $linkText }}
                </a>
            </div>
        @endif

        {{-- Custom Slot --}}
        {{ $slot }}

        {{-- Action / Close Button --}}
        @if($dismissible)
            <div class="mt-6">
                <button
                    @click="show = false"
                    type="button"
                    class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                >
                    OK
                </button>
            </div>
        @endif
    </div>
</div>
