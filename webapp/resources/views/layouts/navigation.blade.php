<!-- Fixed Top Navigation Bar -->
<div class="fixed w-full top-0 z-50 bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo/Title -->
            <div class="flex items-center">
                <img src="{{ asset('images/itms_logo.png') }}" alt="ITMS Logo" class="h-9 w-auto mr-3">
                <h1 class="text-xl font-semibold text-gray-800">
                    ALPRIS
                </h1>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="flex space-x-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('security.index')" :active="request()->routeIs('security.*')">
                        {{ __('Security') }}
                    </x-nav-link>

                    <!-- Reports Dropdown -->
                       @if (Auth::user()->role !== 'Viewer') <!-- Check if the user is not a Viewer -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                                Reports
                                <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-30 bg-white rounded-md shadow-lg py-1 z-20">
                                <div>
                                <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Detected Vehicles
                                </x-nav-link>
                                </div>
                                <div>
                                <x-nav-link :href="route('hotcarreports')" :active="request()->routeIs('hotcarreports')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Hot Vehicles
                                </x-nav-link>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- User Management Dropdown -->
                    @if (Auth::user()->role !== 'Viewer') <!-- Check if the user is not a Viewer -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                                User Management
                                <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-30 bg-white rounded-md shadow-lg py-1 z-20">
                                <div>
                                <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    User Registration
                                </x-nav-link>
                                </div>
                                <div>
                                <x-nav-link :href="route('showregister')" :active="request()->routeIs('showregister')" class="block px-6 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    User Accounts
                                </x-nav-link>
                                </div>
                            </div>
                        </div>
                    @endif

                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        About
                    </x-nav-link>
                </div>
            </div>

            <!-- User Menu -->
            <div class="hidden sm:flex sm:items-center sm:ml-6" x-data="{ open: false }">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm font-semibold text-white">
                        {{ substr(Auth::user()->first_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->role }}</div>
                    </div>
                </div>
            
                <div class="relative ml-4">
                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
            
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                        <a href="{{ route('change-password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Change Password
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" aria-controls="mobile-menu" aria-expanded="false" onclick="toggleMobileMenu()">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu -->
<div class="fixed w-full top-16 z-40 sm:hidden" id="mobile-menu" style="display: none;">
    <div class="bg-white px-2 pt-2 pb-3 space-y-1 shadow-md">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-nav-link>

        <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
            Reports
        </x-nav-link>

        <!-- User Management Dropdown for Mobile -->
        @if (Auth::user()->role !== 'Viewer')
            <div class="relative">
                <button onclick="toggleUserManagementMenu()" class="w-full text-left px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                    User Management
                    <svg class="h-5 w-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu for Mobile -->
                <div id="user-management-mobile-menu" class="pl-4 space-y-1" style="display: none;">
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Register
                    </x-nav-link>
                    <x-nav-link :href="route('showregister')" :active="request()->routeIs('showregister')" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Users
                    </x-nav-link>
                </div>
            </div>
        @endif

        <x-nav-link :href="route('change-password')" :active="request()->routeIs('change-password')">
            Change Password
        </x-nav-link>

        <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
            About
        </x-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:text-red-600 transition-colors">
                Logout
            </button>
        </form>
    </div>
</div>

<!-- Spacer to push content below fixed nav -->
<div class="h-16"></div>

<!-- Page Heading -->
<header class="bg-blue-600 shadow-sm">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-center">
        <h1 class="text-lg font-bold text-white">
            AUTOMATED LICENSE PLATE RECOGNITION INFORMATION SYSTEM
        </h1>
    </div>
</header>

<!-- Main Content -->
<main>
    <!-- Your page content goes here -->
</main>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const isExpanded = mobileMenu.style.display === 'block';
        mobileMenu.style.display = isExpanded ? 'none' : 'block';
    }

    function toggleUserManagementMenu() {
        const userManagementMenu = document.getElementById('user-management-mobile-menu');
        const isExpanded = userManagementMenu.style.display === 'block';
        userManagementMenu.style.display = isExpanded ? 'none' : 'block';
    }
</script>