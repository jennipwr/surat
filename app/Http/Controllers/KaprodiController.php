<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KaprodiController extends Controller
{
    /**
     * Display the TU dashboard
     */
    public function index()
    {
        // Get current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Calculate statistics for the dashboard
        $yearlyTotal = $this->getYearlyTotal($currentYear);
        $yearlyGrowth = $this->calculateYearlyGrowth($currentYear);
        $monthlyTotal = $this->getMonthlyTotal($currentYear, $currentMonth);

        // Get status counts
        $statusCounts = $this->getStatusCounts();

        // Get monthly data for chart
        $monthlyData = $this->getMonthlyData($currentYear);

        // Get latest submissions
        $latestSubmissions = $this->getLatestSubmissions();

        return view('kaprodi.dashboard', [
            'yearlyTotal' => $yearlyTotal,
            'yearlyGrowth' => $yearlyGrowth,
            'monthlyTotal' => $monthlyTotal,
            'pendingCount' => $statusCounts['waiting'],
            'approvedCount' => $statusCounts['approved'],
            'rejectedCount' => $statusCounts['declined'],
            'monthlySubmissions' => $monthlyData['submissions'],
            'months' => $monthlyData['months'],
            'latestSubmissions' => $latestSubmissions
        ]);
    }

    /**
     * Get total submissions for the current year
     */
    private function getYearlyTotal($year)
    {
        return Surat::whereYear('created_at', $year)->count();
    }

    /**
     * Calculate growth percentage compared to previous year
     */
    private function calculateYearlyGrowth($currentYear)
    {
        $currentYearCount = $this->getYearlyTotal($currentYear);
        $previousYearCount = Surat::whereYear('created_at', $currentYear - 1)->count();

        if ($previousYearCount == 0) {
            return 100; // If no submissions last year, consider it 100% growth
        }

        return round((($currentYearCount - $previousYearCount) / $previousYearCount) * 100, 1);
    }

    /**
     * Get total submissions for the current month
     */
    private function getMonthlyTotal($year, $month)
    {
        return Surat::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
    }

    /**
     * Get counts for each status
     */
    private function getStatusCounts()
    {
        $pending = Surat::where('status', 'waiting')->count();
        $approved = Surat::where('status', 'approved')->count();
        $rejected = Surat::where('status', 'declined')->count();

        return [
            'waiting' => $pending,
            'approved' => $approved,
            'declined' => $rejected
        ];
    }

    /**
     * Get monthly data for chart
     */
    private function getMonthlyData($year)
    {
        $monthlyData = Surat::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Make sure we have data for all months (1-12)
        $submissions = [];
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $submissions[] = $monthlyData[$i] ?? 0;
            $months[] = Carbon::create()->month($i)->format('M');
        }

        return [
            'submissions' => $submissions,
            'months' => $months
        ];
    }

    /**
     * Get latest submissions for the table
     */
    private function getLatestSubmissions()
    {
        return Surat::with('mahasiswa') // Assuming there's a relationship with Student model
        ->latest()
            ->take(10)
            ->get();
    }

    /**
     * Filter submissions by date range
     */
    public function filterByDate(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $submissions = Surat::whereBetween('created_at', [$startDate, $endDate])
            ->with('mahasiswa')
            ->get();

        return response()->json([
            'surat' => $submissions
        ]);
    }

    /**
     * Filter submissions by month
     */
    public function filterByMonth(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month;

        $monthlyData = Surat::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        // Get number of days in the selected month
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $submissions = [];
        $days = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $submissions[] = $monthlyData[$i] ?? 0;
            $days[] = $i;
        }

        return response()->json([
            'submissions' => $submissions,
            'days' => $days
        ]);
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
