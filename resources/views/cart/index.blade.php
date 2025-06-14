@extends('layouts.app')

@section('style')
<style>
:root {
  --primary-color: #149d80;
  --primary-dark: #0c8b71;
  --primary-light: rgba(0, 114, 79, 0.1);
  --text-dark: #1e293b;
  --text-medium: #475569;
  --text-light: #64748b;
  --card-border: #f1f5f9;
  --background-light: #f8fafc;
  --white: #ffffff;
  --danger: #ef4444;
  --warning: #f59e0b;
  --success: #10b981;
}

.page-container {
  padding: 1.5rem 0;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  margin: 0;
}

.page-title i {
  color: var(--primary-color);
  margin-right: 0.75rem;
  font-size: 1.5rem;
}

/* Cards Styling */
.card {
  border-radius: 12px;
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
  margin-bottom: 2rem;
  background-color: var(--white);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.card-img-container {
  position: relative;
  height: 180px;
  overflow: hidden;
  background-color: var(--background-light);
}

.card-img-top {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.card:hover .card-img-top {
  transform: scale(1.05);
}

.stock-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  border-radius: 20px;
  padding: 0.25rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 500;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  background-color: var(--primary-color);
  color: var(--white);
}

.expired-badge {
  background-color: var(--danger);
}

.low-stock-badge {
  background-color: var(--warning);
}

.card-body {
  padding: 1.25rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-type {
  display: inline-block;
  background-color: var(--primary-light);
  color: var(--primary-color);
  border-radius: 20px;
  padding: 0.2rem 0.6rem;
  font-size: 0.7rem;
  font-weight: 500;
  margin-bottom: 0.75rem;
}

.product-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.product-price {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--primary-color);
}

.product-quantity {
  font-size: 0.85rem;
  color: var(--text-medium);
  font-weight: 500;
}

.product-expiry {
  font-size: 0.75rem;
  color: var(--text-light);
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.product-expiry i {
  margin-right: 0.3rem;
}

.expired-text {
  color: var(--danger);
  font-weight: 500;
}

.add-to-cart-btn {
  width: 100%;
  padding: 0.6rem 1rem;
  margin-top: 1rem;
  border-radius: 8px;
  background-color: var(--primary-color);
  border: none;
  color: var(--white);
  font-weight: 500;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.add-to-cart-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.add-to-cart-btn i {
  margin-right: 0.5rem;
}

.expired-btn {
  background-color: var(--text-light);
  cursor: not-allowed;
}

.expired-btn:hover {
  background-color: var(--text-light);
  transform: none;
}

/* Info Badge */
.info-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background-color: rgba(255, 255, 255, 0.95);
  color: var(--primary-color);
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.info-badge:hover {
  background-color: var(--primary-color);
  color: var(--white);
  transform: scale(1.1);
}

/* Modal Styles */
.stock-info-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.stock-info-content {
  background-color: var(--white);
  border-radius: 12px;
  padding: 1.5rem;
  max-width: 400px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.stock-info-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.stock-info-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text-dark);
}

.close-modal {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: var(--text-light);
  transition: color 0.3s ease;
}

.close-modal:hover {
  color: var(--text-dark);
}

.batch-item {
  background-color: var(--background-light);
  border-radius: 8px;
  padding: 0.75rem;
  margin-bottom: 0.75rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.batch-item:last-child {
  margin-bottom: 0;
}

.batch-info {
  display: flex;
  flex-direction: column;
}

.batch-id {
  font-size: 0.85rem;
  color: var(--text-medium);
  font-weight: 500;
}

.batch-expiry {
  font-size: 0.8rem;
  color: var(--text-light);
}

.batch-quantity {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--primary-color);
}

.total-info {
  background-color: var(--primary-light);
  border-radius: 8px;
  padding: 0.75rem;
  margin-top: 1rem;
  text-align: center;
}

.total-quantity {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--primary-color);
}

/* Search Bar Styling */
.search-container {
  position: relative;
  margin-bottom: 1rem;
}

.search-input {
  padding: 0.75rem 1rem 0.75rem 3rem;
  border-radius: 10px;
  border: 1px solid var(--card-border);
  width: 100%;
  font-size: 0.95rem;
  transition: all 0.2s;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 114, 79, 0.1);
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-light);
  font-size: 1.2rem;
}

