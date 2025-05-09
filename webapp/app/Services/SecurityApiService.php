<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SecurityApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SECURITY_API_URL', 'http://localhost:5000');
    }

    public function getAlerts()
    {
        try {
            $response = Http::get("{$this->baseUrl}/alerts");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'alerts' => $response->json()['alerts'] ?? []
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch alerts: ' . $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error connecting to security API: ' . $e->getMessage()
            ];
        }
    }
    public function resolveAlert($alertId)
    {
        try {
            $response = Http::post("{$this->baseUrl}/alerts/{$alertId}/resolve");
            
            Log::info("Alert resolve response:", [
                'status' => $response->status(),
                'body' => $response->body(),
                'alertId' => $alertId
            ]);
                
            return [
                'success' => $response->successful(),
                'message' => $response->successful() 
                    ? 'Alert resolved successfully' 
                    : 'Failed to resolve alert: ' . $response->status() . ' - ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Failed to resolve security alert: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error connecting to security API: ' . $e->getMessage()
            ];
        }
    }

    public function checkPlate($plateNumber, $faceName)
    {
        try {
            $response = Http::post("{$this->baseUrl}/check_plate", [
                'plate_number' => $plateNumber,
                'face_name' => $faceName
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'message' => $response->successful() ? null : 'API error: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Failed to check plate: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error connecting to security API: ' . $e->getMessage()
            ];
        }
    }
}