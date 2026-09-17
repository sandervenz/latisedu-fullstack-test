<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SiswaController extends Controller
{
    /**
     * Display list of students with DataTables and institution filter.
     */
    public function index(Request $request)
    {
        // Data lembaga WAJIB mengambil dari database (Requirement #6a, #7d)
        $lembagas = DB::select('SELECT id, nama FROM lembagas ORDER BY nama ASC');

        // Mengambil data siswa dengan Prepared Statement (Requirement #5)
        $query = "SELECT s.id, s.nis, s.nama, s.email, s.foto, s.created_at, l.id as lembaga_id, l.nama as nama_lembaga 
                  FROM siswas s 
                  JOIN lembagas l ON s.lembaga_id = l.id 
                  WHERE 1=1";
        $bindings = [];

        if ($request->filled('lembaga_id')) {
            $query .= " AND s.lembaga_id = ?";
            $bindings[] = $request->lembaga_id;
        }

        if ($request->filled('search')) {
            // Search khusus hanya cari pada kolom NIS & Nama (Requirement #7c)
            $query .= " AND (s.nis LIKE ? OR s.nama LIKE ?)";
            $searchTerm = '%' . $request->search . '%';
            $bindings[] = $searchTerm;
            $bindings[] = $searchTerm;
        }

        $query .= " ORDER BY s.id DESC";

        $siswas = DB::select($query, $bindings);

        return view('siswa.index', compact('siswas', 'lembagas'));
    }

    /**
     * Show form to create new student.
     */
    public function create()
    {
        // Data lembaga wajib mengambil dari database
        $lembagas = DB::select('SELECT id, nama FROM lembagas ORDER BY nama ASC');
        return view('siswa.create', compact('lembagas'));
    }

    /**
     * Store new student with strict validation.
     */
    public function store(Request $request)
    {
        // Validasi sesuai Requirement #6
        $request->validate([
            'lembaga_id' => ['required', 'exists:lembagas,id'],
            'nis' => ['required', 'numeric', 'unique:siswas,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            // Foto: Format JPG & PNG, maksimal 100 KB (Requirement #6e)
            'foto' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:100'],
        ], [
            'lembaga_id.required' => 'Lembaga siswa wajib dipilih.',
            'lembaga_id.exists' => 'Lembaga yang dipilih tidak valid.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.unique' => 'NIS ini sudah terdaftar untuk siswa lain.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email siswa wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'foto.required' => 'Foto siswa wajib diunggah.',
            'foto.mimes' => 'Format foto hanya diperbolehkan JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal adalah 100 KB sesuai kriteria tes.',
        ]);

        $fotoNama = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoNama = 'siswa_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/siswa'), $fotoNama);
        }

        // Simpan menggunakan Prepared Statement (Requirement #5)
        DB::insert(
            'INSERT INTO siswas (lembaga_id, nis, nama, email, foto, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
            [
                $request->lembaga_id,
                $request->nis,
                $request->nama,
                $request->email,
                $fotoNama
            ]
        );

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Show form to edit existing student.
     */
    public function edit(Siswa $siswa)
    {
        // Data lembaga wajib mengambil dari database
        $lembagas = DB::select('SELECT id, nama FROM lembagas ORDER BY nama ASC');
        return view('siswa.edit', compact('siswa', 'lembagas'));
    }

    /**
     * Update existing student data.
     */
    public function update(Request $request, Siswa $siswa)
    {
        // Validasi sesuai Requirement #6
        $request->validate([
            'lembaga_id' => ['required', 'exists:lembagas,id'],
            'nis' => ['required', 'numeric', 'unique:siswas,nis,' . $siswa->id],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:100'],
        ], [
            'lembaga_id.required' => 'Lembaga siswa wajib dipilih.',
            'lembaga_id.exists' => 'Lembaga yang dipilih tidak valid.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.unique' => 'NIS ini sudah terdaftar untuk siswa lain.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email siswa wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'foto.mimes' => 'Format foto hanya diperbolehkan JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal adalah 100 KB sesuai kriteria tes.',
        ]);

        $fotoNama = $siswa->foto;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($siswa->foto && File::exists(public_path('uploads/siswa/' . $siswa->foto))) {
                File::delete(public_path('uploads/siswa/' . $siswa->foto));
            }

            $file = $request->file('foto');
            $fotoNama = 'siswa_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/siswa'), $fotoNama);
        }

        // Update dengan Prepared Statement (Requirement #5)
        DB::update(
            'UPDATE siswas SET lembaga_id = ?, nis = ?, nama = ?, email = ?, foto = ?, updated_at = NOW() WHERE id = ?',
            [
                $request->lembaga_id,
                $request->nis,
                $request->nama,
                $request->email,
                $fotoNama,
                $siswa->id
            ]
        );

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Delete student data and photo.
     */
    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto && File::exists(public_path('uploads/siswa/' . $siswa->foto))) {
            File::delete(public_path('uploads/siswa/' . $siswa->foto));
        }

        // Delete dengan Prepared Statement (Requirement #5)
        DB::delete('DELETE FROM siswas WHERE id = ?', [$siswa->id]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Ekspor Data Siswa ke Excel sesuai filter/pencarian aktif (Requirement #8).
     */
    public function exportExcel(Request $request)
    {
        $query = "SELECT s.nis, s.nama, s.email, l.nama as nama_lembaga, s.created_at 
                  FROM siswas s 
                  JOIN lembagas l ON s.lembaga_id = l.id 
                  WHERE 1=1";
        $bindings = [];
        $filterTitle = 'Semua Lembaga';

        // Filter lembaga dari database
        if ($request->filled('lembaga_id')) {
            $query .= " AND s.lembaga_id = ?";
            $bindings[] = $request->lembaga_id;

            $lembaga = DB::selectOne('SELECT nama FROM lembagas WHERE id = ?', [$request->lembaga_id]);
            if ($lembaga) {
                $filterTitle = $lembaga->nama;
            }
        }

        // Filter search khusus NIS & Nama
        if ($request->filled('search')) {
            $query .= " AND (s.nis LIKE ? OR s.nama LIKE ?)";
            $searchTerm = '%' . $request->search . '%';
            $bindings[] = $searchTerm;
            $bindings[] = $searchTerm;
        }

        $query .= " ORDER BY s.id DESC";
        $dataSiswa = DB::select($query, $bindings);

        // Buat Spreadsheet Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Header Judul Laporan (Latis Education Orange Brand)
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'DATA SISWA - ' . strtoupper($filterTitle));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC2410C'); // Dark Orange

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d-m-Y H:i:s') . ($request->filled('search') ? ' | Filter Pencarian: "' . $request->search . '"' : ''));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Kolom Tabel
        $headers = ['No', 'NIS', 'Nama Siswa', 'Email', 'Lembaga'];
        $columns = ['A', 'B', 'C', 'D', 'E'];

        foreach ($headers as $index => $header) {
            $col = $columns[$index];
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF97316'); // Latis Vibrant Orange
            $sheet->getStyle($col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Isi Data Baris
        $row = 5;
        $no = 1;
        foreach ($dataSiswa as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValueExplicit('B' . $row, $item->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $item->nama);
            $sheet->setCellValue('D' . $row, $item->email);
            $sheet->setCellValue('E' . $row, $item->nama_lembaga);

            // Alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Zebra background effect
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
            }

            $row++;
        }

        // Border Styling
        $lastRow = max(5, $row - 1);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle('A4:E' . $lastRow)->applyFromArray($styleArray);

        // Auto size columns
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output file download
        $fileName = 'Data_Siswa_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $filterTitle) . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
