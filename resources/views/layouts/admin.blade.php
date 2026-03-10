<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Admin @yield('title')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --sub-primary: {{ $subSettings['primary_color'] ?? '#3B82F6' }};
            --sub-secondary: {{ $subSettings['secondary_color'] ?? '#F8FAFC' }};
            --sub-btn: {{ $subSettings['button_color'] ?? '#2563EB' }};
            --sub-btn-text: {{ $subSettings['button_text_color'] ?? '#FFFFFF' }};
            --sub-header-bg: {{ $subSettings['header_bg_color'] ?? '#FFFFFF' }};
            --sub-card-bg: {{ $subSettings['card_bg_color'] ?? '#FFFFFF' }};
            --sub-text: {{ $subSettings['text_color'] ?? '#111827' }};
            --sub-badge: {{ $subSettings['badge_color'] ?? '#3B82F6' }};
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex text-sm"
    style="background-color: var(--sub-secondary, #F8FAFC); color: var(--sub-text, #111827);">

    <!-- Sidebar -->
    <aside class="w-64 text-slate-300 min-h-screen hidden md:flex flex-col"
        style="background-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 20%, #1e293b); border-right: 1px solid color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, #334155);">
        <div class="h-16 flex items-center px-6 border-b"
            style="border-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, #334155);">
            @if (!empty($subSettings['logo_path']))
                <img src="{{ asset('storage/' . $subSettings['logo_path']) }}" alt="Logo"
                    class="h-8 object-contain mr-2 filter brightness-0 invert opacity-90">
            @else
                <i class="fa-solid fa-cube text-white mr-2 opacity-90"></i>
            @endif
            <span
                class="text-white font-bold text-lg tracking-wide">{{ $subSettings['app_name'] ?? 'SaaS Admin' }}</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('subscriptions.admin.dashboard') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.dashboard') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.dashboard') ? 'background-color: var(--sub-primary, #3B82F6);' : 'hover:background-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 15%, transparent);' }}">
                <i class="fa-solid fa-chart-pie w-6"></i>
                <span class="font-medium">Overview</span>
            </a>
            <a href="{{ route('subscriptions.admin.packages.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.packages.*') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.packages.*') ? 'background-color: var(--sub-primary, #3B82F6);' : '' }}">
                <i class="fa-solid fa-box-open w-6"></i>
                <span class="font-medium">Packages</span>
            </a>
            <a href="{{ route('subscriptions.admin.coupons.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.coupons.*') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.coupons.*') ? 'background-color: var(--sub-primary, #3B82F6);' : '' }}">
                <i class="fa-solid fa-ticket w-6"></i>
                <span class="font-medium">Coupons</span>
            </a>
            <a href="{{ route('subscriptions.admin.subscribers.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.subscribers.*') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.subscribers.*') ? 'background-color: var(--sub-primary, #3B82F6);' : '' }}">
                <i class="fa-solid fa-users w-6"></i>
                <span class="font-medium">Subscribers</span>
            </a>
            <a href="{{ route('subscriptions.admin.gateways.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.gateways.*') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.gateways.*') ? 'background-color: var(--sub-primary, #3B82F6);' : '' }}">
                <i class="fa-solid fa-credit-card w-6"></i>
                <span class="font-medium">Gateways</span>
            </a>
            <div class="border-t my-2"
                style="border-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, #334155);"></div>
            <a href="{{ route('subscriptions.admin.settings.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('subscriptions.admin.settings.*') ? 'text-white' : 'hover:text-white' }} rounded-xl transition duration-200"
                style="{{ request()->routeIs('subscriptions.admin.settings.*') ? 'background-color: var(--sub-primary, #3B82F6);' : '' }}">
                <i class="fa-solid fa-sliders w-6"></i>
                <span class="font-medium">Settings</span>
            </a>
        </nav>
        <div class="p-4 border-t" style="border-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, #334155);">
            <a href="{{ url('/') }}"
                class="flex items-center px-4 py-2 text-slate-400 hover:text-white transition duration-200">
                <i class="fa-solid fa-arrow-left w-6"></i>
                <span>Back to App</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-gray-50">
        <!-- Header -->
        <header
            class="h-16 flex items-center justify-between px-8 bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-500"><i class="fa-solid fa-user-circle text-2xl"></i></span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-8 flex-1 overflow-y-auto">
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
                    <i class="fa-solid fa-check-circle mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
                    <i class="fa-solid fa-exclamation-circle mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>

</html>
