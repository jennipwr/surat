<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->nrp;

        // Get counts for each status (align status names with your database)
        $pendingCount = Surat::where('mahasiswa_nrp', $userId)
            ->where('status', 'waiting')
            ->count();

        $approvedCount = Surat::where('mahasiswa_nrp', $userId)
            ->where('status', 'approved')
            ->count();

        $rejectedCount = Surat::where('mahasiswa_nrp', $userId)
            ->where('status', 'declined')
            ->count();

        // Get the latest 5 letter submissions for the table
        $latestSubmissions = Surat::where('mahasiswa_nrp', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($latestSubmissions as $submission) {
            switch ($submission->jenis_surat) {
                case 'keterangan_aktif':
                    $submission->formatted_jenis = 'Keterangan aktif';
                    break;
                case 'pengantar_tugas':
                    $submission->formatted_jenis = 'Pengantar tugas';
                    break;
                case 'hasil_studi':
                    $submission->formatted_jenis = 'Laporan hasil studi';
                    break;
                case 'keterangan_lulus':
                    $submission->formatted_jenis = 'Keterangan lulus';
                    break;
                default:
                    $submission->formatted_jenis = ucfirst(str_replace('_', ' ', $submission->jenis_surat));
            }
        }

        return view('mahasiswa.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'latestSubmissions'
        ));
    }

    /**
     * Display all letter submissions for the current student
     *
     * @return \Illuminate\View\View
     */
    public function allSubmissions()
    {
        $userId = Auth::user()->id;

        $submissions = Surat::where('mahasiswa_nrp', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($submissions as $submission) {
            switch ($submission->jenis_surat) {
                case 'keterangan_aktif':
                    $submission->formatted_jenis = 'Surat Keterangan Aktif';
                    break;
                case 'pengantar_tugas':
                    $submission->formatted_jenis = 'Surat Pengantar Tugas';
                    break;
                case 'hasil_studi':
                    $submission->formatted_jenis = 'Surat Hasil Studi';
                    break;
                case 'keterangan_lulus':
                    $submission->formatted_jenis = 'Surat Keterangan Lulus';
                    break;
                default:
                    $submission->formatted_jenis = ucfirst(str_replace('_', ' ', $submission->jenis_surat));
            }
        }

        return view('mahasiswa.surat', compact('submissions'));
    }

    /**
     * Display details of a specific letter submission
     *
     * @param string $id_surat
     * @return \Illuminate\View\View
     */
    public function showSubmission($id_surat)
    {
        $userId = Auth::user()->nrp;

        $submission = Surat::where('id_surat', $id_surat)
            ->where('mahasiswa_nrp', $userId)
            ->firstOrFail();

        // Format jenis_surat for display
        switch ($submission->jenis_surat) {
            case 'keterangan_aktif':
                $submission->formatted_jenis = 'Surat Keterangan Aktif';
                break;
            case 'pengantar_tugas':
                $submission->formatted_jenis = 'Surat Pengantar Tugas';
                break;
            case 'hasil_studi':
                $submission->formatted_jenis = 'Surat Hasil Studi';
                break;
            case 'keterangan_lulus':
                $submission->formatted_jenis = 'Surat Keterangan Lulus';
                break;
            default:
                $submission->formatted_jenis = ucfirst(str_replace('_', ' ', $submission->jenis_surat));
        }

        return view('mahasiswa.surat', compact('submission'));
    }

    public function download($id_surat)
    {
        $surat = Surat::findOrFail($id_surat);

        if (!Storage::disk('public')->exists('surat/' . $surat->file)) {
            return redirect()->back()->with('error', 'File fisik tidak ditemukan.');
        }

        return Storage::disk('public')->download('surat/' . $surat->file);
    }
}
