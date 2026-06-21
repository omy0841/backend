@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="orders-hero mb-8">
            <div class="orders-hero-copy">
                <p class="orders-overline">Admin Order Management</p>
                <h1 class="orders-title">Review & Approve Seafood Orders</h1>
                <p class="orders-description">Manage restaurant and hotel orders in one place. Approve quickly, keep inventory moving, and keep customers happy.</p>
            </div>
            <div class="orders-hero-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Inventory Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Product Catalog</a>
            </div>
        </div>

        @if(session('status'))
            <div class="success-message mb-8">
                {{ session('status') }}
            </div>
        @endif

        <div class="orders-summary-panel mb-8">
            <div class="orders-summary-grid">
                <div class="orders-summary-card orders-summary-card-total">
                    <p>Total orders</p>
                    <p>{{ $summary['total'] }}</p>
                </div>
                <div class="orders-summary-card orders-summary-card-pending">
                    <p>Pending</p>
                    <p>{{ $summary['pending'] }}</p>
                </div>
                <div class="orders-summary-card orders-summary-card-approved">
                    <p>Approved</p>
                    <p>{{ $summary['approved'] }}</p>
                </div>
                <div class="orders-summary-card orders-summary-card-rejected">
                    <p>Rejected</p>
                    <p>{{ $summary['rejected'] }}</p>
                </div>
            </div>
            <div class="orders-summary-tools">
                <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-secondary">Export CSV</a>
                <div class="orders-filter-chip">
                    Active filters
                    <span class="orders-filter-count">{{ $activeFilterCount }}</span>
                </div>
            </div>
        </div>

            <form id="adminOrderFilter" method="GET" action="{{ route('admin.orders.index') }}" class="orders-filter-panel">
                <div class="filter-field">
                    <label>Search orders</label>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by customer, hotel, location, phone or email" class="form-control" autocomplete="off">
                </div>

                <div class="filter-field">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>

                <div class="filter-row">
                    <div class="filter-field">
                        <label>From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="filter-field">
                        <label>To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary full-width">Apply</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary full-width">Reset</a>
                </div>
            </form>

        @if($orders->isEmpty())
            <div class="order-empty-card card">
                <p class="order-empty-title">No pending orders</p>
                <p class="order-empty-text">All customer seafood orders have already been reviewed.</p>
            </div>
        @else
            <div class="orders-list">
                @foreach($orders as $order)
                    <article class="order-card">
                        <div class="order-card-top">
                            <div class="order-card-columns">
                                <div class="order-info-card">
                                    <p class="order-info-label">Order Date</p>
                                    <p class="order-info-value">{{ $order->created_at->format('d M Y') }}</p>
                                    <p class="order-info-meta">{{ $order->created_at->format('H:i') }}</p>
                                </div>

                                <div class="order-info-card">
                                    <p class="order-info-label">Customer</p>
                                    <p class="order-info-value">{{ $order->contact_name }}</p>
                                    <p class="order-info-meta">{{ $order->hotel_name }}</p>
                                    <p class="order-info-meta">Ordered by <strong>{{ $order->user->name }}</strong></p>
                                    <p class="order-info-meta">{{ $order->user->email }}</p>
                                </div>

                                <div class="order-info-card">
                                    <p class="order-info-label">Delivery</p>
                                    <p class="order-info-value">{{ $order->delivery_location }}</p>
                                    <p class="order-info-meta">📱 {{ $order->phone }}</p>
                                    @if($order->email)
                                        <p class="order-info-meta">📧 {{ $order->email }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="order-card-status">
                                @if($order->status === 'approved')
                                    <span class="order-status order-status-approved">✓ Approved</span>
                                @elseif($order->status === 'rejected')
                                    <span class="order-status order-status-rejected">✗ Rejected</span>
                                @else
                                    <span class="order-status order-status-pending">⏳ Pending</span>
                                @endif

                                <div class="order-info-card order-id-card">
                                    <p class="order-info-label">Order ID</p>
                                    <p class="order-info-value">#{{ $order->id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="order-card-body">
                            <div class="order-details-grid">
                                <div>
                                    <h2 class="order-section-title">Ordered Items</h2>
                                    <ul class="order-items-list">
                                        @foreach($order->items as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="order-support-column">
                                    @if($order->custom_items)
                                        <div class="order-support-card">
                                            <p class="order-support-title">Custom Items</p>
                                            <p class="order-support-text">{{ $order->custom_items }}</p>
                                        </div>
                                    @endif

                                    @if($order->special_request)
                                        <div class="order-support-card order-support-special">
                                            <p class="order-support-title">Special Request</p>
                                            <p class="order-support-text">{{ $order->special_request }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="order-card-footer">
                                <div class="order-total-panel">
                                    <p class="order-info-label">Total Amount</p>
                                    <p class="order-total-value">{{ number_format($order->total_amount, 2) }} TZS</p>
                                </div>
                                @if($order->status === 'pending')
                                    <div class="order-card-actions">
                                        <form action="{{ route('admin.orders.reject', $order) }}" method="POST" data-confirm="Are you sure you want to reject this order?">
                                            @csrf
                                            <button type="submit" class="btn btn-danger full-width">Reject</button>
                                        </form>
                                        <form action="{{ route('admin.orders.approve', $order) }}" method="POST" data-confirm="Approve this order now?">
                                            @csrf
                                            <button type="submit" class="btn btn-success full-width">Approve</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="orders-pagination">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.querySelectorAll('form[data-confirm]').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            var message = form.dataset.confirm || 'Are you sure?';
            if (!confirm(message)) {
                event.preventDefault();
                return;
            }

            var button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.classList.add('opacity-70');
            }
        });
    });
</script>
@endsection
