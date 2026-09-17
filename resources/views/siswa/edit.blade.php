@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('header_title', 'Edit Data Siswa')
@section('header_subtitle', 'Memperbarui data siswa ' . $siswa->nama)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Formulir Edit Siswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi data siswa di bawah ini.</p>
            </div>
            <a href="{{ route('siswa.index') }}" 
               class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form Edit Siswa -->
        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Dropdown Lembaga Siswa (Requirement #6a: Data lembaga dari database) -->
            <div>
                <label for="lembaga_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Lembaga Siswa <span class="text-rose-500">*</span>
                </label>
                <select name="lembaga_id" id="lembaga_id" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    @foreach($lembagas as $lembaga)
                        <option value="{{ $lembaga->id }}" {{ old('lembaga_id', $siswa->lembaga_id) == $lembaga->id ? 'selected' : '' }}>
                            {{ $lembaga->nama }}
                        </option>
                    @endforeach
                </select>
                @error('lembaga_id')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. NIS (Requirement #6b: Required, Unik, Angka) -->
            <div>
                <label for="nis" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis) }}" required 
                       pattern="[0-9]+" inputmode="numeric"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                <p class="text-xs text-slate-400 mt-1">Format angka, wajib diisi dan harus unik.</p>
                @error('nis')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Nama Siswa (Requirement #6c: Required) -->
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Siswa Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}" required 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                @error('nama')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Email (Requirement #6d: Required, Valid Email) -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Email Siswa <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', $siswa->email) }}" required 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. Foto Siswa (Requirement #6e: JPG & PNG, Maksimal 100 KB) -->
            <div>
                <label for="foto" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Ganti Foto Siswa (Opsional)
                </label>
                
                <div class="flex items-center gap-4">
                    <div id="previewContainer" class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden flex-shrink-0">
                        @if($siswa->foto && file_exists(public_path('uploads/siswa/' . $siswa->foto)))
                            <img id="previewImage" src="{{ asset('uploads/siswa/' . $siswa->foto) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                        @else
                            <div id="avatarInitials" class="w-full h-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-base">
                                {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                            </div>
                            <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>

                    <div class="flex-1">
                        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png"
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <div class="flex items-center justify-between text-xs text-slate-500 mt-1.5">
                            <span>Format diizinkan: <strong>JPG, PNG</strong></span>
                            <span class="text-amber-600 font-medium">Batas Maksimal: <strong>100 KB</strong></span>
                        </div>
                        <p id="fileWarning" class="text-xs text-rose-600 font-semibold mt-1 hidden"></p>
                    </div>
                </div>
                @error('foto')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan Perubahan -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('siswa.index') }}" 
                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit" id="btnSubmit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-600/30 transition-all cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live validation & preview foto untuk edit
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const warningEl = document.getElementById('fileWarning');
        const previewImg = document.getElementById('previewImage');
        const avatarInitials = document.getElementById('avatarInitials');
        const btnSubmit = document.getElementById('btnSubmit');

        if (!file) return;

        const maxKiloBytes = 100;
        const fileSizeKb = Math.round(file.size / 1024);

        if (fileSizeKb > maxKiloBytes) {
            warningEl.textContent = `Peringatan: Ukuran file (${fileSizeKb} KB) melebihi batas maksimal 100 KB!`;
            warningEl.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            warningEl.classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewImg.classList.remove('hidden');
            if (avatarInitials) avatarInitials.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
