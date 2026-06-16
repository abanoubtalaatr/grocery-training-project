@php
    $activeClass = 'bg-grocery-900 text-white dark:bg-emerald-500/20 dark:text-emerald-300 dark:border dark:border-emerald-500/30';
    $inactiveClass = 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/5';
@endphp
<!DOCTYPE html>
<html class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('grocery_panel'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        grocery: { 
                            50: '#f3f6f8', 
                            100: '#e3ebf0', 
                            800: '#005580',
                            900: '#003b5c', 
                            950: '#0d1114' 
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Cairo', 'Segoe UI', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
    </style>
</head>
<body class="h-full bg-grocery-50 text-slate-800 dark:bg-gradient-to-br dark:from-grocery-950 dark:via-[#003b5c] dark:to-[#001d2e] dark:text-white transition-colors duration-200">
    
    <div class="h-full flex overflow-hidden">

        <aside id="admin-sidebar" class="fixed inset-y-0 start-0 z-40 w-64 transform -translate-x-full lg:translate-x-0 lg:static flex flex-col shrink-0 bg-white border-e border-slate-200/80 dark:bg-grocery-950/40 dark:backdrop-blur-md dark:border-white/10 transition-transform duration-300 ease-in-out">
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200/80 dark:border-white/10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-cyan-400 text-grocery-950 font-black text-sm shadow-md">
                        G+
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white text-base tracking-wide">{{ __('grocery_panel') }}</span>
                </div>
                <button onclick="toggleSidebar()" aria-label="Close Sidebar" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-white/40 mb-3 px-2">
                        <span>{{ __('main_panel') }}</span>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                            </svg>
                            <span class="text-sm font-medium">
                                <span>{{ __('overview_dashboard') }}</span>
                            </span>
                        </div>
                    </a>
                </div>
                
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-white/40 mb-3 px-2">
                        <span>{{ __('database_models') }}</span>
                    </div>
                    <div class="space-y-1">
                        @foreach($models as $m)
                            <a href="{{ route('admin.model.list', $m['name']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all duration-200 {{ isset($model) && $model === $m['name'] ? $activeClass : $inactiveClass }}">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <svg class="w-4 h-4 shrink-0 text-slate-400 dark:text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span class="truncate font-medium">{{ $m['name'] }}</span>
                                </div>
                                <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold dark:bg-white/10 dark:text-white/50 shrink-0">
                                    {{ number_format($m['count']) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-slate-200/80 dark:border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-r from-emerald-400 to-cyan-500 flex items-center justify-center font-bold text-grocery-950 text-sm shadow-md shrink-0">
                        {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-semibold text-xs text-slate-800 dark:text-white truncate">{{ auth()->user()->username }}</div>
                        <div class="text-[10px] text-slate-400 dark:text-white/40 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Logout" title="{{ __('logout') }}" class="p-2 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>
        

        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden lg:hidden"></div>


        <div class="flex-1 flex flex-col min-h-0 overflow-hidden relative">
            

            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-slate-200/80 dark:bg-grocery-950/20 dark:backdrop-blur-md dark:border-white/10 shrink-0">
                <div class="flex items-center gap-4">

                    <button onclick="toggleSidebar()" aria-label="Open Sidebar" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="text-sm font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-grocery-800 dark:hover:text-emerald-400 transition">
                            <span>{{ __('dashboard') }}</span>
                        </a>
                        @yield('breadcrumb')
                    </div>
                </div>
                

                <div class="flex items-center gap-3">
                    

                    <button onclick="toggleDirection()" aria-label="Change Language" class="flex items-center gap-1.5 bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/10 px-3 py-1.5 rounded-xl transition text-xs font-semibold hover:brightness-105">
                        <svg class="w-4 h-4 text-slate-600 dark:text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.313 1.565-.953 3.051-1.879 4.387M11.24 9.018a16.03 16.03 0 01-2.905 4.474m-2.9-4.474A16.083 16.083 0 011.398 8.5"/>
                        </svg>
                        <span>{{ __('lang_toggle') }}</span>
                    </button>
                    

                    <button onclick="toggleTheme()" aria-label="Toggle Theme" class="p-2 rounded-xl bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 hover:brightness-105 transition">

                        <svg id="theme-icon-sun" class="w-4 h-4 hidden dark:block text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464-4.95a1 1 0 111.414 1.414L14.12 5.05a1 1 0 01-1.414-1.414l.83-.83zm-9.072 0l-.83-.83A1 1 0 001.707 2.707l.83.83a1 1 0 101.414-1.414zm1.414 10.464a1 1 0 010 1.414l-.83.83a1 1 0 11-1.414-1.414l.83-.83a1 1 0 011.414 0zm9.072 0l.83.83a1 1 0 001.414-1.414l-.83-.83a1 1 0 10-1.414 1.414zM17 10a1 1 0 11-2 0h-1a1 1 0 110-2h1a1 1 0 112 0zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>

                        <svg id="theme-icon-moon" class="w-4 h-4 block dark:hidden text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                    </button>
                    
                </div>
            </header>
            

            <main class="flex-1 overflow-y-auto px-6 py-6 min-h-0">
                @yield('content')
            </main>
        </div>
        
    </div>

    @yield('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
