<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role != 'mahasiswa') {
            return redirect()->route('login.mahasiswa')->with('error', 'Akses ditolak');
        }

        // Mahasiswa hanya melihat surat miliknya
        $suratList = Surat::where('mahasiswa_nrp', $user->nrp)->get();

        $suratDiterima = $suratList->where('status', 'approved');
        $suratDitolak = $suratList->where('status', 'declined');
        $suratMenunggu = $suratList->where('status', 'waiting');

        return view('mahasiswa.surat')
            ->with('suratDiterima', $suratDiterima)
            ->with('suratDitolak', $suratDitolak)
            ->with('suratMenunggu', $suratMenunggu);
    }

    public function create(){
        return view('mahasiswa.apply');
    }

    public function store(Request $request){
//        dd($request->all());
        try {
            $request->validate([
                'jenis_surat' => 'required|in:keterangan_aktif,pengantar_tugas,keterangan_lulus,hasil_studi',
            ]);

            if ($request->jenis_surat === 'keterangan_aktif') {
                $request->validate([
                    'keperluan_aktif' => 'required',
                ]);
            } elseif($request->jenis_surat === 'pengantar_tugas') {
                $request->validate([
                    'kode_mk' => 'required',
                    'nama_mk' => 'required',
                    'tujuan' => 'required',
                    'topik' => 'required',
                ]);
            } elseif($request->jenis_surat ==='hasil_studi') {
                $request->validate([
                    'keperluan_hasil_studi' => 'required',
                ]);
            }

            Surat::create([
                'jenis_surat' => $request->jenis_surat,
                'file'=> $request->file,
                'status'=>'waiting',
                'keperluan_aktif' => $request->keperluan_aktif,
                'keperluan_hasil_studi' => $request->keperluan_hasil_studi,
                'tujuan'=> $request->tujuan,
                'topik'=> $request->topik,
                'tanggal_pengajuan'=> date('Y-m-d'),
                'mahasiswa_nrp' => auth()->user()->nrp,
                'mahasiswa_nama' => auth()->user()->nama,
                'alamat' => auth()->user()->mahasiswa->alamat,
                'semester' => auth()->user()->mahasiswa->semester,
                'kode_mk' => $request->kode_mk,
                'nama_mk' => $request->nama_mk,
            ]);
            session()->flash('success', "Surat {$request->jenis_surat} berhasil dibuat!");
            return redirect()->route('mahasiswa.surat');
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    public function edit($id_surat)
    {
        $surat = Surat::findOrFail($id_surat);
        return view('mahasiswa.edit')->with('surat', $surat);
    }

    public function update(Request $request, $id_surat)
    {
        try {
            $surat = Surat::findOrFail($id_surat);

            if ($surat->status !== 'waiting') {
                return redirect()->back()->with('error', 'Surat tidak dapat diedit setelah diproses.');
            }

            $request->validate([
                'jenis_surat' => 'required|in:keterangan_aktif,pengantar_tugas,keterangan_lulus,hasil_studi',
            ]);

            if ($request->jenis_surat === 'keterangan_aktif') {
                $request->validate(['keperluan_aktif' => 'required']);
            } elseif ($request->jenis_surat === 'pengantar_tugas') {
                $request->validate([
                    'kode_mk' => 'required',
                    'nama_mk' => 'required',
                    'tujuan' => 'required',
                    'topik' => 'required',
                ]);
            } elseif ($request->jenis_surat === 'hasil_studi') {
                $request->validate(['keperluan_hasil_studi' => 'required']);
            }

            $surat->update($request->all());

            return redirect()->route('mahasiswa.surat')->with('success', 'Surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id_surat)
    {
        try {
            $surat = Surat::findOrFail($id_surat);

            if ($surat->status !== 'waiting') {
                return redirect()->route('mahasiswa.surat')->with('error', 'Surat tidak dapat dihapus setelah diproses.');
            }

            $surat->delete();

            return redirect()->route('mahasiswa.surat')->with('success', 'Surat berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.surat')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function indexKaprodi()
    {
        $user = Auth::user();

        if ($user->role != 'karyawan') {
            return redirect()->route('login.kaprodi')->with('error', 'Akses ditolak');
        }

        $karyawan = Karyawan::where('nik', $user->nik)->first();

        if (!$karyawan || $karyawan->jabatan != 'kaprodi') {
            return redirect()->route('login.kaprodi')->with('error', 'Akses ditolak');
        }

        // Kaprodi hanya melihat surat dari mahasiswa di prodinya
        $suratList = Surat::whereHas('mahasiswa', function ($query) use ($karyawan) {
            $query->where('prodi_id_prodi', $karyawan->prodi_id_prodi);
        })->get();

        return view('kaprodi.surat')
            ->with('suratList', $suratList);
    }

    public function updateStatus(Request $request, $id_surat)
    {
        $surat = Surat::findOrFail($id_surat);
        $validated = $request->validate([
            'status' => 'required|in:approved,declined',
            'catatan' => 'nullable|string',
        ]);

        $surat->tanggal_persetujuan = date('Y-m-d');
        $surat->status = $validated['status'];

        if ($request->has('catatan') && !empty($request->catatan)) {
            $surat->catatan = $request->catatan;
        }

        $surat->save();

        return redirect()->route('kaprodi.surat')->with('success', 'Status surat berhasil diperbarui.');
    }

    public function download($id_surat)
    {
        $surat = Surat::findOrFail($id_surat);

        if (!Storage::disk('public')->exists('surat/' . $surat->file)) {
            return redirect()->back()->with('error', 'File fisik tidak ditemukan.');
        }

        return Storage::disk('public')->download('surat/' . $surat->file);
    }

    public function previewPage($id)
    {
        $surat = Surat::findOrFail($id);

        return view('preview-surat', compact('surat'));
    }

    public function preview($id)
    {
        $surat = Surat::findOrFail($id);
        $filePath = storage_path('app/public/surat/' . $surat->file);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }

}
