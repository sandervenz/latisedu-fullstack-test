<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pendataan Siswa') - Latis Education</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Compiled via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom DataTables Styling for Tailwind Integration */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #4b5563 !important;
            font-size: 0.875rem;
            margin-top: 1rem;
            margin-bottom: 0.75rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #f97316 !important;
            color: #ffffff !important;
            border: 1px solid #f97316 !important;
            border-radius: 0.5rem !important;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #ffedd5 !important;
            color: #c2410c !important;
            border: 1px solid #fdba74 !important;
            border-radius: 0.5rem !important;
        }
        table.dataTable thead th {
            border-bottom: 2px solid #e5e7eb !important;
            padding: 12px 16px !important;
            font-weight: 600;
            color: #374151;
            background-color: #f9fafb;
        }
        table.dataTable tbody td {
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #e5e7eb !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- SIDEBAR (Requirement #9: Terdiri dari Siswa, Profile, dan Logout) -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-30 transition-transform duration-300">
        <!-- Logo & Branding -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold text-xl">
                    L
                </div>
                <div>
                    <h1 class="font-bold text-base text-white leading-tight">Latis Education</h1>
                    <p class="text-xs text-orange-400 font-medium">& Tutor Indonesia</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <!-- 1. Menu Siswa -->
            <a href="{{ route('siswa.index') }}" 
               class="flex items-center px-3.5 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('siswa.*') ? 'bg-orange-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Siswa</span>
            </a>

            <!-- 2. Menu Profile (Requirement #10) -->
            <a href="{{ route('profile.index') }}" 
               class="flex items-center px-3.5 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'bg-orange-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profile</span>
            </a>
        </div>

        <!-- User Quick Info & 3. Logout (Requirement #9) -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <div class="flex items-center space-x-3 mb-3 px-2">
                @if(Auth::user()->image && file_exists(public_path('uploads/profile/' . Auth::user()->image)))
                    <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover ring-2 ring-orange-500">
                @else
                    <div class="w-9 h-9 rounded-full bg-orange-500/20 text-orange-400 ring-1 ring-orange-500 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->position ?? 'Kandidat' }}</p>
                </div>
            </div>

            <!-- Tombol Logout Bawah -->
            <a href="{{ route('logout') }}" 
               class="w-full flex items-center justify-center px-3 py-2 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-lg transition-colors cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col ml-64 min-w-0">
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-20 flex items-center justify-between px-8">
            <div>
                <h2 class="text-lg font-bold text-slate-800">@yield('header_title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500">@yield('header_subtitle', 'Portal Pendataan Siswa Latis Education & Tutor Indonesia')</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                    Sesi Aktif
                </span>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-8 pt-6">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-rose-800 bg-rose-50 border border-rose-200 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <div class="font-semibold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Mohon periksa kesalahan input berikut:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Main Page Content -->
        <main class="flex-1 px-8 py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="px-8 py-4 bg-white border-t border-slate-200 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Test Skill IT Fullstack - Latis Education & Tutor Indonesia.
        </footer>
    </div>

    <!-- Scripts (jQuery & DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>
</html>
