<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subSettings['app_name'] ?? 'Subscription Plans' }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

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

<body style="background-color: var(--sub-secondary, #F8FAFC); color: var(--sub-text, #111827);"
    class="antialiased min-h-screen flex flex-col">

    <nav style="background-color: var(--sub-header-bg, #FFFFFF);"
        class="border-b border-gray-200 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    @if (!empty($subSettings['logo_path']))
                        <img src="{{ asset('storage/' . $subSettings['logo_path']) }}" alt="Logo"
                            class="h-8 object-contain">
                    @else
                        <i class="fa-solid fa-cube text-xl" style="color: var(--sub-primary, #3B82F6);"></i>
                    @endif
                    <span class="text-xl font-bold" style="color: var(--sub-text, #111827);">
                        {{ $subSettings['app_name'] ?? 'SaaS App' }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('subscriptions.pricing') }}"
                        class="text-gray-500 hover:text-gray-900 font-medium">Pricing</a>
                    <a href="{{ route('subscriptions.my') }}" class="text-gray-500 hover:text-gray-900 font-medium">My
                        Subscription</a>
                    @if (auth()->check())
                        <span class="font-medium px-3 py-1 rounded-full text-sm border"
                            style="color: var(--sub-primary, #3B82F6); background-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, white); border-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 20%, white);">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-8 flex items-center shadow-sm max-w-3xl mx-auto">
                <i class="fa-solid fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-8 flex items-center shadow-sm max-w-3xl mx-auto">
                <i class="fa-solid fa-exclamation-circle mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ $subSettings['app_name'] ?? 'SaaS App' }}. All rights reserved.
        </div>
    </footer>
</body>

</html>
