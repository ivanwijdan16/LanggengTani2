@extends('layouts.app')

@section('style')
    <style>
        .stock-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            height: 100%;
            border: none;
        }

        .stock-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

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

        .stock-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .stock-card .card-actions {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
        }

        .stock-card .action-btn {
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex: 1;
        }

        .stock-card .action-btn:first-child {
            margin-right: 0.5rem;
        }

        .stock-card .action-btn i {
            margin-right: 0.35rem;
            font-size: 1rem;
        }

        /* Edit button styling */
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

        /* Delete button styling */
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

        /* Stock Card Hover Animation Improvement */
        .stock-card {
            position: relative;
            overflow: hidden;
        }

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

        /* Improve card content spacing */
        .stock-card .card-body {
            padding: 1.25rem;
        }

        .stock-card .card-title {
            margin-bottom: 0.75rem;
            color: #1e293b;
        }

        /* Enhanced quantity badge */
        .stock-quantity-badge {
            background-color: #ecfdf5;
            color: #0f766e;
            border: 1px solid #d1fae5;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stock-quantity-badge i {
            color: #10b981;
        }

        .search-wrapper {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .search-control {
            border: none;
            padding: 12px 20px;
            height: auto;
        }

        .search-control:focus {
            box-shadow: none;
        }

        .search-btn {
            border-radius: 0 15px 15px 0 !important;
            padding-left: 25px;
            padding-right: 25px;
            background-color: #149d80;
            border-color: #149d80;
        }

        .search-btn:hover {
            background-color: #0c8b71;
            border-color: #0c8b71;
        }

        .search-addon {
            border: none;
            background-color: transparent;
        }

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

        .price-tag {
            color: #149d80;
            font-weight: 700;
        }

        .quantity-badge {
            background-color: #f1f5f9;
            color: #334155;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .card-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .empty-state {
            padding: 50px 20px;
            border-radius: 15px;
        }

        /* Custom Pagination */
        .pagination {
            gap: 5px;
        }

        .page-item .page-link {
            border-radius: 8px;
            border: none;
            color: #475569;
            padding: 10px 15px;
        }

        .page-item.active .page-link {
            background-color: #149d80;
            color: white;
        }

        .date-badge {
            transition: all 0.3s ease;
        }

        .date-badge i {
            transition: all 0.3s ease;
        }

        .stock-card:hover .date-badge i {
            transform: translateX(-3px);
        }

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

        .sort-dropdown {
            border-radius: 8px;
            background-color: #f1f5f9;
            border: none;
            color: #64748b;
            padding: 6px 15px;
            font-size: 0.9rem;
        }

        /* Total Stock Badge Style */
        .stock-quantity-badge {
            background-color: #e2e8f0;
            color: #334155;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            margin-top: 8px;
        }

        .stock-quantity-badge i {
            margin-right: 5px;
            font-size: 0.8rem;
            color: #149d80;
        }

        /* Shared Modal Styles */
        .success-modal-backdrop,
        .error-modal-backdrop,
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

        .success-modal-backdrop.show,
        .error-modal-backdrop.show,
        .delete-modal-backdrop.show {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .success-modal-dialog,
        .error-modal-dialog,
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

        .success-modal-backdrop.show .success-modal-dialog,
        .error-modal-backdrop.show .error-modal-dialog,
        .delete-modal-backdrop.show .delete-modal-dialog {
            transform: translateY(0) scale(1);
        }

        .success-modal-header,
        .error-modal-header,
        .delete-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .success-modal-title,
        .error-modal-title,
        .delete-modal-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .success-modal-title {
            color: #149d80;
        }

        .error-modal-title,
        .delete-modal-title {
            color: #ef4444;
        }

        .success-modal-title i,
        .error-modal-title i,
        .delete-modal-title i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .success-modal-body,
        .error-modal-body,
        .delete-modal-body {
            padding: 1.75rem;
            color: #475569;
            text-align: center;
        }

        .success-modal-footer,
        .error-modal-footer,
        .delete-modal-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        /* Success Modal Specific Styles */
        .success-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: rgba(0, 114, 79, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .success-icon-wrapper i {
            color: #149d80;
            font-size: 2.5rem;
        }

        .success-message {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .success-detail {
            color: #64748b;
            margin-bottom: 0;
        }

        .success-modal-btn,
        .delete-modal-btn {
            background-color: #149d80;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-modal-btn:hover,
        .delete-modal-btn:hover {
            background-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 79, 0.2);
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

        /* Error Modal Specific Styles */
        .error-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: rgba(239, 68, 68, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .error-icon-wrapper i {
            color: #ef4444;
            font-size: 2.5rem;
        }

        .error-message {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .error-detail {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .error-info-box {
            background-color: #f1f5f9;
            border-radius: 10px;
            padding: 1.25rem;
            text-align: left;
            margin-bottom: 1rem;
            border-left: 4px solid #64748b;
        }

        .error-info-box ul {
            padding-left: 1.25rem;
            margin-bottom: 0;
        }

        .error-info-box li {
            margin-bottom: 0.5rem;
        }

        .error-info-box li:last-child {
            margin-bottom: 0;
        }

        .error-modal-footer {
            justify-content: space-between;
        }

        .error-modal-btn-primary {
            background-color: #149d80;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .error-modal-btn-primary:hover {
            background-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 79, 0.2);
            color: white;
            text-decoration: none;
        }

        .error-modal-btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-modal-btn-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
        }

        /* Success Modal Styles */
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

        @media (max-width: 768px) {

            .error-modal-footer,
            .delete-modal-footer {
                flex-direction: column;
            }

            .error-modal-btn-primary,
            .error-modal-btn-secondary,
            .delete-modal-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-sm-flex justify-content-between align-items-center">
                    <h1 class="header-title mb-3"><i class="bx bx-box"></i>Stok Barang</h1>
                    <a href="{{ route('stocks.create') }}" class="btn add-btn btn-success d-flex align-items-center">
                        <i class="bx bx-plus me-2"></i> Tambah Stok Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4 border-0 search-wrapper">
            <div class="card-body p-0">
                <form method="GET" action="{{ route('stocks.index') }}" id="searchForm">
                    <div class="input-group">
                        <span class="input-group-text search-addon">
                            <i class="bx bx-search fs-5 text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control search-control"
                            placeholder="Cari stok berdasarkan nama..." value="{{ request('search') }}">
                        <input type="hidden" name="sort" id="sort" value="{{ request('sort', 'name') }}">
                        <input type="hidden" name="direction" id="direction" value="{{ request('direction', 'asc') }}">
                        <button type="submit" class="btn search-btn btn-primary">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sorting Links -->
        <div class="sort-links mb-4">
            <p class="sort-label mb-0">Urutkan:</p>
            @php
                $currentSort = request('sort', 'name');
                $currentDirection = request('direction', 'asc');

                function getSortLink($field, $label, $currentSort, $currentDirection)
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
                        'url' => route(
                            'stocks.index',
                            array_merge(request()->except(['sort', 'direction']), [
                                'sort' => $field,
                                'direction' => $direction,
                            ]),
                        ),
                        'label' => $label . $icon,
                        'isActive' => $isActive,
                    ];
                }

                $nameLink = getSortLink('name', 'Nama', $currentSort, $currentDirection);
                $typeLink = getSortLink('type', 'Tipe', $currentSort, $currentDirection);
            @endphp

            <a href="{{ $nameLink['url'] }}"
                class="sort-link {{ $nameLink['isActive'] ? 'active' : '' }}">{!! $nameLink['label'] !!}</a>
            <a href="{{ $typeLink['url'] }}"
                class="sort-link {{ $typeLink['isActive'] ? 'active' : '' }}">{!! $typeLink['label'] !!}</a>
        </div>

        <!-- Stock Grid with Improved Master Stock Cards -->
        <div class="row">
            @forelse ($stocks as $stock)
                @php
                    $image = $stock->image ? asset('storage/' . $stock->image) : asset('images/default.png');
                    $totalQuantity = $stockQuantities[$stock->id] ?? 0;
                    $lowStock = $totalQuantity <= 5 && $totalQuantity > 0;
                    $stockOut = $totalQuantity <= 0;

                    // Check if any stock batches are expired
                    $hasExpired = false;

                    $allStocks = \App\Models\Stock::where('master_stock_id', $stock->id)
                        ->whereNull('deleted_at')
                        ->get();

                    foreach ($allStocks as $stockItem) {
                        $expiredCheck = \Carbon\Carbon::parse($stockItem->expiration_date)->isPast();

                        if ($expiredCheck) {
                            $hasExpired = true;
                            break;
                        }
                    }
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card stock-card" data-id="{{ $stock->id }}"
                        onclick="viewStockSizes({{ $stock->id }})" style="cursor: pointer;">
                        <div class="card-img-wrapper">
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $stock->name }}">

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
                            <h5 class="card-title fw-bold">{{ $stock->name }}</h5>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-subtitle mb-0 text-muted">{{ $stock->sku }}</h6>
                                <span class="stock-quantity-badge">
                                    <i class="bx bx-package"></i> {{ $totalQuantity }} pcs
                                </span>
                            </div>
                            <p class="card-text text-muted mb-1 small">{{ $stock->type }} @if ($stock->sub_type)
                                    <span class="card-text text-muted mb-0 small">{{ $stock->sub_type }}</span>
                                @endif
                            </p>


                            <div class="card-actions">
                                <a href="{{ route('stocks.edit.master', $stock->id) }}" class="btn action-btn btn-edit"
                                    onclick="event.stopPropagation();">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <button type="button" class="btn action-btn btn-delete"
                                    data-stock-id="{{ $stock->id }}" data-stock-name="{{ $stock->name }}"
                                    data-stock-sku="{{ $stock->sku }}"
                                    onclick="event.stopPropagation(); openDeleteMasterModal({{ $stock->id }}, '{{ $stock->name }}', '{{ $stock->sku }}')">
                                    <i class="bx bx-trash"></i> Hapus
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
                            <h3 class="mb-3">Tidak ada stok</h3>
                            <a href="{{ route('stocks.create') }}" class="btn add-btn btn-success">
                                <i class="bx bx-plus me-2"></i> Tambah Stok Baru
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $stocks->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Delete Master Stock Modal -->
    <div class="delete-modal-backdrop" id="deleteMasterModal">
        <div class="delete-modal-dialog">
            <div class="delete-modal-content">
                <div class="delete-modal-header">
                    <h5 class="delete-modal-title">
                        <i class="bx bx-error-circle"></i> Konfirmasi Hapus
                    </h5>
                </div>
                <div class="delete-modal-body">
                    <p>Apakah Anda yakin ingin menghapus seluruh stok untuk produk ini?</p>
                    <div class="delete-modal-product" id="deleteMasterProduct">
                        <!-- Product info will be inserted here -->
                    </div>
                    <p>Tindakan ini tidak dapat dibatalkan dan akan menghapus seluruh data stok untuk SKU ini.</p>
                </div>
                <div class="delete-modal-footer">
                    <button type="button" class="delete-modal-btn delete-modal-btn-cancel"
                        onclick="closeDeleteMasterModal()">
                        <i class="bx bx-x me-1"></i> Batal
                    </button>
                    <button type="button" class="delete-modal-btn delete-modal-btn-delete" onclick="deleteMasterStock()">
                        <i class="bx bx-trash me-1"></i> Hapus Produk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-master-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Success Modal -->
    <div class="success-modal-backdrop" id="successModal">
        <div class="success-modal-dialog">
            <div class="success-modal-content">
                <div class="success-icon-wrapper">
                    <i class="bx bx-check"></i>
                </div>
                <h3 class="success-title">Operasi Berhasil!</h3>
                <p class="success-message" id="success-message">
                    @if (session('message'))
                        {{ session('message') }}
                    @else
                        Perubahan telah berhasil disimpan.
                    @endif
                </p>

                <a href="{{ route('stocks.index') }}" class="success-modal-btn">
                    <i class="bx bx-check me-2"></i> Tutup
                </a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function viewStockSizes(masterStockId) {
            window.location.href = "{{ url('stocks/sizes') }}/" + masterStockId;
        }

        function openDeleteMasterModal(stockId, stockName, stockSku) {
            event.stopPropagation();

            // Set data in the modal
            document.getElementById('deleteMasterProduct').innerHTML = `
      <div class="delete-modal-product-name">${stockName}</div>
      <div class="delete-modal-product-type">${stockSku}</div>
    `;

            // Set form action URL
            document.getElementById('delete-master-form').action = "{{ url('stocks/master') }}/" + stockId;

            // Show the modal
            const modal = document.getElementById('deleteMasterModal');

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

        function closeDeleteMasterModal() {
            const modal = document.getElementById('deleteMasterModal');

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

        function deleteMasterStock() {
            document.getElementById('delete-master-form').submit();
        }

        // Set up event listeners when the document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            const deleteMasterModal = document.getElementById('deleteMasterModal');
            if (deleteMasterModal) {
                deleteMasterModal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeDeleteMasterModal();
                    }
                });
            }

            // Close modal with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && deleteMasterModal && deleteMasterModal.classList.contains(
                        'show')) {
                    closeDeleteMasterModal();
                }
            });

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

            // Add success modal handler if needed
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

                    // Add ESC key handler
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && successModal.classList.contains('show')) {
                            closeSuccessModal();
                        }
                    });
                }
            @endif
        });
    </script>
@endsection
