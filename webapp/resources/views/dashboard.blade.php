<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Hot Cars Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Hot Cars Statistics</h2>
                <div class="flex flex-wrap -mx-4">
                    <!-- Stats Cards -->
                    <div class="w-full md:w-1/4 px-4 mb-6 md:mb-0">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 ">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">Today's Hot Car/s Detected</h3>
                                <div class="bg-red-100 p-4 rounded-lg @if($todayhotcarCount > 0) animate-shake @endif">
                                    <p class="text-3xl font-bold text-red-800">{{ $todayhotcarCount }}</p>
                                    <p class="text-red-600">Detected Today</p>
                                </div>
                            </div>
                        
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">Total Hot Cars Dectected</h3>
                                <div class="bg-red-100 p-4 rounded-lg">
                                    <p class="text-3xl font-bold text-red-800">{{ $totalhotcarCount }}</p>
                                    <p class="text-red-600">All Time Detections</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="w-full md:w-3/4 px-4">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">Hot Cars - Last 7 Days</h3>
                                <canvas id="hotCarsChart" height="110"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Plates Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">All Plate Numbers</h2>
                <div class="flex flex-wrap -mx-4">
                    <!-- Stats Cards -->
                    <div class="w-full md:w-1/4 px-4 mb-6 md:mb-0">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">Today's Plates</h3>
                                <div class="bg-blue-100 p-4 rounded-lg">
                                    <p class="text-3xl font-bold text-blue-800">{{ $todayCount }}</p>
                                    <p class="text-blue-600">Scanned Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">Total Plates</h3>
                                <div class="bg-blue-100 p-4 rounded-lg">
                                    <p class="text-3xl font-bold text-blue-800">{{ $totalCount }}</p>
                                    <p class="text-blue-600">All Time Scans</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="w-full md:w-3/4 px-4">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">All Plates - Last 7 Days</h3>
                                <canvas id="allPlatesChart" height="110"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Plates Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Recent Plate Numbers</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Scanned</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Scanned</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car Color</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Face Detected</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Security</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentPlates as $plate)
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $plate->plate_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($plate->timestamp)->format('Y-m-d') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($plate->timestamp)->format('H:i:s') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plate->car_color ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plate->vehicle_type ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plate->face_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plate->status ?? $plate->login_status ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
    @if((isset($plate->is_mismatch) && $plate->is_mismatch))
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
            Security Alert
        </span>
    @else
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
            OK
        </span>
    @endif
</td>
                            </tr>
                        @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No plates found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/security-alerts.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    const hotCarsCtx = document.getElementById('hotCarsChart')?.getContext('2d');

    if (!hotCarsCtx) {
        console.error("Canvas element 'hotCarsChart' not found.");
        return;
    }

    const hotCarsData = @json($lastWeekDatahotcar);

    if (!Array.isArray(hotCarsData) || hotCarsData.length === 0) {
        console.warn("No data available for the hot cars chart.");
        return;
    }

    new Chart(hotCarsCtx, {
        type: 'bar',
        data: {
            labels: hotCarsData.map(item => new Date(item.date).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            })),
            datasets: [{
                label: 'Hot Cars Detected',
                data: hotCarsData.map(item => item.count),
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        }
    });
});


        // All Plates Chart
        const allPlatesCtx = document.getElementById('allPlatesChart').getContext('2d');
        const allPlatesData = @json($lastWeekData);
        
        new Chart(allPlatesCtx, {
            type: 'line',
            data: {
                labels: allPlatesData.map(item => new Date(item.date).toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric' 
                })),
                datasets: [{
                    label: 'All Plate Numbers Scanned',
                    data: allPlatesData.map(item => item.count),
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>