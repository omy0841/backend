@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p class="section-subtitle">Monitor stock levels and manage products</p>

        @if(session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <div class="dashboard-hero mb-8">
            <div class="dashboard-hero-text">
                <p class="dashboard-overline">Admin Inventory</p>
                <h1 class="dashboard-title">Inventory Control</h1>
                <p class="dashboard-description">View low stock alerts, manage restock operations, and keep the restaurant seafood inventory healthy.</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Review Orders</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
            </div>
        </div>

        <div class="dashboard-alert-card card mb-8">
            <div class="dashboard-alert-header">
                <div>
                    <h2>⚠️ Low Stock Alert</h2>
                    <p>Quickly replenish products before they run out.</p>
                </div>
                <span class="status-pill status-pill-critical">{{ count($lowStockProducts) }} items</span>
            </div>

            @if($lowStockProducts->isEmpty())
                <p class="dashboard-empty-state">All products are well-stocked. Great job!</p>
            @else
                <div class="dashboard-low-stock-grid">
                    @foreach($lowStockProducts as $product)
                        <div class="low-stock-card">
                            <div class="low-stock-card-header">
                                <div>
                                    <h3>{{ $product->emoji }} {{ $product->name }}</h3>
                                    <p>{{ $product->unit_type === 'kilo' ? 'Kilogram' : 'Units' }}</p>
                                </div>
                                <span class="low-stock-count">{{ number_format($product->stock, 1) }}</span>
                            </div>
                            <button 
                                type="button" 
                                class="btn btn-primary btn-block dashboard-btn-bold"
                                onclick="openRestockModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->unit_type) }}, {{ $product->stock }})">
                                Restock Now
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Stock Overview -->
        <div class="card">
            <div class="stock-overview-header mb-6">
                <div>
                    <h2>Stock Overview</h2>
                    <p>Track overall inventory health and refill items fast.</p>
                </div>
                <div class="stock-overview-update">
                    <span class="text-bold">Updated:</span>
                    <span>{{ now()->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="stock-summary-grid mb-8">
                <div class="stock-summary-card stock-summary-card-sky">
                    <p>Total Products</p>
                    <p>{{ $totalProducts }}</p>
                </div>
                <div class="stock-summary-card stock-summary-card-emerald">
                    <p>In Stock</p>
                    <p>{{ $inStockProducts }}</p>
                </div>
                <div class="stock-summary-card stock-summary-card-rose">
                    <p>Low Stock</p>
                    <p>{{ $lowStockCount }}</p>
                </div>
                <div class="stock-summary-card stock-summary-card-amber">
                    <p>Out of Stock</p>
                    <p>{{ $outOfStockCount }}</p>
                </div>
            </div>

            <!-- Product List with Stock Status -->
            <div class="table-card">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock Level</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allProducts as $product)
                            @php
                                $stockPercentage = $product->stock > 0 ? min(100, ($product->stock / 50) * 100) : 0;
                                $statusClass = $product->stock <= 0 ? 'rose' : ($product->stock <= 10 ? 'amber' : 'emerald');
                                $statusLabel = $product->stock <= 0 ? 'Out of Stock' : ($product->stock <= 10 ? 'Low Stock' : 'In Stock');
                            @endphp
                            <tr class="dashboard-table-row">
                                <td>
                                    <div class="dashboard-product-name">{{ $product->emoji }} {{ $product->name }}</div>
                                    <div class="dashboard-product-subtitle">{{ $product->unit_type === 'kilo' ? 'Kilogram' : 'Unit' }}</div>
                                </td>
                                <td>
                                    <div class="stock-bar-row">
                                        <div class="stock-bar">
                                            <div class="stock-bar-fill stock-bar-fill-{{ $statusClass }}" style="width: {{ $stockPercentage }}%"></div>
                                        </div>
                                        <span>{{ number_format($product->stock, 1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="dashboard-price">TZS {{ number_format($product->price_per_unit) }}</span>
                                </td>
                                <td>
                                    <span class="dashboard-badge dashboard-badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-secondary dashboard-action-btn"
                                        onclick="openRestockModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->unit_type) }}, {{ $product->stock }})">
                                        Restock
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div id="restockModal" class="modal-backdrop hidden">
    <div class="modal-container animate-fadeIn">
        <h2 class="modal-title">Restock Product</h2>
        <p id="modalProductName" class="modal-text"></p>

        <form id="restockForm" class="form-grid">
            <div class="form-group">
                <label>Current Stock</label>
                <input type="text" id="currentStock" class="form-control modal-input readonly" readonly>
            </div>

            <div class="form-group">
                <label>Add Amount (<span id="unitLabel"></span>)</label>
                <input type="number" id="restockAmount" class="form-control modal-input" min="0.5" step="0.5" placeholder="Enter amount" required>
            </div>

            <div class="form-group">
                <label>New Stock After Restock</label>
                <input type="text" id="newStock" class="form-control modal-input modal-input-success" readonly>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary full-width" onclick="closeRestockModal()">Cancel</button>
                <button type="submit" class="btn btn-primary full-width">✓ Confirm Restock</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentProductId = null;
let currentStock = 0;
let unitType = 'unit';

function openRestockModal(productId, productName, unit, stock) {
    currentProductId = productId;
    currentStock = stock;
    unitType = unit;

    const unitDisplay = unit === 'kilo' ? 'kg' : 'units';
    document.getElementById('modalProductName').textContent = `Restock ${productName}`;
    document.getElementById('currentStock').value = `${stock} ${unitDisplay}`;
    document.getElementById('unitLabel').textContent = unitDisplay;
    document.getElementById('restockAmount').value = unit === 'kilo' ? '2' : '5';
    document.getElementById('newStock').value = `${(stock + (unit === 'kilo' ? 2 : 5))} ${unitDisplay}`;
    document.getElementById('restockModal').classList.remove('hidden');
    document.getElementById('restockAmount').focus();
}

function closeRestockModal() {
    document.getElementById('restockModal').classList.add('hidden');
    document.getElementById('restockForm').reset();
}

const restockAmountInput = document.getElementById('restockAmount');
const restockForm = document.getElementById('restockForm');
const restockModal = document.getElementById('restockModal');

if (restockAmountInput) {
    restockAmountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const unitDisplay = unitType === 'kilo' ? 'kg' : 'units';
        const newStockValue = currentStock + amount;
        const newStockField = document.getElementById('newStock');
        if (newStockField) {
            newStockField.value = `${newStockValue} ${unitDisplay}`;
        }
    });
}

if (restockForm) {
    restockForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const amount = parseFloat(document.getElementById('restockAmount').value);
    if (!amount || amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }

    try {
        const response = await fetch(`/admin/products/${currentProductId}/restock-ajax`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ amount })
        });

        const data = await response.json();

        if (data.success) {
            closeRestockModal();
            alert(`✓ Successfully added ${amount} to stock!`);
            location.reload();
        } else {
            alert(`Error: ${data.message}`);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while restocking');
    }
});
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRestockModal();
    }
});

if (restockModal) {
    restockModal.addEventListener('click', function(event) {
        if (event.target === this) {
            closeRestockModal();
        }
    });
}
</script>
@endsection
