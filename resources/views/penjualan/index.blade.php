@extends('layouts.app')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background-color: white;
            margin-bottom: 20px;
        }

        .filter-section {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .section-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.75rem;
        }

        .section-title i {
            color: #149d80;
            margin-right: 10px;
            font-size: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #149d80;
            box-shadow: 0 0 0 0.25rem rgba(0, 114, 79, 0.25);
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #149d80;
            border-color: #149d80;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #0c8b71;
            border-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 79, 0.15);
        }

        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
        }

        .btn-success:hover {
            background-color: #0d9668;
            border-color: #0d9668;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.15);
        }

        .table-container {
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 15px;
            border-top: none;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #334155;
            border-color: #f1f5f9;
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background-color: #f1f5f9;
            cursor: pointer;
        }

        .table-row.active {
            background-color: #e6f7f3;
        }

        .currency {
            text-align: right;
            font-weight: 500;
        }

        .currency:before {
            content: 'Rp ';
        }

        .chart-container {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            height: 300px;
        }

        .chart-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .chart-info span {
            font-weight: 600;
            color: #1e293b;
            margin-left: 5px;
        }

        .total-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            margin: 25px 0;
        }

        .total-box h3 {
            margin: 0;
            color: #149d80;
            font-weight: 700;
        }

        /* Improved styles for sale details */
        .details-content {
            padding: 15px;
            border-radius: 10px;
            background-color: #f8fafc;
            margin-bottom: 10px;
            border-left: 4px solid #149d80;
            transition: all 0.3s ease;
        }

        .details-content:hover {
            background-color: #e6f7f3;
            transform: translateX(5px);
        }

        .detail-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 8px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 500;
            color: #334155;
        }

        .stock-id {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 5px;
            display: inline-block;
            background-color: #e2e8f0;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Fade animation for details */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .detail-row {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <h1 class="section-title mb-4">
            <i class="bx bx-cart-alt"></i> Menu Penjualan
        </h1>

        <!-- Filter Section -->
        <div class="filter-section mb-5">
            <form action="" method="get">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Pilih Bulan</label>
                        <select id="bulan" name="bulan" class="form-select">
                            @php
                                Carbon\Carbon::setLocale('id');
                            @endphp

                            @foreach (range(1, 12) as $bulanSelect)
                                <option value="{{ $bulanSelect }}"
                                    {{ $bulanSelect == request('bulan', now()->month) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($bulanSelect)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Pilih Tahun</label>
                        <select id="tahun" name="tahun" class="form-select">
                            @foreach (range(now()->year - 5, now()->year + 5) as $tahunSelect)
                                <option value="{{ $tahunSelect }}"
                                    {{ $tahunSelect == request('tahun', now()->year) ? 'selected' : '' }}>
                                    {{ $tahunSelect }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="button" id="btn-tampilkan" class="btn btn-primary flex-grow-1">
                                <i class="bx bx-search me-1"></i> Tampilkan Data
                            </button>
                            <a id="btn-cetak" href="#" class="btn btn-success flex-grow-1" target="_blank">
                                <i class="bx bx-printer me-1"></i> Cetak Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- Riwayat Penjualan -->
            <div class="col-lg-6">
                <h2 class="section-title">
                    <i class="bx bx-history"></i> Riwayat Penjualan
                </h2>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Penjualan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($penjualans as $penjualan)
                                    <tr class="table-row" data-penjualan-id="{{ $penjualan->id }}">
                                        <td><span class="badge bg-light text-dark">{{ $penjualan->id_penjualan }}</span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($penjualan->created_at)->format('d-m-Y') }}
                                            <small
                                                class="text-muted d-block">{{ \Carbon\Carbon::parse($penjualan->created_at)->format('H:i') }}</small>
                                        </td>
                                        <td class="currency text-start">
                                            {{ number_format($penjualan->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm p-0 toggle-details"
                                                data-penjualan-id="{{ $penjualan->id }}">
                                                <i class="bx bx-chevron-down fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Hidden details container -->
                                    <tr class="details-row" id="details-row-{{ $penjualan->id }}" style="display: none;">
                                        <td colspan="4" class="p-0">
                                            <div class="p-3 fade-in">
                                                @foreach ($penjualan->items as $item)
                                                    <div class="details-content">
                                                        <div class="detail-row">
                                                            <div class="detail-item">
                                                                <span class="detail-label">Nama Barang</span>
                                                                <span class="detail-value">
                                                                    @if ($item->product && $item->product->masterStock)
                                                                        {{ $item->product->masterStock->name }}
                                                                    @else
                                                                        Barang Terhapus
                                                                    @endif
                                                                </span>
                                                                @if ($item->product)
                                                                    <span
                                                                        class="stock-id">{{ $item->product->stock_id }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Ukuran</span>
                                                                <span class="detail-value">
                                                                    @if ($item->product)
                                                                        {{ $item->product->size }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Harga Jual</span>
                                                                <span class="detail-value">Rp
                                                                    {{ number_format($item->price, 0, ',', '.') }}</span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Jumlah</span>
                                                                <span class="detail-value">{{ $item->quantity }} pcs</span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Total</span>
                                                                <span class="detail-value fw-bold">Rp
                                                                    {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>

                                    @php
                                        $total += $penjualan->total_price;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Penjualan -->
            <div class="col-lg-6">
                <h2 class="section-title">
                    <i class="bx bx-line-chart"></i> Grafik Penjualan
                </h2>

                <div class="chart-container">
                    <canvas id="grafikPenjualan"></canvas>
                </div>

                <div class="chart-info">
                    <div>Bulan: <span>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</span></div>
                    <div>Tahun: <span>{{ $tahun }}</span></div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="total-box">
            <h3>Total Penjualan: Rp {{ number_format($total, 0, ',', '.') }}</h3>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Data dari controller
        var totalPerHari = @json($totalPerHari);

        // Debugging - memeriksa data input
        console.log("Data Penjualan:", totalPerHari);

        // Menyiapkan data untuk chart
        var labels = Object.keys(totalPerHari);
        var data = Object.values(totalPerHari);

        // Debugging - memeriksa data terproses
        console.log("Labels:", labels);
        console.log("Data:", data);

        $(document).ready(function() {
            // Mengubah URL tombol saat dropdown bulan atau tahun berubah
            function updateLinks() {
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();
                var baseURL = "{{ url('penjualan') }}";

                // Update link untuk tombol Tampilkan Data
                $('#btn-tampilkan').on('click', function() {
                    window.location.href = baseURL + '/?bulan=' + bulan + '&tahun=' + tahun;
                });

                // Update link untuk tombol Cetak Laporan
                $('#btn-cetak').attr('href', baseURL + '/laporan/' + bulan + '/' + tahun);
            }

            // Panggil fungsi untuk memperbarui link ketika dropdown berubah
            $('#bulan, #tahun').on('change', function() {
                updateLinks();
            });

            // Memanggil updateLinks pada awal halaman dimuat
            updateLinks();

            // Toggle detail rows and highlight active row
            $('.toggle-details, .table-row').on('click', function(e) {
                e.stopPropagation();
                const penjualanId = $(this).hasClass('table-row') ?
                    $(this).data('penjualan-id') :
                    $(this).data('penjualan-id');

                const detailsRow = $('#details-row-' + penjualanId);
                const tableRow = $(`.table-row[data-penjualan-id="${penjualanId}"]`);
                const chevron = tableRow.find('.toggle-details i');

                // Toggle visibility of the details row
                detailsRow.toggle();

                // Toggle active class on the table row
                tableRow.toggleClass('active');

                // Change chevron direction
                if (detailsRow.is(':visible')) {
                    chevron.removeClass('bx-chevron-down').addClass('bx-chevron-up');
                } else {
                    chevron.removeClass('bx-chevron-up').addClass('bx-chevron-down');
                }
            });

            // Membuat chart
            var ctx = document.getElementById('grafikPenjualan').getContext('2d');

            // Pastikan data ada dan valid sebelum membuat chart
            if (labels.length > 0 && data.length > 0) {
                var grafikPenjualan = new Chart(ctx, {
                    type: 'line', // Mengubah tipe chart menjadi line
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Penjualan',
                            data: data,
                            backgroundColor: 'rgba(0, 114, 79, 0.1)', // Area di bawah line
                            borderColor: '#149d80', // Warna garis
                            borderWidth: 3,
                            pointBackgroundColor: '#149d80',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            tension: 0.3, // Memberikan kurva halus pada line
                            fill: true // Mengisi area di bawah line
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'white',
                                titleColor: '#1e293b',
                                bodyColor: '#475569',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    },
                                    color: '#94a3b8'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    maxRotation: 0,
                                    minRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 10
                                }
                            }
                        }
                    }
                });
            } else {
                // Tampilkan pesan jika tidak ada data
                document.getElementById('grafikPenjualan').parentNode.innerHTML =
                    '<div class="d-flex align-items-center justify-content-center h-100">' +
                    '<p class="text-muted">Tidak ada data penjualan untuk ditampilkan</p>' +
                    '</div>';
            }
        });
    </script>
@endsection
