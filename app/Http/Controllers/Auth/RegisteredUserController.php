<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create()
    {
        $prodiList = Prodi::all();
        return view('auth.register-tu', compact('prodiList'));
    }

    /**
     * Handle a registration request.
     */
    public function store(Request $request)
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
            return redirect()->route('tu.dashboard');
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }
}
