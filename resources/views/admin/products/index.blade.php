@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="margin-bottom:1rem;">Add Product</a>

        @if(session('status'))<div class="success-message">{{ session('status') }}</div>@endif

        <div class="grid admin-products-grid">
            @foreach($products as $product)
                <div class="card admin-product-card flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="product-summary">
                        <div class="product-title-row">
                            <h3>{{ $product->name }}</h3>
                            @if($product->stock <= 0)
                                <span class="status-pill out-of-stock">Out of stock</span>
                            @else
                                <span class="status-pill in-stock">In stock</span>
                            @endif
                        </div>
                        <p class="product-desc">{{ $product->description }}</p>
                        <div class="product-meta">TZS {{ number_format($product->price_per_unit, 0) }}/{{ $product->unit_type === 'kilo' ? 'kg' : 'unit' }} • Stock: {{ number_format($product->stock, 2) }} {{ $product->unit_type === 'kilo' ? 'kg' : 'unit' }}</div>
                    </div>
                    <div class="product-actions-admin">
                        <div class="action-buttons">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete product?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('admin.products.restock', $product) }}" class="restock-form">
                            @csrf
                            <label>Restock amount ({{ $product->unit_type === 'kilo' ? 'kg' : 'units' }})</label>
                            <div class="restock-row">
                                <input type="number" name="amount" value="{{ $product->unit_type === 'kilo' ? '2' : '1' }}" min="{{ $product->unit_type === 'kilo' ? '0.5' : '1' }}" step="{{ $product->unit_type === 'kilo' ? '0.5' : '1' }}" class="restock-input">
                                <button class="btn btn-primary">Restock</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:1rem">{{ $products->links() }}</div>
    </div>
 </div>
@endsection
