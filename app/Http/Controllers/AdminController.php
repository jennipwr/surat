<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        return view('admin.dashboard');
    }

    public function listKaryawan()
    {
        $karyawan = Karyawan::with('prodi')->get();
        return view('admin.list-karyawan', compact('karyawan'));
    }

    public function listMahasiswa()
    {
        $mhs = Mahasiswa::with('prodi')->get();
        return view('admin.list-mhs', compact('mhs'));
    }

    public function editMahasiswa($nrp)
    {
        $mahasiswa = Mahasiswa::where('nrp', $nrp)->firstOrFail();
        $prodis = Prodi::all();

        return view('admin.edit-mhs', compact('mahasiswa', 'prodis'));
    }

    /**
     * Update mahasiswa data
     */
    public function updateMahasiswa(Request $request, $nrp)
    {
        $mahasiswa = Mahasiswa::where('nrp', $nrp)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'semester' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa,email,' . $mahasiswa->nrp . ',nrp',
            'prodi_id_prodi' => 'required|exists:prodi,id_prodi',
        ]);

        $mahasiswa->update($request->all());

        return redirect()->route('admin.listMahasiswa')
            ->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    /**
     * Delete mahasiswa data
     */
    public function deleteMahasiswa($nrp)
    {
        $mahasiswa = Mahasiswa::where('nrp', $nrp)->firstOrFail();
        $mahasiswa->delete();

        return redirect()->route('admin.listMahasiswa')
            ->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    public function editKaryawan($nik)
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $prodis = Prodi::all();

        return view('admin.edit-karyawan', compact('karyawan', 'prodis'));
    }

    /**
     * Update karyawan data
     */
    public function updateKaryawan(Request $request, $nik)
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawan,email,' . $karyawan->nik . ',nik',
            'jabatan' => 'required|string',
            'prodi_id_prodi' => 'required|exists:prodi,id_prodi',
        ]);

        $karyawan->update($request->all());

        return redirect()->route('admin.listKaryawan')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Delete karyawan data
     */
    public function deleteKaryawan($nik)
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $karyawan->delete();

        return redirect()->route('admin.listKaryawan')
            ->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function createUser()
    {
        $prodiList = Prodi::all();
        return view('auth.register-admin', compact('prodiList'));
    }

    /**
     * Handle a registration request.
     */
    public function storeUser(Request $request)
    {
//        dd($request->all());
        try {
            $request->validate([
                'role' => 'required',
            ]);

            if ($request->role === 'mahasiswa'){
                $request->validate([
                    'nama_mhs' => 'required|string|max:255',
                    'email_mhs' => 'required|email|unique:users,email',
                    'password_mhs' => 'required|string|min:6|confirmed',
                    'prodi_mhs' => 'required',
                    'nrp' => 'required|string|unique:mahasiswa,nrp',
                    'alamat' => 'required|string|max:255',
                    'semester' => 'required|integer|min:1',
                ]);

                Mahasiswa::create([
                    'nrp' => $request->nrp,
                    'nama' => $request->nama_mhs,
                    'password' => Hash::make($request->password_mhs),
                    'alamat' => $request->alamat,
                    'semester' => $request->semester,
                    'email' => $request->email_mhs,
                    'prodi_id_prodi' => $request->prodi_mhs,
                ]);
            } else {
                $request->validate([
                    'nama_karyawan' => 'required|string|max:255',
                    'email_karyawan' => 'required|email|unique:users,email',
                    'password_karyawan' => 'required|string|min:6|confirmed',
                    'prodi_karyawan' => 'required',
                    'nik' => 'required|string|max:7|unique:karyawan,nik',
                    'jabatan' => 'required|string',
                ]);

                Karyawan::create([
                    'nik' => $request->nik,
                    'nama' => $request->nama_karyawan,
                    'email' => $request->email_karyawan,
                    'jabatan' => $request->jabatan,
                    'prodi_id_prodi' => $request->prodi_karyawan,
                ]);
            }

            $user = User::create([
                'nama' => $request->nama_mhs ?? $request->nama_karyawan,
                'nrp' => $request->nrp ?? null,
                'nik' => $request->nik ?? null,
                'role' => $request->role,
                'email' => $request->email_mhs ?? $request->email_karyawan,
                'password' => Hash::make($request->password_mhs ?? $request->password_karyawan),
            ]);

            session()->flash('success', "Akun {$request->role} berhasil dibuat!");
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }
}
