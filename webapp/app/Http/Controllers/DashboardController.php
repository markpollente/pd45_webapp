<?php

namespace App\Http\Controllers;

use App\Models\PlateNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Get today's Hot Cars count (assuming 'detected' is a column)
        $todayhotcarCount = PlateNumber::whereDate('date_time_scanned', $today)
            ->where('detected', 1)  // Assuming 1 means detected/hot car
            ->count();
        
        // Get total hot cars count
        $totalhotcarCount = PlateNumber::where('detected', 1)->count();

        
        // Get last 7 days Hot Cars data for the graph
        $lastWeekDatahotcar = PlateNumber::whereBetween('date_time_scanned', [
            $today->copy()->subDays(7),
            $today
        ])
        ->where('detected', 1)
        ->selectRaw('DATE(date_time_scanned) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        
        // Get today's all plate numbers count
        $todayCount = PlateNumber::whereDate('date_time_scanned', $today)->count();
        
        // Get total plate numbers count
        $totalCount = PlateNumber::count();
        
        // Get last 7 days data for the graph
        $lastWeekData = PlateNumber::whereBetween('date_time_scanned', [
            $today->copy()->subDays(7),
            $today
        ])
        ->selectRaw('DATE(date_time_scanned) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        $recentPlates = DB::table('parking_records')
            ->orderBy('timestamp', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard', compact(
            'todayCount',
            'totalCount',
            'todayhotcarCount',
            'totalhotcarCount',
            'lastWeekData',
            'recentPlates',
            'lastWeekDatahotcar'
        ));
    }
    
    public function checkSecurityAlerts()
    {
        try {
            // Query from parking_records table for mismatches
            $securityAlerts = DB::table('parking_records')
                ->where('is_mismatch', true)
                ->orderBy('timestamp', 'desc')
                ->where('timestamp', '>=', now()->subHours(24))
                ->limit(5)
                ->get();
            
            // Or if you prefer to use plate_numbers table instead
            $plateAlerts = DB::table('plate_numbers')
                ->where('security_match', false)
                ->orderBy('date_time_scanned', 'desc')
                ->where('date_time_scanned', '>=', now()->subHours(24))
                ->limit(5)
                ->get();
            
            // Combine results if using both tables
            $allAlerts = $securityAlerts->merge($plateAlerts ?? collect([]));
            
            return response()->json([
                'alerts' => $allAlerts,
                'count' => $allAlerts->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error checking for security alerts: ' . $e->getMessage());
            return response()->json([
                'alerts' => [],
                'count' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }
}