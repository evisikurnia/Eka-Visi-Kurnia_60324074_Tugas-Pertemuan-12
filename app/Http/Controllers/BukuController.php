<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Rules\KodeBukuFormat;
use Illuminate\Validation\Rule;
 
class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();
        
        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();
        
        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis'
        ));
    }

        public function search(Request $request)
    {
        $query = Buku::query();

        // 1. Filter Kata Kunci (Judul, Pengarang, Penerbit)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('pengarang', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Kategori (Dropdown)
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Tahun (Dropdown)
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_terbit', $request->tahun);
        }

        // 4. Filter Ketersediaan (Semua / Tersedia / Habis)
        if ($request->has('stok_status') && $request->stok_status != '') {
            if ($request->stok_status == 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->stok_status == 'habis') {
                $query->where('stok', '<=', 0);
            }
        }

        // Ambil data hasil filter
        $bukus = $query->latest()->get();

        // Dapatkan semua data buku untuk kalkulasi statistik & dropdown tahun di view
        $allBuku = Buku::all();
        $totalBuku = $allBuku->count();
        $bukuTersedia = $allBuku->where('stok', '>', 0)->count();
        $bukuHabis = $allBuku->where('stok', '<=', 0)->count();
        
        $daftarTahun = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        return view('buku.index', compact('bukus', 'totalBuku', 'bukuTersedia', 'bukuHabis', 'daftarTahun'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Akan diimplementasi di pertemuan 12
        return view('buku.create');
    }
 
    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'kode_buku' => ['required', 'string', 'unique:buku,kode_buku', new KodeBukuFormat],
        'judul' => 'required|string|max:255',
        'kategori' => 'required|string',
        
        // Conditional 1: Jika kategori "Programming", bahasa harus "Inggris"
        'bahasa' => [
            'required',
            'string',
            Rule::requiredIf($request->kategori === 'Programming' && $request->bahasa !== 'Inggris'),
        ],
        
        'tahun_terbit' => 'required|integer|digits:4',
        
        // Conditional 2: Jika tahun terbit < 2000, stok maksimal 5
        'stok' => [
            'required',
            'integer',
            'min:0',
            $request->tahun_terbit < 2000 ? 'max:5' : 'max:999'
        ],
        'pengarang' => 'required|string',
        'penerbit' => 'required|string',
        'harga' => 'required|numeric|min:0',
    ], [
        // Custom Error Messages Bahasa Indonesia
        'required' => 'Kolom :attribute wajib diisi.',
        'string' => 'Kolom :attribute harus berupa teks.',
        'unique' => ':attribute sudah terdaftar di database.',
        'integer' => 'Kolom :attribute harus berupa angka bulat.',
        'digits' => 'Kolom :attribute harus berukuran :digits digit.',
        'min' => 'Kolom :attribute minimal bernilai :min.',
        'max' => 'Kolom :attribute maksimal bernilai :max.',
        'numeric' => 'Kolom :attribute harus berupa angka.',
    ]);

    // Tambahan logika khusus untuk conditional Bahasa jika Programming
    if ($request->kategori === 'Programming' && $request->bahasa !== 'Inggris') {
        return back()->withErrors(['bahasa' => 'Jika kategori "Programming", maka bahasa harus "Inggris".'])->withInput();
    }
    
    // Tambahan logika jika tahun < 2000 dan stok > 5
    if ($request->tahun_terbit < 2000 && $request->stok > 5) {
        return back()->withErrors(['stok' => 'Buku lama (tahun < 2000) stok maksimal adalah 5 buku.'])->withInput();
    }

    // Kode untuk menyimpan ke database (Lanjutan Pertemuan 12)
    // Buku::create($validated);
    // return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);
        
        // Return view detail buku
        return view('buku.show', compact('buku'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
{
    try {
        $buku = Buku::findOrFail($id);
        
        // Update buku dengan validated data
        $buku->update($request->validated());
        
        // Redirect dengan success message
        return redirect()->route('buku.show', $buku->id)
                         ->with('success', 'Buku berhasil diupdate!');
                         
    } catch (\Exception $e) {
        // Redirect dengan error message jika gagal
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
    }
}
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Akan diimplementasi di pertemuan 12
    }
    
    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();
        
        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();
        
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }

        public function bulkDelete(Request $request)
    {
        $ids = $request->buku_ids;
        
        if (!$ids || count($ids) === 0) {
            return redirect()->route('buku.index')->with('error', 'Pilih minimal satu buku untuk dihapus!');
        }

        // Hapus semua data yang id-nya ada di dalam array $ids
        Buku::whereIn('id', $ids)->delete();

        return redirect()->route('buku.index')
                        ->with('success', count($ids) . ' buku berhasil dihapus sekaligus!');
    }

        public function export()
    {
        $bukus = Buku::all();
        
        $filename = 'buku_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($bukus) {
            $file = fopen('php://output', 'w');
            
            // Header Kolom CSV
            fputcsv($file, [
                'Kode Buku', 'Judul', 'Kategori', 'Pengarang', 
                'Penerbit', 'Tahun', 'ISBN', 'Harga', 'Stok'
            ]);
            
            // Mengisi Data Kolom CSV
            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku,
                    $buku->judul,
                    $buku->kategori,
                    $buku->pengarang,
                    $buku->penerbit,
                    $buku->tahun_terbit,
                    $buku->isbn,
                    $buku->harga,
                    $buku->stok,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}