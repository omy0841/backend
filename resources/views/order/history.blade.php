@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Your Order History</h1>
        <p style="color: #64748b; margin-bottom: 2rem;">View all your seafood orders and their current status</p>

        @if(session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="card" style="text-align: center; padding: 3rem;">
                <p style="font-size: 1.2rem; color: #64748b;">You haven't placed any orders yet</p>
                <a href="{{ route('order.create') }}" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Place Your First Order</a>
            </div>
        @else
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                @foreach($orders as $order)
                    <div class="card">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                            <div>
                                <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Order Date</p>
                                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Hotel / Restaurant</p>
                                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">{{ $order->hotel_name }}</p>
                            </div>
                            <div>
                                <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Delivery Location</p>
                                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">{{ $order->delivery_location }}</p>
                            </div>
                        </div>

                        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div>
                                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Contact</p>
                                    <p style="margin: 0.5rem 0 0 0;">{{ $order->contact_name }} · {{ $order->phone }}<br>{{ $order->email }}</p>
                                </div>
                                @if($order->status === 'approved')
                                    <span class="badge-approved">✓ Approved</span>
                                @elseif($order->status === 'rejected')
                                    <span class="badge-rejected">✗ Rejected</span>
                                @else
                                    <span class="badge-pending">⏳ Pending</span>
                                @endif
                            </div>

                            <div style="margin: 1rem 0;">
                                <p style="margin: 0 0 0.5rem 0; font-weight: 600;">Ordered Items:</p>
                                <ul style="margin: 0; padding-left: 1.5rem;">
                                    @foreach($order->items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($order->custom_items)
                                <div style="margin: 1rem 0; padding: 1rem; background: #f0f9ff; border-radius: 0.5rem;">
                                    <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #0369a1;">Custom Items:</p>
                                    <p style="margin: 0; white-space: pre-wrap;">{{ $order->custom_items }}</p>
                                </div>
                            @endif

                            @if($order->special_request)
                                <div style="margin: 1rem 0; padding: 1rem; background: #fef3c7; border-radius: 0.5rem;">
                                    <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #92400e;">Special Request:</p>
                                    <p style="margin: 0; white-space: pre-wrap;">{{ $order->special_request }}</p>
                                </div>
                            @endif

                            @if($order->total_amount)
                                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Total Amount</p>
                                    <p style="margin: 0.5rem 0 0 0; font-weight: 600; font-size: 1.2rem; color: #10b981;">{{ number_format($order->total_amount, 2) }} TZS</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 2rem;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