/* Cart Section Styling - same as original */
.cart-container {
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  background-color: var(--white);
  padding: 1.5rem;
  position: sticky;
  top: 20px;
}

.cart-header {
  display: flex;
  align-items: center;
  margin-bottom: 1.25rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--card-border);
}

.cart-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
}

.cart-title i {
  color: var(--primary-color);
  margin-right: 0.5rem;
  font-size: 1.3rem;
}

.cart-items {
  margin-bottom: 1.5rem;
  max-height: 320px;
  overflow-y: auto;
  padding-right: 0.5rem;
}

.cart-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--card-border);
}

.cart-item:last-child {
  border-bottom: none;
}

.cart-item-details {
  flex: 1;
}

.cart-item-name {
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--text-dark);
  margin-bottom: 0.2rem;
}

.cart-item-price {
  font-size: 0.85rem;
  color: var(--text-medium);
}

.cart-item-quantity {
  display: flex;
  align-items: center;
  margin: 0 1rem;
}

.cart-quantity-text {
  padding: 0.2rem 0.6rem;
  min-width: 2rem;
  text-align: center;
  font-weight: 500;
  color: var(--text-dark);
}

.quantity-btn {
  width: 28px;
  height: 28px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background-color: var(--background-light);
  border: 1px solid var(--card-border);
  color: var(--text-medium);
  transition: all 0.2s ease;
}

.quantity-btn:hover {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: var(--white);
}

.quantity-btn i {
  font-size: 0.85rem;
}

.cart-item-total {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--primary-color);
  margin-right: 0.75rem;
  white-space: nowrap;
}

.cart-remove-btn {
  background-color: transparent;
  border: none;
  color: var(--danger);
  font-size: 1.1rem;
  padding: 0.3rem;
  cursor: pointer;
  transition: transform 0.2s;
}

.cart-remove-btn:hover {
  transform: scale(1.1);
}

.cart-empty {
  text-align: center;
  padding: 2rem 0;
  color: var(--text-light);
}

.cart-empty i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #cbd5e1;
}

.cart-summary {
  background-color: var(--background-light);
  border-radius: 10px;
  padding: 1.25rem;
  margin-bottom: 1.5rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
  color: var(--text-medium);
}

.summary-row.total {
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px dashed var(--card-border);
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-dark);
}

.payment-input {
  padding: 0.75rem;
  border-radius: 8px;
  border: 1px solid var(--card-border);
  width: 100%;
  font-size: 0.95rem;
  margin-bottom: 1rem;
  transition: all 0.2s;
}

.payment-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 114, 79, 0.1);
}

.payment-label {
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--text-medium);
  margin-bottom: 0.5rem;
  display: block;
}

.checkout-btn {
  width: 100%;
  padding: 0.75rem;
  border-radius: 8px;
  background-color: var(--primary-color);
  border: none;
  color: var(--white);
  font-weight: 600;
  font-size: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.checkout-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.checkout-btn i {
  margin-right: 0.5rem;
}

/* Alert Styling */
.alert {
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  position: relative;
  border: none;
}

.alert-danger {
  background-color: #fee2e2;
  color: #b91c1c;
}

.alert-success {
  background-color: #dcfce7;
  color: #15803d;
}

.alert strong {
  font-weight: 600;
}

.btn-close {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  font-size: 1.2rem;
  color: currentColor;
  opacity: 0.7;
}

.btn-close:hover {
  opacity: 1;
}

/* Sorting styles */
.sort-links {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 15px;
  align-items: center;
}

.sort-label {
  font-size: 0.9rem;
  color: #64748b;
  margin: 0;
  padding: 6px 0;
  display: flex;
  align-items: center;
  margin-right: 5px;
}

.sort-link {
  font-size: 0.9rem;
  color: #64748b;
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 8px;
  background-color: #f1f5f9;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.sort-link:hover {
  background-color: #e2e8f0;
  color: #334155;
}

.sort-link.active {
  background-color: #149d80;
  color: white;
}

.sort-link i {
  margin-left: 5px;
  font-size: 0.75rem;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .cart-container {
    position: static;
    margin-top: 2rem;
  }
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .sort-links {
    justify-content: flex-start;
    margin-bottom: 20px;
  }
}

@media (max-width: 576px) {
  .product-grid {
    grid-template-columns: 1fr;
  }

  .cart-item {
    flex-wrap: wrap;
  }

  .cart-item-details {
    width: 100%;
    margin-bottom: 0.5rem;
  }

  .cart-item-actions {
    display: flex;
    justify-content: space-between;
    width: 100%;
    align-items: center;
  }

  .sort-links {
    gap: 5px;
  }

  .sort-link {
    padding: 4px 8px;
    font-size: 0.8rem;
  }
}

/* Scrollbar Styling */
.cart-items::-webkit-scrollbar {
  width: 6px;
  border-radius: 3px;
}

.cart-items::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.cart-items::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 3px;
}
</style>
@endsection

