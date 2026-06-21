@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Add Product</h1>
        <form method="POST" action="{{ route('admin.products.store') }}" class="card grid gap-6">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Emoji</label>
                <input name="emoji" class="form-control" placeholder="🐟 🦀 🦞 🐙">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Unit Type</label>
                    <select name="unit_type" class="form-control" required>
                        <option value="unit">Unit (whole)</option>
                        <option value="kilo">Kilogram</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price Per Unit</label>
                    <input name="price_per_unit" type="number" step="0.01" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input name="stock" type="number" step="0.5" class="form-control" required>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
