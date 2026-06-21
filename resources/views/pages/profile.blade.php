@extends('layouts.authenticated.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Profile" />


    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-200">
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-4a1 1 0 10-2 0v4zm1-8a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
            <ul class="list-disc space-y-1 pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── LEFT: Avatar card ── --}}
            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h4 class="mb-4 text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Photo</h4>

                    {{-- Avatar preview --}}
                    <div class="flex flex-col items-center gap-5">
                        <div class="relative">
                            <div class="h-28 w-28 overflow-hidden rounded-full ring-4 ring-amber-100 dark:ring-amber-900/40">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile photo" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-100 to-orange-200 text-3xl font-bold text-amber-900 dark:from-amber-900 dark:to-orange-900 dark:text-amber-100">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                            </div>
                            {{-- Camera badge --}}
                            <label for="avatar-upload" class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-amber-500 shadow-md shadow-amber-300/40 transition hover:bg-amber-600 dark:shadow-amber-900/40">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </label>
                        </div>

                        <div class="w-full text-center">
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                        </div>

                        {{-- Hidden file input --}}
                        <div class="w-full">
                            <input id="avatar-upload" type="file" name="avatar" accept="image/*" class="hidden" />
                            <label for="avatar-upload" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700 dark:border-gray-600 dark:bg-gray-800/50 dark:text-gray-400 dark:hover:border-amber-600 dark:hover:bg-amber-900/10 dark:hover:text-amber-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload new photo
                            </label>
                            <p class="mt-1.5 text-center text-xs text-gray-400 dark:text-gray-600">JPG, PNG or GIF · max 2 MB</p>
                            @error('avatar')
                                <p class="mt-1 text-center text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Form sections ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Personal Information --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h4 class="mb-5 text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Personal Information</h4>

                    <div class="grid gap-5 sm:grid-cols-2">
                        {{-- Full Name --}}
                        <div class="sm:col-span-2">
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Full Name
                            </label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                                placeholder="John Doe"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                @error('name') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                    placeholder="you@example.com"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                    @error('email') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    placeholder="+1 (555) 000-0000"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                    @error('phone') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valid ID --}}
                        {{-- <div>
                            <label for="valid_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Valid ID Number
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                                </span>
                                <input id="valid_id" type="text" name="valid_id" value="{{ old('valid_id', $user->valid_id) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                    @error('valid_id') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            </div>
                            @error('valid_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        {{-- Address (spans full width) --}}
                        <div class="sm:col-span-2">
                            <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                placeholder="Street, City, Province, ZIP Code"
                                class="block w-full resize-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                @error('address') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-red-600 bg-white p-6 dark:border-red-800 dark:bg-white/[0.03]">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Change Password</h4>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">Leave blank to keep your current password.</p>
                        </div>
                        <span class="mt-0.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">Optional</span>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        {{-- New Password --}}
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                New Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input id="password" type="password" name="password"
                                    placeholder="Min. 8 characters"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                    @error('password') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </span>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="Re-enter new password"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-transparent dark:text-gray-300 dark:hover:bg-gray-800">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-amber-300/30 transition hover:from-amber-600 hover:to-amber-700 dark:shadow-amber-900/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>

            </div>{{-- /right column --}}
        </div>{{-- /grid --}}
    </form>
@endsection