@section('content')
<div class="container page-container">
  <!-- Error Alert -->
  @if (session('error'))
  <div class="alert alert-danger" role="alert">
    <strong>Error!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      <i class="bx bx-x"></i>
    </button>
  </div>
  @endif

  <!-- Success Alert -->
  @if (session('success'))
  <div class="alert alert-success" role="alert">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      <i class="bx bx-x"></i>
    </button>
  </div>
  @endif

  <div class="row">
    <!-- Kolom Kiri: Daftar Barang -->
    <div class="col-lg-8">
      <div class="page-header">
        <h1 class="page-title">
          <i class="bx bx-cart"></i> Kasir
        </h1>
      </div>

      <!-- Pencarian Barang -->
      <form method="GET" action="{{ route('cart.index') }}" id="searchForm">
        <div class="search-container">
          <i class="bx bx-search search-icon"></i>
          <input type="text" id="search-bar" name="search" class="search-input" placeholder="Cari nama barang..."
            value="{{ $searchQuery ?? '' }}">
          <input type="hidden" name="sort" value="{{ $sort ?? 'newest' }}">
          <input type="hidden" name="direction" value="{{ $direction ?? 'desc' }}">
        </div>
      </form>

      <!-- Sorting Links -->
      <div class="sort-links mb-4">
        <p class="sort-label mb-0">Urutkan:</p>
        <a href="{{ route('cart.index', ['sort' => 'price', 'direction' => $sort == 'price' && $direction == 'asc' ? 'desc' : 'asc', 'search' => $searchQuery ?? '']) }}"
          class="sort-link {{ $sort == 'price' ? 'active' : '' }}">
          Harga {!! $sort == 'price'
          ? ($direction == 'asc'
          ? '<i class="bx bx-sort-up"></i>'
          : '<i class="bx bx-sort-down"></i>')
          : '' !!}
        </a>
        <a href="{{ route('cart.index', ['sort' => 'expiry', 'direction' => $sort == 'expiry' && $direction == 'asc' ? 'desc' : 'asc', 'search' => $searchQuery ?? '']) }}"
          class="sort-link {{ $sort == 'expiry' ? 'active' : '' }}">
          Kadaluwarsa {!! $sort == 'expiry'
          ? ($direction == 'asc'
          ? '<i class="bx bx-sort-up"></i>'
          : '<i class="bx bx-sort-down"></i>')
          : '' !!}
        </a>
        <a href="{{ route('cart.index', ['sort' => 'newest', 'direction' => $sort == 'newest' && $direction == 'desc' ? 'asc' : 'desc', 'search' => $searchQuery ?? '']) }}"
          class="sort-link {{ $sort == 'newest' ? 'active' : '' }}">
          Terbaru {!! $sort == 'newest'
          ? ($direction == 'desc'
          ? '<i class="bx bx-sort-down"></i>'
          : '<i class="bx bx-sort-up"></i>')
          : '' !!}
        </a>
      </div>

      <!-- Daftar Barang dalam bentuk Card Grid -->
      <div class="row" id="products-container">
        @forelse ($products as $product)
        <div class="col-md-6 col-xl-4 px-3">
          <div class="card {{ $product->expired ? 'border-danger' : '' }}">
            <div class="card-img-container">
              <img src="{{ $product->image ? '/storage/' . $product->image : '/images/default.png' }}"
                class="card-img-top" alt="{{ $product->masterStock->name }}">

              <!-- Info Badge -->
              <div class="info-badge" onclick="showStockInfo({{ $product->master_stock_id }}, '{{ $product->size }}')"
                title="Lihat info stok">
                <i class="bx bx-info-circle"></i>
              </div>
            </div>
            <div class="card-body">
              <span class="product-type">{{ $product->masterStock->type }}</span>
              <h5 class="card-title">{{ $product->masterStock->name }} ({{ $product->size }})</h5>

              <div class="product-meta">
                <span class="product-price">Rp
                  {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                @if ($product->retail_price !== null)
                <div class="d-flex flex-column">
                  <span class="product-quantity">Total: {{ $product->total_quantity }}
                    pcs</span>
                </div>
                @else
                <span class="product-quantity">Total: {{ $product->total_quantity }} pcs</span>
                @endif
              </div>

              <div class="product-quantity-input row">
                <label for="quantity-{{ $product->id }}">Jumlah:</label>
                <input type="number" id="quantity-{{ $product->id }}" name="quantity" value="1" min="1"
                  max="{{ $product->total_quantity }}" class="form-control">
              </div>

              <br>

              {{-- <div class="product-expiry">
                                        <i class="bx {{ $product->expired ? 'bx-x-circle' : 'bx-calendar' }}"></i>
              <span class="{{ $product->expired ? 'expired-text' : '' }}">
                {{ $product->expired ? 'Sudah Kadaluarsa' : 'Exp: ' . $product->expiration_date_formatted }}
              </span>
            </div> --}}

            <!-- Check if retail_price is not null -->
            @if ($product->retail_price !== null)
            <button type="button" class="add-to-cart-btn {{ $product->expired ? 'expired-btn' : '' }}"
              {{ $product->expired ? 'disabled' : 'onclick=addToCart(' . $product->id . ')' }}>
              <i class="bx {{ $product->expired ? 'bx-x' : 'bx-cart-add' }}"></i>
              Tambahkan
            </button>
            <button type="button" class="add-to-cart-btn {{ $product->expired ? 'expired-btn' : '' }}"
              {{ $product->expired ? 'disabled' : 'onclick=addToCart(' . $product->id . ',`retail`)' }}>
              <i class="bx {{ $product->expired ? 'bx-x' : 'bx-cart-add' }}"></i>
              Tambah Eceran
            </button>
            @else
            <button type="button" class="add-to-cart-btn {{ $product->expired ? 'expired-btn' : '' }}"
              {{ $product->expired ? 'disabled' : 'onclick=addToCart(' . $product->id . ')' }}>
              <i class="bx {{ $product->expired ? 'bx-x' : 'bx-cart-add' }}"></i>
              Tambahkan
            </button>
            @endif
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="bx bx-package" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
        <h4 style="color: #64748b; font-weight: 500;">Tidak ada stok ditemukan</h4>
        <p style="color: #94a3b8;">Coba cari dengan kata kunci lain</p>
      </div>
      @endforelse
    </div>
  </div>

  <!-- Kolom Kanan: Keranjang Belanja -->
  <div class="col-lg-4">
    <div class="cart-container">
      <div class="cart-header">
        <h2 class="cart-title">
          <i class="bx bx-cart"></i> Keranjang Belanja
        </h2>
      </div>

      <div class="cart-items" id="cart-container">
        <!-- Cart items will be loaded here by AJAX -->
      </div>

      <div class="cart-summary">
        <div class="summary-row total">
          <span>Total</span>
          <span id="display-total">Rp 0</span>
        </div>
      </div>

      <form action="{{ route('checkout') }}" method="GET">
        <label for="total_paid" class="payment-label">Jumlah Bayar</label>
        <input type="number" class="payment-input" name="total_paid" id="total_paid" required>
        <input type="hidden" id="total_price" name="total_price">

        <button type="submit" class="checkout-btn">
          <i class="bx bx-check-circle"></i> Bayar Sekarang
        </button>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Stock Info Modal -->
<div class="stock-info-modal" id="stockInfoModal">
  <div class="stock-info-content">
    <div class="stock-info-header">
      <h3 class="stock-info-title" id="modalTitle">Info Stok</h3>
      <button class="close-modal" onclick="closeStockInfoModal()">
        <i class="bx bx-x"></i>
      </button>
    </div>
    <div id="batchInfo">
      <!-- Batch information will be inserted here -->
    </div>
    <div class="total-info">
      <p class="mb-0">Total Stok</p>
      <h3 class="total-quantity" id="totalQuantity">0 pcs</h3>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
// Function to handle real-time search input
$('#search-bar').on('input', function() {
  const query = $(this).val();
  if (query.length >= 2 || query.length === 0) {
    $('#searchForm').submit();
  }
});

// Function to show stock info modal
function showStockInfo(masterStockId, size) {
  $.ajax({
    url: "{{ route('cart.index') }}/stock-info",
    method: 'GET',
    data: {
      master_stock_id: masterStockId,
      size: size
    },
    success: function(response) {
      let batchHtml = '';
      let totalQuantity = 0;

      if (response.stocks.length > 0) {
        response.stocks.forEach(function(stock) {
          totalQuantity += parseInt(stock.quantity);
          batchHtml += `
                                <div class="batch-item">
                                    <div class="batch-info">
                                        <div class="batch-id">${stock.stock_id}</div>
                                        <div class="batch-expiry">Kadaluwarsa: ${stock.expiration_date}</div>
                                    </div>
                                    <div class="batch-quantity">${stock.quantity} pcs</div>
                                </div>
                            `;
        });
      } else {
        batchHtml = '<p class="text-center text-muted">Tidak ada stok tersedia</p>';
      }

      $('#batchInfo').html(batchHtml);
      $('#totalQuantity').text(totalQuantity + ' pcs');
      $('#stockInfoModal').css('display', 'flex');
    }
  });
}

// Function to close stock info modal
function closeStockInfoModal() {
  $('#stockInfoModal').css('display', 'none');
}

// Close modal when clicking outside
$(window).click(function(e) {
  if ($(e.target).closest('.stock-info-content').length === 0 && $(e.target).closest('.info-badge')
    .length === 0) {
    closeStockInfoModal();
  }
});

// Function to add to cart
function addToCart(productId, type) {
  const quantity = $(`#quantity-${productId}`).val();
  $.ajax({
    url: "{{ route('cart.add') }}",
    method: 'POST',
    data: {
      product_id: productId,
      quantity: quantity,
      type: type ?? 'normal',
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      loadCart(); // Reload cart after adding an item
    }
  });
}

// Function to load cart
function loadCart() {
  $.ajax({
    url: "{{ route('cart.get') }}",
    method: 'GET',
    success: function(response) {
      let cartHtml = '';
      let totalPrice = 0;
      let cartIsEmpty = response.carts.length === 0;

      if (!cartIsEmpty) {
        response.carts.forEach(function(cart) {
          totalPrice += parseFloat(cart.subtotal);
          cartHtml += `
             <div class="cart-item">
               <div class="cart-item-details">
                 <div class="cart-item-name">${cart.product.master_stock.name} <span style="font-size: 0.8rem; color: var(--text-medium);">(${cart.product.size})</span></div>
                 <div class="cart-item-price">Rp ${new Intl.NumberFormat('id-ID').format(cart.type == 'normal' ? cart.product.selling_price : cart.product.retail_price)} × ${cart.quantity}</div>
               </div>
               <div class="cart-item-quantity">
                 <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" onclick="updateCartQuantity(${cart.id}, ${cart.quantity - 1})">
                   <i class="bx bx-minus"></i>
                 </button>
                 <span class="cart-quantity-text">${cart.quantity}</span>
                 <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" onclick="updateCartQuantity(${cart.id}, ${cart.quantity + 1})">
                   <i class="bx bx-plus"></i>
                 </button>
               </div>
               <div class="cart-item-total">Rp ${new Intl.NumberFormat('id-ID').format(cart.subtotal)}</div>
               <button type="button" class="cart-remove-btn" onclick="removeFromCart(${cart.id})">
                 <i class="bx bx-trash"></i>
               </button>
             </div>
           `;
        });
      } else {
        cartHtml = `
           <div class="cart-empty">
             <i class="bx bx-cart"></i>
             <h4>Keranjang Kosong</h4>
             <p>Tambahkan barang ke keranjang</p>
           </div>
         `;
      }

      $('#cart-container').html(cartHtml);

      // Display total price
      const formattedTotal = new Intl.NumberFormat('id-ID').format(totalPrice);
      $('#display-total').text('Rp ' + formattedTotal);
      $('#total_price').val(totalPrice);

      // Enable/disable payment form based on cart status
      $('#total_paid').prop('disabled', cartIsEmpty);
      $('.checkout-btn').prop('disabled', cartIsEmpty);

      // Update checkout button style based on cart status
      if (cartIsEmpty) {
        $('.checkout-btn').css({
          'background-color': 'var(--text-light)',
          'cursor': 'not-allowed',
          'opacity': '0.6'
        }).hover(function() {
          $(this).css({
            'transform': 'none',
            'background-color': 'var(--text-light)'
          });
        }, function() {
          $(this).css({
            'transform': 'none',
            'background-color': 'var(--text-light)'
          });
        });
      } else {
        $('.checkout-btn').css({
          'background-color': 'var(--primary-color)',
          'cursor': 'pointer',
          'opacity': '1'
        }).off('mouseenter mouseleave');
      }
    }
  });
}

function removeFromCart(cartId) {
  $.ajax({
    url: `/cart/${cartId}`,
    type: 'POST',
    data: {
      _method: 'DELETE',
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      loadCart();
    },
    error: function(xhr, status, error) {
      alert('Terjadi kesalahan saat menghapus barang.');
    }
  });
}

function updateCartQuantity(cartId, newQuantity) {
  // Don't allow quantities less than 1
  if (newQuantity < 1) return;
  
  $.ajax({
    url: `/cart/${cartId}/quantity`,
    type: 'POST',
    data: {
      _method: 'PUT',
      quantity: newQuantity,
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      loadCart();
    },
    error: function(xhr, status, error) {
      if (xhr.status === 400) {
        // If the error is because of insufficient stock
        alert('Stok tidak mencukupi. Tersedia: ' + xhr.responseJSON.available);
      } else {
        alert('Terjadi kesalahan saat mengubah jumlah barang.');
      }
    }
  });
}

// When the document is ready, load the cart
$(document).ready(function() {
  loadCart();
});
</script>
@endsection