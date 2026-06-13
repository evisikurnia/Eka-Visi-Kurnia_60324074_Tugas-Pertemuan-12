<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use App\Exports\AnggotaExport;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar anggota (Halaman Utama)
     */
    public function index()
    {
        $anggotas = Anggota::latest()->get();
        
        $totalAnggota = $anggotas->count();
        $anggotaAktif = $anggotas->where('status', 'Aktif')->count();
        $anggotaNonaktif = $anggotas->where('status', 'Nonaktif')->count();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

    /**
     * Menampilkan form tambah anggota dengan Auto-Generate Kode (Tugas 1)
     */
    public function create()
    {
        $kodeAnggota = $this->generateKodeAnggota();
        return view('anggota.create', compact('kodeAnggota'));
    }

    /**
     * Menyimpan data anggota baru
     */
    public function store(StoreAnggotaRequest $request)
    {
        $validatedData = $request->validated();
        Anggota::create($validatedData);

        return redirect()->route('anggota.index')
                         ->with('success', 'Anggota baru berhasil didaftarkan!');
    }

    /**
     * Menampilkan detail anggota
     */
    public function show(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.show', compact('anggota'));
    }

    /**
     * Menampilkan form edit anggota
     */
    public function edit(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Memperbarui data anggota
     */
    public function update(UpdateAnggotaRequest $request, string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $anggota->update($request->validated());
            
            return redirect()->route('anggota.show', $anggota->id)
                             ->with('success', 'Data anggota berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal mengupdate anggota: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data anggota
     */
    public function destroy(string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $namaAnggota = $anggota->nama;
            $anggota->delete();
            
            return redirect()->route('anggota.index')
                             ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    /**
     * Advanced Search & Filter Anggota (Tugas 3)
     */
    public function search(Request $request)
    {
        $query = Anggota::query();
        
        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('telepon', 'like', '%' . $request->keyword . '%');
            });
        }
        
        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->pekerjaan) {
            $query->where('pekerjaan', $request->pekerjaan);
        }
        
        $anggotas = $query->latest()->get();
        
        $totalAnggota = $anggotas->count();
        $anggotaAktif = $anggotas->where('status', 'Aktif')->count();
        $anggotaNonaktif = $anggotas->where('status', 'Nonaktif')->count();
        
        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

    /**
     * Export ke file Excel (Tugas 2)
     */
    public function export()
    {
        return Excel::download(new AnggotaExport, 'anggota_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Helper Function: Auto-generate kode anggota (Tugas 1)
     */
    private function generateKodeAnggota()
    {
        $tahun = date('Y');
        $lastAnggota = Anggota::whereYear('created_at', $tahun)
                              ->orderBy('kode_anggota', 'desc')
                              ->first();
        
        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->kode_anggota, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'AGT-' . $tahun . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}