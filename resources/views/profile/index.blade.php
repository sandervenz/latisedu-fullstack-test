@extends('layouts.app')

@section('title', 'Profil Kandidat')
@section('header_title', 'Profil')
@section('header_subtitle', 'Informasi Kandidat & Administrator')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Profile Overview Card (Requirement #10: a. Nama, b. Position, c. Image) -->
    <div class="bg-slate-900 rounded-xl p-6 text-white border border-slate-800">
        <div class="flex flex-col sm:flex-row items-center gap-5">
            <!-- 10.c Image Kandidat -->
            <div class="relative">
                @if($user->image && file_exists(public_path('uploads/profile/' . $user->image)))
                    <img src="{{ asset('uploads/profile/' . $user->image) }}" 
                         alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-lg object-cover ring-2 ring-orange-500">
                @else
                    <div class="w-24 h-24 rounded-lg bg-orange-500/20 text-orange-400 ring-1 ring-orange-500 flex items-center justify-center font-bold text-3xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- 10.a & 10.b Nama & Position Kandidat -->
            <div class="text-center sm:text-left flex-1">
                <!-- a. Nama Kandidat -->
                <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                <!-- b. Position Kandidat -->
                <p class="text-orange-400 font-medium text-sm mt-0.5">{{ $user->position ?? 'IT Fullstack Developer' }}</p>
                
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-3 text-xs text-slate-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bergabung: {{ $user->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-bold text-slate-800 text-base mb-1">Perbarui Data Profil</h3>
        <p class="text-xs text-slate-500 mb-5">Perbarui nama, posisi/jabatan, atau foto profil di bawah ini.</p>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama Kandidat -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-colors">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posisi Kandidat -->
            <div>
                <label for="position" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Posisi / Jabatan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}" required
                       placeholder="Contoh: IT Fullstack Developer"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-colors">
                @error('position')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Profil Kandidat -->
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Foto Profil (Opsional)
                </label>
                <div class="flex items-center gap-4">
                    <div id="profilePreviewBox" class="w-14 h-14 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                        @if($user->image && file_exists(public_path('uploads/profile/' . $user->image)))
                            <img id="profilePreviewImg" src="{{ asset('uploads/profile/' . $user->image) }}" alt="Preview" class="w-full h-full object-cover">
                        @else
                            <div id="profilePlaceholder" class="w-full h-full bg-orange-500/20 text-orange-600 flex items-center justify-center font-bold text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="profilePreviewImg" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png"
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Maksimal 500 KB.</p>
                        <p id="profileFileWarning" class="text-xs text-rose-600 font-semibold mt-1 hidden"></p>
                    </div>
                </div>
                @error('image')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewImg = document.getElementById('profilePreviewImg');
        const placeholder = document.getElementById('profilePlaceholder');
        const warning = document.getElementById('profileFileWarning');

        if (!file) return;

        if (file.size > 500 * 1024) {
            warning.textContent = 'Peringatan: Ukuran foto profil melebihi batas 500 KB!';
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
