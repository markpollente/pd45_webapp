<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\EnsureTwoFactorVerified;
// Add with your other use statements at the top of the file
use App\Http\Controllers\SecurityController;

// Auto-login route - for development only
Route::get('/dev-login', function() {
    // Find a user to log in as
    $user = \App\Models\User::first();
    
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard');
    }
    
    // If no users exist, create a default admin user with ALL required fields
    $user = \App\Models\User::create([
        'name' => 'Admin User',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'Admin',
        'rank' => 'Administrator', // Added missing rank field
        'two_factor_enabled' => 0, // Adding this as it might be required
        'two_factor_secret' => null, // Adding this as it might be required
        'remember_token' => null, // Adding this as it might be required
        'email_verified_at' => now(), // Adding this as it might be required
    ]);
    
    \Illuminate\Support\Facades\Auth::login($user);
    session(['auth.password_confirmed_at' => time()]);
    session(['2fa.verified' => true]);
    
    return redirect()->route('dashboard');
});

// Public Routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes (from auth.php)
require __DIR__.'/auth.php';

// 2FA Routes (protected by auth but don't need 2fa.verified)
Route::middleware('auth')->group(function () {
    Route::get('/2fa/setup', [TwoFactorController::class, 'showSetupForm'])
        ->name('2fa.setup');
    
    Route::get('/2fa/verify', [TwoFactorController::class, 'showVerifyForm'])
        ->name('2fa.verify');
    
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])
        ->name('2fa.verify.submit');

    Route::post('/2fa/reset/{user}', [RegisteredUserController::class, 'reset2fa'])
        ->name('2fa.reset');

    Route::post('/2fa/cancel', [TwoFactorController::class, 'cancel'])
        ->name('2fa.cancel');

});

// Fully Protected Routes (require both auth and 2FA verification)
//Route::middleware(['auth', EnsureTwoFactorVerified::class])->group(function () {
    // Main Application Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::view('/maintenance', 'maintenance')->name('maintenance');
    Route::view('/about', 'about')->name('about');
    Route::get('/hotcarreports', [ReportController::class, 'hotcarreports'])->name('hotcarreports');
    
    // Password Change
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])
        ->name('change-password');
    Route::put('/change-password-update', [ChangePasswordController::class, 'update'])
        ->name('change-password-update');
    
    // User Management
    Route::get('showregister', [RegisteredUserController::class, 'showregister'])
        ->name('showregister');
    Route::post('createregister', [RegisteredUserController::class, 'createregister'])
        ->name('createregister');
    Route::get('register', [RegisteredUserController::class, 'register'])
        ->name('register');
    Route::put('/users/{user}/reset-password', [RegisteredUserController::class, 'resetPassword'])
        ->name('reset.password');
    Route::put('/users/{user}/change-role', [RegisteredUserController::class, 'changeRole'])
        ->name('change.role');
    Route::delete('/users/{user}', [RegisteredUserController::class, 'destroy'])
        ->name('delete.user');
//});


Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
Route::put('/security/resolve/{alertId}', [SecurityController::class, 'resolveAlert'])->name('security.resolve');
Route::post('/security/check-plate', [SecurityController::class, 'checkPlate'])->name('security.check-plate');
Route::get('/check-security-alerts', [DashboardController::class, 'checkSecurityAlerts'])->name('dashboard.checkSecurityAlerts');

Route::get('/test-security-alert-fixed', function() {
    try {
        // Insert a test plate with only the columns that exist
        $plateId = DB::table('plate_numbers')->insertGetId([
            'plate_number' => 'SECURITY-112',
            'date_time_scanned' => now(),
            'detected' => 1, // Using 1 for true
            'created_at' => now(),
            'updated_at' => now(),
            'location' => 'Security Alert - Unauthorized Driver'
        ]);
        
        // Also add security_match column if it's missing
        if (!Schema::hasColumn('plate_numbers', 'security_match')) {
            Schema::table('plate_numbers', function($table) {
                $table->boolean('security_match')->default(true)->after('detected');
            });
            
            // After adding the column, update this record
            DB::table('plate_numbers')
                ->where('id', $plateId)
                ->update(['security_match' => false]);
        }
        
        return redirect('/dashboard')->with('status', 'Test security alert created with ID: ' . $plateId);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});
