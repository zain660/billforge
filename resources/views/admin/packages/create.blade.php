@extends('subscriptions::layouts.admin')

@section('title', '| Create Package')
@section('page_title', 'Create Package')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('subscriptions.admin.packages.store') }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf

            {{-- Basic Details --}}
            <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Basic Details</h3>
                    <p class="text-sm text-gray-500">Name, description, and pricing for this package.</p>
                </div>

                <div class="md:w-2/3 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Package Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="e.g. Pro Plan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="Features included in this plan...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', '0.00') }}" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                                placeholder="29.99">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency <span
                                    class="text-red-500">*</span></label>
                            <select name="currency" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm">
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle <span
                                    class="text-red-500">*</span></label>
                            <select name="billing_cycle" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm">
                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly
                                </option>
                                <option value="lifetime" {{ old('billing_cycle') == 'lifetime' ? 'selected' : '' }}>Lifetime
                                    (One-time)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Free Trial Days <span
                                    class="text-gray-400 font-normal">(0 for none)</span></label>
                            <input type="number" name="trial_days" value="{{ old('trial_days', '0') }}" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                                placeholder="e.g. 14">
                        </div>
                    </div>

                    <div class="flex items-center mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">Package is
                            active and visible</label>
                    </div>
                </div>
            </div>

            {{-- Gateway Identifiers --}}
            <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row gap-8 bg-slate-50">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gateway Identifiers</h3>
                    <p class="text-sm text-gray-500">Link this package to specific plans created in your payment gateways.
                    </p>
                </div>

                <div class="md:w-2/3 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                class="fa-brands fa-stripe text-[#635BFF] mr-1"></i> Stripe Price ID</label>
                        <input type="text" name="stripe_price_id" value="{{ old('stripe_price_id') }}"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-[#635BFF] focus:border-[#635BFF] block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="price_1Mo...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                class="fa-brands fa-paypal text-[#003087] mr-1"></i> PayPal Plan ID</label>
                        <input type="text" name="paypal_plan_id" value="{{ old('paypal_plan_id') }}"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-[#003087] focus:border-[#003087] block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="P-1234...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                class="fa-solid fa-credit-card text-gray-600 mr-1"></i> Authorize.net Plan ID</label>
                        <input type="text" name="authorize_plan_id" value="{{ old('authorize_plan_id') }}"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-gray-500 focus:border-gray-500 block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="...">
                    </div>
                </div>
            </div>

            {{-- Features Section --}}
            <div class="p-8 flex flex-col md:flex-row gap-8 border-b border-gray-100">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><i class="fa-solid fa-star text-yellow-500 mr-1"></i>
                        Features</h3>
                    <p class="text-sm text-gray-500">Add features for this package. Each feature has a display name and an
                        optional protected route.</p>
                </div>

                <div class="md:w-2/3">
                    <div id="features-container" class="space-y-3">
                        {{-- Feature rows will appear here --}}
                    </div>

                    <button type="button" id="add-feature-btn"
                        class="mt-4 flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Feature
                    </button>

                    {{-- Route options for JS --}}
                    <script id="route-options-data" type="application/json">
                    @json($appRoutes)
                </script>
                </div>
            </div>

            <div class="bg-gray-50 p-6 flex items-center justify-end gap-3">
                <a href="{{ route('subscriptions.admin.packages.index') }}"
                    class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition shadow-sm">Cancel</a>
                <button type="submit"
                    class="px-5 py-2.5 text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 font-medium transition shadow-sm shadow-blue-500/30 flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Save Package
                </button>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const container = document.getElementById('features-container');
            const addBtn = document.getElementById('add-feature-btn');
            const routeOptionsRaw = document.getElementById('route-options-data').textContent;
            const routes = JSON.parse(routeOptionsRaw);

            function buildRouteOptions(selectedRoute) {
                let opts = '<option value="">— No route restriction —</option>';
                routes.forEach(r => {
                    const sel = r.name === selectedRoute ? 'selected' : '';
                    opts += `<option value="${r.name}" ${sel}>${r.name} (${r.uri})</option>`;
                });
                return opts;
            }

            function addFeatureRow(featureName = '', selectedRoute = '') {
                const idx = container.children.length;
                const row = document.createElement('div');
                row.className = 'flex gap-2 items-start feature-row';
                row.innerHTML = `
            <div class="flex-1">
                <input type="text"
                    name="features[${idx}][name]"
                    value="${featureName}"
                    placeholder="e.g. Access to Reports"
                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition"
                    required>
            </div>
            <div class="flex-1">
                <select name="features[${idx}][route]"
                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-purple-500 transition">
                    ${buildRouteOptions(selectedRoute)}
                </select>
            </div>
            <button type="button" class="remove-feature mt-0.5 text-red-400 hover:text-red-600 transition flex-shrink-0 w-8 h-9 flex items-center justify-center rounded-lg hover:bg-red-50">
                <i class="fa-solid fa-times"></i>
            </button>`;

                row.querySelector('.remove-feature').addEventListener('click', () => {
                    row.remove();
                    renumberRows();
                });

                container.appendChild(row);
            }

            function renumberRows() {
                container.querySelectorAll('.feature-row').forEach((row, i) => {
                    row.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/features\[\d+\]/, `features[${i}]`);
                    });
                });
            }

            addBtn.addEventListener('click', () => addFeatureRow());

            // Pre-populate from old() if validation failed
            @if (old('features'))
                @foreach (old('features') as $feat)
                    addFeatureRow('{{ addslashes($feat['name'] ?? '') }}', '{{ $feat['route'] ?? '' }}');
                @endforeach
            @else
                addFeatureRow(); // start with one empty row
            @endif
        })();
    </script>
@endsection
