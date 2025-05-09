<?php

namespace App\Http\Controllers;

use App\Services\SecurityApiService;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    protected $securityApi;

    public function __construct(SecurityApiService $securityApi)
    {
        $this->securityApi = $securityApi;
    }

    public function index()
    {
        $result = $this->securityApi->getAlerts();
        
        if ($result['success']) {
            $alerts = $result['alerts'];
            $unresolvedCount = collect($alerts)->where('resolved', false)->count();
            
            return view('security.index', [
                'alerts' => $alerts,
                'unresolvedCount' => $unresolvedCount
            ]);
        }
        
        return view('security.index', [
            'error' => $result['message'] ?? 'Unknown error'
        ]);
    }

    public function resolveAlert($alertId)
    {
        $result = $this->securityApi->resolveAlert($alertId);
        
        return redirect()->route('security.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function checkPlate(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string',
            'driver_name' => 'required|string'
        ]);
        
        $result = $this->securityApi->checkPlate(
            $request->plate_number,
            $request->driver_name
        );
        
        return redirect()->route('security.index')
            ->with('check_result', $result['data'] ?? [])
            ->with($result['success'] ? 'success' : 'error', $result['message'] ?? 'Plate checked');
    }
}