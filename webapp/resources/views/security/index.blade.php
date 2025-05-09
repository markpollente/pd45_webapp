<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Security Alerts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(isset($error))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <p>{{ $error }}</p>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Manual Plate Check Form -->
                    <div class="mb-8 p-4 bg-gray-100 dark:bg-gray-700 rounded">
                        <h3 class="font-semibold text-lg mb-4">Manual Plate Check</h3>
                        
                        <form action="{{ route('security.check-plate') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Plate Number
                                    </label>
                                    <input type="text" name="plate_number" id="plate_number" class="text-gray-700 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label for="driver_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Driver Name
                                    </label>
                                    <input type="text" name="driver_name" id="driver_name" class="text-gray-700 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Check Plate
                                </button>
                            </div>
                        </form>

                        @if(session('check_result'))
                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900 rounded">
                                <h4 class="font-semibold">Check Result:</h4>
                                <pre class="mt-2 text-sm">{{ json_encode(session('check_result'), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>

                    <!-- Security Alerts -->
                    <h3 class="font-semibold text-lg mb-4">
                        Security Alerts 
                        @if(isset($unresolvedCount) && $unresolvedCount > 0)
                            <span class="ml-2 px-2 py-1 text-xs bg-red-600 text-white rounded-full">{{ $unresolvedCount }}</span>
                        @endif
                    </h3>

                    @if(isset($alerts) && count($alerts) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plate Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered Driver</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detected Driver</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($alerts as $id => $alert)
                                        <tr class="{{ $alert['resolved'] ? '' : 'bg-red-50 dark:bg-red-900' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $alert['plate_number'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $alert['registered_driver'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $alert['detected_driver'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($alert['timestamp'])->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $alert['vehicle_color'] }} {{ $alert['vehicle_type'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($alert['resolved'])
                                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full dark:bg-green-700 dark:text-green-100">
                                                        Resolved
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full dark:bg-red-700 dark:text-red-100">
                                                        Unresolved
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                @if(!$alert['resolved'])
                                                    <form action="{{ route('security.resolve', $alert['id']) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100">
                                                            Resolve
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No security alerts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>