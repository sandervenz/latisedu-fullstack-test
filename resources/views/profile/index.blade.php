@extends('layouts.app')

@section('title', 'Profil Kandidat')
@section('header_title', 'Profil Kandidat')
@section('header_subtitle', 'Informasi Kandidat IT Fullstack (Requirement #10)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Overview Card (Requirement #10: a. Nama, b. Position, c. Image) -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
            <!-- 10.c Image Kandidat -->
            <div class="relative group">
                @if($user->image && file_exists(public_path('uploads/profile/' . $user->image)))
                    <img src="{{ asset('uploads/profile/' . $user->image) }}" 
                         alt="{{ $user->name }}" 
                         class="w-28 h-28 rounded-2xl object-cover ring-4 ring-white/20 shadow-2xl">
                @else
                    <div class="w-28 h-28 rounded-2xl bg-blue-600/30 ring-4 ring-white/20 flex items-center justify-center font-extrabold text-3xl text-blue-300 shadow-2xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <span class="absolute -bottom-2 -right-2 bg-emerald-500 border-2 border-slate-900 w-5 h-5 rounded-full" title="Online / Aktif"></span>
            </div>

            <!-- 10.a & 10.b Nama & Position Kandidat -->
            <div class="text-center sm:text-left flex-1">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/20 mb-2">
                    Kandidat Pelamar
                </div>
                <!-- a. Nama Kandidat -->
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $user->name }}</h2>
                <!-- b. Position Kandidat -->
                <p class="text-blue-200 font-medium text-base mt-1">{{ $user->position ?? 'IT Fullstack Developer' }}</p>
                
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-4 text-xs text-slate-300">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bergabung: {{ $user->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h3 class="font-bold text-slate-800 text-lg mb-1">Perbarui Profil Kandidat</h3>
        <p class="text-xs text-slate-500 mb-6">Anda dapat mengubah nama, posisi lamaran kerja, atau mengunggah foto profil kandidat di sini.</p>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Kandidat -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Kandidat <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posisi Kandidat -->
            <div>
                <label for="position" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Posisi / Jabatan Kandidat <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}" required
                       placeholder="Contoh: IT Fullstack Developer"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                @error('position')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Profil Kandidat -->
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Foto Kandidat (Opsional)
                </label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png"
                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Maksimal 500 KB.</p>
                @error('image')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-600/30 transition-all cursor-pointer">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
