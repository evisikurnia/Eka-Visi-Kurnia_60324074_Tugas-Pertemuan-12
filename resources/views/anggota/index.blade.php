@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
<div class="container-fluid">
    
    {{-- BAGIAN ATAS: Judul Halaman & Tombol Aksi (Tugas 2) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-people"></i> Daftar Anggota</h2>
        <div class="d-flex gap-2">
            {{-- Tombol Export Excel (Tugas 2) --}}
            <a href="{{ route('anggota.export') }}" class="btn btn-success">
                <i class="bi bi-file-excel"></i> Export Excel
            </a>
            {{-- Tombol Tambah Anggota Baru --}}
            <a href="{{ route('anggota.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Tambah Anggota Baru
            </a>
        </div>
    </div>

    {{-- KELOMPOK STATISTIK (Sesuai Tampilan Gambar Anda) --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Anggota</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalAnggota }}</h2>
                    </div>
                    <i class="bi bi-people-fill text-success fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Anggota Aktif</h6>
                        <h2 class="mb-0 fw-bold">{{ $anggotaAktif }}</h2>
                    </div>
                    <i class="bi bi-person-check-fill text-primary fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-secondary shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Anggota Nonaktif</h6>
                        <h2 class="mb-0 fw-bold">{{ $anggotaNonaktif }}</h2>
                    </div>
                    <i class="bi bi-person-x-fill text-secondary fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TUGAS 3: Form Advanced Search & Filter --}}
    <div class="card mb-4 shadow-sm border-0 bg-light">
        <div class="card-body">
            <form action="{{ route('anggota.search') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="keyword" class="form-control" 
                               value="{{ request('keyword') }}" placeholder="Cari nama/email/telepon...">
                    </div>
                    <div class="col-md-2">
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="pekerjaan" class="form-select">
                            <option value="">Semua Pekerjaan</option>
                            <option value="Mahasiswa" {{ request('pekerjaan') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="Pegawai" {{ request('pekerjaan') == 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
                            <option value="Wiraswasta" {{ request('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATA UTAMA --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop menggunakan $anggotas sesuai kembalian dari method search & index --}}
                    @forelse ($anggotas as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-danger fw-bold">{{ $item->kode_anggota }}</td>
                        <td><strong>{{ $item->nama }}</strong></td>
                        <td><i class="bi bi-envelope text-muted"></i> {{ $item->email }}</td>
                        <td><i class="bi bi-telephone text-muted"></i> {{ $item->telepon }}</td>
                        <td>
                            <i class="bi bi-gender-{{ $item->jenis_kelamin == 'Laki-laki' ? 'male text-primary' : 'female text-danger' }}"></i>
                            {{ $item->jenis_kelamin }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Aktif' ? 'success' : 'secondary' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{-- Tombol Aksi Tunggal & Bersih --}}
                            <a href="{{ route('anggota.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Data anggota tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection