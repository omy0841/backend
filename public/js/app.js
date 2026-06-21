// Modern Seafood Ordering System - JavaScript

document.addEventListener('DOMContentLoaded', function() {
  // Form validation
  validateForms();
  
  // Cart functionality
  initCart();
  
  // Order total preview
  initOrderTotals();
  
  // Smooth scroll for anchors
  smoothScroll();
  
  // Image lazy loading
  lazyLoadImages();
  
  // Mobile menu toggle
  mobileMenuToggle();
});

// ============ FORM VALIDATION ============
function validateForms() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!validateFormFields(this)) {
        e.preventDefault();
        showError('Please fill all required fields correctly.');
      }
    });
  });
}

function validateFormFields(form) {
  let isValid = true;
  const errors = {};
  
  form.querySelectorAll('[required]').forEach(field => {
    const value = field.value.trim();
    
    if (!value) {
      errors[field.name] = `${field.placeholder || field.name} is required.`;
      isValid = false;
    }
    
    if (field.type === 'email' && value && !isValidEmail(value)) {
      errors[field.name] = 'Please enter a valid email.';
      isValid = false;
    }
    
    if (field.type === 'tel' && value && !isValidPhone(value)) {
      errors[field.name] = 'Please enter a valid phone number.';
      isValid = false;
    }
  });
  
  displayFieldErrors(form, errors);
  return isValid;
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPhone(phone) {
  return /^[\d\s\-\+\(\)]{7,}$/.test(phone);
}

function displayFieldErrors(form, errors) {
  // Remove existing error displays
  form.querySelectorAll('.form-error').forEach(el => el.remove());
  
  // Add new errors
  Object.keys(errors).forEach(fieldName => {
    const field = form.querySelector(`[name="${fieldName}"]`);
    if (field) {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'form-error';
      errorDiv.textContent = errors[fieldName];
      field.parentElement.appendChild(errorDiv);
      field.style.borderColor = '#ef4444';
    }
  });
}

// ============ CART FUNCTIONALITY ============
function initCart() {
  const cartContainer = document.getElementById('cartItems');
  if (!cartContainer) return;
  
  const addToCartBtns = document.querySelectorAll('.add-to-cart');
  addToCartBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      addToCart(this);
    });
  });
}

function addToCart(button) {
  const card = button.closest('.product-card');
  const name = card.querySelector('.product-name').textContent;
  const qtyInput = card.querySelector('.qty-control input');
  const qty = parseInt(qtyInput.value) || 1;
  
  if (qty > 0) {
    const cartContainer = document.getElementById('cartItems');
    const existingItem = cartContainer.querySelector(`[data-product="${name}"]`);
    
    if (existingItem) {
      const currentQty = parseInt(existingItem.getAttribute('data-qty')) + qty;
      existingItem.setAttribute('data-qty', currentQty);
      existingItem.querySelector('.item-qty').textContent = currentQty;
    } else {
      const cartItem = document.createElement('div');
      cartItem.className = 'cart-item';
      cartItem.setAttribute('data-product', name);
      cartItem.setAttribute('data-qty', qty);
      cartItem.innerHTML = `
        <div class="cart-item-info">
          <h4>${name}</h4>
          <small>Qty: <span class="item-qty">${qty}</span></small>
        </div>
        <button class="btn-remove" onclick="removeFromCart('${name}')">Remove</button>
      `;
      cartContainer.appendChild(cartItem);
    }
    
    qtyInput.value = 1;
    showSuccess(`${name} added to cart!`);
    updateCartTotal();
  }
}

function removeFromCart(productName) {
  const cartContainer = document.getElementById('cartItems');
  const item = cartContainer.querySelector(`[data-product="${productName}"]`);
  if (item) {
    item.remove();
    showSuccess(`${productName} removed from cart.`);
    updateCartTotal();
  }
}

function updateCartTotal() {
  const cartContainer = document.getElementById('cartItems');
  const totalSpan = document.getElementById('orderTotal');
  
  if (!cartContainer || !totalSpan) return;
  
  let total = 0;
  cartContainer.querySelectorAll('.cart-item').forEach(item => {
    const qty = parseInt(item.getAttribute('data-qty')) || 1;
    total += qty * 50000; // Example price per item
  });
  
  totalSpan.textContent = `TZS ${total.toLocaleString()}`;
}

// ============ NOTIFICATIONS ============
function showError(message) {
  showNotification(message, 'error');
}

function showSuccess(message) {
  showNotification(message, 'success');
}

function showNotification(message, type) {
  const notification = document.createElement('div');
  notification.className = type === 'error' ? 'error-message' : 'success-message';
  notification.textContent = message;
  notification.style.position = 'fixed';
  notification.style.top = '20px';
  notification.style.right = '20px';
  notification.style.zIndex = '9999';
  notification.style.maxWidth = '400px';
  notification.style.animation = 'slideIn 0.3s ease';
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 4000);
}

// ============ SMOOTH SCROLL ============
function smoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// ============ LAZY LOAD IMAGES ============
function lazyLoadImages() {
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src || img.src;
          img.classList.add('loaded');
          imageObserver.unobserve(img);
        }
      });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }
}

// ============ MOBILE MENU ============
function mobileMenuToggle() {
  const toggleBtn = document.querySelector('.mobile-toggle');
  const navMenu = document.querySelector('nav');
  
  if (toggleBtn && navMenu) {
    toggleBtn.addEventListener('click', function() {
      navMenu.classList.toggle('active');
      this.textContent = navMenu.classList.contains('active') ? '✕' : '☰';
    });
  }
}

function initOrderTotals() {
  const totalDisplay = document.getElementById('estimatedTotal');
  const totalField = document.getElementById('totalAmountField');
  if (!totalDisplay) return;

  const itemCheckboxes = document.querySelectorAll('input[name="items[]"]');
  const quantityInputs = document.querySelectorAll('input[name^="quantity"]');

    const calculateTotal = () => {
    let total = 0;

    itemCheckboxes.forEach(checkbox => {
      if (!checkbox.checked) return;
      // parse price robustly (remove commas or non-numeric chars)
      const rawPrice = (checkbox.dataset.price || '0').toString();
      const pricePerUnit = parseFloat(rawPrice.replace(/[^0-9.\-]/g, '')) || 0;
      const unitType = checkbox.dataset.unit || 'unit';
      let quantityInput = document.querySelector(`input[name="quantity[${checkbox.value}]"]`);
      if (!quantityInput) quantityInput = checkbox.closest('.order-product-card')?.querySelector('.product-qty') || checkbox.closest('.order-product-card')?.querySelector('input[type="number"]');
      const rawQty = quantityInput ? (quantityInput.value || '0') : '0';
      const qty = Math.max(unitType === 'kilo' ? 0.5 : 1, Number(String(rawQty).replace(/[^0-9.\-]/g, '')) || 0);
      total += pricePerUnit * qty;
    });

    totalDisplay.textContent = `TZS ${total.toLocaleString()}`;
    if (totalField) {
      totalField.value = total;
    }
  };

  itemCheckboxes.forEach(checkbox => checkbox.addEventListener('change', calculateTotal));
  quantityInputs.forEach(input => input.addEventListener('input', calculateTotal));
  calculateTotal();
}

// Recalculate totals (exposed for external callers like quick-add)
function recalcTotals() {
  const totalDisplay = document.getElementById('estimatedTotal');
  const totalField = document.getElementById('totalAmountField');
  if (!totalDisplay) return;

  const itemCheckboxes = document.querySelectorAll('input[name="items[]"]');

  let total = 0;
  itemCheckboxes.forEach(checkbox => {
    if (!checkbox.checked) return;
    const rawPrice = (checkbox.dataset.price || '0').toString();
    const pricePerUnit = parseFloat(rawPrice.replace(/[^0-9.\-]/g, '')) || 0;
    const unitType = checkbox.dataset.unit || 'unit';
    let quantityInput = document.querySelector(`input[name="quantity[${checkbox.value}]"]`);
    if (!quantityInput) quantityInput = checkbox.closest('.order-product-card')?.querySelector('.product-qty') || checkbox.closest('.order-product-card')?.querySelector('input[type="number"]');
    const rawQty = quantityInput ? (quantityInput.value || '0') : '0';
    const qty = Math.max(unitType === 'kilo' ? 0.5 : 1, Number(String(rawQty).replace(/[^0-9.\-]/g, '')) || 0);
    total += pricePerUnit * qty;
  });

  const newText = `TZS ${total.toLocaleString()}`;
  if (totalDisplay.textContent !== newText) {
    totalDisplay.textContent = newText;
    totalDisplay.classList.add('pulse-update');
    setTimeout(() => totalDisplay.classList.remove('pulse-update'), 500);
  }
  if (totalField) totalField.value = total;
}

  // toggle selected class when checkboxes change (global handler)
  document.querySelectorAll('input[name="items[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const card = checkbox.closest('.order-product-card');
      if (card) {
        if (checkbox.checked) card.classList.add('selected'); else card.classList.remove('selected');
      }
    });
  });

// ============ PRODUCT CARD INTERACTIVITY ============
function handleQuickAdd(btn) {
  console.log('handleQuickAdd: clicked', btn, 'dataset.target=', btn?.dataset?.target);
  let card = btn.closest('.order-product-card');
  let checkbox = card?.querySelector('input[name="items[]"]');
  if (!checkbox && btn.dataset.target) {
    checkbox = document.getElementById(btn.dataset.target);
    card = checkbox?.closest('.order-product-card');
  }
  if (!checkbox) {
    console.warn('handleQuickAdd: checkbox not found', btn, btn.dataset?.target);
    return;
  }

  const productId = checkbox.value;
  const qtyInput = card?.querySelector(`input[name="quantity[${productId}]"]`) ||
    document.getElementById(`quantity_${productId}`) ||
    card?.querySelector('.product-qty') ||
    card?.querySelector('input[type="number"]');
  let addedQty = 1;

  if (qtyInput) {
    const oldValue = Number(qtyInput.value) || 0;
    const step = Number(qtyInput.getAttribute('step')) || (qtyInput.getAttribute('min') ? Number(qtyInput.getAttribute('min')) : 1);
    const max = qtyInput.getAttribute('max') ? Number(qtyInput.getAttribute('max')) : Infinity;
    const newValue = Math.min(max, +(oldValue + step).toFixed(3));
    qtyInput.value = newValue;
    qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
    addedQty = Math.max(0, newValue - oldValue);
  }

  checkbox.checked = true;
  checkbox.dispatchEvent(new Event('change', { bubbles: true }));

  const pricePerUnit = parseFloat((checkbox.dataset.price || '0').toString().replace(/[^0-9.\-]/g, '')) || 0;
  if (addedQty > 0) {
    const totalDisplay = document.getElementById('estimatedTotal');
    const totalField = document.getElementById('totalAmountField');
    if (totalDisplay) {
      const currentTotal = Number(totalDisplay.textContent.replace(/[^0-9.]/g, '')) || 0;
      const newTotal = currentTotal + pricePerUnit * addedQty;
      console.log('handleQuickAdd: currentTotal=', currentTotal, 'pricePerUnit=', pricePerUnit, 'addedQty=', addedQty, 'newTotal=', newTotal);
      totalDisplay.textContent = `TZS ${newTotal.toLocaleString()}`;
      totalDisplay.classList.add('pulse-update');
      setTimeout(() => totalDisplay.classList.remove('pulse-update'), 500);
      if (totalField) totalField.value = newTotal;
    }
  }

  if (card) card.classList.add('selected');
  recalcTotals();
  showSuccess('Added to order');
}

function initProductCards() {
  document.querySelectorAll('.order-product-card').forEach(card => {
    card.addEventListener('click', function (e) {
      if (e.target.closest('input') || e.target.closest('button') || e.target.closest('a')) return;
      card.classList.toggle('expanded');
    });
  });

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.quick-add');
    if (!btn) return;
    e.stopPropagation();
    handleQuickAdd(btn);
  });

  // also bind direct onclick handlers to guarantee Quick add works
  document.querySelectorAll('.quick-add').forEach(btn => {
    btn.onclick = function (e) {
      e.preventDefault();
      handleQuickAdd(this);
      return false;
    };
  });
}

// expose for debugging
window.recalcTotals = recalcTotals;
window.initProductCards = initProductCards;
window.quickAddProduct = handleQuickAdd;
window.quickAddOnOrderPage = handleQuickAdd;

// ============ QUANTITY CONTROLS ============
function changeQty(input, delta) {
  let value = parseInt(input.value) || 1;
  value = Math.max(1, value + delta);
  input.value = value;
}

// ============ ANIMATIONS ============
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// Export functions for global use
window.changeQty = changeQty;
window.removeFromCart = removeFromCart;
window.showError = showError;
window.showSuccess = showSuccess;

// ============ MAP & GEOLOCATION ============
function initMapForOrder() {
  const mapEl = document.getElementById('orderMap');
  if (!mapEl || typeof L === 'undefined') return;

  const latField = document.getElementById('latitudeField');
  const lngField = document.getElementById('longitudeField');

  const defaultLat = latField?.value ? Number(latField.value) : -6.1630;
  const defaultLng = lngField?.value ? Number(lngField.value) : 39.1979;

  const map = L.map('orderMap').setView([defaultLat, defaultLng], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  let marker = null;
  function setMarker(lat, lng, label) {
    if (marker) marker.remove();
    marker = L.marker([lat, lng]).addTo(map).bindPopup(label || 'Delivery location').openPopup();
    map.setView([lat, lng], 14);
    if (latField) latField.value = lat;
    if (lngField) lngField.value = lng;
  }

  // click on map to set location
  map.on('click', function (e) {
    setMarker(e.latlng.lat, e.latlng.lng, 'Selected location');
  });

  // Use my location button
  const useBtn = document.getElementById('useLocationBtn');
  if (useBtn) {
    useBtn.addEventListener('click', function () {
      if (!navigator.geolocation) {
        showError('Geolocation is not supported by your browser');
        return;
      }
      navigator.geolocation.getCurrentPosition(function (pos) {
        setMarker(pos.coords.latitude, pos.coords.longitude, 'Your location');
      }, function (err) {
        showError('Unable to retrieve your location');
      }, { enableHighAccuracy: true });
    });
  }

  // Find address by calling Nominatim
  const findBtn = document.getElementById('findAddressBtn');
  const addressInput = document.querySelector('input[name="delivery_location"]');
  if (findBtn && addressInput) {
    findBtn.addEventListener('click', async function () {
      const q = addressInput.value.trim();
      if (!q) { showError('Please enter the delivery address first'); return; }
      try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
        const data = await res.json();
        if (data && data.length) {
          const lat = parseFloat(data[0].lat);
          const lon = parseFloat(data[0].lon);
          setMarker(lat, lon, data[0].display_name);
          showSuccess('Address located on the map');
        } else {
          showError('Address not found. Try a more specific query');
        }
      } catch (e) {
        showError('Address lookup failed');
      }
    });
  }
}

// Initialize map when DOM has fully loaded and after init functions
document.addEventListener('DOMContentLoaded', function() {
  initMapForOrder();
  initProductCards();
  initChat();
});

// ============ I18N (English / Kiswahili) ============
const I18N_STRINGS = {
  en: {
    brand: 'Seif Sea Fresh Zanzibar (from Pemba)',
    footer_brand: 'Seif Sea Fresh Zanzibar © 2026',
    footer_note: 'Fresh seafood delivery for hotels and restaurants across Zanzibar',
    order_overline: 'Order Fresh Seafood',
    order_title: 'Submit your hotel or restaurant order and our admin team will review it for approval.',
    order_intro: 'Premium seafood ordering and delivery system for hotels and restaurants across Zanzibar.',
    order_details_overline: 'Order Details',
    order_details_title: 'Tell us where and what you need today.',
    contact_name: 'Contact Name',
    hotel_name: 'Hotel / Restaurant Name',
    phone_number: 'Phone Number',
    email_address: 'Email Address',
    delivery_location_label: 'Delivery Location',
    delivery_location: 'Search city, street, or hotel',
    use_location: 'Use my location',
    find_location: 'Find location',
    location_help: 'Enter a city, street, or hotel name and tap Find location to place your delivery pin.',
    select_products_overline: 'Select Seafood Products',
    select_products_note: 'Choose the best seafood for your hotel or restaurant.',
    custom_items: 'Custom Order Items',
    special_request: 'Special Preparation Request',
    estimated_total: 'Estimated total',
    submit_order: 'Submit Order'
  },
  sw: {
    brand: 'Seif Sea Fresh Zanzibar (kutoka Pemba)',
    footer_brand: 'Seif Sea Fresh Zanzibar © 2026',
    footer_note: 'Usambazaji wa samaki safi kwa hoteli na mikahawa Zanzibar',
    order_overline: 'Agiza Samaki Safi',
    order_title: 'Wasilisha oda yako ya hoteli au mkahawa na timu yetu ya admin itaitathmini.',
    order_intro: 'Mfumo wa kuagiza samaki wa ubora kwa hoteli na mikahawa Zanzibar.',
    order_details_overline: 'Maelezo ya Oda',
    order_details_title: 'Tueleze wapi na vitu unavyohitaji leo.',
    contact_name: 'Jina la Mwasiliani',
    hotel_name: 'Jina la Hoteli / Mkahawa',
    phone_number: 'Nambari ya Simu',
    email_address: 'Anwani ya Barua Pepe',
    delivery_location_label: 'Mahali pa Uwasilishaji',
    delivery_location: 'Tafuta mji, mtaa, au hoteli',
    use_location: 'Tumia eneo langu',
    find_location: 'Tafuta mahali',
    location_help: 'Weka mji, mtaa, au jina la hoteli kisha bonyeza Tafuta mahali kuweka alama ya uwasilishaji.',
    select_products_overline: 'Chagua Bidhaa za Samaki',
    select_products_note: 'Chagua samaki bora kwa hoteli au mkahawa wako.',
    custom_items: 'Vitu Maalum vya Oda',
    special_request: 'Maombi ya Maandalizi Maalum',
    estimated_total: 'Jumla Inakadiriwa',
    submit_order: 'Wasilisha Oda'
  }
};

function applyLanguage(lang) {
  const dict = I18N_STRINGS[lang] || I18N_STRINGS.en;
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (dict[key]) el.textContent = dict[key];
  });
  document.querySelectorAll('[data-i18n-placeholder]').forEach(inp => {
    const key = inp.getAttribute('data-i18n-placeholder');
    if (dict[key]) inp.setAttribute('placeholder', dict[key]);
  });
  // update lang active state
  document.getElementById('lang-en').classList.toggle('active', lang === 'en');
  document.getElementById('lang-sw').classList.toggle('active', lang === 'sw');
  localStorage.setItem('lang', lang);
}

document.addEventListener('DOMContentLoaded', function () {
  // attach language toggle handlers
  const enBtn = document.getElementById('lang-en');
  const swBtn = document.getElementById('lang-sw');
  if (enBtn && swBtn) {
    enBtn.addEventListener('click', () => applyLanguage('en'));
    swBtn.addEventListener('click', () => applyLanguage('sw'));
    const stored = localStorage.getItem('lang') || 'en';
    applyLanguage(stored);
  }
});

// ============ SIMPLE LIVECHAT WIDGET ============
function initChat() {
  const chatRoot = document.getElementById('liveChatRoot');
  if (!chatRoot) return;

  const messagesBox = document.createElement('div');
  messagesBox.style.maxHeight = '300px';
  messagesBox.style.overflow = 'auto';
  messagesBox.style.padding = '0.5rem';
  messagesBox.style.background = '#fff';
  messagesBox.style.border = '1px solid rgba(0,0,0,0.06)';
  messagesBox.style.borderRadius = '0.5rem';

  const form = document.createElement('form');
  form.style.display = 'flex';
  form.style.gap = '0.5rem';
  form.style.marginTop = '0.5rem';

  const input = document.createElement('input');
  input.type = 'text';
  input.placeholder = 'Type a message...';
  input.style.flex = '1';
  input.required = true;

  const send = document.createElement('button');
  send.type = 'submit';
  send.className = 'btn btn-primary';
  send.textContent = 'Send';

  form.appendChild(input);
  form.appendChild(send);

  chatRoot.appendChild(messagesBox);
  chatRoot.appendChild(form);

  async function loadMessages() {
    try {
      const res = await fetch('/chat/messages');
      const data = await res.json();
      messagesBox.innerHTML = '';
      data.reverse().forEach(m => {
        const el = document.createElement('div');
        el.style.padding = '0.5rem 0';
        el.innerHTML = `<strong>${escapeHtml(m.name)}</strong>: ${escapeHtml(m.message)}`;
        messagesBox.appendChild(el);
      });
      messagesBox.scrollTop = messagesBox.scrollHeight;
    } catch (e) {
      console.error(e);
    }
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const txt = input.value.trim();
    if (!txt) return;
    try {
      await fetch('/chat/messages', { method: 'POST', headers: { 'Content-Type': 'application/json','X-CSRF-TOKEN': getCsrfToken() }, body: JSON.stringify({ message: txt }) });
      input.value = '';
      await loadMessages();
    } catch (err) {
      showError('Unable to send message');
    }
  });

  loadMessages();
  setInterval(loadMessages, 5000);
}

// admin chat
function initAdminChat() {
  const root = document.getElementById('adminChatRoot');
  if (!root) return;

  const searchInput = document.getElementById('chatSearchInput');

  const messagesBox = document.createElement('div');
  messagesBox.style.maxHeight = '500px';
  messagesBox.style.overflow = 'auto';
  messagesBox.style.padding = '0.5rem';
  messagesBox.style.background = '#fff';
  messagesBox.style.border = '1px solid rgba(0,0,0,0.06)';
  messagesBox.style.borderRadius = '0.5rem';

  const form = document.createElement('form');
  form.style.display = 'flex';
  form.style.gap = '0.5rem';
  form.style.marginTop = '0.5rem';

  const input = document.createElement('input');
  input.type = 'text';
  input.placeholder = 'Reply to customers...';
  input.style.flex = '1';
  input.required = true;

  const send = document.createElement('button');
  send.type = 'submit';
  send.className = 'btn btn-primary';
  send.textContent = 'Send';

  form.appendChild(input);
  form.appendChild(send);

  root.appendChild(messagesBox);
  root.appendChild(form);

  async function loadMessages(searchQuery = '') {
    try {
      const url = searchQuery ? `/chat/messages?search=${encodeURIComponent(searchQuery)}` : '/chat/messages';
      const res = await fetch(url);
      const data = await res.json();
      messagesBox.innerHTML = '';
      data.reverse().forEach(m => {
        const el = document.createElement('div');
        el.style.padding = '0.5rem 0';
        el.style.borderBottom = '1px solid #eee';
        el.innerHTML = `<strong>${escapeHtml(m.name)}</strong>: ${escapeHtml(m.message)}`;
        messagesBox.appendChild(el);
      });
      messagesBox.scrollTop = messagesBox.scrollHeight;
    } catch (e) {
      console.error(e);
    }
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const txt = input.value.trim();
    if (!txt) return;
    try {
      await fetch('/chat/messages', { method: 'POST', headers: { 'Content-Type': 'application/json','X-CSRF-TOKEN': getCsrfToken() }, body: JSON.stringify({ message: txt }) });
      input.value = '';
      await loadMessages(searchInput ? searchInput.value : '');
    } catch (err) {
      showError('Unable to send message');
    }
  });

  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
      loadMessages(e.target.value);
    });
  }

  loadMessages();
  setInterval(function() {
    loadMessages(searchInput ? searchInput.value : '');
  }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
  initAdminChat();
});

function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; });
}

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}
