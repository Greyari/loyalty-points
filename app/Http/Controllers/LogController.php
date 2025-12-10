<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query builder
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Search by module name atau user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Pagination, misal 10 log per halaman
        $histories = $query->paginate(10)->withQueryString();

        // Statistik singkat
        $statistics = [
            'total'      => ActivityLog::count(),
            'this_month' => ActivityLog::whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->count(),
            'today'      => ActivityLog::whereDate('created_at', now()->toDateString())->count(),
        ];

        $availableMonths = ActivityLog::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('log.log_activity', compact('histories', 'statistics', 'availableMonths'));
    }

    public function clearMonthly(Request $request)
    {
        $request->validate([
            'month_year' => 'required'
        ]);

        list($year, $month) = explode('-', $request->month_year);

        $deleted = ActivityLog::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->delete();

        if ($deleted > 0) {
            return back()->with('success', "Log bulan $month tahun $year berhasil dihapus ($deleted record)");
        }

        return back()->with('error', "Log tidak ditemukan!");
    }

    public function deleteThisMonth(Request $request)
    {
        try {
            $month = now()->month;
            $year = now()->year;

            // Hapus log bulan sekarang berdasarkan created_at
            ActivityLog::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->delete();

            return redirect()->route('log.index')
                ->with('success', 'Log bulan ini berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('log.index')
                ->with('info', 'Terjadi kesalahan saat menghapus log: ' . $e->getMessage());
        }
    }

}
