<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TUController extends Controller
{
    public function index()
    {
        $prodiId = Auth::user()->karyawan->prodi_id_prodi;

        $suratSudahUpload = Surat::whereNotNull('file')
            ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id_prodi', $prodiId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $suratBelumUpload = Surat::whereNull('file')
            ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id_prodi', $prodiId);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $month->format('M'),
                'uploaded' => Surat::whereNotNull('file')
                    ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                        $query->where('prodi_id_prodi', $prodiId);
                    })
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
                'pending' => Surat::whereNull('file')
                    ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                        $query->where('prodi_id_prodi', $prodiId);
                    })
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
            ];
        }

        return view('tu.dashboard', [
            'suratSudahUpload' => $suratSudahUpload,
            'suratBelumUpload' => $suratBelumUpload,
            'jumlahSuratSudahUpload' => $suratSudahUpload->count(),
            'jumlahSuratBelumUpload' => $suratBelumUpload->count(),
            'monthlyData' => $monthlyData,
        ]);
    }

    public function listMhs()
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('nik', $user->nik)->first();

        $mahasiswaList = Mahasiswa::where('prodi_id_prodi', $karyawan->prodi_id_prodi)->get();

        return view('tu.list-mhs')
            ->with('mhs', $mahasiswaList);
    }

    public function listKaryawan()
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('nik', $user->nik)->first();

        $karyawanList = Karyawan::where('prodi_id_prodi', $karyawan->prodi_id_prodi)->get();

        return view('tu.list-karyawan')
            ->with('karyawan', $karyawanList);
    }

    public function listSurat()
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('nik', $user->nik)->first();

        $suratList = Surat::whereHas('mahasiswa', function ($query) use ($karyawan) {
            $query->where('prodi_id_prodi', $karyawan->prodi_id_prodi);
        })->where('status', 'approved')->get();

        $suratBelumUpload = $suratList->filter(function ($surat) {
            return $surat->file === null;
        });

        $suratSudahUpload = $suratList->filter(function ($surat) {
            return $surat->file !== null;
        });

        return view('tu.list-surat', compact('suratBelumUpload', 'suratSudahUpload'));
    }


    public function upload(Request $request, $id_surat)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $user = Auth::user();
        $karyawan = Karyawan::where('nik', $user->nik)->first();

        $surat = Surat::find($id_surat);

        if (!$surat) {
            return back()->with('error', 'Surat tidak ditemukan');
        }

        $mahasiswa = Mahasiswa::where('nrp', $surat->mahasiswa_nrp)->first();
        if (!$mahasiswa || $mahasiswa->prodi_id_prodi != $karyawan->prodi_id_prodi) {
            return back()->with('error', 'Anda tidak memiliki akses untuk surat ini');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $surat->mahasiswa_nrp . '_' . $surat->jenis_surat . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/surat', $fileName);
            $updated = Surat::where('id_surat', $id_surat)
                ->update(['file' => $fileName]);

            if ($updated) {
                return back()->with('success', 'File surat berhasil diunggah');
            } else {
                return back()->with('error', 'Gagal memperbarui informasi surat di database');
            }
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah file');
    }

    public function downloadSurat($id)
    {
        $surat = Surat::findOrFail($id);

        if (!Storage::disk('public')->exists('surat/' . $surat->file)) {
            return redirect()->back()->with('error', 'File fisik tidak ditemukan.');
        }

        return Storage::disk('public')->download('surat/' . $surat->file);
    }
}
