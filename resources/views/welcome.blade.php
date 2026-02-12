<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tender Management System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <style>
        /* Custom Animations - working in both Vite and CDN modes */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #2563eb, #9333ea);
        }
        
        /* Utility overrides to ensure visibility if JS fails */
        .opacity-0 { opacity: 0; }
        
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-x-hidden relative">

    <!-- Ambient Background Animation -->
    <div class="fixed inset-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">Tender<span class="text-blue-600">Sys</span></span>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    @auth
                        <a href="{{ route('dashboard', ['username' => \Illuminate\Support\Str::slug(Auth::user()->name)]) }}" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 hover:shadow-xl hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-0.5">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-sm font-medium mb-8 opacity-0 animate-fade-in-up">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                Next Gen Tender Management
            </div>

            <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold tracking-tight text-slate-900 mb-8 opacity-0 animate-fade-in-up" style="animation-delay: 0.1s;">
                Simplify Your <br class="hidden sm:block" />
                <span class="text-gradient">Bidding Process</span>
            </h1>

            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 mb-10 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s;">
                A powerful, secure, and intuitive platform to manage construction tenders, track bids, and streamline your entire workflow.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center opacity-0 animate-fade-in-up" style="animation-delay: 0.3s;">
                @auth
                     <a href="{{ route('dashboard', ['username' => \Illuminate\Support\Str::slug(Auth::user()->name)]) }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-slate-900 text-white font-semibold text-lg hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 hover:-translate-y-1">
                        Go into Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-xl shadow-blue-500/30 hover:-translate-y-1">
                        Start Free Trial
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white text-gray-700 border border-gray-200 font-semibold text-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-lg shadow-gray-200/50">
                        Learn More
                    </a>
                @endauth
            </div>
            
            <!-- Hero Dashboard Image Mockup -->
            <div class="mt-20 relative opacity-0 animate-fade-in-up" style="animation-delay: 0.5s">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent z-10"></div>
                <div class="rounded-xl bg-gray-900/5 p-2 sm:p-4 ring-1 ring-inset ring-gray-900/10 lg:rounded-2xl lg:p-5 max-w-5xl mx-auto">
                    <div class="rounded-lg bg-white overflow-hidden shadow-2xl ring-1 ring-gray-900/10">
                         <!-- Abstract UI Representation -->
                         <div class="h-64 sm:h-96 w-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative overflow-hidden">
                             <div class="absolute top-10 left-10 right-10 bottom-0 bg-white shadow-lg rounded-t-xl p-6">
                                 <div class="flex gap-4 mb-6">
                                     <div class="w-1/4 h-24 bg-blue-50 rounded-lg animate-pulse"></div>
                                     <div class="w-1/4 h-24 bg-purple-50 rounded-lg animate-pulse delay-75"></div>
                                     <div class="w-1/4 h-24 bg-pink-50 rounded-lg animate-pulse delay-150"></div>
                                     <div class="w-1/4 h-24 bg-green-50 rounded-lg animate-pulse delay-200"></div>
                                 </div>
                                 <div class="space-y-3">
                                     <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                                     <div class="h-4 bg-gray-50 rounded w-1/2"></div>
                                     <div class="h-4 bg-gray-50 rounded w-5/6"></div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Features Grid -->
    <div id="features" class="py-24 relative bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-blue-600 font-bold tracking-wide uppercase">Core Features</h2>
                <h3 class="mt-2 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl">Everything you need to succeed</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <!-- Feature 1 -->
                <div class="group relative p-8 bg-white rounded-3xl border border-gray-100 hover:border-blue-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity blur-2xl"></div>
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Smart Tracking</h4>
                    <p class="text-gray-500 leading-relaxed">
                        Monitor every tender's lifecycle in real-time. Automated status updates keep your team aligned.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group relative p-8 bg-white rounded-3xl border border-gray-100 hover:border-purple-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1">
                     <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-purple-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity blur-2xl"></div>
                    <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Secure Bidding</h4>
                    <p class="text-gray-500 leading-relaxed">
                        Enterprise-grade encryption ensures your bid data remains confidential and tamper-proof.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group relative p-8 bg-white rounded-3xl border border-gray-100 hover:border-pink-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-pink-500/10 transition-all duration-300 hover:-translate-y-1">
                     <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-pink-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity blur-2xl"></div>
                    <div class="w-14 h-14 bg-pink-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Analytics & Insights</h4>
                    <p class="text-gray-500 leading-relaxed">
                        Visualize your performance with detailed analytics. Make data-driven decisions to win more bids.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <div class="flex items-center gap-2 mb-4">
                 <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">TS</div>
                 <span class="text-lg font-bold text-gray-900">Tender Management System</span>
            </div>
            <p class="text-gray-400 text-sm mb-6">Built for precision and performance.</p>
            <div class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
