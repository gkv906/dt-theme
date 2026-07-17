// ===== WordPress / HTML Compatibility Layer =====
const _isWP = typeof dt_theme_vars !== 'undefined';
const _IMG_BASE = _isWP ? (dt_theme_vars.images_url + '/') : 'assets/images/';
const _URL = {
  home:     _isWP ? dt_theme_vars.home_url         : 'index.html',
  shop:     _isWP ? dt_theme_vars.shop_url          : 'shop.html',
  cart:     _isWP ? dt_theme_vars.cart_url          : 'cart.html',
  checkout: _isWP ? dt_theme_vars.checkout_url      : 'checkout.html',
  account:  _isWP ? dt_theme_vars.account_url       : 'my-account.html',
  wishlist: _isWP ? dt_theme_vars.wishlist_page_url : 'wishlist.html',
  track:    _isWP ? dt_theme_vars.track_url         : 'order-tracking.html',
  about:    _isWP ? dt_theme_vars.about_url         : 'about.html',
  story:    _isWP ? dt_theme_vars.story_url         : 'our-story.html',
  faq:      _isWP ? dt_theme_vars.faq_url           : 'faqs.html',
  shipping: _isWP ? dt_theme_vars.shipping_url      : 'shipping-policy.html',
  contact:  _isWP ? dt_theme_vars.contact_url       : 'contact.html',
};
function _productUrl(id) {
  return _isWP ? (String(dt_theme_vars.home_url).replace(/\/$/, '') + '/?p=' + id) : ('product.html?id=' + id);
}
// ================================================
// Global Products Catalog
const PRODUCTS = [
  { id: 1, name: "Midnight Onyx Banarasi", fabric: "Pure Silk Banarasi", rating: 4.9, price: 24999, mrp: 35000, discount: "28", img1: _IMG_BASE+"saree-1.jpg", img2: _IMG_BASE+"saree-2.jpg", gallery: [_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-4.jpg"], isNew: true },
  { id: 2, name: "Royal Amber Drape", fabric: "Kanjeevaram Silk", rating: 4.8, price: 18500, mrp: 25000, discount: "26", img1: _IMG_BASE+"saree-2.jpg", img2: _IMG_BASE+"saree-3.jpg", gallery: [_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-1.jpg"], isNew: false },
  { id: 3, name: "Blush Rose Organza", fabric: "Embroidered Organza", rating: 4.7, price: 12999, mrp: 18500, discount: "30", img1: _IMG_BASE+"saree-3.jpg", img2: _IMG_BASE+"saree-4.jpg", gallery: [_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-2.jpg"], isNew: true },
  { id: 4, name: "Crimson Bandhani", fabric: "Georgette Bandhani", rating: 4.9, price: 15499, mrp: 22000, discount: "29", img1: _IMG_BASE+"saree-4.jpg", img2: _IMG_BASE+"saree-1.jpg", gallery: [_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-3.jpg"], isNew: false },
  { id: 5, name: "Emerald Zari Tissue", fabric: "Tissue Silk", rating: 4.6, price: 21000, mrp: 28000, discount: "25", img1: _IMG_BASE+"saree-1.jpg", img2: _IMG_BASE+"saree-3.jpg", gallery: [_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-4.jpg"], isNew: true },
  { id: 6, name: "Ivory Pearl Border", fabric: "Chiffon Silk", rating: 4.8, price: 9999, mrp: 14500, discount: "31", img1: _IMG_BASE+"saree-2.jpg", img2: _IMG_BASE+"saree-4.jpg", gallery: [_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-1.jpg"], isNew: false },
  { id: 7, name: "Sapphire Night Katan", fabric: "Katan Silk", rating: 5.0, price: 32000, mrp: 45000, discount: "28", img1: _IMG_BASE+"saree-3.jpg", img2: _IMG_BASE+"saree-1.jpg", gallery: [_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-2.jpg"], isNew: true },
  { id: 8, name: "Golden Aura Chanderi", fabric: "Chanderi Silk", rating: 4.7, price: 11500, mrp: 16000, discount: "28", img1: _IMG_BASE+"saree-4.jpg", img2: _IMG_BASE+"saree-2.jpg", gallery: [_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-3.jpg"], isNew: false },
  { id: 9, name: "Ruby Brocade Saree", fabric: "Brocade Silk", rating: 4.9, price: 27500, mrp: 38000, discount: "27", img1: _IMG_BASE+"saree-1.jpg", img2: _IMG_BASE+"saree-4.jpg", gallery: [_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-4.jpg",_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-3.jpg"], isNew: false },
  { id: 10, name: "Silver Dust Georgette", fabric: "Georgette", rating: 4.8, price: 14200, mrp: 20000, discount: "29", img1: _IMG_BASE+"saree-2.jpg", img2: _IMG_BASE+"saree-3.jpg", gallery: [_IMG_BASE+"saree-2.jpg",_IMG_BASE+"saree-3.jpg",_IMG_BASE+"saree-1.jpg",_IMG_BASE+"saree-4.jpg"], isNew: true }
];

// Initialize State
let cart = _isWP ? [] : (JSON.parse(localStorage.getItem('arshman_cart')) || [
  { id: 1, quantity: 1, color: 'black', size: '5.5m' },
  { id: 2, quantity: 1, color: 'deep red', size: 'Free Size' }
]);
let wpCartCount = _isWP ? Number(dt_theme_vars.cart_count || 0) : 0;
let wishlist = _isWP
  ? (Array.isArray(dt_theme_vars.wishlist_items) ? dt_theme_vars.wishlist_items.map(Number) : [])
  : (JSON.parse(localStorage.getItem('arshman_wishlist')) || [3, 5]);
let user = JSON.parse(localStorage.getItem('arshman_user')) || null;

// Currency formatter
function formatCurrency(num) {
  return '₹' + Number(num).toLocaleString('en-IN');
}

// Save State helpers
function saveCart() {
  if (_isWP) {
    updateHeaderWidgets();
    return;
  }
  localStorage.setItem('arshman_cart', JSON.stringify(cart));
  updateHeaderWidgets();
  renderCartDrawer();
  if (typeof renderCheckoutSummary === 'function') renderCheckoutSummary();
}

function saveWishlist() {
  if (_isWP) {
    updateHeaderWidgets();
    return;
  }
  localStorage.setItem('arshman_wishlist', JSON.stringify(wishlist));
  updateHeaderWidgets();
  if (typeof renderWishlistPage === 'function') renderWishlistPage();
}

// Update Header Icons (Counts)
function updateHeaderWidgets() {
  const cartBadges = document.querySelectorAll('.cart-badge');
  const cartCount = _isWP ? wpCartCount : cart.reduce((sum, item) => sum + item.quantity, 0);
  cartBadges.forEach(badge => {
    badge.textContent = cartCount;
    if (cartCount > 0) {
      badge.classList.remove('hidden');
    } else {
      badge.classList.add('hidden');
    }
  });

  const wishlistBadges = document.querySelectorAll('.wishlist-badge');
  wishlistBadges.forEach(badge => {
    badge.textContent = wishlist.length;
    if (wishlist.length > 0) {
      badge.classList.remove('hidden');
    } else {
      badge.classList.add('hidden');
    }
  });

  // Amazon Header username greeting
  const userLabels = document.querySelectorAll('[data-user-toggle] span.user-greeting');
  userLabels.forEach(label => {
    if (user) {
      label.textContent = `Hello, ${user.name}`;
    } else {
      label.textContent = 'Hello, Sign In';
    }
  });
}

function getCardProductMeta(productId, contextEl) {
  const product = PRODUCTS.find(p => p.id === productId);
  const card = contextEl ? contextEl.closest('.arrival-card, .product-card, li.product') : null;
  const titleEl = card ? card.querySelector('.arrival-title, .woocommerce-loop-product__title, h2, h3, h4') : null;
  const imgEl = card ? card.querySelector('img') : null;

  return {
    name: product ? product.name : (titleEl ? titleEl.textContent.trim() : 'Product'),
    img: product ? product.img1 : (imgEl ? imgEl.currentSrc || imgEl.src : _IMG_BASE + 'saree-1.jpg')
  };
}

function setButtonBusy(button, busy) {
  if (!button) return;
  button.disabled = !!busy;
  button.classList.toggle('opacity-60', !!busy);
  button.classList.toggle('pointer-events-none', !!busy);
}

// Show Toast Alert
function showCartToast(productName, img) {
  // Remove existing toasts
  const existing = document.getElementById('cart-toast');
  if (existing) existing.remove();

  const toastHTML = `
    <div id="cart-toast" class="fixed top-24 right-4 z-50 w-80 bg-[#111] border border-[#C8A46A]/30 text-[#F7F4EE] shadow-2xl p-4 animate-slide-in-right">
      <div class="flex gap-3">
        <img src="${img}" alt="${productName}" class="w-12 h-16 object-cover border border-[#C8A46A]/20" />
        <div class="flex-1">
          <div class="text-[10px] text-[#C8A46A] uppercase tracking-widest font-semibold mb-0.5">Added to Cart</div>
          <div class="text-sm font-medium text-white truncate w-48">${productName}</div>
          <div class="text-xs text-[#a3a3a3] mt-1">Successfully added to bag.</div>
        </div>
        <button onclick="document.getElementById('cart-toast').remove()" class="text-[#a3a3a3] hover:text-white self-start">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="mt-3 flex items-center justify-between gap-4">
        <a href="${_URL.checkout}" class="text-xs uppercase tracking-wider text-black bg-[#C8A46A] px-3 py-1.5 font-bold hover:bg-[#b08d55] transition-colors">Checkout</a>
        ${_isWP
          ? `<a href="${_URL.cart}" class="text-xs uppercase tracking-wider text-[#C8A46A] font-semibold hover:underline">View Cart</a>`
          : `<button onclick="toggleCartDrawer(true); document.getElementById('cart-toast').remove();" class="text-xs uppercase tracking-wider text-[#C8A46A] font-semibold hover:underline">View Cart</button>`}
      </div>
      <div class="absolute bottom-0 left-0 h-[2px] bg-[#C8A46A] animate-progress-drain"></div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', toastHTML);

  setTimeout(() => {
    const toast = document.getElementById('cart-toast');
    if (toast) {
      toast.classList.add('opacity-0');
      toast.style.transition = 'opacity 0.5s ease';
      setTimeout(() => toast.remove(), 500);
    }
  }, 5000);
}

// Add Item to Cart
async function addToCart(productId, quantity = 1, color = 'black', size = '5.5m', contextEl = null) {
  if (_isWP) {
    const meta = getCardProductMeta(productId, contextEl);
    const body = new URLSearchParams();
    body.append('product_id', productId);
    body.append('quantity', quantity);

    setButtonBusy(contextEl, true);

    try {
      const response = await fetch(dt_theme_vars.add_to_cart_url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body
      });
      const data = await response.json();

      if (!response.ok || data.error) {
        if (data.product_url) {
          window.location.href = data.product_url;
          return;
        }
        throw new Error(data.message || 'Unable to add product to cart.');
      }

      wpCartCount += Number(quantity) || 1;
      updateHeaderWidgets();
      showCartToast(meta.name, meta.img);
      document.body.dispatchEvent(new CustomEvent('added_to_cart', { detail: data }));
    } catch (error) {
      alert(error.message || 'Unable to add product to cart. Please try again.');
    } finally {
      setButtonBusy(contextEl, false);
    }
    return;
  }

  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;

  const existingIndex = cart.findIndex(item => item.id === productId && item.color === color && item.size === size);
  if (existingIndex > -1) {
    cart[existingIndex].quantity += quantity;
  } else {
    cart.push({ id: productId, quantity, color, size });
  }

  saveCart();
  showCartToast(product.name, product.img1);
}

// Toggle Wishlist
async function toggleWishlist(productId, contextEl = null) {
  if (_isWP) {
    const isInWishlist = wishlist.includes(Number(productId));
    const body = new URLSearchParams();
    body.append('action', isInWishlist ? 'dt_remove_from_wishlist' : 'dt_add_to_wishlist');
    body.append('nonce', dt_theme_vars.wishlist_nonce);
    body.append('product_id', productId);

    setButtonBusy(contextEl, true);

    try {
      const response = await fetch(dt_theme_vars.ajax_url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body
      });
      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error((result.data && result.data.message) || 'Unable to update wishlist.');
      }

      wishlist = Array.isArray(result.data.items) ? result.data.items.map(Number) : wishlist;
      updateHeaderWidgets();
      updateWishlistButtons(productId);
    } catch (error) {
      alert(error.message || 'Unable to update wishlist. Please try again.');
    } finally {
      setButtonBusy(contextEl, false);
    }
    return;
  }

  const index = wishlist.indexOf(productId);
  if (index > -1) {
    wishlist.splice(index, 1);
  } else {
    wishlist.push(productId);
  }
  saveWishlist();

  updateWishlistButtons(productId);
}

function updateWishlistButtons(productId) {
  const heartBtns = document.querySelectorAll(`[data-wishlist-btn="${productId}"]`);
  heartBtns.forEach(btn => {
    if (wishlist.includes(Number(productId))) {
      btn.innerHTML = `<svg class="w-5 h-5 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
    } else {
      btn.innerHTML = `<svg class="w-5 h-5 text-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
    }
  });
}

// Cart Drawer Injection and Toggling
function toggleCartDrawer(open) {
  if (_isWP && open) {
    window.location.href = _URL.cart;
    return;
  }

  const drawer = document.getElementById('cart-drawer-overlay');
  if (!drawer) return;

  if (open) {
    drawer.classList.remove('hidden');
    setTimeout(() => {
      document.getElementById('cart-drawer-panel').classList.remove('translate-x-full');
    }, 10);
    renderCartDrawer();
  } else {
    document.getElementById('cart-drawer-panel').classList.add('translate-x-full');
    setTimeout(() => {
      drawer.classList.add('hidden');
    }, 300);
  }
}

// Render Items inside Cart Drawer
function renderCartDrawer() {
  const listContainer = document.getElementById('cart-drawer-items');
  const footerContainer = document.getElementById('cart-drawer-footer');
  if (!listContainer) return;

  if (cart.length === 0) {
    listContainer.innerHTML = `
      <div class="flex flex-col items-center justify-center h-full text-center p-8">
        <svg class="w-16 h-16 text-[#C8A46A]/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <h3 class="font-cormorant text-xl text-white mb-2 font-medium">Your Bag is Empty</h3>
        <p class="text-xs text-[#a3a3a3] mb-6 max-w-[240px]">Woven luxury awaits. Fill your bag with pieces crafted by masters.</p>
        <button onclick="toggleCartDrawer(false); window.location.href=_URL.shop;" class="btn-gold-shimmer px-6 py-3 text-xs uppercase tracking-widest font-semibold">Start Shopping</button>
      </div>
    `;
    if (footerContainer) footerContainer.classList.add('hidden');
    return;
  }

  if (footerContainer) footerContainer.classList.remove('hidden');

  let subtotal = 0;
  let itemsHTML = '';

  cart.forEach((item, index) => {
    const product = PRODUCTS.find(p => p.id === item.id);
    if (!product) return;

    const itemTotal = product.price * item.quantity;
    subtotal += itemTotal;

    itemsHTML += `
      <div class="flex gap-4 p-4 border-b border-[#C8A46A]/10">
        <img onclick="toggleCartDrawer(false); window.location.href=_productUrl(product.id)" src="${product.img1}" alt="${product.name}" class="w-16 h-20 object-cover border border-[#C8A46A]/20 cursor-pointer hover:border-[#C8A46A] transition-colors" />
        <div class="flex-1 min-w-0">
          <h4 onclick="toggleCartDrawer(false); window.location.href=_productUrl(product.id)" class="text-sm font-medium text-white truncate cursor-pointer hover:text-[#C8A46A] transition-colors">${product.name}</h4>
          <p class="text-xs text-[#C8A46A] mt-0.5">${product.fabric}</p>
          <div class="flex gap-3 text-[10px] text-[#a3a3a3] mt-1.5 uppercase tracking-wide">
            <span>Color: ${item.color}</span>
            <span>Size: ${item.size}</span>
          </div>
          <div class="flex items-center justify-between mt-3">
            <!-- Qty selector -->
            <div class="flex items-center border border-[#C8A46A]/30 bg-black/40">
              <button onclick="updateCartItemQty(${index}, ${item.quantity - 1})" class="px-2 py-1 text-xs text-[#a3a3a3] hover:text-[#C8A46A]">-</button>
              <span class="px-2 text-xs text-white">${item.quantity}</span>
              <button onclick="updateCartItemQty(${index}, ${item.quantity + 1})" class="px-2 py-1 text-xs text-[#a3a3a3] hover:text-[#C8A46A]">+</button>
            </div>
            <button onclick="removeCartItem(${index})" class="text-xs text-[#a3a3a3] hover:text-red-500 uppercase tracking-widest text-[9px]">Remove</button>
          </div>
        </div>
        <div class="text-right flex flex-col justify-between">
          <span class="text-sm font-semibold text-white">${formatCurrency(itemTotal)}</span>
          ${product.mrp ? `<span class="text-xs text-[#a3a3a3] line-through">${formatCurrency(product.mrp * item.quantity)}</span>` : ''}
        </div>
      </div>
    `;
  });

  listContainer.innerHTML = itemsHTML;

  // Update Footer totals
  document.getElementById('cart-drawer-subtotal').textContent = formatCurrency(subtotal);
}

function updateCartItemQty(index, qty) {
  if (qty <= 0) {
    removeCartItem(index);
    return;
  }
  cart[index].quantity = qty;
  saveCart();
}

function removeCartItem(index) {
  cart.splice(index, 1);
  saveCart();
}

// Inject global components (Cart Drawer & Login Modal)
function injectGlobalUI() {
  // Inject global custom scrollbars stylesheet dynamically
  if (!document.getElementById('global-scrollbar-styles')) {
    const style = document.createElement('style');
    style.id = 'global-scrollbar-styles';
    style.innerHTML = `
      .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
      }
      .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
      }
      .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #C8A46A;
        border-radius: 2px;
      }
      /* Ensure modal overlay scrolling doesn't show standard white scrollbars */
      #login-modal-overlay *, #cart-drawer-panel *, #address-modal *, #order-details-modal *, #support-modal *, #mobile-menu-drawer *, #mobile-menu-panel * {
        scrollbar-width: thin;
        scrollbar-color: #C8A46A rgba(255, 255, 255, 0.02);
      }
    `;
    document.head.appendChild(style);
  }

  // 1. Cart Drawer
  const cartDrawerHTML = `
    <div id="cart-drawer-overlay" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden">
      <!-- Backdrop close button -->
      <div class="absolute inset-0" onclick="toggleCartDrawer(false)"></div>
      <div id="cart-drawer-panel" class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-[#0a0a0a] border-l border-[#C8A46A]/20 shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out z-10">
        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40">
          <div class="flex items-center gap-3">
            <h2 class="font-cormorant text-2xl font-bold tracking-wider text-[#C8A46A]">Shopping Bag</h2>
            <span class="cart-badge bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
          </div>
          <button onclick="toggleCartDrawer(false)" class="text-[#a3a3a3] hover:text-[#C8A46A] transition-colors p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <!-- Drawer Items -->
        <div id="cart-drawer-items" class="flex-1 overflow-y-auto custom-scrollbar bg-black/20"></div>
        <!-- Drawer Footer -->
        <div id="cart-drawer-footer" class="p-6 border-t border-[#C8A46A]/20 bg-black/40 space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-sm uppercase tracking-widest text-[#a3a3a3]">Subtotal</span>
            <span id="cart-drawer-subtotal" class="text-lg font-bold text-white">₹0</span>
          </div>
          <p class="text-[10px] text-[#a3a3a3] leading-relaxed">Taxes, shipping, and promotional discounts calculated at checkout.</p>
          <div class="grid grid-cols-2 gap-4 pt-2">
            <button onclick="toggleCartDrawer(false); window.location.href=_URL.shop;" class="border border-[#C8A46A]/40 text-[#C8A46A] hover:bg-[#C8A46A]/10 py-3.5 uppercase tracking-widest text-xs font-semibold text-center transition-all">Shop More</button>
            <a href="${_URL.checkout}" class="btn-gold-shimmer py-3.5 uppercase tracking-widest text-xs font-semibold text-center">Checkout</a>
          </div>
        </div>
      </div>
    </div>
  `;

  // 2. Login Modal
  const loginModalHTML = `
    <div id="login-modal-overlay" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
      <div class="absolute inset-0" onclick="toggleLoginModal(false)"></div>
      <div class="relative bg-[#0d0d0d] border border-[#C8A46A]/30 w-full max-w-md max-h-[90vh] overflow-y-auto custom-scrollbar p-8 shadow-2xl z-10 animate-scale-in">
        <!-- Close Button -->
        <button onclick="toggleLoginModal(false)" class="absolute top-4 right-4 text-[#a3a3a3] hover:text-[#C8A46A]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Dynamic Content Forms -->
        <div id="login-form-view">
          <div class="text-center mb-6">
            <h2 class="font-cormorant text-3xl font-bold tracking-widest text-[#C8A46A] uppercase mb-2">Login</h2>
            <p class="text-xs text-[#a3a3a3]">Access your account, order status & wishlist</p>
          </div>
          <form onsubmit="handleAuthSubmit(event, 'login')" class="space-y-5">
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1.5 font-medium">Email Address</label>
              <input type="email" required id="auth-email-login" class="w-full bg-black/40 border border-[#C8A46A]/20 p-3 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="e.g. name@domain.com" />
            </div>
            <div>
              <div class="flex justify-between items-center mb-1.5">
                <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] font-medium">Password</label>
                <button type="button" onclick="switchAuthView('forgot')" class="text-[10px] text-[#C8A46A] hover:underline uppercase tracking-wider">Forgot?</button>
              </div>
              <input type="password" required id="auth-pass-login" class="w-full bg-black/40 border border-[#C8A46A]/20 p-3 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="••••••••" />
            </div>
            <button type="submit" class="btn-gold-shimmer w-full py-3.5 uppercase tracking-widest text-xs font-bold mt-2">Sign In</button>
          </form>
          <div class="mt-6 text-center text-xs text-[#a3a3a3] border-t border-[#C8A46A]/10 pt-5">
            Don't have an account? 
            <button onclick="switchAuthView('signup')" class="text-[#C8A46A] hover:underline font-medium">Create Account</button>
          </div>
        </div>

        <div id="signup-form-view" class="hidden">
          <div class="text-center mb-6">
            <h2 class="font-cormorant text-3xl font-bold tracking-widest text-[#C8A46A] uppercase mb-2">Create Account</h2>
            <p class="text-xs text-[#a3a3a3]">Join ARSHMAN & weave your style dreams</p>
          </div>
          <form onsubmit="handleAuthSubmit(event, 'signup')" class="space-y-4">
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium">Full Name</label>
              <input type="text" required id="auth-name-signup" class="w-full bg-black/40 border border-[#C8A46A]/20 p-2.5 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="Priya Sharma" />
            </div>
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium">Email Address</label>
              <input type="email" required id="auth-email-signup" class="w-full bg-black/40 border border-[#C8A46A]/20 p-2.5 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="priya@sharma.com" />
            </div>
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium">Mobile Number</label>
              <input type="tel" required id="auth-phone-signup" class="w-full bg-black/40 border border-[#C8A46A]/20 p-2.5 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="+91 98765 43210" />
            </div>
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-2 font-medium">Account Role <span class="text-red-400 font-bold">*</span></label>
              <input type="hidden" id="auth-role-signup" required value="" />
              <div class="grid grid-cols-2 gap-2 text-center text-[10px] uppercase tracking-wider font-semibold">
                <button type="button" onclick="selectAuthRole('customer')" id="role-btn-customer" class="role-btn border border-white/10 text-white/50 bg-black/40 py-2 rounded-sm transition-all hover:border-white/20">Customer</button>
                <button type="button" onclick="selectAuthRole('wholesaler')" id="role-btn-wholesaler" class="role-btn border border-white/10 text-white/50 bg-black/40 py-2 rounded-sm transition-all hover:border-white/20">Wholesaler</button>
                <button type="button" onclick="selectAuthRole('retailer')" id="role-btn-retailer" class="role-btn border border-white/10 text-white/50 bg-black/40 py-2 rounded-sm transition-all hover:border-white/20">Retailer</button>
                <button type="button" onclick="selectAuthRole('reseller')" id="role-btn-reseller" class="role-btn border border-white/10 text-white/50 bg-black/40 py-2 rounded-sm transition-all hover:border-white/20">Reseller</button>
              </div>
            </div>
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium">Password</label>
              <input type="password" required id="auth-pass-signup" class="w-full bg-black/40 border border-[#C8A46A]/20 p-2.5 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="Minimum 6 characters" />
            </div>
            <div class="flex items-center gap-2 pt-1">
              <input type="checkbox" required id="auth-terms" class="rounded border-[#C8A46A]/30 bg-black/40 accent-[#C8A46A]" />
              <label for="auth-terms" class="text-[10px] text-[#a3a3a3]">I agree to the Terms of Service & Privacy Policy</label>
            </div>
            <button type="submit" class="btn-gold-shimmer w-full py-3.5 uppercase tracking-widest text-xs font-bold mt-3">Register Now</button>
          </form>
          <div class="mt-6 text-center text-xs text-[#a3a3a3] border-t border-[#C8A46A]/10 pt-5">
            Already have an account? 
            <button onclick="switchAuthView('login')" class="text-[#C8A46A] hover:underline font-medium">Sign In</button>
          </div>
        </div>

        <div id="forgot-form-view" class="hidden">
          <div class="text-center mb-6">
            <h2 class="font-cormorant text-3xl font-bold tracking-widest text-[#C8A46A] uppercase mb-2">Reset Password</h2>
            <p class="text-xs text-[#a3a3a3]">We will send you a link to reset your password</p>
          </div>
          <form onsubmit="handleAuthSubmit(event, 'forgot')" class="space-y-5">
            <div>
              <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1.5 font-medium">Email Address</label>
              <input type="email" required id="auth-email-forgot" class="w-full bg-black/40 border border-[#C8A46A]/20 p-3 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-[#333]" placeholder="priya@sharma.com" />
            </div>
            <button type="submit" class="btn-gold-shimmer w-full py-3.5 uppercase tracking-widest text-xs font-bold mt-2">Send Recovery Link</button>
          </form>
          <div class="mt-6 text-center text-xs text-[#a3a3a3] border-t border-[#C8A46A]/10 pt-5">
            Back to 
            <button onclick="switchAuthView('login')" class="text-[#C8A46A] hover:underline font-medium">Sign In</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // 3. Quick View Modal
  const quickViewModalHTML = `
    <div id="quickview-modal-overlay" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
      <div class="absolute inset-0" onclick="toggleQuickViewModal(false)"></div>
      <div id="quickview-content" class="relative bg-[#0a0a0a] border border-[#C8A46A]/30 w-full max-w-4xl shadow-2xl z-10 flex flex-col md:flex-row overflow-hidden max-h-[90vh]">
        <!-- Close Button -->
        <button onclick="toggleQuickViewModal(false)" class="absolute top-4 right-4 z-20 text-[#a3a3a3] hover:text-[#C8A46A] bg-black/50 p-1.5 rounded-full">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <!-- Modal body filled dynamically -->
        <div id="quickview-body" class="flex flex-col md:flex-row w-full h-full overflow-y-auto"></div>
      </div>
    </div>
  `;

  // 4. Mobile Search Overlay
  const mobileSearchOverlayHTML = `
    <div id="mobile-search-overlay" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md hidden flex flex-col p-5">
      <!-- Top row: Back button & search bar -->
      <div class="flex items-center gap-3 mb-6">
        <button onclick="toggleMobileSearchOverlay(false)" class="text-[#C8A46A] hover:text-[#F7F4EE] transition-colors p-2 shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        
        <div class="flex-1 relative">
          <form onsubmit="handleMobileSearchSubmit(event)" class="flex w-full rounded-sm overflow-hidden border border-[#C8A46A]/40 focus-within:border-[#C8A46A] bg-[#111] h-11">
            <input 
              type="text" 
              id="overlay-search-input" 
              placeholder="Search sarees, fabrics, colors..." 
              class="flex-1 bg-transparent text-[#F7F4EE] px-4 outline-none placeholder:text-[#F7F4EE]/30 text-sm font-light"
              autocomplete="off"
            />
            <button type="submit" class="bg-gradient-to-r from-[#b08d55] to-[#d8ba82] text-black px-4 flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
          </form>
        </div>
      </div>

      <!-- Popular / Trending Searches -->
      <div id="overlay-trending" class="mb-6 text-left">
        <h4 class="text-[10px] uppercase tracking-widest text-[#C8A46A] mb-3 font-semibold">Trending Searches</h4>
        <div class="flex flex-wrap gap-2">
          <button onclick="fillOverlaySearch('Banarasi')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Banarasi</button>
          <button onclick="fillOverlaySearch('Kanjeevaram')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Kanjeevaram</button>
          <button onclick="fillOverlaySearch('Organza')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Organza</button>
          <button onclick="fillOverlaySearch('Bandhani')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Bandhani</button>
          <button onclick="fillOverlaySearch('Silk')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Silk</button>
          <button onclick="fillOverlaySearch('Red')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Red</button>
          <button onclick="fillOverlaySearch('Sale')" class="px-3 py-1.5 bg-[#111] hover:bg-[#C8A46A]/10 border border-[#C8A46A]/20 hover:border-[#C8A46A]/50 text-xs text-[#F7F4EE] rounded-sm transition-all">Sale</button>
        </div>
      </div>

      <!-- Live Search Results -->
      <div class="flex-1 overflow-y-auto no-scrollbar text-left">
        <h4 class="text-[10px] uppercase tracking-widest text-[#C8A46A] mb-3 font-semibold" id="overlay-results-title">Suggested Products</h4>
        <div id="overlay-search-results" class="space-y-3">
          <!-- suggestions loaded dynamically -->
        </div>
      </div>
    </div>
  `;

  // 5. Mobile Menu Drawer
  const mobileMenuDrawerHTML = `
    <div id="mobile-menu-drawer" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden">
      <!-- Backdrop -->
      <div class="absolute inset-0" onclick="toggleMobileMenuDrawer(false)"></div>
      <!-- Drawer Panel (Slides in from the left) -->
      <div id="mobile-menu-panel" class="absolute left-0 top-0 w-80 h-full bg-[#0a0a0a] border-r border-[#C8A46A]/20 shadow-2xl flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40">
          <!-- Logo -->
          <div class="flex flex-col text-left">
            <span class="font-serif text-lg text-[#C8A46A] tracking-widest uppercase font-bold leading-none">ARSHMAN</span>
            <span class="text-[6px] tracking-[0.2em] text-[#C8A46A]/80 uppercase mt-1">We Weave Your Dreams</span>
          </div>
          <!-- Close Button -->
          <button onclick="toggleMobileMenuDrawer(false)" title="Close" aria-label="Close" class="text-[#a3a3a3] hover:text-[#C8A46A] transition-colors p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        
        <!-- Body Navigation Links -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-8 text-left">
          <!-- Menu Items -->
          <div class="space-y-6">
            <div class="flex flex-col gap-2">
              <a href="${_URL.home}" class="flex items-center justify-between text-sm uppercase tracking-[0.2em] font-semibold text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-3 px-3 rounded border-b border-white/5 group">
                <span>Home</span>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-600 group-hover:text-[#C8A46A] transition-colors"></i>
              </a>
              <a href="${_URL.shop}" class="flex items-center justify-between text-sm uppercase tracking-[0.2em] font-semibold text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-3 px-3 rounded border-b border-white/5 group">
                <span>Shop Collection</span>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-600 group-hover:text-[#C8A46A] transition-colors"></i>
              </a>
            </div>
            
            <!-- Categories Collapse/Group -->
            <div class="space-y-3">
              <span class="text-[10px] uppercase tracking-[0.25em] text-[#C8A46A] font-bold block mb-2 px-1">Weave Categories</span>
              <div class="flex flex-col gap-1.5">
                <a href="${_URL.shop}?fabric=Banarasi" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2.5 px-3 rounded flex items-center gap-3">
                  <i data-lucide="gem" class="w-3.5 h-3.5 text-[#C8A46A]/60"></i> <span>Banarasi Silk</span>
                </a>
                <a href="${_URL.shop}?fabric=Kanjeevaram" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2.5 px-3 rounded flex items-center gap-3">
                  <i data-lucide="crown" class="w-3.5 h-3.5 text-[#C8A46A]/60"></i> <span>Kanjeevaram Silk</span>
                </a>
                <a href="${_URL.shop}?fabric=Organza" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2.5 px-3 rounded flex items-center gap-3">
                  <i data-lucide="feather" class="w-3.5 h-3.5 text-[#C8A46A]/60"></i> <span>Organza Silk</span>
                </a>
                <a href="${_URL.shop}?fabric=Bandhani" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2.5 px-3 rounded flex items-center gap-3">
                  <i data-lucide="dot" class="w-3.5 h-3.5 text-[#C8A46A]/60"></i> <span>Bandhani</span>
                </a>
                <a href="${_URL.shop}?fabric=Georgette" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2.5 px-3 rounded flex items-center gap-3">
                  <i data-lucide="wind" class="w-3.5 h-3.5 text-[#C8A46A]/60"></i> <span>Georgette</span>
                </a>
              </div>
            </div>
            
            <!-- Quick Links -->
            <div class="space-y-3">
              <span class="text-[10px] uppercase tracking-[0.25em] text-[#C8A46A] font-bold block mb-2 px-1">Company</span>
              <div class="grid grid-cols-2 gap-2">
                <a href="${_URL.about}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="info" class="w-3 h-3 text-[#C8A46A]/40"></i> About Us
                </a>
                <a href="${_URL.story}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="book-open" class="w-3 h-3 text-[#C8A46A]/40"></i> Our Story
                </a>
                <a href="${_URL.faq}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="help-circle" class="w-3 h-3 text-[#C8A46A]/40"></i> FAQs
                </a>
                <a href="${_URL.shipping}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="truck" class="w-3 h-3 text-[#C8A46A]/40"></i> Shipping
                </a>
                <a href="${_URL.contact}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="mail" class="w-3 h-3 text-[#C8A46A]/40"></i> Contact Us
                </a>
                <a href="${_URL.track}" class="text-xs text-gray-400 hover:text-[#C8A46A] hover:bg-white/[0.02] transition-all py-2 px-2 rounded flex items-center gap-2">
                  <i data-lucide="map-pin" class="w-3 h-3 text-[#C8A46A]/40"></i> Track Order
                </a>
              </div>
            </div>
          </div>
          
          <!-- Login/Logout & Account Link -->
          <div id="mob-menu-auth-section" class="pt-6 border-t border-[#C8A46A]/10">
            <!-- Dynamic auth link rendered by JS -->
          </div>
        </div>
        
        <!-- Footer containing Support & Social Icons -->
        <div class="p-6 border-t border-[#C8A46A]/20 bg-black/40 space-y-4 text-left">
          <div class="space-y-1.5">
            <span class="text-[9px] uppercase tracking-widest text-[#C8A46A]/60 block">Customer Support</span>
            <a href="tel:+919876543210" class="text-xs text-[#F7F4EE] hover:text-[#C8A46A] flex items-center gap-2 transition-colors font-medium">
              <svg class="w-3.5 h-3.5 text-[#C8A46A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              +91 98765 43210
            </a>
            <a href="mailto:support@arshmandesigns.com" class="text-xs text-[#F7F4EE] hover:text-[#C8A46A] flex items-center gap-2 transition-colors font-medium">
              <svg class="w-3.5 h-3.5 text-[#C8A46A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              support@arshmandesigns.com
            </a>
          </div>
          
          <!-- Social Icons -->
          <div class="flex items-center gap-4 pt-2">
            <a href="#" class="text-gray-400 hover:text-[#C8A46A] transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#C8A46A] transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2.551L12 2.55c-3.13 0-3.522.012-4.757.067-1.233.056-2.078.252-2.817.54-.764.296-1.412.693-2.06 1.342-.648.648-1.046 1.296-1.342 2.06-.288.738-.484 1.584-.54 2.817C.424 10.58.412 10.97.412 14.1c0 3.13.012 3.522.067 4.757.056 1.233.252 2.078.54 2.817.296.764.693 1.412 1.342 2.06.648.648 1.296 1.046 2.06 1.342.738.288 1.584.484 2.817.54 1.233.056 1.623.067 4.757.067 3.13 0 3.522-.012 4.757-.067 1.233-.056 2.078-.252 2.817-.54.764-.296 1.412-.693 2.06-1.342.648-.648 1.046-1.296 1.342-2.06.288-.738.484-1.584.54-2.817.056-1.233.067-1.623.067-4.757 0-3.13-.012-3.522-.067-4.757-.056-1.233-.252-2.078-.54-2.817a4.917 4.917 0 00-1.342-2.06 4.917 4.917 0 00-2.06-1.342c-.738-.288-1.584-.484-2.817-.54-1.233-.056-1.623-.067-4.757-.067zm0-2.438c3.185 0 3.58.012 4.832.072 1.25.057 2.106.257 2.853.548a7.352 7.352 0 012.673 1.74 7.352 7.352 0 011.74 2.673c.29.747.49 1.603.548 2.853.06 1.25.072 1.647.072 4.832s-.012 3.58-.072 4.832c-.057 1.25-.257 2.106-.548 2.853a7.352 7.352 0 01-1.74 2.673 7.352 7.352 0 01-2.673 1.74c-.747.29-1.603.49-2.853.548-1.25.06-1.647.072-4.832.072s-3.58-.012-4.832-.072c-1.25-.057-2.106-.257-2.853-.548a7.352 7.352 0 01-2.673-1.74 7.352 7.352 0 01-1.74-2.673c-.29-.747-.49-1.603-.548-2.853C.012 17.68 0 17.283 0 14.1s.012-3.58.072-4.832c.057-1.25.257-2.106.548-2.853a7.352 7.352 0 011.74-2.673 7.352 7.352 0 012.673-1.74C5.074.387 5.93.187 7.18.13 8.43.072 8.827.06 12.012.06l.303-.009z"/></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#C8A46A] transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', cartDrawerHTML + loginModalHTML + quickViewModalHTML + mobileSearchOverlayHTML + mobileMenuDrawerHTML);

  // Bind search overlay input events
  setTimeout(() => {
    const overlayInput = document.getElementById('overlay-search-input');
    if (overlayInput) {
      overlayInput.addEventListener('input', (e) => {
        renderOverlaySearchSuggestions(e.target.value);
      });
    }
  }, 50);
}

// Toggle Login Modal
function toggleLoginModal(open) {
  const modal = document.getElementById('login-modal-overlay');
  if (!modal) return;
  if (open) {
    modal.classList.remove('hidden');
    switchAuthView('login');
  } else {
    modal.classList.add('hidden');
  }
}

function switchAuthView(view) {
  document.getElementById('login-form-view').classList.add('hidden');
  document.getElementById('signup-form-view').classList.add('hidden');
  document.getElementById('forgot-form-view').classList.add('hidden');

  if (view === 'login') {
    document.getElementById('login-form-view').classList.remove('hidden');
  } else if (view === 'signup') {
    document.getElementById('signup-form-view').classList.remove('hidden');
  } else if (view === 'forgot') {
    document.getElementById('forgot-form-view').classList.remove('hidden');
  }
}

function handleAuthSubmit(event, action) {
  event.preventDefault();
  if (action === 'login') {
    const email = document.getElementById('auth-email-login').value;
    user = { name: email.split('@')[0], email: email };
    localStorage.setItem('arshman_user', JSON.stringify(user));
    toggleLoginModal(false);
    alert('Logged in successfully as ' + user.name + '!');
  } else if (action === 'signup') {
    const name = document.getElementById('auth-name-signup').value;
    const email = document.getElementById('auth-email-signup').value;
    const phone = document.getElementById('auth-phone-signup').value;
    const role = document.getElementById('auth-role-signup').value;
    
    if (!role) {
      alert('Please select an Account Role (Customer, Wholesaler, Retailer, or Reseller) before registering.');
      return;
    }
    
    user = { name: name, email: email, phone: phone, role: role };
    localStorage.setItem('arshman_user', JSON.stringify(user));
    toggleLoginModal(false);
    alert('Account created successfully! Welcome ' + name + ' (' + role.toUpperCase() + ')!');
  } else if (action === 'forgot') {
    alert('Password reset link sent to your email!');
    switchAuthView('login');
    return;
  }
  
  if (typeof updateAccountView === 'function') updateAccountView();
  if (typeof updateMobileMenuAuth === 'function') updateMobileMenuAuth();
}

function logoutUser() {
  user = null;
  localStorage.removeItem('arshman_user');
  alert('You have logged out.');
  if (typeof updateAccountView === 'function') updateAccountView();
  window.location.reload();
}

// Quick View Rendering
function openQuickView(productId) {
  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;

  const quickViewBody = document.getElementById('quickview-body');
  if (!quickViewBody) return;

  quickViewBody.innerHTML = `
    <!-- Left Column: Image -->
    <div class="w-full md:w-1/2 aspect-[3/4] relative bg-[#111]">
      <img src="${product.img1}" alt="${product.name}" class="w-full h-full object-cover object-top" id="qv-main-img" />
      <!-- Small thumbnail row -->
      <div class="absolute bottom-4 left-4 right-4 flex gap-2 justify-center">
        <button onclick="document.getElementById('qv-main-img').src='${product.img1}'" class="w-10 h-12 border border-[#C8A46A] overflow-hidden"><img src="${product.img1}" class="w-full h-full object-cover" /></button>
        <button onclick="document.getElementById('qv-main-img').src='${product.img2}'" class="w-10 h-12 border border-transparent hover:border-[#C8A46A] overflow-hidden"><img src="${product.img2}" class="w-full h-full object-cover" /></button>
      </div>
    </div>
    <!-- Right Column: Details -->
    <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-between text-[#F7F4EE]">
      <div>
        <span class="text-[10px] text-[#C8A46A] uppercase tracking-widest font-semibold">${product.fabric}</span>
        <h3 class="font-cormorant text-2xl md:text-3xl font-bold mt-1 mb-2 text-white">${product.name}</h3>
        
        <div class="flex items-center gap-2 mb-4">
          <div class="flex text-[#C8A46A] text-sm">
            ${Array.from({ length: 5 }).map((_, i) => `
              <svg class="w-4 h-4 ${i < Math.round(product.rating) ? 'fill-[#C8A46A]' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>
            `).join('')}
          </div>
          <span class="text-xs text-[#a3a3a3]">(${product.rating} Stars)</span>
        </div>

        <div class="flex items-baseline gap-3 mb-6">
          <span class="text-2xl font-bold text-white">${formatCurrency(product.price)}</span>
          ${product.mrp ? `
            <span class="text-sm text-[#a3a3a3] line-through">${formatCurrency(product.mrp)}</span>
            <span class="text-xs text-[#C8A46A] font-semibold">${product.discount}% OFF</span>
          ` : ''}
        </div>

        <p class="text-xs text-[#a3a3a3] leading-relaxed mb-6 font-light">
          A timeless masterwork displaying rich zari work and intricate textures. Exquisitely handcrafted from fine silk fibers, curated especially for royal Indian celebrations.
        </p>

        <!-- Options select -->
        <div class="space-y-4 mb-6">
          <div>
            <span class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-2 font-medium">Select Size</span>
            <div class="flex gap-2 text-xs">
              <button class="border border-[#C8A46A] px-3 py-1.5 text-white font-medium bg-[#C8A46A]/10">5.5m (With Blouse)</button>
              <button class="border border-[#333] px-3 py-1.5 text-[#a3a3a3] hover:border-[#C8A46A] transition-colors">6.0m</button>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-3">
        <button onclick="addToCart(${product.id}, 1); toggleQuickViewModal(false);" class="btn-premium-cart w-full py-4 text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2">
          <svg class="w-4 h-4 icon-bag" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
          Add to Bag
        </button>
        <button onclick="toggleQuickViewModal(false); window.location.href=_productUrl(product.id);" class="w-full border border-[#C8A46A]/50 text-[#C8A46A] hover:bg-[#C8A46A]/10 py-3 uppercase tracking-widest text-[10px] font-semibold transition-colors">View Full Details</button>
      </div>
    </div>
  `;

  toggleQuickViewModal(true);
}

function toggleQuickViewModal(open) {
  const overlay = document.getElementById('quickview-modal-overlay');
  if (!overlay) return;

  if (open) {
    overlay.classList.remove('hidden');
  } else {
    overlay.classList.add('hidden');
  }
}

// Intersection Observer for Scroll Fade-in Animations
function setupScrollReveal() {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          entry.target.classList.add('is-visible');
        }
      });
    },
    { threshold: 0.1 }
  );

  document.querySelectorAll('.reveal-on-scroll').forEach((el) => observer.observe(el));
}

// Global Initialization
document.addEventListener('DOMContentLoaded', () => {
  injectGlobalUI();
  loadGlobalHeader();
  setupScrollReveal();
});

// Load header.html dynamically
function loadGlobalHeader() {
  const container = document.getElementById('global-header');
  if (!container) return;

  fetch('header.html?v=' + new Date().getTime())
    .then(res => {
      if (!res.ok) throw new Error('Failed to load header');
      return res.text();
    })
    .then(html => {
      container.innerHTML = html;
      
      // Initialize Lucide icons inside header
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }

      // Re-initialize header widgets (badge counts, account label)
      updateHeaderWidgets();

      // Bind search autocomplete input events
      const searchInput = document.getElementById('header-search-input');
      const searchSuggestions = document.getElementById('search-suggestions');
      if (searchInput && searchSuggestions) {
        searchInput.addEventListener('focus', () => {
          renderGlobalSearchSuggestions(searchInput.value);
          searchSuggestions.classList.remove('hidden');
        });

        searchInput.addEventListener('input', () => {
          renderGlobalSearchSuggestions(searchInput.value);
        });

        // Close suggestions on outside click
        document.addEventListener('click', (e) => {
          if (!e.target.closest('#header-search-input') && !e.target.closest('#search-suggestions')) {
            searchSuggestions.classList.add('hidden');
          }
        });
      }

      // Bind cart drawer toggles globally
      const bagBtns = document.querySelectorAll('[data-bag-toggle]');
      bagBtns.forEach(btn => btn.addEventListener('click', () => toggleCartDrawer(true)));

      // Bind user modal/dashboard toggles globally
      const userBtns = document.querySelectorAll('[data-user-toggle]');
      userBtns.forEach(btn => btn.addEventListener('click', () => {
        if (user) {
          window.location.href = _URL.account;
        } else {
          toggleLoginModal(true);
        }
      }));

      // Active Navigation link highlighting inside header sub-nav
      const rawPath = window.location.pathname.split('/').pop() || 'index.html';
      const currentPath = rawPath.includes('.') ? rawPath : (rawPath ? rawPath + '.html' : 'index.html');
      const navLinks = container.querySelectorAll('.bg-\\[\\#111\\] a');
      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.startsWith(currentPath)) {
          link.classList.add('text-white');
          link.classList.remove('text-[#C8A46A]');
        }
      });

      // Switch mobile header sub-layouts based on current page path
      const mHome = document.getElementById('mobile-header-home');
      const mShop = document.getElementById('mobile-header-shop');
      const mProduct = document.getElementById('mobile-header-product');
      const pName = window.location.pathname;

      // ============ Highlight active mobile bottom nav item ============
      const bottomNavLinks = document.querySelectorAll('#mobile-bottom-nav [data-nav-link]');
      bottomNavLinks.forEach(link => {
        const key = link.getAttribute('data-nav-link');
        const pathLower = currentPath.toLowerCase();
        let isActive = false;
        if (key === 'home' && (pathLower === 'index.html' || pName === '/' || pName === '')) isActive = true;
        if (key === 'shop' && pathLower.startsWith('shop')) isActive = true;
        if (key === 'search' && pathLower.startsWith('shop')) isActive = true;
        if (key === 'wishlist' && pathLower.startsWith('wishlist')) isActive = true;
        if (isActive) link.classList.add('active');
      });

      if (mHome && mShop && mProduct) {
        mHome.classList.add('hidden');
        mHome.classList.remove('flex');
        mShop.classList.add('hidden');
        mShop.classList.remove('flex');
        mProduct.classList.add('hidden');
        mProduct.classList.remove('flex');

        const isHome = currentPath === 'index.html' || pName === '/' || pName === '' || pName.endsWith('/');
        const isShop = currentPath.startsWith('shop.html');

        if (isHome) {
          mHome.classList.remove('hidden');
          mHome.classList.add('flex');
        } else if (isShop) {
          mShop.classList.remove('hidden');
          mShop.classList.add('flex');
          // Bind the mobile search input with current search query if any
          const mobSearchInput = document.getElementById('mobile-search-input');
          if (mobSearchInput) {
            const params = new URLSearchParams(window.location.search);
            if (params.has('search')) {
              mobSearchInput.value = params.get('search');
            }
          }
        } else {
          // Product Details, Wishlist, Checkout, Contact, etc.
          mProduct.classList.remove('hidden');
          mProduct.classList.add('flex');
        }
      }

      // Hide mobile bottom navigation bar on product details page
      const mobileBottomNav = document.getElementById('mobile-bottom-nav');
      if (mobileBottomNav) {
        const isProductPage = currentPath.startsWith('product.html') || /\/product\/[^/]+\/?$/.test(window.location.pathname);
        if (isProductPage) {
          mobileBottomNav.classList.add('hidden');
        } else {
          mobileBottomNav.classList.remove('hidden');
        }
      }

      // Highlight active bottom nav links
      const bottomLinks = document.querySelectorAll('.fixed.bottom-0 button');
      bottomLinks.forEach(btn => {
        const onclickAttr = btn.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(currentPath)) {
          btn.classList.add('text-[#C8A46A]');
          btn.classList.remove('text-gray-400');
        }
      });

      // Dispatch load event
      document.dispatchEvent(new CustomEvent('header-loaded'));
    })
    .catch(err => console.error(err));
}

// Global Search Handler
function handleGlobalSearch(event) {
  event.preventDefault();
  let searchInput = document.getElementById('header-search-input');
  // Check if we are using the mobile search input
  if (!searchInput || searchInput.offsetParent === null) {
    searchInput = document.getElementById('mobile-search-input');
  }
  const isShopPage = window.location.pathname.includes('shop');
  if (isShopPage) {
    if (searchInput) searchInput.blur();
    return;
  }
  const catSelect = document.getElementById('header-category-select');
  if (!searchInput) return;

  const query = searchInput.value.trim();
  const cat = catSelect ? catSelect.value : 'all';

  let params = `search=${encodeURIComponent(query)}`;
  if (cat !== 'all' && catSelect && catSelect.offsetParent !== null) {
    params += `&fabric=${encodeURIComponent(cat)}`;
  }
  
  const isHtml = window.location.pathname.includes('.html');
  const dest = _isWP ? (_URL.shop + '?' + params) : ((isHtml ? 'shop.html' : 'shop') + '?' + params);
  window.location.href = dest;
}

// Global autocomplete suggestions
function renderGlobalSearchSuggestions(query) {
  const container = document.getElementById('suggested-products-list');
  if (!container || typeof PRODUCTS === 'undefined') return;

  const q = query.toLowerCase().trim();
  if (!q) {
    container.innerHTML = PRODUCTS.slice(0, 3).map(prod => getSuggestionRowHTML(prod)).join('');
    return;
  }

  const matched = PRODUCTS.filter(p => p.name.toLowerCase().includes(q) || p.fabric.toLowerCase().includes(q)).slice(0, 4);

  if (matched.length === 0) {
    container.innerHTML = `<p class="text-[10px] text-gray-500 py-3 text-center">No suggested products match your query.</p>`;
    return;
  }

  container.innerHTML = matched.map(prod => getSuggestionRowHTML(prod)).join('');
}

function getSuggestionRowHTML(prod) {
  return `
    <div onclick="window.location.href=_productUrl(prod.id)" class="flex items-center gap-3 p-1.5 hover:bg-[#1A1A1A] rounded-sm transition-colors cursor-pointer border border-transparent hover:border-[#C8A46A]/20 group">
      <div class="w-10 h-12 bg-[#1A1A1A] overflow-hidden rounded-sm shrink-0">
        <img src="${prod.img1}" alt="${prod.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
      </div>
      <div class="flex-1 min-w-0 text-left">
        <p class="text-xs font-medium text-[#F7F4EE] group-hover:text-[#C8A46A] transition-colors truncate">${prod.name}</p>
        <p class="text-[10px] text-[#C8A46A] mt-0.5">${formatCurrency(prod.price)}</p>
      </div>
    </div>
  `;
}

// Mobile Search Overlay Helpers
function toggleMobileSearchOverlay(open) {
  const overlay = document.getElementById('mobile-search-overlay');
  if (!overlay) return;

  if (open) {
    overlay.classList.remove('hidden');
    const input = document.getElementById('overlay-search-input');
    if (input) {
      input.value = '';
      input.focus();
    }
    renderOverlaySearchSuggestions('');
  } else {
    overlay.classList.add('hidden');
  }
}

function fillOverlaySearch(term) {
  const input = document.getElementById('overlay-search-input');
  if (input) {
    input.value = term;
    input.focus();
    renderOverlaySearchSuggestions(term);
  }
}

// Global search submit
function handleMobileSearchSubmit(event) {
  event.preventDefault();
  const input = document.getElementById('overlay-search-input');
  if (!input) return;
  const query = input.value.trim();
  
  const isHtml = window.location.pathname.includes('.html');
  const dest = _isWP ? (_URL.shop + '?search=' + encodeURIComponent(query)) : ((isHtml ? 'shop.html' : 'shop') + '?search=' + encodeURIComponent(query));
  window.location.href = dest;
}

function renderOverlaySearchSuggestions(query) {
  const container = document.getElementById('overlay-search-results');
  const title = document.getElementById('overlay-results-title');
  if (!container || typeof PRODUCTS === 'undefined') return;

  const q = query.toLowerCase().trim();
  if (!q) {
    title.textContent = 'Suggested Products';
    container.innerHTML = PRODUCTS.slice(0, 4).map(prod => getOverlaySuggestionRowHTML(prod)).join('');
    return;
  }

  const matched = PRODUCTS.filter(p => p.name.toLowerCase().includes(q) || p.fabric.toLowerCase().includes(q) || (p.color && p.color.toLowerCase().includes(q))).slice(0, 8);

  if (matched.length === 0) {
    title.textContent = 'Search Results';
    container.innerHTML = `<p class="text-xs text-gray-500 py-6 text-center font-light">No products found matching your search.</p>`;
    return;
  }

  title.textContent = 'Search Results';
  container.innerHTML = matched.map(prod => getOverlaySuggestionRowHTML(prod)).join('');
}

function getOverlaySuggestionRowHTML(prod) {
  return `
    <div onclick="window.location.href=_productUrl(prod.id)" class="flex items-center gap-4 p-2 bg-[#111] hover:bg-[#C8A46A]/5 rounded-sm transition-all cursor-pointer border border-[#C8A46A]/10 hover:border-[#C8A46A]/40 group">
      <div class="w-12 h-16 bg-[#1A1A1A] overflow-hidden rounded-sm shrink-0 border border-white/5">
        <img src="${prod.img1}" alt="${prod.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
      </div>
      <div class="flex-1 min-w-0 text-left">
        <p class="text-xs font-semibold text-[#F7F4EE] group-hover:text-[#C8A46A] transition-colors truncate">${prod.name}</p>
        <p class="text-[10px] text-gray-400 mt-1 uppercase">${prod.fabric}</p>
        <p class="text-xs font-bold text-[#C8A46A] mt-1.5">${formatCurrency(prod.price)}</p>
      </div>
      <div class="text-[#C8A46A] opacity-50 group-hover:opacity-100 transition-opacity pr-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </div>
    </div>
  `;
}

// Mobile Side Menu Drawer Logic (Slides in from left)
function toggleMobileMenuDrawer(open) {
  const drawer = document.getElementById('mobile-menu-drawer');
  const panel = document.getElementById('mobile-menu-panel');
  if (!drawer || !panel) return;

  if (open) {
    drawer.classList.remove('hidden');
    updateMobileMenuAuth();
    setTimeout(() => {
      panel.classList.remove('-translate-x-full');
    }, 10);
    if (typeof lucide !== 'undefined') lucide.createIcons();
  } else {
    panel.classList.add('-translate-x-full');
    setTimeout(() => {
      drawer.classList.add('hidden');
    }, 300);
  }
}

function updateMobileMenuAuth() {
  const authSection = document.getElementById('mob-menu-auth-section');
  if (!authSection) return;

  const storedUser = localStorage.getItem('arshman_user');
  if (storedUser) {
    try {
      const loggedUser = JSON.parse(storedUser);
      authSection.innerHTML = `
        <div class="space-y-4 text-left">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#C8A46A]/20 border border-[#C8A46A]/30 flex items-center justify-center text-[#C8A46A] uppercase font-serif text-lg font-bold">
              ${loggedUser.name.charAt(0)}
            </div>
            <div>
              <p class="text-[10px] text-gray-500 uppercase tracking-widest">Namaste,</p>
              <p class="text-sm font-semibold text-[#F7F4EE] truncate max-w-[180px]">${loggedUser.name}</p>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3 pt-2">
            <a href="${_URL.account}" class="border border-[#C8A46A]/20 text-[#C8A46A] hover:bg-[#C8A46A]/10 text-center py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm transition-all">My Profile</a>
            <button onclick="toggleMobileMenuDrawer(false); logoutUser()" class="border border-white/10 text-gray-400 hover:text-white text-center py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm transition-all">Logout</button>
          </div>
        </div>
      `;
    } catch (e) {
      console.error(e);
      renderLoggedOutMenuAuth(authSection);
    }
  } else {
    renderLoggedOutMenuAuth(authSection);
  }
}

function renderLoggedOutMenuAuth(container) {
  container.innerHTML = `
    <div class="space-y-3 text-left">
      <p class="text-xs text-gray-500 font-light">Access your orders, wishlist, and exclusive collections.</p>
      <button onclick="toggleMobileMenuDrawer(false); toggleLoginModal(true);" class="w-full btn-gold-shimmer py-3 uppercase tracking-widest text-xs font-semibold rounded-sm text-center">Login / Register</button>
    </div>
  `;
}

function selectAuthRole(role) {
  const hiddenInput = document.getElementById('auth-role-signup');
  if (hiddenInput) hiddenInput.value = role;
  
  const buttons = document.querySelectorAll('.role-btn');
  buttons.forEach(btn => {
    btn.classList.remove('border-[#C8A46A]', 'bg-[#C8A46A]/10', 'text-white');
    btn.classList.add('border-white/10', 'text-white/50', 'bg-black/40');
  });
  
  const activeBtn = document.getElementById('role-btn-' + role);
  if (activeBtn) {
    activeBtn.classList.add('border-[#C8A46A]', 'bg-[#C8A46A]/10', 'text-white');
    activeBtn.classList.remove('border-white/10', 'text-white/50', 'bg-black/40');
  }
}

function initCheckoutEnhancements() {
  const checkout = document.querySelector('form.woocommerce-checkout');
  if (!checkout) return;

  const getSteps = () => Array.from(checkout.querySelectorAll('.dt-checkout-step[data-checkout-step]'));

  const isFieldVisible = (field) => {
    if (!field) return false;
    const panel = field.closest('.dt-shipping-address-panel, .shipping_address');
    if (panel && panel.getAttribute('aria-hidden') === 'true') return false;
    return !!(field.offsetWidth || field.offsetHeight || field.getClientRects().length);
  };

  const getRequiredFields = (step) => Array.from(step.querySelectorAll('.validate-required input, .validate-required select, .validate-required textarea'))
    .filter((field) => !field.disabled && isFieldVisible(field));

  const clearStepValidation = (step) => {
    step.classList.remove('is-step-pending');
    step.querySelectorAll('.dt-field-missing').forEach((field) => field.classList.remove('dt-field-missing'));
    step.querySelectorAll('.dt-step-required-note').forEach((note) => note.remove());
  };

  const validateStep = (step) => {
    clearStepValidation(step);

    const missing = getRequiredFields(step).filter((field) => {
      if (field.type === 'checkbox' || field.type === 'radio') {
        const group = field.name ? step.querySelectorAll(`[name="${CSS.escape(field.name)}"]`) : [field];
        return !Array.from(group).some((item) => item.checked);
      }
      return String(field.value || '').trim() === '';
    });

    if (!missing.length) {
      return true;
    }

    step.classList.add('is-step-pending');
    missing.forEach((field) => {
      const row = field.closest('.form-row') || field;
      row.classList.add('dt-field-missing');
      field.setAttribute('aria-invalid', 'true');
    });

    const body = step.querySelector('.dt-checkout-step-body');
    if (body) {
      body.insertAdjacentHTML('afterbegin', '<div class="dt-step-required-note">Please fill all required fields before continuing.</div>');
    }

    const firstMissing = missing[0];
    if (firstMissing) {
      firstMissing.focus({ preventScroll: true });
    }

    return false;
  };

  const openStep = (stepNumber) => {
    const steps = getSteps();
    let activeStep = steps.find((step) => step.dataset.checkoutStep === String(stepNumber));
    if (!activeStep) {
      activeStep = steps[0];
    }

    steps.forEach((step) => {
      const isActive = step === activeStep;
      const stepNumberValue = Number(step.dataset.checkoutStep || 0);
      const activeNumberValue = Number(activeStep.dataset.checkoutStep || 0);
      const isComplete = stepNumberValue < activeNumberValue && !step.classList.contains('is-step-pending');
      step.classList.toggle('is-current', isActive);
      step.classList.toggle('is-step-collapsed', !isActive);
      step.classList.toggle('is-step-complete', isComplete);
    });

    activeStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  const syncShippingPanel = () => {
    const checkbox = document.getElementById('ship-to-different-address-checkbox');
    const panel = document.querySelector('.dt-shipping-address-panel, .shipping_address');
    if (!checkbox || !panel) return;

    const isOpen = checkbox.checked;
    panel.classList.toggle('hidden', !isOpen);
    panel.classList.toggle('is-open', isOpen);
    panel.style.display = isOpen ? '' : 'none';
    panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
  };

  const setCurrentStep = (target) => {
    const steps = checkout.querySelectorAll('.dt-checkout-step');
    const activeStep = target ? target.closest('.dt-checkout-step') : steps[0];
    steps.forEach((step) => {
      step.classList.toggle('is-current', step === activeStep);
    });
  };

  checkout.addEventListener('click', (event) => {
    const button = event.target.closest('[data-next-step]');
    const header = event.target.closest('.dt-checkout-step-head');

    if (button) {
      event.preventDefault();
      const currentStep = button.closest('.dt-checkout-step[data-checkout-step]');
      const requestedStep = Number(button.dataset.nextStep || 0);
      const currentStepNumber = Number(currentStep ? currentStep.dataset.checkoutStep || 0 : 0);

      if (requestedStep > currentStepNumber && currentStep && !validateStep(currentStep)) {
        openStep(currentStep.dataset.checkoutStep);
        return;
      }

      openStep(button.dataset.nextStep);
      return;
    }

    if (header) {
      const step = header.closest('.dt-checkout-step[data-checkout-step]');
      if (step) openStep(step.dataset.checkoutStep);
    }
  });

  checkout.addEventListener('change', (event) => {
    if (event.target && event.target.id === 'ship-to-different-address-checkbox') {
      syncShippingPanel();
    }
    const step = event.target.closest('.dt-checkout-step[data-checkout-step]');
    if (step) {
      if (event.target.closest('.dt-field-missing') && String(event.target.value || '').trim() !== '') {
        event.target.closest('.dt-field-missing').classList.remove('dt-field-missing');
        event.target.removeAttribute('aria-invalid');
      }
      openStep(step.dataset.checkoutStep);
    }
  });

  checkout.addEventListener('input', (event) => {
    const row = event.target.closest('.dt-field-missing');
    if (row && String(event.target.value || '').trim() !== '') {
      row.classList.remove('dt-field-missing');
      event.target.removeAttribute('aria-invalid');
    }
  });

  checkout.addEventListener('focusin', (event) => {
    const step = event.target.closest('.dt-checkout-step[data-checkout-step]');
    if (step && step.classList.contains('is-step-collapsed')) {
      openStep(step.dataset.checkoutStep);
    } else {
      setCurrentStep(event.target);
    }
  });

  syncShippingPanel();
  openStep(1);

  if (window.jQuery) {
    window.jQuery(document.body).on('updated_checkout', () => {
      window.setTimeout(syncShippingPanel, 30);
    });

    window.jQuery(document.body).on('checkout_error', () => {
      const firstErrorField = checkout.querySelector('.woocommerce-invalid input, .woocommerce-invalid select, .woocommerce-invalid textarea');
      const step = firstErrorField ? firstErrorField.closest('.dt-checkout-step[data-checkout-step]') : getSteps()[0];
      if (step) openStep(step.dataset.checkoutStep);
    });
  }
}

document.addEventListener('DOMContentLoaded', initCheckoutEnhancements);

// =============================================================
// REVIEWS — 50+ Authentic Indian Customer Reviews
// =============================================================
const REVIEWS = [
  { name:"Priya Sharma", city:"Mumbai, Maharashtra", product:"Banarasi Silk Saree", rating:5, text:"Absolutely stunning! The zari work is so intricate and the silk quality is far beyond what I expected. Got so many compliments at my cousin's wedding. Will definitely order again!", date:"12 July 2026" },
  { name:"Ananya Patel", city:"Ahmedabad, Gujarat", product:"Chanderi Saree", rating:5, text:"Ordered the Chanderi saree for Navratri and it was perfect. Lightweight, elegant, and the embroidery is so delicate. Delivery within 3 days — amazing service!", date:"10 July 2026" },
  { name:"Divya Reddy", city:"Hyderabad, Telangana", product:"Linen Saree", rating:5, text:"The linen saree is breathable, crisp, and looks very premium. I wore it to office and everyone asked where I got it from. Quality is top class!", date:"9 July 2026" },
  { name:"Kavya Nair", city:"Kochi, Kerala", product:"Cotton Kurti", rating:5, text:"Superb quality cotton kurti! Soft fabric, exact sizing, and the print doesn't fade after washing. Fast delivery to Kerala — very impressed!", date:"8 July 2026" },
  { name:"Meera Verma", city:"Jaipur, Rajasthan", product:"Bandhani Saree", rating:5, text:"The Bandhani saree is gorgeous! Colors are vibrant and fabric is pure silk. Wore it on Teej and looked like a queen. Packaging was also very beautiful.", date:"7 July 2026" },
  { name:"Sunita Singh", city:"Lucknow, Uttar Pradesh", product:"Banarasi Saree", rating:5, text:"Being from Lucknow I know what good Banarasi looks like — and this is the real deal! Genuine zari, heavy border, excellent finish. Bahut acha maal hai!", date:"6 July 2026" },
  { name:"Rashmi Joshi", city:"Pune, Maharashtra", product:"Organza Saree", rating:4, text:"Beautiful organza saree, very sheer and elegant. The embroidery is hand done and you can see the craftsmanship. Slight delay in delivery but quality made up for it.", date:"5 July 2026" },
  { name:"Deepika Rao", city:"Bangalore, Karnataka", product:"Linen Saree", rating:5, text:"Perfect for Bangalore weather! Linen saree drapes beautifully and stays cool all day. Got it for work wear and it looks super professional. 100% recommend!", date:"4 July 2026" },
  { name:"Neha Malhotra", city:"Delhi, NCR", product:"Chanderi Saree", rating:5, text:"Ordered for my daughter's engagement and it was a showstopper. The gold border against the cream fabric looked royal. Multiple guests asked for the brand name!", date:"3 July 2026" },
  { name:"Shreya Krishnamurthy", city:"Chennai, Tamil Nadu", product:"Kanjivaram Saree", rating:5, text:"As a Tamil bride I was very particular about the saree. This Kanjivaram is authentic — heavy silk, thick border, rich temple motifs. Mottama perfect!", date:"2 July 2026" },
  { name:"Pallavi Bose", city:"Kolkata, West Bengal", product:"Cotton Kurti", rating:4, text:"Simple, elegant cotton kurti. The block print reminds me of Santiniketan crafts. Good quality, runs true to size. Great for casual Bengali occasions.", date:"1 July 2026" },
  { name:"Rekha Mehta", city:"Surat, Gujarat", product:"Georgette Saree", rating:5, text:"Being from Surat (fabric city!) I know my textiles. This georgette is top quality — smooth, flowy, no pilling. The embellishments are well-sewn. Excellent!", date:"30 June 2026" },
  { name:"Swati Desai", city:"Vadodara, Gujarat", product:"Banarasi Silk Saree", rating:5, text:"Wore this to a garba night — I was literally the best dressed woman there! The saree is heavy but so worth it. Will order more for my daughter's trousseau!", date:"29 June 2026" },
  { name:"Nandini Kumar", city:"Mysore, Karnataka", product:"Silk Saree", rating:5, text:"From Mysore, silk is part of our culture. This saree does justice to the tradition. Pure silk, good weight, beautiful motifs. Fast delivery and great packing.", date:"28 June 2026" },
  { name:"Bhavna Shah", city:"Rajkot, Gujarat", product:"Bandhani Saree", rating:5, text:"The Bandhani pattern is so fine and even — clearly handmade with love. Colors are rich and didn't bleed even after the first wash. Very happy customer!", date:"27 June 2026" },
  { name:"Geeta Pillai", city:"Thiruvananthapuram, Kerala", product:"Cotton Kurti", rating:4, text:"Love the cotton fabric — so comfortable in Kerala humidity! The kurti has nice detailing on the neck. Delivery was prompt. Would order again!", date:"26 June 2026" },
  { name:"Aarti Saxena", city:"Agra, Uttar Pradesh", product:"Linen Saree", rating:5, text:"The linen saree arrived beautifully packed with a nice golden ribbon. Quality exceeded expectations. Wore it to a friend's wedding and got endless compliments!", date:"25 June 2026" },
  { name:"Ritu Kapoor", city:"Chandigarh, Punjab", product:"Organza Saree", rating:5, text:"Organza sarees are trendy right now and this one is just perfect for the price. The handwork is delicate and won't tear easily. Loved it thoroughly!", date:"24 June 2026" },
  { name:"Preeti Choudhary", city:"Jodhpur, Rajasthan", product:"Banarasi Saree", rating:5, text:"Wearing a banarasi saree has always been my dream and this one made it special! The weave is tight, the zari is bright gold. Packaging was luxurious too!", date:"23 June 2026" },
  { name:"Lalita Murthy", city:"Visakhapatnam, Andhra Pradesh", product:"Chanderi Saree", rating:4, text:"Nice chanderi saree, very lightweight for summer. The border design is pretty. Delivery was 2 days faster than expected. Good experience overall!", date:"22 June 2026" },
  { name:"Shalini Bajaj", city:"Nagpur, Maharashtra", product:"Cotton Kurti", rating:5, text:"This cotton kurti is my new favourite! Washed it 5 times and it still looks brand new. The fabric is thick enough but breathable. Best kurti I've bought online!", date:"21 June 2026" },
  { name:"Usha Bhatt", city:"Dehradun, Uttarakhand", product:"Silk Saree", rating:5, text:"Bought for my retirement function and it was perfect! The silk is genuine — you can feel it. Everyone asked where I shopped from. Will tell all my friends!", date:"20 June 2026" },
  { name:"Kavita Srivastava", city:"Allahabad, Uttar Pradesh", product:"Banarasi Silk Saree", rating:5, text:"UP mein Banarasi saree ki bohot value hai. Ye saree ekdum asli lagti hai — genuine silk, real zari, beautiful packaging. Koi complaint nahi, sab kuch perfect!", date:"19 June 2026" },
  { name:"Madhuri Tiwari", city:"Bhopal, Madhya Pradesh", product:"Linen Saree", rating:5, text:"Ordered the linen saree for office wear. It drapes beautifully even without starch and the color hasn't faded despite regular washing. Perfect professional look!", date:"18 June 2026" },
  { name:"Archana Ghosh", city:"Kolkata, West Bengal", product:"Tant Saree", rating:5, text:"A Bengali loves her tant and this one is exceptional! The weave is fine, fabric soft, and the border is traditional jamdani style. Exceptional quality!", date:"17 June 2026" },
  { name:"Seema Pandey", city:"Varanasi, Uttar Pradesh", product:"Banarasi Saree", rating:5, text:"Main Varanasi se hoon — yahan banarasi saree ki quality samajh aati hai. Ye saree bilkul asli hai! Zari chamakdar, resham smooth. 5 star kum hai iske liye!", date:"16 June 2026" },
  { name:"Jyoti Chatterjee", city:"Siliguri, West Bengal", product:"Cotton Kurti", rating:4, text:"Simple and elegant cotton kurti for everyday wear. The print is subtle and classy. Sizing is accurate and delivery was on time. Good product overall!", date:"15 June 2026" },
  { name:"Vidya Kulkarni", city:"Nasik, Maharashtra", product:"Organza Saree", rating:5, text:"Organza saree is absolutely gorgeous for festive occasions! The peach color with gold embroidery is divine. Wore it for Ganesh Chaturthi and got so many compliments!", date:"14 June 2026" },
  { name:"Meenakshi Rajan", city:"Coimbatore, Tamil Nadu", product:"Silk Saree", rating:5, text:"Silk quality is superb — I've bought from many places but this stands out. The weave is tight and the sheen is natural. Delivery to Coimbatore was very fast!", date:"13 June 2026" },
  { name:"Radhika Menon", city:"Thrissur, Kerala", product:"Chanderi Saree", rating:5, text:"Chanderi is perfect for Kerala occasions — not too heavy, beautiful drape. The colour is exactly as shown in the photo. Very satisfied with this purchase!", date:"12 June 2026" },
  { name:"Savita Pujari", city:"Goa", product:"Cotton Kurti", rating:5, text:"Even in Goa's heat and humidity this cotton kurti stays comfortable! The fabric is pre-washed so no shrinkage. Bought 3 more colors after receiving the first one!", date:"11 June 2026" },
  { name:"Komal Thakur", city:"Shimla, Himachal Pradesh", product:"Linen Saree", rating:5, text:"Ordered for a hill station trip and it was perfect! Linen is comfortable in cooler weather too. The saree is versatile and packs without wrinkling much. Love it!", date:"10 June 2026" },
  { name:"Ritika Banerjee", city:"Durgapur, West Bengal", product:"Banarasi Silk Saree", rating:5, text:"Brought this for my sister's wedding. The saree is so rich and heavy — proper bridal quality! Everyone thought it was custom-ordered. Outstanding purchase!", date:"9 June 2026" },
  { name:"Nisha Dubey", city:"Raipur, Chhattisgarh", product:"Bandhani Saree", rating:4, text:"Lovely bandhani saree with beautiful tie-dye pattern. Colors are rich and vibrant. Small delay in delivery but the product quality is excellent. Recommend!", date:"8 June 2026" },
  { name:"Sunanda Roy", city:"Guwahati, Assam", product:"Cotton Kurti", rating:5, text:"Finally a cotton kurti that doesn't become shapeless after washing! The fabric holds its form beautifully. Northeast India delivery was faster than expected!", date:"7 June 2026" },
  { name:"Sangita Patil", city:"Solapur, Maharashtra", product:"Silk Saree", rating:5, text:"For Mahalakshmi puja I ordered this silk saree and it was absolutely divine! The gold border against the red silk is so auspicious and traditional. Khup chhan!", date:"6 June 2026" },
  { name:"Monika Bhattacharya", city:"Patna, Bihar", product:"Banarasi Saree", rating:5, text:"Chhath Puja mein ye banarasi saree pahni — ghaat pe sabse sundar lag rahi thi! Silk asli hai, zari genuine hai. Packaging mein tulsi leaf bhi thi — lovely touch!", date:"5 June 2026" },
  { name:"Anjali Mishra", city:"Indore, Madhya Pradesh", product:"Chanderi Saree", rating:5, text:"Bought it for a corporate event and received countless compliments! Chanderi looks premium without being too formal. The colour blocking in the border is modern!", date:"4 June 2026" },
  { name:"Smita Parekh", city:"Surat, Gujarat", product:"Organza Saree", rating:4, text:"The organza fabric is very delicate and beautiful. Need to be careful while draping but the end result is stunning! Worth every rupee for festive occasions.", date:"3 June 2026" },
  { name:"Hema Ganesh", city:"Madurai, Tamil Nadu", product:"Silk Saree", rating:5, text:"Temple saree quality from this brand! The silk has natural sheen, border is classic, and the fabric weight is perfect for temple visits. Nandri!", date:"2 June 2026" },
  { name:"Parvati Pillai", city:"Palakkad, Kerala", product:"Cotton Kurti", rating:5, text:"The cotton is so soft it feels like wearing a cloud! Hand block print design is authentic and unique. Ordered 4 kurtis at once and all arrived in perfect condition!", date:"1 June 2026" },
  { name:"Lakshmi Devi", city:"Tirupati, Andhra Pradesh", product:"Silk Saree", rating:5, text:"Wore this for Tirumala darshan — the gold silk saree looked so divine at the temple! Vastra is premium quality, colour is auspicious. Jai Balaji!", date:"31 May 2026" },
  { name:"Chameli Agarwal", city:"Amritsar, Punjab", product:"Cotton Kurti", rating:5, text:"Perfect Punjabi kurti for summer! The cotton is thick enough to be modest but light enough for Punjab heat. The embroidery on the yoke is exquisite. Will reorder!", date:"30 May 2026" },
  { name:"Sudha Iyengar", city:"Bangalore, Karnataka", product:"Linen Saree", rating:5, text:"A working Bangalorean's dream saree! Linen stays fresh throughout long work days, looks professional, and the border gives a traditional touch. Absolutely worth it!", date:"29 May 2026" },
  { name:"Bindu Nambiar", city:"Calicut, Kerala", product:"Chanderi Saree", rating:4, text:"Beautiful chanderi saree! The fabric is lightweight and airy, great for Kerala climate. Slight delay in delivery but quality is very good. Would recommend!", date:"28 May 2026" },
  { name:"Anita Sharma", city:"Jaipur, Rajasthan", product:"Bandhani Saree", rating:5, text:"Being from Rajasthan, bandhani is our identity — and this one is spectacular! The dye is even, the fabric is pure, the colors are exactly as shown. Bhangu!", date:"27 May 2026" },
  { name:"Pooja Verma", city:"Bhubaneswar, Odisha", product:"Cotton Kurti", rating:5, text:"Sambalpuri-inspired cotton kurti — beautiful ikat print that I love! Great quality, fast delivery to Odisha. This is now my everyday favourite kurti. Khusi lagla!", date:"26 May 2026" },
  { name:"Geeta Kumari", city:"Ranchi, Jharkhand", product:"Banarasi Saree", rating:5, text:"Ordered for daughter's mundan ceremony and she looked like a little princess! The fabric is so rich and the packaging was beautifully done with a card. Loved it!", date:"25 May 2026" },
  { name:"Sarla Trivedi", city:"Varanasi, Uttar Pradesh", product:"Silk Saree", rating:5, text:"Varanasi ki silk ka dil se shaukin hoon — aur ye brand ne nirasha nahi kiya! Ekdum sahi rang, sahi weight, sahi banaavat. Sab kuch perfect. Bahut shukriya!", date:"24 May 2026" },
  { name:"Pushpa Reddy", city:"Vijayawada, Andhra Pradesh", product:"Linen Saree", rating:5, text:"For our hot Andhra summers, linen is the best choice. This saree keeps me cool all day! The weave is smooth and the border is beautiful. Manchi quality!", date:"23 May 2026" },
  { name:"Kamla Bhatt", city:"Haridwar, Uttarakhand", product:"Chanderi Saree", rating:5, text:"Wore this for Ganga aarti at Har ki Pauri — everyone stopped to admire the saree! The fabric drapes beautifully. Pure, divine, elegant. Hari Om!", date:"22 May 2026" },
  { name:"Radha Krishnan", city:"Madurai, Tamil Nadu", product:"Cotton Kurti", rating:5, text:"Perfect kurti for temple visits! Cotton fabric is appropriate and the traditional print makes it look very respectful. Size chart is accurate. Very happy!", date:"21 May 2026" }
];

// Review state
let reviewIdx = 0;
let reviewsAutoTimer = null;
let reviewsPaused = false;

function buildReviewCard(r, i) {
  const stars = Array.from({length:5},(_,s)=>`<svg class="w-4 h-4 ${s < r.rating ? 'text-[#C8A46A] fill-[#C8A46A]' : 'text-gray-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>`).join('');
  const initials = r.name.split(' ').map(n=>n[0]).join('').slice(0,2).toUpperCase();
  return `<div class="review-card"><div class="flex items-center gap-3 mb-4"><div class="review-avatar">${initials}</div><div><div class="text-sm font-semibold text-[#F7F4EE]">${r.name}</div><div class="text-[10px] text-[#C8A46A]/70 uppercase tracking-widest mt-0.5">${r.city}</div></div><div class="ml-auto flex gap-0.5">${stars}</div></div><div class="review-category-badge">${r.product}</div><p class="review-text">"${r.text}"</p><div class="review-meta">${r.date} · Verified Purchase ✓</div></div>`;
}

function setReviewIndex(idx) {
  const track = document.getElementById('reviews-track');
  if (!track) return;
  const cards = track.querySelectorAll('.review-card');
  if (!cards.length) return;
  const isMobile = window.innerWidth < 768;
  const perView = isMobile ? 1 : (window.innerWidth < 1024 ? 2 : 3);
  const maxIdx = Math.max(0, REVIEWS.length - perView);
  reviewIdx = Math.max(0, Math.min(idx, maxIdx));
  const gap = isMobile ? 0 : 32;
  const cardW = cards[0].offsetWidth + gap;
  track.style.transform = `translateX(-${reviewIdx * cardW}px)`;
  document.querySelectorAll('.review-dot').forEach((d,i) => d.classList.toggle('active', i === reviewIdx));
}

function reviewsNav(dir) {
  const isMobile = window.innerWidth < 768;
  const perView = isMobile ? 1 : (window.innerWidth < 1024 ? 2 : 3);
  const maxIdx = Math.max(0, REVIEWS.length - perView);
  let n = reviewIdx + dir;
  if (n < 0) n = maxIdx;
  if (n > maxIdx) n = 0;
  setReviewIndex(n);
  restartReviewsAuto();
}

function startReviewsAuto() {
  clearInterval(reviewsAutoTimer);
  reviewsAutoTimer = setInterval(() => { if (!reviewsPaused) reviewsNav(1); }, 4000);
}
function restartReviewsAuto() {
  clearInterval(reviewsAutoTimer);
  startReviewsAuto();
}

// =============================================================
// MOBILE SLIDERS — New Arrivals (1-col), Top Sellers (2-col),
//                  Why Choose (1-col step)
// =============================================================
const _mobileSliders = {};

function initMobileSlider(id, intervalMs) {
  const track = document.getElementById(id + '-mobile-track');
  if (!track) return;
  const slides = track.children;
  if (!slides.length) return;
  _mobileSliders[id] = { idx: 0, total: slides.length };
  _renderMsDots(id);
  // Touch / swipe
  let tStart = null;
  track.parentElement.addEventListener('touchstart', e => { tStart = e.touches[0].clientX; }, { passive: true });
  track.parentElement.addEventListener('touchend', e => {
    if (tStart === null) return;
    const diff = tStart - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) mobileSliderNav(id, diff > 0 ? 1 : -1);
    tStart = null;
  }, { passive: true });
  // Auto-advance
  if (intervalMs) {
    _mobileSliders[id].timer = setInterval(() => mobileSliderNav(id, 1), intervalMs);
  }
}

function mobileSliderNav(id, dir) {
  const s = _mobileSliders[id];
  if (!s) return;
  s.idx = (s.idx + dir + s.total) % s.total;
  const track = document.getElementById(id + '-mobile-track');
  if (track) track.style.transform = `translateX(-${s.idx * 100}%)`;
  _renderMsDots(id);
  // Reset auto timer
  if (s.timer) {
    clearInterval(s.timer);
    s.timer = setInterval(() => mobileSliderNav(id, 1), id === 'wc' ? 3500 : 3000);
  }
}

function mobileSliderGoTo(id, idx) {
  const s = _mobileSliders[id];
  if (!s) return;
  s.idx = idx;
  const track = document.getElementById(id + '-mobile-track');
  if (track) track.style.transform = `translateX(-${s.idx * 100}%)`;
  _renderMsDots(id);
}

function _renderMsDots(id) {
  const s = _mobileSliders[id];
  const el = document.getElementById(id + '-dots');
  if (!s || !el) return;
  el.innerHTML = Array.from({length: s.total}, (_,i) =>
    `<button class="mobile-slider-dot ${i===s.idx?'active':''}" onclick="mobileSliderGoTo('${id}',${i})" aria-label="Slide ${i+1}"></button>`
  ).join('');
}

// Init all mobile sliders on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  if (window.innerWidth < 768) {
    initMobileSlider('na', 3200);   // New Arrivals 1-col
    initMobileSlider('ts', 3000);   // Top Sellers 2-col paged
    initMobileSlider('wc', 3500);   // Why Choose Us steps
  }

  // Reviews init
  const reviewsTrack = document.getElementById('reviews-track');
  const reviewsDots  = document.getElementById('reviews-dots');
  const reviewsWrap  = document.getElementById('reviews-carousel-wrap');
  if (reviewsTrack && reviewsDots) {
    reviewsTrack.innerHTML = REVIEWS.map((r,i) => buildReviewCard(r,i)).join('');
    const isMobile = window.innerWidth < 768;
    const perView  = isMobile ? 1 : (window.innerWidth < 1024 ? 2 : 3);
    const dotCount = REVIEWS.length - perView + 1;
    reviewsDots.innerHTML = Array.from({length: dotCount}, (_,i) =>
      `<span class="review-dot ${i===0?'active':''}" onclick="setReviewIndex(${i}); restartReviewsAuto();"></span>`
    ).join('');
    setTimeout(() => { setReviewIndex(0); startReviewsAuto(); }, 200);

    if (reviewsWrap) {
      reviewsWrap.addEventListener('mouseenter', () => {
        reviewsPaused = true;
        const pl = document.getElementById('reviews-play-label');
        const pi = document.getElementById('reviews-play-icon');
        if (pl) pl.textContent = 'Paused';
        if (pi) { pi.classList.remove('animate-pulse'); pi.style.background='#666'; }
      });
      reviewsWrap.addEventListener('mouseleave', () => {
        reviewsPaused = false;
        const pl = document.getElementById('reviews-play-label');
        const pi = document.getElementById('reviews-play-icon');
        if (pl) pl.textContent = 'Auto-playing';
        if (pi) { pi.classList.add('animate-pulse'); pi.style.background='#C8A46A'; }
      });
    }

    window.addEventListener('resize', () => { setReviewIndex(reviewIdx); });
    let rTouchStart = null;
    if (reviewsTrack) {
      reviewsTrack.addEventListener('touchstart', e => { rTouchStart = e.touches[0].clientX; }, { passive: true });
      reviewsTrack.addEventListener('touchend', e => {
        if (rTouchStart === null) return;
        const diff = rTouchStart - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) reviewsNav(diff > 0 ? 1 : -1);
        rTouchStart = null;
      }, { passive: true });
    }
  }
});
