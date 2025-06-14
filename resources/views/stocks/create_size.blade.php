@extends('layouts.app')

@section('style')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
        }

        .header-title i {
            color: #149d80;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .form-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            border: none;
        }

        .form-card-body {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #149d80;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #475569;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #1e293b;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-static {
            background-color: #f8fafc;
            cursor: not-allowed;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .required-label::after {
            content: '*';
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            color: #fff;
            background-color: #149d80;
            border-color: #149d80;
        }

        .btn-primary:hover {
            background-color: #0c8b71;
            border-color: #0c8b71;
            box-shadow: 0 4px 10px rgba(0, 114, 79, 0.2);
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: #fff;
            background-color: #94a3b8;
            border-color: #94a3b8;
        }

        .btn-secondary:hover {
            background-color: #64748b;
            border-color: #64748b;
            box-shadow: 0 4px 10px rgba(100, 116, 139, 0.2);
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
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

        .success-detail {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .success-detail-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .success-detail-value {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="header-title">
                <i class="bx bx-plus-circle"></i> Tambah Stok {{ $masterStock->name }} - {{ $size }}
            </h1>
            <a href="{{ route('stocks.sizes', $masterStock->id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-body">
                <form action="{{ route('stocks.store.size') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="master_stock_id" value="{{ $masterStock->id }}">
                    <input type="hidden" name="size" value="{{ $size }}">

                    <!-- Basic Info Section - Read Only -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="bx bx-info-circle"></i> Informasi Produk
                        </h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="{{ $masterStock->name }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">SKU</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="{{ $masterStock->sku }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tipe</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="{{ $masterStock->type }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ukuran</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="{{ $size }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Harga Beli</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="Rp {{ number_format($sizeStock->purchase_price, 0, ',', '.') }}" readonly>
                                    <input type="hidden" name="purchase_price" value="{{ $sizeStock->purchase_price }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Harga Jual</label>
                                    <input type="text" class="form-control form-control-static"
                                        value="Rp {{ number_format($sizeStock->selling_price, 0, ',', '.') }}" readonly>
                                    <input type="hidden" name="selling_price" value="{{ $sizeStock->selling_price }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Info Section - Editable -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="bx bx-package"></i> Informasi Stok Baru
                        </h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required-label">Jumlah</label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required-label">Tanggal Kadaluwarsa</label>
                                    <input type="date" name="expiration_date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Gambar Produk untuk Ukuran Ini</label>
                                    @if ($masterStock->image)
                                        <div class="file-input-preview">
                                            <img src="{{ asset('storage/' . $masterStock->image) }}"
                                                alt="{{ $masterStock->name }}" style="max-height: 150px; max-width: 100%;">
                                            <p class="text-muted small">Gambar default produk (Anda dapat mengunggah gambar
                                                khusus untuk ukuran ini)</p>
                                        </div>
                                    @endif
                                    <input type="file" name="image" class="form-control mt-3" accept="image/*">
                                    <div class="form-text text-muted">Biarkan kosong untuk menggunakan gambar default
                                        produk.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('stocks.sizes', $masterStock->id) }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal-backdrop" id="successModal">
        <div class="success-modal-dialog">
            <div class="success-modal-content">
                <div class="success-icon-wrapper">
                    <i class="bx bx-check"></i>
                </div>
                <h3 class="success-title">Stok Berhasil Ditambahkan!</h3>
                <p class="success-message">Stok baru telah berhasil ditambahkan ke database.</p>

                <div class="success-detail">
                    <div class="row mb-2">
                        <div class="col-5">
                            <div class="success-detail-label">Produk:</div>
                        </div>
                        <div class="col-7">
                            <div class="success-detail-value" id="success-product-name">{{ $masterStock->name }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5">
                            <div class="success-detail-label">Ukuran:</div>
                        </div>
                        <div class="col-7">
                            <div class="success-detail-value" id="success-product-size">{{ $size }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5">
                            <div class="success-detail-label">Jumlah:</div>
                        </div>
                        <div class="col-7">
                            <div class="success-detail-value" id="success-product-quantity"></div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('stocks.sizes', $masterStock->id) }}" class="success-modal-btn">
                    <i class="bx bx-check me-2"></i> Kembali ke Daftar Stok
                </a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Show success modal if session has success message
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                // Get the modal element
                const modal = document.getElementById('successModal');

                // Set product quantity if available in session
                @if (session('quantity'))
                    document.getElementById('success-product-quantity').textContent =
                        "{{ session('quantity') }} pcs";
                @endif

                // Show the modal
                if (modal) {
                    modal.style.display = 'flex';
                    setTimeout(function() {
                        modal.classList.add('show');
                        document.body.style.overflow = 'hidden';
                    }, 100);
                }
            });
        @endif

        // Close modal when clicking outside or pressing ESC key
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('successModal');

            if (modal) {
                // Close modal when clicking outside the content
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

                // Close modal with ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modal.classList.contains('show')) {
                        closeModal();
                    }
                });
            }

            function closeModal() {
                const modal = document.getElementById('successModal');
                modal.classList.remove('show');
                setTimeout(function() {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }
        });
    </script>
@endsection
