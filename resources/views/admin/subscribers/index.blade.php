@extends('subscriptions::layouts.admin')

@section('title', '| Subscribers')
@section('page_title', 'Subscriber Management')

@section('content')

    {{-- Block Confirmation Modal --}}
    <div id="block-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="block-modal-backdrop"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 z-10 border border-red-100">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mx-auto mb-4">
                <i class="fa-solid fa-ban text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Block this subscriber?</h3>
            <p class="text-gray-500 text-sm text-center mb-6">
                This will immediately cancel their subscription and <strong class="text-gray-700">prevent them from
                    subscribing again</strong>. This action cannot be easily undone — you'd need to manually activate them.
            </p>
            <div class="flex gap-3">
                <button type="button" id="block-modal-cancel"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition">
                    Cancel
                </button>
                <form id="block-form" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition shadow-sm shadow-red-500/30">
                        <i class="fa-solid fa-ban mr-1"></i> Yes, Block User
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <form method="GET" action="{{ route('subscriptions.admin.subscribers.index') }}"
            class="flex gap-2 items-center flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm w-56">

            <select name="status"
                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                <option value="">All statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
            </select>

            <button type="submit"
                class="text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow"
                style="background-color: var(--sub-primary, #3B82F6);">
                <i class="fa-solid fa-search mr-1"></i> Filter
            </button>

            @if (request('search') || request('status'))
                <a href="{{ route('subscriptions.admin.subscribers.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 transition">
                    <i class="fa-solid fa-times mr-1"></i> Clear
                </a>
            @endif
        </form>

        <span class="text-sm text-gray-400">{{ $subscribers->total() }} subscriber(s)</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Package</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Gateway</th>
                        <th class="px-6 py-4 font-semibold">Subscribed</th>
                        <th class="px-6 py-4 font-semibold">Ends At</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $sub->user->name ?? '—' }}</div>
                                <div class="text-gray-400 text-xs">{{ $sub->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $sub->package->name ?? '—' }}</div>
                                <div class="text-gray-400 text-xs capitalize">{{ $sub->package->billing_cycle ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-gray-100 text-gray-700',
                                        'blocked' => 'bg-red-100 text-red-800',
                                        'past_due' => 'bg-yellow-100 text-yellow-800',
                                        'trialing' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $colorClass = $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                                    @if ($sub->status === 'active')
                                        <i class="fa-solid fa-circle-check text-xs"></i>
                                    @elseif($sub->status === 'cancelled')
                                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                                    @elseif($sub->status === 'blocked')
                                        <i class="fa-solid fa-ban text-xs"></i>
                                    @endif
                                    {{ ucfirst($sub->status) }}
                                </span>
                                @if ($sub->is_blocked)
                                    <span
                                        class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fa-solid fa-lock text-xs mr-1"></i> Blocked
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-gray-600 capitalize text-sm">{{ str_replace('_', ' ', $sub->gateway_key) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ $sub->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Activate --}}
                                    @if ($sub->status !== 'active' && !$sub->is_blocked)
                                        <form action="{{ route('subscriptions.admin.subscribers.activate', $sub->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Activate"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:bg-green-50 transition"
                                                onclick="return confirm('Activate this subscription?')">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Cancel --}}
                                    @if ($sub->status === 'active')
                                        <form action="{{ route('subscriptions.admin.subscribers.cancel', $sub->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Cancel Subscription"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-yellow-600 hover:bg-yellow-50 transition"
                                                onclick="return confirm('Cancel this subscription?')">
                                                <i class="fa-solid fa-circle-pause"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Block --}}
                                    @if (!$sub->is_blocked)
                                        <button type="button" title="Block User"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition block-btn"
                                            data-action="{{ route('subscriptions.admin.subscribers.block', $sub->id) }}">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300"
                                            title="Already blocked">
                                            <i class="fa-solid fa-ban"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                    <i class="fa-solid fa-users text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-900 mb-1">No subscribers found</p>
                                <p class="text-gray-400">No subscriptions match your current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subscribers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('block-modal');
            const form = document.getElementById('block-form');
            const cancelBtn = document.getElementById('block-modal-cancel');
            const backdrop = document.getElementById('block-modal-backdrop');

            document.querySelectorAll('.block-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    form.action = btn.dataset.action;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            cancelBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
        });
    </script>
@endsection
