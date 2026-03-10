@extends('subscriptions::layouts.frontend')

@section('content')
    <div class="text-center mb-16">
        <h2 class="text-base font-semibold tracking-wide uppercase" style="color: var(--sub-primary, #3B82F6);">Pricing</h2>
        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight sm:text-4xl" style="color: var(--sub-text, #111827);">
            Plans that scale with your business
        </p>
        <p class="mt-4 max-w-2xl text-xl mx-auto" style="color: #6B7280;">
            Choose the perfect subscription package to unlock premium tools and accelerate your growth.
        </p>
    </div>

    <div
        class="mt-12 space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-6 lg:mx-auto lg:max-w-4xl xl:max-w-none xl:mx-0 xl:grid-cols-3">
        @forelse($packages as $package)
            @php
                $isCurrentPlan = $activeSubscription && $activeSubscription->package_id === $package->id;
            @endphp

            <div class="border rounded-2xl shadow-sm divide-y flex flex-col transition hover:shadow-lg relative overflow-hidden group"
                style="border-color: {{ $isCurrentPlan ? 'var(--sub-badge, #3B82F6)' : '#E5E7EB' }}; {{ $isCurrentPlan ? 'box-shadow: 0 0 0 2px var(--sub-badge, #3B82F6);' : '' }} background-color: var(--sub-card-bg, #FFFFFF);">

                @if ($isCurrentPlan)
                    <div class="absolute top-0 right-0 left-0 text-white text-xs font-bold text-center py-1 uppercase tracking-wider"
                        style="background-color: var(--sub-badge, #3B82F6);">
                        Current Plan
                    </div>
                @endif

                <div class="p-8 {{ $isCurrentPlan ? 'mt-4' : '' }}">
                    <h3 class="text-xl leading-6 font-semibold border-b border-gray-100 pb-4 mb-4"
                        style="color: var(--sub-text, #111827);">
                        {{ $package->name }}
                    </h3>
                    <p class="mt-4 text-sm text-gray-500 min-h-[40px]">
                        {{ $package->description ?? 'Get access to premium features and support.' }}
                    </p>
                    <div class="mt-8 flex items-baseline relative">
                        <p class="text-5xl font-extrabold tracking-tight" style="color: var(--sub-text, #111827);">
                            {{ number_format($package->price, 2) }}
                        </p>
                        <span
                            class="absolute left-[calc(5rem+4px)] -top-1 text-2xl font-medium text-gray-500">{{ $package->currency }}</span>
                        <span
                            class="ml-16 text-xl font-medium text-gray-500">/{{ rtrim($package->billing_cycle, 'ly') }}</span>
                    </div>
                </div>

                <div class="flex-1 p-8 bg-gray-50 flex flex-col justify-between"
                    style="background-color: color-mix(in srgb, var(--sub-card-bg, #FFFFFF) 85%, #F9FAFB);">
                    <ul class="space-y-3 text-sm text-gray-600">
                        @forelse($package->routes as $feature)
                            <li class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-white text-xs"
                                    style="background-color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </span>
                                <span>{{ $feature->feature_name ?: $feature->route_name ?: 'Premium feature' }}</span>
                            </li>
                        @empty
                            <li class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-white text-xs"
                                    style="background-color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </span>
                                <span>Access to all premium features</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-white text-xs"
                                    style="background-color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </span>
                                <span>Standard support</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-white text-xs"
                                    style="background-color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </span>
                                <span>Cancel anytime</span>
                            </li>
                        @endforelse
                    </ul>

                    <div class="mt-8">
                        @if ($isCurrentPlan)
                            <button disabled class="w-full font-semibold py-3 px-4 rounded-xl cursor-not-allowed"
                                style="background-color: color-mix(in srgb, var(--sub-badge, #3B82F6) 15%, white); color: var(--sub-badge, #3B82F6);">
                                Currently Active
                            </button>
                        @else
                            <form action="{{ route('subscriptions.checkout', $package->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <div>
                                    <input type="text" name="promo_code" placeholder="Promo code (optional)"
                                        class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm text-center uppercase tracking-wider font-medium placeholder:normal-case placeholder:tracking-normal placeholder:font-normal">
                                </div>
                                <button type="submit"
                                    class="w-full font-semibold py-3 px-4 rounded-xl transition shadow-sm hover:shadow-md flex items-center justify-center"
                                    style="background-color: var(--sub-btn, #2563EB); color: var(--sub-btn-text, #FFFFFF);">
                                    Subscribe Now <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full border border-gray-200 rounded-2xl p-12 text-center bg-white shadow-sm">
                <i class="fa-solid fa-ghost text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No active plans available</h3>
                <p class="mt-1 text-gray-500">Please check back later or contact administrators.</p>
            </div>
        @endforelse
    </div>
@endsection
