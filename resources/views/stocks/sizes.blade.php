@extends('layouts.app')

@section('style')
    <style>
        /* Card Styling */
        .stock-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            height: 100%;
            border: none;
            position: relative;
        }

        .stock-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stock-card .card-subtitle {
            font-size: 0.75rem;
            /* Reduced from default size */
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0;
        }

        /* Green top border animation */
        .stock-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #149d80, #0c8b71);
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }

        .stock-card:hover::after {
            transform: translateY(0);
        }

        /* Card Image */
        .stock-card .card-img-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .stock-card .card-img-top {
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .stock-card:hover .card-img-top {
            transform: scale(1.08);
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            position: absolute;
            top: 10px;
            z-index: 10;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .stock-out {
            left: 10px;
            background-color: #ef4444;
        }

        .stock-minimal {
            left: 10px;
            background-color: #FF9800;
        }

        .nearly-expired {
            right: 10px;
            background-color: #FF9800;
        }

        .expired {
            right: 10px;
            background-color: #ef4444;
        }

        .status-badge i {
            margin-right: 4px;
            font-size: 0.8rem;
        }

        /* Card Body */
        .stock-card .card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .stock-card .card-title {
            margin-bottom: 0.75rem;
            color: #1e293b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Card Actions */
        .stock-card .card-actions {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
            gap: 0.5rem;
        }

        /* Action Buttons */
        .stock-card .action-btn {
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex: 1;
            white-space: nowrap;
        }

        .stock-card .action-btn i {
            font-size: 1rem;
        }

        /* Button Types */
        .stock-card .btn-edit {
            background-color: #e0f2f1;
            color: #0c8b71;
            border: none;
        }

        .stock-card .btn-edit:hover {
            background-color: #0c8b71;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(12, 139, 113, 0.2);
        }

        .stock-card .btn-delete {
            background-color: #fee2e2;
            color: #ef4444;
            border: none;
        }

        .stock-card .btn-delete:hover {
            background-color: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.2);
        }

        .stock-card .btn-info {
            background-color: #e0f7fa;
            color: #0288d1;
            border: none;
        }

        .stock-card .btn-info:hover {
            background-color: #0288d1;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(2, 136, 209, 0.2);
        }

        .stock-card .btn-success {
            background-color: #e3f8e9;
            color: #10b981;
            border: none;
        }

        .stock-card .btn-success:hover {
            background-color: #10b981;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.2);
        }

        /* Badges and Tags */
        .badge-small {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
        }

        .badge-small i {
            font-size: 0.7rem;
            margin-right: 3px;
        }

        .stock-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .stock-quantity-badge {
            background-color: #ecfdf5;
            color: #0f766e;
            border: 1px solid #d1fae5;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .stock-quantity-badge i {
            color: #10b981;
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .price-tag {
            color: #149d80;
            font-weight: 700;
        }

        /* Date Badge */
        .date-badge {
            transition: all 0.3s ease;
        }

        .date-badge i {
            transition: all 0.3s ease;
        }

        .stock-card:hover .date-badge i {
            transform: translateX(-3px);
        }

        /* Header */
        .header-title {
            color: #1e293b;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .header-title i {
            color: #149d80;
            margin-right: 10px;
            font-size: 1.5rem;
        }

        /* Add Button */
        .add-btn {
            background-color: #149d80;
            border-color: #149d80;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 114, 79, 0.2);
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            background-color: #0c8b71;
            border-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 114, 79, 0.3);
        }

        /* Sorting Styles */
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

        /* Breadcrumb Styles */
        .breadcrumbs {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item {
            color: #64748b;
        }

        .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
        }

        .breadcrumb-divider {
            margin: 0 0.5rem;
            color: #cbd5e1;
        }

        /* Empty State */
        .empty-state {
            padding: 50px 20px;
            border-radius: 15px;
        }

        .success-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.7);
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .success-modal-backdrop.show {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .success-modal-dialog {
            background-color: white;
            border-radius: 15px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-30px) scale(0.95);
            transition: transform 0.3s ease;
            margin: 1.5rem;
            overflow: hidden;
        }

        .success-modal-backdrop.show .success-modal-dialog {
            transform: translateY(0) scale(1);
        }

        .success-modal-content {
            padding: 2rem;
            text-align: center;
        }

        .success-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: rgba(0, 114, 79, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon-wrapper i {
            color: #149d80;
            font-size: 2.5rem;
        }

        .success-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .success-message {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .success-modal-btn {
            background-color: #149d80;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .success-modal-btn:hover {
            background-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 79, 0.2);
            color: white;
            text-decoration: none;
        }

        /* Delete Modal Styles */
        .delete-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.7);
            z-index: 1050;
            display: none;
            /* Initially hidden */
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .delete-modal-backdrop.show {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .delete-modal-dialog {
            background-color: white;
            border-radius: 15px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-30px) scale(0.95);
            transition: transform 0.3s ease;
            margin: 1.5rem;
            overflow: hidden;
        }

        .delete-modal-backdrop.show .delete-modal-dialog {
            transform: translateY(0) scale(1);
        }

        .delete-modal-content {
            width: 100%;
        }

        .delete-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .delete-modal-title {
            margin: 0;
            color: #ef4444;
            font-size: 1.35rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .delete-modal-title i {
            color: #ef4444;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .delete-modal-body {
            padding: 1.75rem;
            color: #475569;
            text-align: center;
        }

        .delete-modal-product {
            margin: 0.5rem 0;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 12px;
            text-align: center;
        }

        .delete-modal-product-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.15rem;
        }

        .delete-modal-product-type {
            display: inline-block;
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .delete-modal-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .delete-modal-btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .delete-modal-btn-cancel {
            background-color: #f1f5f9;
            color: #475569;
            border: none;
        }

        .delete-modal-btn-cancel:hover {
            background-color: #e2e8f0;
            color: #334155;
        }

        .delete-modal-btn-delete {
            background-color: #ef4444;
            color: white;
            border: none;
        }

        .delete-modal-btn-delete:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2);
        }

        /* Responsive Styles */
        @media (max-width: 575.98px) {
            .stock-card .card-actions {
                flex-direction: column;
            }

            .stock-card .action-btn {
                margin-bottom: 0.5rem;
                width: 100%;
                justify-content: center;
            }

            .stock-card .action-btn i {
                margin-right: 0.5rem;
            }

            .delete-modal-footer {
                flex-direction: column;
            }

            .delete-modal-btn {
                width: 100%;
            }
        }

        /* Small tablets and large phones */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .stock-card .action-btn {
                padding: 0.4rem;
            }

            .stock-card .action-btn span {
                display: none;
            }

            .stock-card .action-btn i {
                margin-right: 0;
                font-size: 1.1rem;
            }
        }

        /* Tablets */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .stock-card .action-btn {
                padding: 0.5rem 0.75rem;
            }

            .stock-card .action-btn i {
                margin-right: 0.35rem;
            }
        }

        /* Show text on large screens */
        @media (min-width: 992px) {
            .stock-card .action-btn {
                padding: 0.5rem 0.75rem;
            }

            .stock-card .action-btn i {
                margin-right: 0.35rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-sm-flex justify-content-between align-items-center">
                    <h1 class="header-title mb-3">
                        <i class="bx bx-box"></i>{{ $masterStock->name }} ({{ $masterStock->sku }})
                    </h1>
                    <a href="{{ route('stocks.index') }}" class="btn btn-secondary mr-2">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="breadcrumb-item">
                <a href="{{ route('stocks.index') }}">Stok</a>
            </div>
            <div class="breadcrumb-divider">
                <i class="bx bx-chevron-right"></i>
            </div>
            <div class="breadcrumb-item">{{ $masterStock->name }}</div>
        </div>

        <!-- Add this after the breadcrumbs section -->
        <div class="sort-links mb-4">
            <p class="sort-label mb-0">Urutkan:</p>
            @php
                $currentSort = request('sort', '');
                $currentDirection = request('direction', 'asc');

                function getSortLink($field, $label, $currentSort, $currentDirection, $masterId)
                {
                    $direction = $currentSort == $field && $currentDirection == 'asc' ? 'desc' : 'asc';
                    $isActive = $currentSort == $field;
                    $icon = '';

                    if ($isActive) {
                        $icon =
                            $currentDirection == 'asc'
                                ? '<i class="bx bx-sort-down"></i>'
                                : '<i class="bx bx-sort-up"></i>';
                    }

                    return [
                        'url' => route('stocks.sizes', [
                            'master_id' => $masterId,
                            'sort' => $field,
                            'direction' => $direction,
                        ]),
                        'label' => $label . ' ' . $icon,
                        'isActive' => $isActive,
                    ];
                }

                $defaultLink = [
                    'url' => route('stocks.sizes', ['master_id' => $masterStock->id]),
                    'label' => 'Default',
                    'isActive' => $currentSort === '',
                ];

                $priceLink = getSortLink('price', 'Harga', $currentSort, $currentDirection, $masterStock->id);

            @endphp

            <a href="{{ $priceLink['url'] }}"
                class="sort-link {{ $priceLink['isActive'] ? 'active' : '' }}">{!! $priceLink['label'] !!}</a>

        </div>

        <!-- Size-based Stock Grid -->
        <div class="row">
            @forelse ($sizeGroups as $size => $stocks)
                @php
                    // We need to make sure only non-deleted stocks are included in the group
                    $nonDeletedStocks = $stocks->reject(function ($stock) {
                        return $stock->trashed();
                    });
                    $totalQuantity = $nonDeletedStocks->sum('quantity');
                    $lowStock = $totalQuantity <= 5 && $totalQuantity > 0;
                    $stockOut = $totalQuantity <= 0;

                    // Check if any stock is expired for this specific size
                    $hasExpired = false;

                    // Get all stocks for this master stock and specific size
                    $allSizeStocks = \App\Models\Stock::where('master_stock_id', $masterStock->id)
                        ->where('size', $size)
                        ->whereNull('deleted_at')
                        ->get();

                    foreach ($allSizeStocks as $stockItem) {
                        $expiredCheck = \Carbon\Carbon::parse($stockItem->expiration_date)->isPast();

                        if ($expiredCheck) {
                            $hasExpired = true;
                            break;
                        }
                    }

                    $stock = $stocks->first(); // Representative stock for this size
                    $sizeImagePath =
                        isset($sizeImages[$size]) && $sizeImages[$size]->image
                            ? $sizeImages[$size]->image
                            : $masterStock->image;
                    $image = $sizeImagePath ? asset('storage/' . $sizeImagePath) : asset('images/default.png');
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card stock-card" onclick="viewStockBatches({{ $masterStock->id }}, '{{ $size }}')"
                        style="cursor: pointer;">
                        <div class="card-img-wrapper">
                            <img src="{{ $image }}" class="card-img-top"
                                alt="{{ $masterStock->name }} - {{ $size }}">

                            @if ($stockOut)
                                <span class="status-badge stock-out">
                                    <i class="bx bx-x-circle"></i> Stok Habis
                                </span>
                            @elseif($lowStock)
                                <span class="status-badge stock-minimal">
                                    <i class="bx bx-package"></i> Stok Menipis
                                </span>
                            @endif

                            @if ($hasExpired)
                                <span class="status-badge expired">
                                    <i class="bx bx-x-circle"></i> Ada Kadaluwarsa
                                </span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $masterStock->name }} - {{ $size }}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-subtitle mb-0">{{ $stock->stock_id }}</h6>
                                <span class="stock-quantity-badge">
                                    <i class="bx bx-package"></i> {{ $totalQuantity }} pcs
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                <span class="price-tag fs-5">Rp
                                    {{ number_format($stock->selling_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('stocks.create.size', ['master_id' => $masterStock->id, 'size' => $size]) }}"
                                    class="btn action-btn btn-success" onclick="event.stopPropagation();">
                                    <i class="bx bx-plus"></i> <span>Tambah</span>
                                </a>
                                <a href="{{ route('stocks.edit.size', ['master_id' => $masterStock->id, 'size' => $size]) }}"
                                    class="btn action-btn btn-edit" onclick="event.stopPropagation();">
                                    <i class="bx bx-edit"></i> <span>Edit</span>
                                </a>
                                <button class="btn action-btn btn-delete"
                                    onclick="event.stopPropagation(); openDeleteSizeModal('{{ $size }}')">
                                    <i class="bx bx-trash"></i> <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card empty-state border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bx bx-package" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                            <h3 class="mb-3">Tidak ada stok untuk produk ini</h3>
                            <a href="{{ route('stocks.create.size', ['master_id' => $masterStock->id]) }}"
                                class="btn add-btn btn-success">
                                <i class="bx bx-plus me-2"></i> Tambah Stok Baru
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Delete Size Modal -->
        <div class="delete-modal-backdrop" id="deleteSizeModal">
            <div class="delete-modal-dialog">
                <div class="delete-modal-content">
                    <div class="delete-modal-header">
                        <h5 class="delete-modal-title">
                            <i class="bx bx-error-circle"></i> Konfirmasi Hapus
                        </h5>
                    </div>
                    <div class="delete-modal-body">
                        <p>Apakah Anda yakin ingin menghapus seluruh stok untuk ukuran ini?</p>
                        <div class="delete-modal-product" id="deleteSizeProduct">
                            <!-- Size info will be inserted here -->
                        </div>
                        <p>Tindakan ini tidak dapat dibatalkan dan akan menghapus seluruh data stok untuk ukuran ini.</p>
                    </div>
                    <div class="delete-modal-footer">
                        <button type="button" class="delete-modal-btn delete-modal-btn-cancel"
                            onclick="closeDeleteSizeModal()">
                            <i class="bx bx-x me-1"></i> Batal
                        </button>
                        <button type="button" class="delete-modal-btn delete-modal-btn-delete" onclick="deleteSize()">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form id="delete-size-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <div class="success-modal-backdrop" id="successModal">
            <div class="success-modal-dialog">
                <div class="success-modal-content">
                    <div class="success-icon-wrapper">
                        <i class="bx bx-check"></i>
                    </div>
                    <h3 class="success-title">Berhasil!</h3>
                    <p class="success-message" id="success-message">
                        @if (session('message'))
                            {{ session('message') }}
                        @else
                            Stok Berhasil Ditambah
                        @endif
                    </p>

                    <a href="{{ route('stocks.sizes', $masterStock->id) }}" class="success-modal-btn">
                        <i class="bx bx-check me-2"></i> Tutup
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function viewStockBatches(masterStockId, size) {
            window.location.href = "{{ url('stocks/batches') }}/" + masterStockId + "/" + encodeURIComponent(size);
        }

        function openDeleteSizeModal(size) {
            event.stopPropagation();

            // Set form action URL
            document.getElementById('delete-size-form').action = "{{ url('stocks/size') }}/" + {{ $masterStock->id }} +
                "/" + encodeURIComponent(size);

            // Set size info in modal
            document.getElementById('deleteSizeProduct').innerHTML = `
      <div class="delete-modal-product-name">{{ $masterStock->name }} - ${size}</div>
    `;

            // Show the modal
            const modal = document.getElementById('deleteSizeModal');

            // Set display to flex first
            modal.style.display = 'flex';

            // Trigger a reflow
            void modal.offsetWidth;

            // Then add the show class for the transitions
            modal.classList.add('show');
            modal.style.opacity = '1';
            modal.style.visibility = 'visible';

            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteSizeModal() {
            const modal = document.getElementById('deleteSizeModal');

            // Start the transition
            modal.style.opacity = '0';

            // Wait for transition to finish before hiding
            setTimeout(function() {
                modal.classList.remove('show');
                modal.style.visibility = 'hidden';
                modal.style.display = 'none';

                // Re-enable background scrolling
                document.body.style.overflow = '';
            }, 300); // Match this to your CSS transition duration
        }

        function deleteSize() {
            document.getElementById('delete-size-form').submit();
        }

        // Define closeSuccessModal at the top level so it's available everywhere
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.opacity = '0';
                setTimeout(function() {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // Document ready event for all event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            const deleteSizeModal = document.getElementById('deleteSizeModal');
            if (deleteSizeModal) {
                deleteSizeModal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeDeleteSizeModal();
                    }
                });
            }

            // Close modal with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (document.getElementById('deleteSizeModal')?.classList.contains('show')) {
                        closeDeleteSizeModal();
                    }
                    if (document.getElementById('successModal')?.classList.contains('show')) {
                        closeSuccessModal();
                    }
                }
            });

            // Show success modal if needed
            @if (session('success') || session('showSuccessModal'))
                // Get the modal element
                const successModal = document.getElementById('successModal');

                // Set custom message if available
                @if (session('message'))
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.textContent = "{{ session('message') }}";
                    }
                @endif

                // Show the modal
                if (successModal) {
                    successModal.style.display = 'flex';
                    setTimeout(function() {
                        successModal.classList.add('show');
                        successModal.style.opacity = '1';
                        document.body.style.overflow = 'hidden';
                    }, 100);

                    // Add click handler for closing
                    successModal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeSuccessModal();
                        }
                    });
                }
            @endif
        });
    </script>
@endsection
