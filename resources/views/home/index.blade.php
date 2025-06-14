@extends('layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
    <style>
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
            background-color: white;
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .icon-wrapper i {
            font-size: 32px;
        }

        .box-icon-bg {
            background-color: #e8f5f0;
            color: #149d80;
        }

        .store-icon-bg {
            background-color: #e8f5f0;
            color: #149d80;
        }

        .cart-icon-bg {
            background-color: #e8f5f0;
            color: #149d80;
        }

        .profit-icon-bg {
            background-color: #e8f5f0;
            color: #149d80;
        }

        .stat-details {
            flex: 1;
        }

        .stat-title {
            color: #566a75;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #32325d;
            margin-bottom: 0;
        }

        .filter-container {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .chart-card {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .chart-card .card-header {
            background-color: white;
            color: #566a75;
            font-weight: 600;
            border: none;
            padding: 15px 20px;
            font-size: 1.1rem;
        }

        .btn-tampilkan {
            background: #149d80 !important;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-tampilkan:hover {
            background: #0c8b71 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 79, 0.4);
        }

        .form-select,
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            box-shadow: none;
        }


        .card-header {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.75rem;
        }

        .card-header i {
            color: #149d80;
            margin-right: 10px;
            font-size: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-1">

        <!-- Filter Form -->
        <div class="filter-container">
            <form action="" method="get">
                <div class="row align-items-end">
                    <div class="col-md-5 col-sm-6 mb-3 mb-md-0">
                        <label for="bulan" class="form-label">Bulan</label>
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

                    <div class="col-md-5 col-sm-6 mb-3 mb-md-0">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" name="tahun" class="form-select">
                            @foreach (range(now()->year - 5, now()->year + 5) as $tahunSelect)
                                <option value="{{ $tahunSelect }}"
                                    {{ $tahunSelect == request('tahun', now()->year) ? 'selected' : '' }}>
                                    {{ $tahunSelect }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <button type="submit" class="btn btn-tampilkan text-white w-100 py-2">
                            <i class="bx bx-filter-alt me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-wrapper box-icon-bg">
                        <i class="bx bx-box"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-title">Total Stok Barang</div>
                        <div class="stat-value">{{ number_format($totalStock) }} Barang</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-wrapper store-icon-bg">
                        <i class="bx bx-store"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-title">Total Pembelian</div>
                        <div class="stat-value">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-wrapper cart-icon-bg">
                        <i class="bx bx-cart-alt"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-title">Omset Penjualan</div>
                        <div class="stat-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-wrapper profit-icon-bg">
                        <i class="bx bx-dollar-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-title">Keuntungan</div>
                        <div class="stat-value">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="row mt-1">
            <div class="col-12">
                <div class="chart-card">
                    <div class="card-header">
                        <i class="bx bx-bar-chart me-2"></i> Grafik Penjualan Harian
                    </div>
                    <div class="card-body p-4">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesData = @json($dailySales);

        var labels = salesData.map(function(item) {
            return item.date;
        });

        var data = salesData.map(function(item) {
            return item.total_sales;
        });

        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset Penjualan Harian',
                    data: data,
                    backgroundColor: '#149d80',
                    borderColor: '#149d80',
                    borderWidth: 0,
                    borderRadius: 5,
                    barThickness: 40,
                    maxBarThickness: 60,
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
                        backgroundColor: '#fff',
                        titleColor: '#32325d',
                        bodyColor: '#525f7f',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        padding: 12,
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
                                return 'Rp. ' + value.toLocaleString('id-ID');
                            },
                            font: {
                                size: 11
                            },
                            color: '#888'
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#888'
                        },
                        border: {
                            display: false
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 20,
                        bottom: 10
                    }
                }
            }
        });
    </script>
@endsection
