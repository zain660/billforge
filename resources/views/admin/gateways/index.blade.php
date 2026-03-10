@extends('subscriptions::layouts.admin')

@section('title', '| Gateways')
@section('page_title', 'Payment Gateways')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($gateways as $key => $gateway)
            <div class="bg-white rounded-2xl shadow-sm border {{ $gateway->is_active ? 'ring-1 shadow-blue-100' : 'border-gray-200' }} overflow-hidden flex flex-col transition duration-300 relative group"
                style="{{ $gateway->is_active ? 'border-color: var(--sub-primary, #3B82F6); --tw-ring-color: var(--sub-primary, #3B82F6);' : '' }}">

                <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">{{ $gateway->name }}</h3>
                    <div class="{{ $gateway->is_active ? '' : 'bg-gray-200 text-gray-500' }} px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider"
                        style="{{ $gateway->is_active ? 'background-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 15%, transparent); color: var(--sub-primary, #3B82F6);' : '' }}">
                        {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col space-y-4">
                    <form action="{{ route('subscriptions.admin.gateways.update', $gateway->id) }}" method="POST"
                        id="form-update-{{ $key }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Public Key / Client ID</label>
                                <input type="text" name="public_key"
                                    value="{{ old('public_key', $gateway->public_key) }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 outline-none transition transition-shadow duration-200 shadow-sm"
                                    placeholder="pk_test_...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key / Secret ID</label>
                                <input type="password" name="secret_key"
                                    value="{{ old('secret_key', $gateway->secret_key) }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 outline-none transition transition-shadow duration-200 shadow-sm"
                                    placeholder="sk_test_...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret
                                    (Optional)
                                </label>
                                <input type="password" name="webhook_secret"
                                    value="{{ old('webhook_secret', $gateway->webhook_secret) }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 outline-none transition transition-shadow duration-200 shadow-sm"
                                    placeholder="whsec_...">
                            </div>
                        </div>
                    </form>

                    <div class="mt-auto pt-4 flex items-center gap-3">
                        <button type="submit" form="form-update-{{ $key }}"
                            class="{{ !$gateway->is_active ? 'w-1/2' : 'w-full' }} bg-gray-800 hover:bg-gray-900 text-white font-medium py-2.5 px-4 rounded-xl transition duration-200 shadow-sm hover:shadow text-center flex items-center justify-center">
                            <i class="fa-solid fa-save mr-2"></i> Save
                        </button>

                        @if (!$gateway->is_active)
                            <form action="{{ route('subscriptions.admin.gateways.activate', $gateway->id) }}"
                                method="POST" class="w-1/2">
                                @csrf
                                <button type="submit"
                                    class="w-full text-white font-medium py-2.5 px-4 rounded-xl transition duration-200 shadow-sm hover:shadow text-center flex items-center justify-center"
                                    style="background-color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-power-off mr-2"></i> Activate
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
