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
        
        // Get recent 10 recorded plates
        $recentPlates = PlateNumber::orderBy('date_time_scanned', 'desc')
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
            // Check if security_match column exists
            $hasSecurityMatch = Schema::hasColumn('plate_numbers', 'security_match');
            
            // Query for alerts - limit to recent ones only (last 24 hours)
            $query = DB::table('plate_numbers')
                ->orderBy('date_time_scanned', 'desc')
                ->where('date_time_scanned', '>=', now()->subHours(24));
            
            // If we have security_match column, filter by it
            if ($hasSecurityMatch) {
                $query->where('security_match', false);
            } else {
                // Otherwise, use location to identify security alerts
                $query->where('location', 'like', '%Security Alert%');
            }
            
            $securityAlerts = $query->limit(5)->get();
            
            return response()->json([
                'alerts' => $securityAlerts,
                'count' => $securityAlerts->count()
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