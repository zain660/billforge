@extends('subscriptions::layouts.frontend')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div
                class="p-8 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">My Subscription Details</h3>
                    <p class="text-gray-500 mt-1">Manage your active plans, billing details, and view payment history.</p>
                </div>
                @if ($subscription && $subscription->isActive())
                    <span
                        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Active
                    </span>
                @else
                    <span
                        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                        Inactive
                    </span>
                @endif
            </div>

            <div class="p-8">
                @if ($subscription && $subscription->isActive())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Current Plan</h4>
                            <div class="flex items-baseline space-x-2">
                                <span
                                    class="text-3xl font-extrabold text-gray-900">{{ $subscription->package->name }}</span>
                                <span
                                    class="text-xl font-medium text-gray-500">{{ number_format($subscription->package->price, 2) }}
                                    {{ $subscription->package->currency }} /
                                    {{ $subscription->package->billing_cycle }}</span>
                            </div>
                            <p class="mt-3 text-gray-600 block">{{ $subscription->package->description }}</p>
                        </div>

                        <div class="border-t md:border-t-0 md:border-l border-gray-100 md:pl-8 pt-6 md:pt-0">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Billing Cycle</h4>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 flex items-center"><i
                                            class="fa-solid fa-calendar mr-2 text-gray-400"></i> Subscription Started</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $subscription->created_at->format('M j, Y') }}</span>
                                </div>

                                @if ($subscription->ends_at)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500 flex items-center"><i
                                                class="fa-solid fa-clock mr-2 text-orange-400"></i> Renews/Ends At</span>
                                        <span
                                            class="font-medium text-gray-900">{{ $subscription->ends_at->format('M j, Y') }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 flex items-center"><i
                                            class="fa-solid fa-credit-card mr-2 text-blue-400"></i> Payment Gateway</span>
                                    <span
                                        class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $subscription->gateway_key) }}</span>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-3">
                                <form action="{{ route('subscriptions.billing-portal') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-slate-800 text-white hover:bg-slate-700 font-medium py-2 px-4 rounded-xl transition shadow-sm text-center flex items-center justify-center">
                                        <i class="fa-solid fa-credit-card mr-2"></i> Update Payment Method
                                    </button>
                                </form>
                                <div class="flex gap-3">
                                    <button
                                        class="w-full bg-white text-red-600 border border-red-200 hover:bg-red-50 hover:border-red-300 font-medium py-2 px-4 rounded-xl transition shadow-sm text-center">
                                        Cancel Subscription
                                    </button>
                                    <a href="{{ route('subscriptions.pricing') }}"
                                        class="w-full bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 font-medium py-2 px-4 rounded-xl transition shadow-sm text-center">
                                        Change Plan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-6">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Active Subscription</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-8">You are currently not subscribed to any premium plan.
                            View our pricing options to unlock features and elevate your workflow.</p>
                        <a href="{{ route('subscriptions.pricing') }}"
                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-sm text-lg shadow-blue-500/30">
                            View Pricing Plans <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if ($allSubscriptions && $allSubscriptions->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Subscription History</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach ($allSubscriptions as $sub)
                        <li class="px-8 py-4 hover:bg-gray-50 transition flex items-center justify-between">
                            <div>
                                <span
                                    class="font-semibold text-gray-900 block">{{ $sub->package->name ?? 'Unknown Plan' }}</span>
                                <span class="text-sm text-gray-500">{{ $sub->created_at->format('M j, Y') }} via
                                    {{ ucfirst($sub->gateway_key) }}</span>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sub->isActive() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $sub->status }}
                                </span>
                                <span class="text-sm font-medium text-gray-900 ml-6 w-20 text-right">
                                    @if ($sub->package)
                                        ${{ number_format($sub->package->price, 2) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
