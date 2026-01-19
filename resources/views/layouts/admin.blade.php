<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Gawean</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        .sidebar {
            transition: transform 0.3s ease, width 0.3s ease;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }
        
        /* Main content scrollable */
        .main-content {
            height: 100vh;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Fixed, tidak ikut scroll -->
        <aside id="sidebar" class="sidebar fixed lg:sticky top-0 z-40 w-64 h-screen bg-white border-r border-gray-200 shadow-lg lg:shadow-none flex flex-col">
            <!-- Logo -->
            <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl">
                    <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-6 h-6">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Gawean</h1>
                    <p class="text-xs text-gray-500">Admin Panel</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('users.*') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Users</span>
                </a>
                
                <a href="{{ route('user-profiles.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user-profiles.*') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-id-card w-5"></i>
                    <span class="font-medium">User Profiles</span>
                </a>
                
                <a href="{{ route('company-profiles.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('company-profiles.*') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-building w-5"></i>
                    <span class="font-medium">Company Profiles</span>
                </a>
                
                <a href="{{ route('jobs.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('jobs.*') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-briefcase w-5"></i>
                    <span class="font-medium">Jobs</span>
                </a>
            </nav>
            
            <!-- User Info -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->nama ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->nama ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>
        
        <!-- Main Content - Scrollable -->
        <main class="flex-1 main-content">
            <!-- Top Navbar - Sticky dalam content area -->
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                    <div class="flex items-center gap-4">
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-sm text-gray-500">@yield('page-subtitle', 'Selamat datang di panel admin')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(Auth::user()->nama ?? 'A', 0, 1) }}
                                </div>
                                <span class="hidden md:block font-medium text-gray-700">{{ Auth::user()->nama ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" id="dropdownArrow"></i>
                            </button>
                            
                            <div id="userDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="p-4 lg:p-8">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-primary-50 border border-primary-200 text-primary-700 rounded-xl flex items-center gap-3" id="successAlert">
                        <i class="fas fa-check-circle text-primary-500"></i>
                        <span>{{ session('success') }}</span>
                        <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-primary-500 hover:text-primary-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3" id="errorAlert">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ session('error') }}</span>
                        <button onclick="document.getElementById('errorAlert').remove()" class="ml-auto text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        // Store token in localStorage if provided
        @if(session('token'))
            localStorage.setItem('auth_token', '{{ session('token') }}');
        @endif
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }
        
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            dropdown.classList.toggle('show');
            
            // Toggle arrow direction
            if (dropdown.classList.contains('show')) {
                arrow.style.transform = 'rotate(180deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.getElementById('dropdownArrow');
            const button = event.target.closest('button');
            
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (successAlert) successAlert.remove();
            if (errorAlert) errorAlert.remove();
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
