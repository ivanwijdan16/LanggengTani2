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
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: #149d80;
        }

        .breadcrumb-divider {
            margin: 0 0.5rem;
            color: #cbd5e1;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
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

        .form-control:focus {
            border-color: #149d80;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(0, 114, 79, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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
            white-space: nowrap;
            vertical-align: middle;
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

        .btn-primary:hover,
        .btn-primary:focus {
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

        .btn-secondary:hover,
        .btn-secondary:focus {
            background-color: #64748b;
            border-color: #64748b;
            box-shadow: 0 4px 10px rgba(100, 116, 139, 0.2);
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .form-hint {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.4rem;
        }

        .preview-image {
            max-width: 100px;
            border-radius: 0.5rem;
            margin-top: 0.75rem;
            border: 2px solid #e2e8f0;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input-preview {
            margin-top: 0.75rem;
        }

        .input-group {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 400;
            line-height: 1.5;
            color: #475569;
            text-align: center;
            white-space: nowrap;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem 0 0 0.5rem;
            border-right: none;
        }

        .input-group .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .required-label::after {
            content: '*';
            color: #ef4444;
            margin-left: 0.25rem;
        }

        /* Success Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.7);
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-dialog {
            background-color: white;
            border-radius: 15px;
            max-width: 480px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px) scale(0.95);
            transition: transform 0.3s ease;
            margin: 1rem;
        }

        .modal-backdrop.show .modal-dialog {
            transform: translateY(0) scale(1);
        }

        .modal-content {
            position: relative;
            padding: 2rem;
            text-align: center;
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(0, 114, 79, 0.1);
            color: #149d80;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .modal-icon i {
            font-size: 2.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .modal-message {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .modal-product {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .product-item {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .modal-product-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* Style untuk tombol hapus pada form yang dikloning */
        .remove-form {
            z-index: 10;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ef4444;
            border-color: #ef4444;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .remove-form:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(239, 68, 68, 0.3);
        }

        .remove-form i {
            margin: 0;
            font-size: 1.25rem;
        }

        .form-container {
            position: relative;
            padding-top: 15px;
            border-top: 1px dashed #e2e8f0;
            margin-top: 20px;
        }

        #form-container {
            border-top: none;
            margin-top: 0;
            padding-top: 0;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-card-body {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .modal-dialog {
                width: 95%;
            }

            .modal-buttons {
                flex-direction: column;
            }

            .modal-buttons .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="header-title">
                <i class="bx bx-package"></i> Tambah Stok Baru
            </h1>
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="breadcrumb-item">
                <a href="{{ route('stocks.index') }}">Stok</a>
            </div>
            <div class="breadcrumb-divider">
                <i class="bx bx-chevron-right"></i>
            </div>
            <div class="breadcrumb-item">Tambah Stok</div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-body">
                <form action="{{ route('stocks.store') }}" method="POST" enctype="multipart/form-data" id="stockForm">
                    @csrf

                    @if ($errors->any())
                        <div class="error-messages" style="color: red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="form-container" class="form-container">
                        <!-- Basic Info Section -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="bx bx-info-circle"></i> Informasi Dasar
                            </h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required-label">Nama Stok</label>
                                    <input type="text" name="name[]" id="name" class="form-control"
                                        placeholder="Masukkan nama produk" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required-label">Tipe</label>
                                    <select name="type[]" id="type" class="form-control type" required>
                                        <option value="" disabled selected>Pilih tipe produk</option>
                                        <option value="Obat">Obat</option>
                                        <option value="Pupuk">Pupuk</option>
                                        <option value="Bibit">Bibit</option>
                                    </select>
                                </div>

                                <div class="form-group sub_type_container" id="sub_type_container" style="display: none">
                                    <label class="form-label required-label">Sub Tipe</label>
                                    <select name="sub_type[]" id="sub_type" class="form-control sub_type">
                                        <option value="" selected>Pilih sub tipe produk</option>
                                        <option value="Subsidi">Subsidi</option>
                                        <option value="Non-Subsidi">Non-Subsidi</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required-label">Ukuran</label>
                                    <select name="size[]" id="size" class="form-control size" required>
                                        <option value="" disabled selected>Pilih ukuran</option>
                                        <option value="Kecil">Kecil</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Besar">Besar</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Gambar Produk</label>
                                    <input type="file" name="image[]" class="form-control" accept="image/*">
                                    <div class="form-hint">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description[]" id="description" class="form-control" placeholder="Masukkan deskripsi produk"></textarea>
                            </div>
                        </div>

                        <!-- Inventory Section -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="bx bx-cabinet"></i> Informasi Inventaris
                            </h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required-label">Harga Beli</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="purchase_price[]" id="purchase_price"
                                            class="form-control" placeholder="0" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required-label">Harga Jual</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="selling_price[]" id="selling_price" class="form-control"
                                            placeholder="0" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required-label">Jumlah</label>
                                    <input type="number" name="quantity[]" id="quantity" class="form-control"
                                        placeholder="0" required>
                                    <div class="form-hint">Jumlah stok yang tersedia.</div>
                                </div>

                                <div class="form-group retail_price_container" style="display: none"
                                    id="retail_price_container">
                                    <label class="form-label">Harga Jual Eceran</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="retail_price[]" id="retail_price"
                                            class="form-control retail_price" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group retail_quantity_container" style="display: none"
                                    id="retail_quantity_container">
                                    <label class="form-label">Jumlah Eceran</label>
                                    <input type="number" name="retail_quantity[]" id="retail_quantity"
                                        class="form-control retail_quantity" placeholder="0">
                                    <div class="form-hint">Jumlah stok yang tersedia.</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required-label">Tanggal Kadaluwarsa</label>
                                    <input type="date" name="expiration_date[]" id="expiration_date"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cloned-container"></div>
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-success" id="clone-element">
                            <i class="bx bx-plus"></i> Tambahkan Item
                        </button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
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
    <div class="modal-backdrop" id="successModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-icon">
                    <i class="bx bx-check"></i>
                </div>
                <h3 class="modal-title">Stok Berhasil Ditambahkan!</h3>
                <p class="modal-message">Produk baru telah berhasil ditambahkan ke database.</p>

                <div class="modal-product" id="productListContainer">
                    <!-- Product items will be inserted here -->
                </div>

                <div class="modal-buttons">
                    <a href="{{ route('stocks.create') }}" class="btn btn-secondary">
                        <i class="bx bx-plus"></i> Tambah Produk Lain
                    </a>
                    <a href="{{ route('stocks.index') }}" class="btn btn-primary">
                        <i class="bx bx-list-ul"></i> Lihat Daftar Stok
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Handler untuk form submission
            $('#stockForm').on('submit', function(e) {
                // Mencegah pengiriman form secara default
                e.preventDefault();

                // Variabel untuk menghitung berapa form yang valid
                let validForms = 0;

                // Seleksi semua form container (asli dan yang dikloning)
                const formContainers = $('.form-container');

                // Loop melalui setiap form container
                formContainers.each(function(index) {
                    const container = $(this);

                    // Ambil input penting dari form
                    const name = container.find('input[name="name[]"]').val();
                    const type = container.find('select[name="type[]"]').val();
                    const quantity = container.find('input[name="quantity[]"]').val();

                    // Cek jika form kosong atau hanya diisi sebagian
                    if (!name && !type && !quantity) {
                        // Form kosong, hapus dari DOM agar tidak ikut terkirim
                        container.remove();
                    } else {
                        // Cek apakah form memiliki semua field yang diperlukan
                        if (name && type && container.find('input[name="purchase_price[]"]')
                            .val() &&
                            container.find('input[name="selling_price[]"]').val() &&
                            quantity && container.find('input[name="expiration_date[]"]').val()) {
                            // Form terisi lengkap
                            validForms++;
                        }
                    }
                });

                // Jika tidak ada form yang valid, tampilkan pesan error
                if (validForms === 0) {
                    alert('Mohon isi minimal satu form produk dengan lengkap');
                    return false;
                }

                // Jika sudah valid, kirim form
                this.submit();
            });

            // Handler untuk tipe produk
            $('.type').change(function() {
                const type = $(this).val();
                const container = $(this).closest('.form-container');
                let hidden_size = container.find('.hidden_size');
                const size = container.find('.size');
                const sub_type_container = container.find('.sub_type_container');
                const sub_type = container.find('.sub_type');
                const retail_price_container = container.find('.retail_price_container');
                const retail_quantity_container = container.find('.retail_quantity_container');
                const retail_price = container.find('.retail_price');
                const retail_quantity = container.find('.retail_quantity');

                if (type === 'Bibit') {
                    // Menambahkan input hidden jika belum ada
                    if (hidden_size.length === 0) {
                        size.after(
                            '<input class="hidden_size" type="hidden" id="hidden_size" name="size[]" value=""/>'
                        );
                        hidden_size = container.find('.hidden_size');
                    }
                    // Menambahkan opsi "Pack" jika belum ada
                    if (size.find('option[value="Pack"]').length === 0) {
                        size.append('<option value="Pack">Pack</option>');
                    }
                    // Set nilai #size menjadi 'Pack', disable dropdown #size dan simpan nilai di hidden field
                    size.val('Pack').prop('disabled', true);
                    hidden_size.val('Pack');
                    sub_type_container.hide(); // Sembunyikan #sub_type_container
                    sub_type.val('');
                    retail_price_container.hide();
                    retail_quantity_container.hide();
                    retail_price.val('')
                    retail_quantity.val('')
                } else if (type === 'Pupuk') {
                    // Menampilkan #sub_type_container saat 'Pupuk' dipilih
                    sub_type_container.show();
                    retail_price_container.hide();
                    retail_quantity_container.hide();
                    size.find('option[value="Pack"]').remove();
                    size.prop('disabled', false);
                    size.val(size.find('option:first').val());
                    hidden_size.remove();
                    retail_price.val('')
                    retail_quantity.val('')
                } else {
                    // Jika #type bukan 'Bibit' atau 'Pupuk', reset semua elemen
                    size.prop('disabled', false);
                    size.val(size.find('option:first').val());
                    hidden_size.remove();
                    size.find('option[value="Pack"]').remove(); // Hapus opsi "Pack"
                    sub_type_container.hide(); // Sembunyikan #sub_type_container
                    sub_type.val('');
                    retail_price_container.hide();
                    retail_quantity_container.hide();
                    retail_price.val('')
                    retail_quantity.val('')
                }
            });

            // Handler untuk sub tipe
            $('.sub_type').change(function() {
                const sub_type = $(this).val();
                const container = $(this).closest('.form-container');
                let hidden_size = container.find('.hidden_size');
                const size = container.find('.size');
                const sub_type_container = container.find('.sub_type_container');
                const retail_price_container = container.find('.retail_price_container');
                const retail_quantity_container = container.find('.retail_quantity_container');
                const retail_price = container.find('.retail_price');
                const retail_quantity = container.find('.retail_quantity');

                if (sub_type === 'Subsidi') {
                    // Menambahkan input hidden jika belum ada
                    if (hidden_size.length === 0) {
                        size.after(
                            '<input class="hidden_size" type="hidden" id="hidden_size" name="size[]" value=""/>'
                        );
                        hidden_size = container.find('.hidden_size');
                    }
                    if (size.find('option[value="50kg"]').length === 0) {
                        size.append('<option value="50kg">50kg</option>');
                    }
                    // Set nilai #size menjadi '50kg', disable dropdown #size dan simpan nilai di hidden field
                    size.val('50kg').prop('disabled', true);
                    hidden_size.val('50kg');
                    retail_price_container.hide();
                    retail_quantity_container.hide();
                    retail_price.val('')
                    retail_quantity.val('')
                } else {
                    // Menambahkan input hidden jika belum ada
                    if (hidden_size.length === 0) {
                        size.after(
                            '<input class="hidden_size" type="hidden" id="hidden_size" name="size[]" value=""/>'
                        );
                        hidden_size = container.find('.hidden_size');
                    }
                    if (size.find('option[value="50kg"]').length === 0) {
                        size.append('<option value="50kg">50kg</option>');
                    }
                    // Set nilai #size menjadi '50kg', disable dropdown #size dan simpan nilai di hidden field
                    size.val('50kg').prop('disabled', true);
                    hidden_size.val('50kg');
                    retail_price_container.show();
                    retail_quantity_container.show();
                    retail_price.val('')
                    retail_quantity.val('')
                }
            });

            // Handler untuk tombol tambah item baru
            $('#clone-element').click(() => {
                const cloned = $('#form-container').clone(true);

                // Reset nilai input pada form yang dikloning
                cloned.find('input[type="text"], input[type="number"], textarea').val('');
                cloned.find('select').each(function() {
                    $(this).val($(this).find('option:first').val());
                });
                cloned.find('input[type="date"]').val('');
                cloned.find('input[type="file"]').val('');

                // Tambahkan tombol hapus pada form yang dikloning
                const deleteButton = $(
                    '<button type="button" class="btn btn-danger remove-form"><i class="bx bx-trash"></i></button>'
                );

                // Handler untuk tombol hapus
                deleteButton.on('click', function() {
                    cloned.remove();
                });

                cloned.prepend(deleteButton);
                $('#cloned-container').append(cloned);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a success message in the session
            @if (session('success'))
                // Show the success modal with the stored product data
                @if (session('product_names'))
                    // If we have multiple products
                    const productNames = @json(session('product_names') ?? []);
                    const productTypes = @json(session('product_types') ?? []);
                    const productSizes = @json(session('product_sizes') ?? []);
                    const productQuantities = @json(session('product_quantities') ?? []);
                    const productPrices = @json(session('product_prices') ?? []);

                    showSuccessModalMultiple(productNames, productTypes, productSizes, productQuantities,
                        productPrices);
                @else
                    // Fallback to single product display for backward compatibility
                    showSuccessModal({
                        name: "{{ session('product_name') }}",
                        type: "{{ session('product_type') }}",
                        size: "{{ session('product_size') }}",
                        quantity: "{{ session('product_quantity') }}",
                        sellingPrice: "{{ session('product_selling_price') }}"
                    });
                @endif
            @endif
        });

        function showSuccessModalMultiple(names, types, sizes, quantities, prices) {
            // Get the container for the product list
            const productListContainer = document.getElementById('productListContainer');
            productListContainer.innerHTML = ''; // Clear any existing content
            // Add each product to the list
            for (let i = 0; i < names.length; i++) {
                const productItem = document.createElement('div');
                productItem.className = 'product-item';

                productItem.innerHTML = `
          <div class="modal-product-name">${names[i]}</div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Tipe:</span>
            <span style="font-weight: 500; color: #1e293b;">${types[i]}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Ukuran:</span>
            <span style="font-weight: 500; color: #1e293b;">${sizes[i]}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Jumlah:</span>
            <span style="font-weight: 500; color: #1e293b;">${quantities[i]} pcs</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Harga Jual:</span>
            <span style="font-weight: 500; color: #149d80;">Rp ${parseInt(prices[i]).toLocaleString('id-ID')}</span>
          </div>
        `;

                productListContainer.appendChild(productItem);
            }

            // Show the modal
            const modal = document.getElementById('successModal');
            modal.classList.add('show');

            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
        }

        function showSuccessModal(product) {
            // Set product details in the modal for backward compatibility
            const productListContainer = document.getElementById('productListContainer');
            productListContainer.innerHTML = `
        <div class="product-item">
          <div class="modal-product-name">${product.name}</div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Tipe:</span>
            <span style="font-weight: 500; color: #1e293b;">${product.type}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Ukuran:</span>
            <span style="font-weight: 500; color: #1e293b;">${product.size}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Jumlah:</span>
            <span style="font-weight: 500; color: #1e293b;">${product.quantity} pcs</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 8px;">
            <span style="color: #64748b;">Harga Jual:</span>
            <span style="font-weight: 500; color: #149d80;">Rp ${parseInt(product.sellingPrice).toLocaleString('id-ID')}</span>
          </div>
        </div>
      `;

            // Show the modal
            const modal = document.getElementById('successModal');
            modal.classList.add('show');

            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
        }

        // Menutup modal dengan tombol ESC atau klik di luar modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('successModal');
                if (modal.classList.contains('show')) {
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });

        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
@endsection
