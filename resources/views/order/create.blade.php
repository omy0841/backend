@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="order-hero">
                <div class="order-hero-copy">
                <p class="orders-overline" data-i18n="order_overline">Order Fresh Seafood</p>
                <h1 class="order-page-title" data-i18n="order_title">Submit your hotel or restaurant order and our admin team will review it for approval.</h1>
                <p class="order-page-intro" data-i18n="order_intro">Premium seafood ordering and delivery system for hotels and restaurants across Zanzibar.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <div class="order-layout-grid">
            <form action="{{ route('order.store') }}" method="POST" class="card order-form-card">
                @csrf
                <div class="order-form-header">
                    <p class="orders-overline" data-i18n="order_details_overline">Order Details</p>
                    <h2 class="order-form-title" data-i18n="order_details_title">Tell us where and what you need today.</h2>
                </div>

                <div class="form-group">
                    <label data-i18n="contact_name">Contact Name</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="form-control" required>
                    @error('contact_name') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label data-i18n="hotel_name">Hotel / Restaurant Name</label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name') }}" class="form-control" required>
                    @error('hotel_name') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-row form-row-split">
                    <div class="form-group">
                        <label data-i18n="phone_number">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
                        @error('phone') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label data-i18n="email_address">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label data-i18n="delivery_location_label">Delivery Location</label>
                    <input type="text" name="delivery_location" value="{{ old('delivery_location') }}" placeholder="Search city, street, or hotel" class="form-control" required data-i18n-placeholder="delivery_location">
                    @error('delivery_location') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Delivery on Map</label>
                    <div class="location-actions">
                        <button type="button" id="useLocationBtn" class="btn btn-secondary" data-i18n="use_location">Use my location</button>
                        <button type="button" id="findAddressBtn" class="btn btn-secondary" data-i18n="find_location">Find location</button>
                    </div>
                    <p class="location-help" data-i18n="location_help">Enter a city, street, or hotel name and tap Find location to place your delivery pin.</p>
                    <input type="hidden" name="latitude" id="latitudeField" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitudeField" value="{{ old('longitude') }}">
                </div>

                <div class="form-group order-form-section">
                    <div class="order-form-section-header">
                        <div>
                            <p class="orders-overline" data-i18n="select_products_overline">Select Seafood Products</p>
                            <p class="order-form-note" data-i18n="select_products_note">Choose the best seafood for your hotel or restaurant.</p>
                        </div>
                    </div>
                    <div class="order-products-grid">
                        @php
                            // Pull named products into slots for fixed layout
                            $pweza = $products->firstWhere('name', 'Pweza');
                            $ngisi = $products->firstWhere('name', 'Ngisi');
                            $samaki = $products->firstWhere('name', 'Samaki');
                            $kamba = $products->firstWhere('name', 'Kamba');
                            $kaa = $products->firstWhere('name', 'Kaa');
                        @endphp

                        <div class="seafood-grid">
                            <div class="seafood-col">
                                {{-- Left column: Pweza (top) then Samaki (below) --}}
                                @if($pweza)
                                    @php $product = $pweza; $isOutOfStock = $product->stock <= 0; $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit'; $pricePerUnit = $product->price_per_unit ?? $product->price; @endphp
                                    <div class="order-product-card product-wave product-{{ strtolower(str_replace(' ', '-', $product->name)) }}" data-product-id="{{ $product->id }}">
                                        <div class="order-product-card-top">
                                            <div class="order-product-hero">
                                                <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                                <div class="order-product-summary">
                                                    <p class="product-title">{{ $product->name }}</p>
                                                    <p class="product-description">{{ $product->description }}</p>
                                                </div>
                                            </div>
                                            <div class="order-product-meta">
                                                <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                                @if($isOutOfStock)
                                                    <div class="product-status">Wameisha</div>
                                                @else
                                                    <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions-row">
                                            <div class="quantity-row qty-inline">
                                                @if($product->unit_type === 'kilo')
                                                    <label for="quantity_{{ $product->id }}">Kilos</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @else
                                                    <label for="quantity_{{ $product->id }}">Qty</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                        </div>
                                    </div>
                                @endif

                                @if($samaki)
                                    @php $product = $samaki; $isOutOfStock = $product->stock <= 0; $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit'; $pricePerUnit = $product->price_per_unit ?? $product->price; @endphp
                                    <div class="order-product-card product-wave product-{{ strtolower(str_replace(' ', '-', $product->name)) }}" data-product-id="{{ $product->id }}">
                                        <div class="order-product-card-top">
                                            <div class="order-product-hero">
                                                <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                                <div class="order-product-summary">
                                                    <p class="product-title">{{ $product->name }}</p>
                                                    <p class="product-description">{{ $product->description }}</p>
                                                </div>
                                            </div>
                                            <div class="order-product-meta">
                                                <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                                @if($isOutOfStock)
                                                    <div class="product-status">Wameisha</div>
                                                @else
                                                    <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions-row">
                                            <div class="quantity-row qty-inline">
                                                @if($product->unit_type === 'kilo')
                                                    <label for="quantity_{{ $product->id }}">Kilos</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @else
                                                    <label for="quantity_{{ $product->id }}">Qty</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="seafood-col">
                                {{-- Right column: Ngisi (top) then Kamba (below) --}}
                                @if($ngisi)
                                    @php $product = $ngisi; $isOutOfStock = $product->stock <= 0; $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit'; $pricePerUnit = $product->price_per_unit ?? $product->price; @endphp
                                    <div class="order-product-card product-wave product-{{ strtolower(str_replace(' ', '-', $product->name)) }}" data-product-id="{{ $product->id }}">
                                        <div class="order-product-card-top">
                                            <div class="order-product-hero">
                                                <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                                <div class="order-product-summary">
                                                    <p class="product-title">{{ $product->name }}</p>
                                                    <p class="product-description">{{ $product->description }}</p>
                                                </div>
                                            </div>
                                            <div class="order-product-meta">
                                                <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                                @if($isOutOfStock)
                                                    <div class="product-status">Wameisha</div>
                                                @else
                                                    <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions-row">
                                            <div class="quantity-row qty-inline">
                                                @if($product->unit_type === 'kilo')
                                                    <label for="quantity_{{ $product->id }}">Kilos</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @else
                                                    <label for="quantity_{{ $product->id }}">Qty</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                        </div>
                                    </div>
                                @endif

                                @if($kamba)
                                    @php $product = $kamba; $isOutOfStock = $product->stock <= 0; $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit'; $pricePerUnit = $product->price_per_unit ?? $product->price; @endphp
                                    <div class="order-product-card product-wave product-{{ strtolower(str_replace(' ', '-', $product->name)) }}" data-product-id="{{ $product->id }}">
                                        <div class="order-product-card-top">
                                            <div class="order-product-hero">
                                                <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                                <div class="order-product-summary">
                                                    <p class="product-title">{{ $product->name }}</p>
                                                    <p class="product-description">{{ $product->description }}</p>
                                                </div>
                                            </div>
                                            <div class="order-product-meta">
                                                <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                                @if($isOutOfStock)
                                                    <div class="product-status">Wameisha</div>
                                                @else
                                                    <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions-row">
                                            <div class="quantity-row qty-inline">
                                                @if($product->unit_type === 'kilo')
                                                    <label for="quantity_{{ $product->id }}">Kilos</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @else
                                                    <label for="quantity_{{ $product->id }}">Qty</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                        </div>
                                    </div>
                                @endif

                                @if($kaa)
                                    @php $product = $kaa; $isOutOfStock = $product->stock <= 0; $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit'; $pricePerUnit = $product->price_per_unit ?? $product->price; @endphp
                                    <div class="order-product-card product-wave product-{{ strtolower(str_replace(' ', '-', $product->name)) }}" data-product-id="{{ $product->id }}">
                                        <div class="order-product-card-top">
                                            <div class="order-product-hero">
                                                <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                                <div class="order-product-summary">
                                                    <p class="product-title">{{ $product->name }}</p>
                                                    <p class="product-description">{{ $product->description }}</p>
                                                </div>
                                            </div>
                                            <div class="order-product-meta">
                                                <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                                @if($isOutOfStock)
                                                    <div class="product-status">Wameisha</div>
                                                @else
                                                    <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions-row">
                                            <div class="quantity-row qty-inline">
                                                @if($product->unit_type === 'kilo')
                                                    <label for="quantity_{{ $product->id }}">Kilos</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @else
                                                    <label for="quantity_{{ $product->id }}">Qty</label>
                                                    <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Render any remaining products not in the five-slot layout --}}
                        @foreach($products->whereNotIn('name', ['Pweza','Ngisi','Samaki','Kamba','Kaa']) as $product)
                            @php
                                $isOutOfStock = $product->stock <= 0;
                                $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit';
                                $pricePerUnit = $product->price_per_unit ?? $product->price;
                            @endphp
                            <div class="order-product-card {{ $isOutOfStock ? 'out-of-stock' : '' }} product-{{ strtolower(str_replace(' ', '-', $product->name)) }}">
                                <div class="order-product-card-top">
                                    <div class="order-product-hero">
                                        <input type="checkbox" id="product_{{ $product->id }}" name="items[]" value="{{ $product->id }}" data-price="{{ $pricePerUnit }}" data-unit="{{ $product->unit_type }}" @checked(in_array($product->id, old('items', []))) {{ $isOutOfStock ? 'disabled' : '' }}>
                                        <div class="order-product-summary">
                                            <p class="product-title">{{ $product->name }}</p>
                                            <p class="product-description">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                    <div class="order-product-meta">
                                        <div class="product-price">TZS {{ number_format($pricePerUnit) }}/{{ $unitLabel }}</div>
                                        @if($isOutOfStock)
                                            <div class="product-status">Wameisha</div>
                                        @else
                                            <div class="product-stock">Stock: {{ $product->stock }} {{ $unitLabel }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($product->unit_type === 'kilo')
                                    <div class="quantity-row qty-inline">
                                        <label for="quantity_{{ $product->id }}">Kilos</label>
                                        <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="0.5" max="{{ $product->stock }}" step="0.5" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                    </div>
                                @else
                                    <div class="quantity-row qty-inline">
                                        <label for="quantity_{{ $product->id }}">Qty</label>
                                        <input type="number" id="quantity_{{ $product->id }}" name="quantity[{{ $product->id }}]" value="{{ old('quantity.' . $product->id, 1) }}" min="1" max="{{ $product->stock }}" step="1" class="form-control product-qty" {{ $isOutOfStock ? 'disabled' : '' }}>
                                    </div>
                                @endif
                                <div class="product-actions-row">
                                    <button type="button" class="btn btn-secondary quick-add" data-target="product_{{ $product->id }}">Quick add</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('items') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label data-i18n="custom_items">Custom Order Items</label>
                    <textarea name="custom_items" rows="3" class="form-control" placeholder="Add special seafood items, weights, or quantities here">{{ old('custom_items') }}</textarea>
                </div>

                <div class="form-group">
                    <label data-i18n="special_request">Special Preparation Request</label>
                    <textarea name="special_request" rows="3" class="form-control" placeholder="Any special cooking or handling instructions">{{ old('special_request') }}</textarea>
                </div>

                <input type="hidden" name="total_amount" id="totalAmountField" value="{{ old('total_amount', 50000) }}">
                <div class="order-summary-bar">
                    <div class="total-preview">
                        <p class="orders-overline" data-i18n="estimated_total">Estimated total</p>
                        <strong id="estimatedTotal">TZS 50,000</strong>
                    </div>
                    <button type="submit" class="btn-approve full-width" data-i18n="submit_order">Submit Order</button>
                </div>
            </form>
            <script>
                (function () {
                    function parseMoney(value) {
                        return Number(String(value).replace(/[^0-9.\-]/g, '')) || 0;
                    }

                    function formatMoney(value) {
                        return 'TZS ' + Number(value).toLocaleString();
                    }

                    function calculateTotals() {
                        const totalDisplay = document.getElementById('estimatedTotal');
                        const totalField = document.getElementById('totalAmountField');
                        if (!totalDisplay) return;

                        const checkboxes = document.querySelectorAll('input[name="items[]"]');
                        let total = 0;
                        checkboxes.forEach(checkbox => {
                            if (!checkbox.checked) return;
                            const price = parseMoney(checkbox.dataset.price || '0');
                            const id = checkbox.value;
                            const card = checkbox.closest('.order-product-card');
                            const qtyInput = card?.querySelector(`input[name="quantity[${id}]"]`) || card?.querySelector('.product-qty') || card?.querySelector('input[type="number"]');
                            const qty = Math.max(checkbox.dataset.unit === 'kilo' ? 0.5 : 1, parseMoney(qtyInput?.value || '1'));
                            total += price * qty;
                        });

                        totalDisplay.textContent = formatMoney(total);
                        if (totalField) totalField.value = total;
                    }

                    function quickAddOnOrderPage(btn) {
                        let card = btn.closest('.order-product-card');
                        console.log('quickAddOnOrderPage: clicked', btn, 'dataset.target=', btn?.dataset?.target);
                        let checkbox = card?.querySelector('input[name="items[]"]');

                        if (!checkbox && btn.dataset.target) {
                            checkbox = document.getElementById(btn.dataset.target);
                            card = checkbox?.closest('.order-product-card');
                        }

                        if (!checkbox) {
                            console.warn('quickAddOnOrderPage: checkbox not found for', btn, 'dataset.target=', btn?.dataset?.target);
                            return;
                        }

                        const id = checkbox.value;
                        let qtyInput = card?.querySelector(`input[name="quantity[${id}]"]`) ||
                            document.getElementById(`quantity_${id}`) ||
                            card?.querySelector('.product-qty') ||
                            card?.querySelector('input[type="number"]') ||
                            document.querySelector(`input[name="quantity[${id}]"]`);

                        let oldQty = Number(qtyInput?.value) || 0;
                        const step = qtyInput ? Number(qtyInput.getAttribute('step')) || (qtyInput.getAttribute('min') ? Number(qtyInput.getAttribute('min')) : 1) : 1;
                        const max = qtyInput && qtyInput.getAttribute('max') ? Number(qtyInput.getAttribute('max')) : Infinity;
                        const newQty = Math.min(max, +(oldQty + step).toFixed(3));

                        if (qtyInput) {
                            console.log('quickAddOnOrderPage: qtyInput found', qtyInput, 'oldQty=', oldQty, 'newQty=', newQty);
                            qtyInput.value = newQty;
                            qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
                        } else {
                            console.log('quickAddOnOrderPage: no qtyInput for product', id);
                        }

                        console.log('quickAddOnOrderPage: checkbox before change', checkbox, 'data-price=', checkbox.dataset.price);
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                        if (card) card.classList.add('selected');

                        calculateTotals();
                        const totalDisplay = document.getElementById('estimatedTotal');
                        console.log('quickAddOnOrderPage: after calculateTotals display=', totalDisplay?.textContent);
                        if (totalDisplay) {
                            totalDisplay.classList.add('pulse-update');
                            setTimeout(() => totalDisplay.classList.remove('pulse-update'), 500);
                        }
                    }

                    window.quickAddOnOrderPage = quickAddOnOrderPage;
                    window.quickAddProduct = quickAddOnOrderPage;

                    console.log('order page quick-add script loaded', window.quickAddOnOrderPage);

                    function bindQuickAddButton(btn) {
                        if (!btn) return;
                        if (btn.dataset.boundQuickAdd) return;
                        btn.dataset.boundQuickAdd = '1';
                        btn.removeAttribute('onclick');
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            console.log('quick-add clicked via bindQuickAddButton', btn, btn.closest('.order-product-card')?.className);
                            window.quickAddOnOrderPage?.(this);
                        });
                    }

                    function quickAddFallback(event) {
                        const btn = event.target.closest('.quick-add');
                        if (!btn) return;
                        event.preventDefault();
                        console.log('quick-add clicked via body fallback', btn);
                        window.quickAddOnOrderPage?.(btn);
                    }

                    document.querySelectorAll('.quick-add').forEach(bindQuickAddButton);
                    document.body.addEventListener('click', quickAddFallback, true);

                    // Ensure Kaa product quick-add works with robust fallback handling
                    setTimeout(function() {
                        document.querySelectorAll('.product-kaa .quick-add').forEach(function(btn) {
                            console.log('Kaa quick-add button found, binding:', btn);
                            bindQuickAddButton(btn);
                            // Force click handler as additional fallback
                            btn.onclick = function(e) {
                                e.preventDefault();
                                console.log('Kaa quick-add clicked via onclick');
                                window.quickAddOnOrderPage?.(this);
                                return false;
                            };
                        });
                        
                        // Verify Kaa product checkbox and attributes
                        const kaaCheckbox = document.querySelector('.product-kaa input[name="items[]"]');
                        if (kaaCheckbox) {
                            console.log('Kaa checkbox found:', kaaCheckbox, 'price:', kaaCheckbox.dataset.price, 'value:', kaaCheckbox.value);
                            // Ensure price is set correctly
                            if (!kaaCheckbox.dataset.price || kaaCheckbox.dataset.price === '') {
                                const priceEl = document.querySelector('.product-kaa .product-price');
                                if (priceEl) {
                                    const priceText = priceEl.textContent || '';
                                    const parsed = priceText.replace(/[^0-9.]/g, '');
                                    if (parsed) {
                                        kaaCheckbox.dataset.price = parsed;
                                        console.log('Kaa checkbox price set to:', parsed);
                                    }
                                }
                            }
                        } else {
                            console.warn('Kaa checkbox not found');
                        }
                    }, 100);

                    calculateTotals();
                })();
            </script>
            <div class="order-aside-grid">
                <div class="card order-map-card">
                    <div class="order-panel-header">
                        <p class="orders-overline">Delivery Map</p>
                        <h2>Set your delivery point</h2>
                        <p class="section-subtitle">Tap the map or use the location buttons to choose the exact delivery location.</p>
                    </div>
                    <div id="orderMap" class="order-map"></div>
                </div>
                <div class="card live-chat-card">
                    <div class="order-panel-header">
                        <p class="orders-overline">Live Chat</p>
                        <h3>SeaFresh Admin</h3>
                        <p class="section-subtitle">Chat in real time about availability, arrival time, or special preparation.</p>
                    </div>
                    <div id="liveChatRoot"></div>
                </div>

                <div class="card contact-card">
                    <div class="order-panel-header">
                        <p class="orders-overline">Contact & Follow Us</p>
                        <h2>Stay connected</h2>
                        <p class="section-subtitle">Reach us on WhatsApp or follow the latest SeaFresh updates.</p>
                    </div>
                    <div class="contact-links">
                        <a href="https://wa.me/255123456789" target="_blank" rel="noopener" class="contact-pill contact-pill-whatsapp">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.47-.149-.669.149-.198.297-.767.967-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.058-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.074 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="currentColor"/>
                                    <path d="M12 .04C5.371.04-.04 5.41-.04 12.042c0 2.127.56 4.15 1.624 5.937L0 24l5.2-1.592A11.98 11.98 0 0012 23.94C18.629 23.94 24 18.57 24 11.94 24 5.311 18.629.04 12 .04zm0 21.88a10.846 10.846 0 01-5.78-1.543l-.414-.245-3.086.945.978-3.008-.269-.4A10.82 10.82 0 011.16 12.042 10.84 10.84 0 0112 1.2a10.84 10.84 0 0110.84 10.84A10.84 10.84 0 0112 21.92z" fill="currentColor"/>
                                </svg>
                            </span>
                            WhatsApp
                        </a>
                        <a href="https://facebook.com/seafreshzanzibar" target="_blank" rel="noopener" class="contact-pill contact-pill-facebook">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.408.593 24 1.325 24H12.82v-9.294H9.692V11.01h3.128V8.414c0-3.1 1.893-4.788 4.66-4.788 1.325 0 2.466.098 2.797.142v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763V11.01h3.587l-.467 3.696h-3.12V24h6.116C23.406 24 24 23.408 24 22.674V1.326C24 .593 23.406 0 22.675 0z" fill="currentColor"/>
                                </svg>
                            </span>
                            Facebook
                        </a>
                        <a href="https://instagram.com/seafreshzanzibar" target="_blank" rel="noopener" class="contact-pill contact-pill-instagram">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5A4.25 4.25 0 0020.5 16.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5z" fill="currentColor"/>
                                    <path d="M12 7.187a4.813 4.813 0 110 9.626 4.813 4.813 0 010-9.626zm0 1.5a3.313 3.313 0 100 6.626 3.313 3.313 0 000-6.626z" fill="currentColor"/>
                                    <path d="M17.875 5.313a1.063 1.063 0 11-2.125 0 1.063 1.063 0 012.125 0z" fill="currentColor"/>
                                </svg>
                            </span>
                            Instagram
                        </a>
                        <a href="https://x.com/seafreshzanzibar" target="_blank" rel="noopener" class="contact-pill contact-pill-x">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M21.94 6.02c-.62.35-1.28.59-1.97.7.71-.42 1.26-1.08 1.52-1.86-.67.4-1.42.7-2.21.87a3.898 3.898 0 00-6.65 3.55A11.06 11.06 0 013.6 4.65a3.892 3.892 0 001.2 5.2c-.59-.02-1.14-.18-1.62-.45v.05c0 1.86 1.32 3.42 3.08 3.77a3.9 3.9 0 01-1.76.07c.5 1.57 1.95 2.71 3.67 2.74A7.82 7.82 0 012.2 18.57 11.06 11.06 0 008.29 20c6.87 0 10.62-5.7 10.62-10.63l-.01-.47c.73-.53 1.36-1.2 1.86-1.96z" fill="currentColor"/>
                                </svg>
                            </span>
                            X
                        </a>
                    </div>
                </div>

                <div class="card info-card">
                    <div class="order-panel-header">
                        <p class="orders-overline">Sea Fresh Zanzibar</p>
                        <h2>Premium seafood ordering</h2>
                    </div>
                    <p>Premium seafood ordering and delivery system for hotels and restaurants across Zanzibar.</p>

                    <div class="info-steps">
                        <div class="info-step"><strong>Step 1:</strong> Submit your order</div>
                        <div class="info-step"><strong>Step 2:</strong> Admin reviews</div>
                        <div class="info-step"><strong>Step 3:</strong> Get delivered</div>
                    </div>

                    <div class="info-approval">
                        <h3>Admin Approval</h3>
                        <p>All orders are carefully reviewed. Track status in order history.</p>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection


