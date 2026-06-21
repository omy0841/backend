@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="card grid gap-6">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input name="name" value="{{ $product->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Emoji</label>
                <input name="emoji" value="{{ $product->emoji }}" class="form-control" placeholder="🐟 🦀 🦞 🐙">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $product->description }}</textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Unit Type</label>
                    <select name="unit_type" class="form-control" required>
                        <option value="unit" {{ $product->unit_type === 'unit' ? 'selected' : '' }}>Unit (whole)</option>
                        <option value="kilo" {{ $product->unit_type === 'kilo' ? 'selected' : '' }}>Kilogram</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price Per Unit</label>
                    <input name="price_per_unit" type="number" step="0.01" value="{{ $product->price_per_unit }}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input name="stock" type="number" step="0.5" value="{{ $product->stock }}" class="form-control" required>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
