@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="margin-bottom: 0.5rem;">Welcome, {{ auth()->user()->name }}!</h1>
            <p style="color: #64748b; font-size: 1.1rem;">Manage your seafood orders from your dashboard</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
            <div class="card">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📝</div>
                <h2 style="margin-top: 0;">Place Order</h2>
                <p style="color: #64748b;">Create a new order for fresh seafood delivery to your hotel or restaurant.</p>
                <a href="{{ route('order.create') }}" class="btn btn-primary">New Order</a>
            </div>

            <div class="card">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📋</div>
                <h2 style="margin-top: 0;">Order History</h2>
                <p style="color: #64748b;">View all your past orders, track status, and manage order details.</p>
                <a href="{{ route('order.history') }}" class="btn btn-secondary">View History</a>
            </div>

            @if(auth()->user()->is_admin)
                <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚙️</div>
                    <h2 style="color: white; margin-top: 0;">Admin Panel</h2>
                    <p>Review and approve customer orders. Manage order status and delivery.</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="background: #fff; color: #d97706; border: none;">Go to Admin</a>
                </div>
            @endif
        </div>

        <div style="background: #f0f9ff; border-radius: 0.75rem; padding: 2rem; margin-top: 2rem;">
            <h2>Our Premium Seafood Selection</h2>
            <p style="color: #64748b;">Fresh daily from Zanzibar waters</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐟</div>
                    <h3 style="margin: 0.5rem 0;">Samaki</h3>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Premium whole fish for grilling and frying</p>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐙</div>
                    <h3 style="margin: 0.5rem 0;">Pweza</h3>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Fresh octopus perfect for soups</p>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🦞</div>
                    <h3 style="margin: 0.5rem 0;">Kamba</h3>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Premium lobster for special dishes</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
