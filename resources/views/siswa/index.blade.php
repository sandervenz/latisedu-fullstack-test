@extends('layouts.app')

@section('title', 'Data Siswa')
@section('header_title', 'Manajemen Data Siswa')
@section('header_subtitle', 'Data Siswa Latis Education & Tutor Indonesia')

@section('content')
<div class="space-y-6">

    <!-- Card Top Action & Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Filter Section (Requirement #7d: Filter dropdown lembaga dari database) -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Dropdown Filter Lembaga -->
                <div class="w-full sm:w-64">
                    <label for="filterLembaga" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Filter Lembaga:</label>
                    <select id="filterLembaga" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                        <option value="">Semua Lembaga</option>
                        @foreach($lembagas as $l)
                            <option value="{{ $l->nama }}" data-id="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Search (Requirement #7c: Search hanya cari pada kolom NIS & Nama) -->
                <div class="w-full sm:w-72">
                    <label for="customSearchInput" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari NIS & Nama:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="customSearchInput" placeholder="Ketik NIS atau Nama..." 
                               class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Action Buttons: Tambah Siswa & Ekspor Excel (Requirement #8) -->
            <div class="flex items-center gap-2.5 self-end sm:self-auto">
                <!-- Tombol Ekspor Excel (Requirement #8: Ekspor sesuai hasil pencarian/filter) -->
                <button type="button" id="btnExportExcel"
                        class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-150 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Ekspor Excel
                </button>

                <!-- Tombol Tambah Siswa -->
                <a href="{{ route('siswa.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Siswa
                </a>
            </div>

        </div>
    </div>

    <!-- DataTables Table Container (Requirement #7) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table id="siswaTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-12 text-center">Foto</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Email</th>
                        <th>Lembaga</th>
                        <th class="w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Foto (Maks 100 KB - Requirement #6e) -->
                            <td class="text-center">
                                @if($siswa->foto && file_exists(public_path('uploads/siswa/' . $siswa->foto)))
                                    <img src="{{ asset('uploads/siswa/' . $siswa->foto) }}" 
                                         alt="{{ $siswa->nama }}" 
                                         class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 mx-auto shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 ring-2 ring-orange-50 mx-auto flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                    </div>
                                @endif
                            </td>

                            <!-- NIS (Unique & Angka - Requirement #6b) -->
                            <td class="font-mono text-sm font-semibold text-slate-700">{{ $siswa->nis }}</td>

                            <!-- Nama Siswa (Requirement #6c) -->
                            <td class="font-medium text-slate-900">{{ $siswa->nama }}</td>

                            <!-- Email (Requirement #6d) -->
                            <td class="text-slate-600 text-sm">{{ $siswa->email }}</td>

                            <!-- Lembaga (Requirement #6a) -->
                            <td>
                                @if($siswa->nama_lembaga === 'Latiseducation')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                                        {{ $siswa->nama_lembaga }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $siswa->nama_lembaga }}
                                    </span>
                                @endif
                            </td>

                            <!-- Action Button (Requirement #7a: Action button edit) -->
                            <td class="text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Edit Button -->
                                    <a href="{{ route('siswa.edit', $siswa->id) }}" 
                                       title="Edit Data Siswa"
                                       class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Button with Confirm -->
                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $siswa->nama }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Data Siswa" 
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables (Requirement #7)
        // dom: 'lrtip' menyembunyikan kotak filter bawaan karena kita pakai custom search (hanya NIS & Nama)
        const table = $('#siswaTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"l>rt<"flex flex-col sm:flex-row justify-between items-center mt-4 text-xs text-slate-500"ip>',
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data siswa yang cocok dengan kriteria pencarian",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ total siswa",
                infoEmpty: "Menampilkan 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            columnDefs: [
                { orderable: false, targets: [0, 5] } // Kolom Foto dan Aksi tidak bisa disortir
            ]
        });

        // Requirement #7c: Search (HANYA cari data pada kolom NIS & Nama)
        // Data index 1 = NIS, Data index 2 = Nama
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const searchTerm = $('#customSearchInput').val().toLowerCase().trim();
            if (!searchTerm) {
                return true;
            }

            const nis = (data[1] || '').toLowerCase();
            const nama = (data[2] || '').toLowerCase();

            // Hanya mencocokkan kolom NIS atau Nama
            return nis.includes(searchTerm) || nama.includes(searchTerm);
        });

        // Requirement #7d: Filter dropdown lembaga (data diambil dari database)
        // Data index 4 = Lembaga
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const selectedLembaga = $('#filterLembaga').val().trim();
            if (!selectedLembaga) {
                return true;
            }

            const rowLembaga = (data[4] || '').trim();
            return rowLembaga.includes(selectedLembaga);
        });

        // Event listener saat user mengetik di kotak pencarian NIS & Nama
        $('#customSearchInput').on('keyup input', function() {
            table.draw();
        });

        // Event listener saat user memilih dropdown lembaga
        $('#filterLembaga').on('change', function() {
            table.draw();
        });

        // Requirement #8: Ekspor Excel menampilkan data siswa sesuai hasil pencarian/filter
        $('#btnExportExcel').on('click', function() {
            const selectedOption = $('#filterLembaga').find(':selected');
            const lembagaId = selectedOption.data('id') || '';
            const searchTerm = $('#customSearchInput').val().trim();

            let exportUrl = "{{ route('siswa.export') }}";
            let params = new URLSearchParams();

            if (lembagaId) {
                params.append('lembaga_id', lembagaId);
            }
            if (searchTerm) {
                params.append('search', searchTerm);
            }

            const finalUrl = exportUrl + (params.toString() ? '?' + params.toString() : '');
            window.location.href = finalUrl;
        });
    });
</script>
@endpush
