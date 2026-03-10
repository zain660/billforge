@extends('subscriptions::layouts.admin')

@section('title', '| Settings')
@section('page_title', 'Pricing Page Settings')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('subscriptions.admin.settings.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf

            {{-- App Identity --}}
            <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><i class="fa-solid fa-id-card text-blue-500 mr-1"></i>
                        App Identity</h3>
                    <p class="text-sm text-gray-500">Set the app name and logo shown on the public pricing page.</p>
                </div>

                <div class="md:w-2/3 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">App Name</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? '') }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg px-4 py-2.5 outline-none focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="My SaaS App">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                        @if (!empty($settings['logo_path']))
                            <div class="mb-3 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo"
                                    class="h-12 object-contain rounded border border-gray-200 p-1 bg-white">
                                <span class="text-xs text-gray-500">Current logo</span>
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*"
                            class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG up to 2MB</p>
                    </div>
                </div>
            </div>

            {{-- Color Palette --}}
            <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row gap-8 bg-slate-50">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><i
                            class="fa-solid fa-palette text-purple-500 mr-1"></i> Color Palette</h3>
                    <p class="text-sm text-gray-500">Customize the colors used throughout the pricing page.</p>
                </div>

                <div class="md:w-2/3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        @php
                            $colorFields = [
                                'primary_color' => [
                                    'label' => 'Primary / Accent Color',
                                    'default' => '#3B82F6',
                                    'hint' => 'Used for badges, highlights',
                                ],
                                'secondary_color' => [
                                    'label' => 'Secondary / Background',
                                    'default' => '#F8FAFC',
                                    'hint' => 'Page background',
                                ],
                                'button_color' => [
                                    'label' => 'Subscribe Button Color',
                                    'default' => '#2563EB',
                                    'hint' => 'CTA button background',
                                ],
                                'button_text_color' => [
                                    'label' => 'Subscribe Button Text',
                                    'default' => '#FFFFFF',
                                    'hint' => 'CTA button text color',
                                ],
                                'header_bg_color' => [
                                    'label' => 'Navbar Background',
                                    'default' => '#FFFFFF',
                                    'hint' => 'Top navigation bar',
                                ],
                                'card_bg_color' => [
                                    'label' => 'Pricing Card Background',
                                    'default' => '#FFFFFF',
                                    'hint' => 'Plan card background',
                                ],
                                'text_color' => [
                                    'label' => 'Primary Text Color',
                                    'default' => '#111827',
                                    'hint' => 'Headings and body text',
                                ],
                                'badge_color' => [
                                    'label' => 'Current Plan Badge Color',
                                    'default' => '#3B82F6',
                                    'hint' => 'Active plan badge',
                                ],
                            ];
                        @endphp

                        @foreach ($colorFields as $key => $meta)
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                <label class="block text-xs font-semibold text-gray-600 mb-0.5">{{ $meta['label'] }}</label>
                                <p class="text-xs text-gray-400 mb-2">{{ $meta['hint'] }}</p>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="{{ $key }}"
                                        value="{{ old($key, $settings[$key] ?? $meta['default']) }}"
                                        class="w-10 h-10 rounded cursor-pointer border border-gray-200 shadow-sm p-0.5">
                                    <input type="text" value="{{ old($key, $settings[$key] ?? $meta['default']) }}"
                                        class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 outline-none font-mono color-hex-input"
                                        data-target="{{ $key }}" placeholder="{{ $meta['default'] }}"
                                        maxlength="7">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><i class="fa-solid fa-eye text-gray-500 mr-1"></i>
                        Preview</h3>
                    <p class="text-sm text-gray-500">Approximate preview of your pricing page colors.</p>
                </div>
                <div class="md:w-2/3">
                    <div id="preview-box" class="rounded-xl overflow-hidden border border-gray-200 shadow-sm text-sm">
                        <div id="preview-header" class="px-4 py-3 flex items-center gap-2">
                            <div id="preview-logo-area" class="font-bold text-base">My App</div>
                        </div>
                        <div id="preview-body" class="p-6">
                            <div id="preview-card" class="rounded-xl p-5 border border-gray-100 shadow-sm">
                                <div class="font-bold text-base mb-1" id="preview-text">Pro Plan</div>
                                <div class="text-gray-500 text-xs mb-3">Access to all premium features</div>
                                <div class="flex items-center gap-2 mb-4">
                                    <span id="preview-badge"
                                        class="text-white text-xs font-bold px-2 py-0.5 rounded-full">Current Plan</span>
                                </div>
                                <button id="preview-button" class="w-full py-2 rounded-lg font-semibold text-sm">Subscribe
                                    Now →</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 flex items-center justify-end gap-3">
                <button type="submit"
                    class="px-5 py-2.5 text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 font-medium transition shadow-sm flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sync color picker ↔ hex text input
            document.querySelectorAll('input[type="color"]').forEach(picker => {
                const fieldName = picker.name;
                const hexInput = document.querySelector(`.color-hex-input[data-target="${fieldName}"]`);

                picker.addEventListener('input', () => {
                    if (hexInput) hexInput.value = picker.value;
                    updatePreview();
                });

                if (hexInput) {
                    hexInput.addEventListener('input', () => {
                        const val = hexInput.value;
                        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                            picker.value = val;
                            updatePreview();
                        }
                    });
                }
            });

            function getColor(name) {
                const p = document.querySelector(`input[type="color"][name="${name}"]`);
                return p ? p.value : '#3B82F6';
            }

            function updatePreview() {
                document.getElementById('preview-header').style.backgroundColor = getColor('header_bg_color');
                document.getElementById('preview-body').style.backgroundColor = getColor('secondary_color');
                document.getElementById('preview-card').style.backgroundColor = getColor('card_bg_color');
                document.getElementById('preview-text').style.color = getColor('text_color');
                document.getElementById('preview-button').style.backgroundColor = getColor('button_color');
                document.getElementById('preview-button').style.color = getColor('button_text_color');
                document.getElementById('preview-badge').style.backgroundColor = getColor('badge_color');
                document.getElementById('preview-logo-area').style.color = getColor('primary_color');
            }

            updatePreview();

            // Sync app name to preview
            const appNameInput = document.querySelector('input[name="app_name"]');
            if (appNameInput) {
                appNameInput.addEventListener('input', () => {
                    document.getElementById('preview-logo-area').textContent = appNameInput.value ||
                        'My App';
                });
            }
        });
    </script>
@endsection
