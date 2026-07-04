@extends('layouts.authenticated.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endpush

@section('content')
    @php($canEditValidId = strtolower($valid_id_status ?? 'pending') === 'pending')

    <x-common.page-breadcrumb pageTitle="User Profile" />


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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" data-confirm-leave>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h4 class="mb-4 text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Photo</h4>
                    <div class="flex flex-col items-center gap-5">
                        <div class="relative">
                            <div id="avatar-ring" class="h-28 w-28 overflow-hidden rounded-full ring-4 ring-amber-100 dark:ring-amber-900/40">
                                @if($user->avatar)
                                    <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="Profile photo" class="h-full w-full object-cover" />
                                @else
                                    <div id="avatar-initials" class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-100 to-orange-200 text-3xl font-bold text-amber-900 dark:from-amber-900 dark:to-orange-900 dark:text-amber-100">
                                        {{ $user->initials }}
                                    </div>
                                    <img id="avatar-preview" src="" alt="Profile photo" class="hidden h-full w-full object-cover" />
                                @endif
                            </div>
                            <label for="avatar-upload" class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-amber-500 shadow-md shadow-amber-300/40 transition hover:bg-amber-600 dark:shadow-amber-900/40">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </label>
                        </div>

                        <div class="w-full text-center">
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                        </div>

                        <div class="w-full">
                            <input id="avatar-upload" type="file" name="_avatar_raw" accept="image/*" class="hidden" />
                            {{-- This hidden input carries the cropped base64 data on submit --}}
                            <input type="hidden" name="avatar_cropped" id="avatar-cropped" />

                            <label for="avatar-upload" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700 dark:border-gray-600 dark:bg-gray-800/50 dark:text-gray-400 dark:hover:border-amber-600 dark:hover:bg-amber-900/10 dark:hover:text-amber-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload new photo
                            </label>
                            <p class="mt-1.5 text-center text-xs text-gray-400 dark:text-gray-600">JPG, PNG or GIF · max 2 MB</p>
                            @error('avatar_cropped')
                                <p class="mt-1 text-center text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Valid ID</h4>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">
                                @if($canEditValidId)
                                    Upload a clear image of your government-issued ID while it is still pending review.
                                @else
                                    Your valid ID is already {{ $valid_id_status }} and cannot be changed right now.
                                @endif
                            </p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            {{ ucfirst($valid_id_status ?? 'pending') }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-3 dark:border-gray-600 dark:bg-gray-800/50">
                            @if($user->valid_id)
                                <img id="valid-id-preview" src="{{ asset('storage/' . $user->valid_id) }}" alt="Valid ID preview" class="h-48 w-full rounded-lg bg-white object-contain" />
                            @else
                                <div id="valid-id-placeholder" class="flex h-48 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white text-center text-sm text-gray-400 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-500">
                                    No valid ID uploaded yet
                                </div>
                                <img id="valid-id-preview" src="" alt="Valid ID preview" class="hidden h-48 w-full rounded-lg bg-white object-contain" />
                            @endif
                        </div>

                        <div class="w-full">
                            <input id="valid-id-upload" type="file" name="valid_id_upload" accept="image/*" class="hidden" {{ $canEditValidId ? '' : 'disabled' }} />

                            @if($canEditValidId)
                                <label for="valid-id-upload" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700 dark:border-gray-600 dark:bg-gray-800/50 dark:text-gray-400 dark:hover:border-amber-600 dark:hover:bg-amber-900/10 dark:hover:text-amber-300">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload new valid ID
                                </label>
                            @else
                                <div class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-400 dark:border-gray-600 dark:bg-gray-800/50 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload unavailable
                                </div>
                            @endif

                            <p class="mt-1.5 text-center text-xs text-gray-400 dark:text-gray-600">JPG, PNG or GIF · max 5 MB</p>
                            @error('valid_id_upload')
                                <p class="mt-1 text-center text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div id="crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                    <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Crop your photo</h3>

                    <div class="overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800" style="max-height: 340px;">
                        <img id="crop-image" src="" alt="Crop preview" class="block max-w-full" />
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" id="crop-cancel" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                            Cancel
                        </button>
                        <button type="button" id="crop-confirm" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                            Apply crop
                        </button>
                    </div>
                </div>
            </div>


            <div class="lg:col-span-2 space-y-5">

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

                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    placeholder="+63 (912) 000-0000"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-600 dark:focus:ring-amber-800/30
                                    @error('phone') border-red-400 focus:border-red-400 focus:ring-red-200/60 dark:border-red-600 @enderror" />
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

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


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        (function () {
            const fileInput    = document.getElementById('avatar-upload');
            const cropModal    = document.getElementById('crop-modal');
            const cropImage    = document.getElementById('crop-image');
            const cropConfirm  = document.getElementById('crop-confirm');
            const cropCancel   = document.getElementById('crop-cancel');
            const preview      = document.getElementById('avatar-preview');
            const initials     = document.getElementById('avatar-initials');
            const croppedInput = document.getElementById('avatar-cropped');
            const validIdInput = document.getElementById('valid-id-upload');
            const validIdPreview = document.getElementById('valid-id-preview');
            const validIdPlaceholder = document.getElementById('valid-id-placeholder');

            let cropper = null;

            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                // Basic size guard (2 MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Please choose an image under 2 MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    cropImage.src = e.target.result;
                    cropModal.classList.remove('hidden');
                    cropModal.classList.add('flex');

                    // Destroy old cropper instance if any
                    if (cropper) { cropper.destroy(); cropper = null; }

                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,          // force square
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.85,
                        responsive: true,
                        restore: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                };
                reader.readAsDataURL(file);
            });

            cropConfirm.addEventListener('click', function () {
                if (!cropper) return;

                // Get a 400×400 canvas (plenty for an avatar)
                const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                const dataURL = canvas.toDataURL('image/jpeg', 0.88);

                // Update the visible preview circle
                preview.src = dataURL;
                preview.classList.remove('hidden');
                if (initials) initials.classList.add('hidden');

                // Store the base64 for form submission
                croppedInput.value = dataURL;

                closeModal();
            });

            cropCancel.addEventListener('click', closeModal);

            if (validIdInput) {
                validIdInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;

                    if (file.size > 5 * 1024 * 1024) {
                        alert('Please choose an image under 5 MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (validIdPreview) {
                            validIdPreview.src = e.target.result;
                            validIdPreview.classList.remove('hidden');
                        }

                        if (validIdPlaceholder) {
                            validIdPlaceholder.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Close on backdrop click
            cropModal.addEventListener('click', function (e) {
                if (e.target === cropModal) closeModal();
            });

            function closeModal() {
                cropModal.classList.add('hidden');
                cropModal.classList.remove('flex');
                if (cropper) { cropper.destroy(); cropper = null; }
                fileInput.value = '';
            }
        })();
    </script>
@endpush
