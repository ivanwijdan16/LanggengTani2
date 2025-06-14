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

        .table-dark th {
            background-color: #1e293b;
        }

        .kerugian-section {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 30px;
        }

        /* Improved styles for purchase details */
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

        .deleted-item {
            color: #ef4444;
            font-style: italic;
        }

        .deleted-item .stock-id {
            background-color: #fee2e2;
            color: #b91c1c;
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

        .category-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .category-item {
            flex: 1 1 200px;
            background-color: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .category-title {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 5px;
        }

        .category-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #149d80;
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
            <i class="bx bx-store"></i> Menu Pembelian
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
            <!-- Riwayat Pembelian -->
            <div class="col-lg-6">
                <h2 class="section-title">
                    <i class="bx bx-history"></i> Riwayat Pembelian
                </h2>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($pembelians as $pembelian)
                                    <tr class="table-row" data-pembelian-id="{{ $pembelian->id }}">
                                        <td><span class="badge bg-light text-dark">{{ $pembelian->purchase_code }}</span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pembelian->date)->format('d-m-Y') }}
                                            <small
                                                class="text-muted d-block">{{ \Carbon\Carbon::parse($pembelian->created_at)->format('H:i') }}</small>
                                        </td>
                                        <td class="currency text-start">
                                            {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm p-0 toggle-details"
                                                data-pembelian-id="{{ $pembelian->id }}">
                                                <i class="bx bx-chevron-down fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Hidden details container -->
                                    <!-- Hidden details container -->
                                    <tr class="details-row" id="details-row-{{ $pembelian->id }}" style="display: none;">
                                        <td colspan="4" class="p-0">
                                            <div class="p-3 fade-in">
                                                @foreach ($pembelian->pembelians as $item)
                                                    <div class="details-content">
                                                        <div class="detail-row">
                                                            <div class="detail-item">
                                                                <span class="detail-label">Nama Barang</span>
                                                                <span
                                                                    class="detail-value {{ $item->stock && !$item->stock->masterStock ? 'deleted-item' : '' }}">
                                                                    @if ($item->stock && $item->stock->masterStock)
                                                                        {{ $item->stock->masterStock->name }}
                                                                    @elseif ($item->stock)
                                                                        Barang Terhapus
                                                                    @else
                                                                        Barang Tidak Ditemukan
                                                                    @endif
                                                                </span>
                                                                @if ($item->stock)
                                                                    <span
                                                                        class="stock-id {{ $item->stock->masterStock ? '' : 'deleted-item' }}">
                                                                        {{ $item->stock->stock_id }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Ukuran</span>
                                                                <span class="detail-value">
                                                                    @if ($item->stock)
                                                                        {{ $item->stock->size }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Harga Beli</span>
                                                                <span class="detail-value">Rp
                                                                    {{ number_format($item->purchase_price, 0, ',', '.') }}</span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Jumlah</span>
                                                                <span class="detail-value">{{ $item->quantity }} pcs</span>
                                                            </div>

                                                            <div class="detail-item">
                                                                <span class="detail-label">Total</span>
                                                                <span class="detail-value fw-bold">Rp
                                                                    {{ number_format($item->purchase_price * $item->quantity, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>

                                    @php
                                        $total += $pembelian->total;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Pembelian -->
            <div class="col-lg-6">
                <h2 class="section-title">
                    <i class="bx bx-line-chart"></i> Grafik Pembelian
                </h2>

                <div class="chart-container">
                    <canvas id="grafikPembelian"></canvas>
                </div>

                <div class="chart-info">
                    <div>Bulan: <span>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</span></div>
                    <div>Tahun: <span>{{ $tahun }}</span></div>
                </div>
            </div>
        </div>

        <!-- Total Pembelian -->
        <div class="total-box">
            <h3>Total Pembelian: Rp {{ number_format($total, 0, ',', '.') }}</h3>
        </div>

        <!-- Kerugian Section -->
        <div class="kerugian-section">
            <h2 class="section-title text-center mb-4">
                <i class="bx bx-error-circle text-danger"></i> Barang Kadaluwarsa dan Kerugian Harga
            </h2>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th>Stock ID</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok Kadaluwarsa</th>
                                <th>Harga Beli (Per pcs)</th>
                                <th>Kerugian (Total)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalKerugian = 0;
                            @endphp
                            @foreach ($kerugian as $item)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ $item['stock_id'] }}</span></td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['stok_kadaluarsa'] }} pcs</td>
                                    <td class="currency text-start">{{ number_format($item['harga_beli'], 0, ',', '.') }}
                                    </td>
                                    <td class="currency text-start">{{ number_format($item['kerugian'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @php
                                    $totalKerugian += $item['kerugian'];
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="total-box" style="border-color: #fee2e2; background-color: #fef2f2;">
            <h3 style="color: #ef4444;">Total Kerugian: Rp {{ number_format($totalKerugian, 0, ',', '.') }}</h3>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Data dari controller
        var totalPerHari = @json($totalPerHari);

        // Debugging - memeriksa data input
        console.log("Data Pembelian:", totalPerHari);

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
                var baseURL = "{{ url('pembelian') }}";

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
                const pembelianId = $(this).hasClass('table-row') ?
                    $(this).data('pembelian-id') :
                    $(this).data('pembelian-id');

                const detailsRow = $('#details-row-' + pembelianId);
                const tableRow = $(`.table-row[data-pembelian-id="${pembelianId}"]`);
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
            var ctx = document.getElementById('grafikPembelian').getContext('2d');

            // Pastikan data ada dan valid sebelum membuat chart
            if (labels.length > 0 && data.length > 0) {
                var grafikPembelian = new Chart(ctx, {
                    type: 'line', // Mengubah tipe chart menjadi line
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Pembelian',
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
                document.getElementById('grafikPembelian').parentNode.innerHTML =
                    '<div class="d-flex align-items-center justify-content-center h-100">' +
                    '<p class="text-muted">Tidak ada data pembelian untuk ditampilkan</p>' +
                    '</div>';
            }
        });
    </script>
@endsection
