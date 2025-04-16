<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function createMahasiswa(): View
    {
        return view('auth.login-mahasiswa');
    }

    public function createKaprodi(): View
    {
        return view('auth.login-kaprodi');
    }

    public function createTU(): View
    {
        return view('auth.login-tu');
    }

    public function createAdmin(): View
    {
        return view('auth.login-admin');
    }

    public function storeAdmin(Request $request)
    {
        try {
            $request->validate([
                'nik' => ['required'],
                'password' => ['required'],
            ]);


            if (Auth::attempt(['nik' => $request->nik, 'password' => $request->password])) {
                $request->session()->regenerate();
                return $this->redirectToDashboard();
            }

            return back()->withErrors([
                'nik' => 'NIK atau Password Salah.',
            ]);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    /**
     * Handle an incoming authentication request.
     */
//    public function store(LoginRequest $request): RedirectResponse
//    {
//        $request->authenticate();
//
//        $request->session()->regenerate();
//
//        return redirect()->intended(route('dashboard', absolute: false));
//    }

    public function storeMahasiswa(Request $request)
    {
        try {
            $request->validate([
                'nrp' => ['required'],
                'password' => ['required'],
            ]);


            if (Auth::attempt(['nrp' => $request->nrp, 'password' => $request->password])) {
                $request->session()->regenerate();
                return $this->redirectToDashboard();
            }

            return back()->withErrors([
                'nrp' => 'NRP atau Password Salah.',
            ]);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    public function storeKaprodi(Request $request)
    {
        try {
            $request->validate([
                'nik' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt(['nik' => $request->nik, 'password' => $request->password])) {
                $request->session()->regenerate();
                return $this->redirectToDashboard();
            }

            return back()->withErrors([
                'nik' => 'NIK atau Password Salah.',
            ]);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    public function storeTU(Request $request)
    {
        try {
            $request->validate([
                'nik' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt(['nik' => $request->nik, 'password' => $request->password])) {
                $request->session()->regenerate();
                return $this->redirectToDashboard();
            }

            return back()->withErrors([
                'nik' => 'NIK atau Password Salah.',
            ]);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    protected function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->role == 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        } elseif ($user->role == 'karyawan') {
            $karyawan = $user->karyawan;

            if ($karyawan) {
                if ($karyawan->jabatan == 'kaprodi') {
                    return redirect()->route('kaprodi.dashboard');
                } elseif ($karyawan->jabatan == 'tu') {
                    return redirect()->route('tu.dashboard');
                }
            }
        } elseif ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
