<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Toko Pertanian Joyo Langgeng Sejahtera</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #149d80;
            --primary-light: #E8F5F0;
            --primary-dark: #0c8b71;
            --text-dark: #1e293b;
            --text-medium: #475569;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --background-light: #f8fafc;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background-color: var(--white);
            font-size: 12px;
        }

        .container {
            width: 100%;
            max-width: 180mm;
            /* A bit narrower than A4 width to ensure margins */
            margin: 0 auto;
            padding: 15mm 0;
            /* Top and bottom padding only */
        }

        .header {
            text-align: center;
            margin-bottom: 7mm;
            padding-bottom: 3mm;
            border-bottom: 1px solid var(--border-color);
        }

        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
            margin-top: 0;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-medium);
            margin-top: 2px;
            margin-bottom: 3px;
        }

        .report-subtitle {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-light);
            margin-top: 0;
            margin-bottom: 0;
        }

        .table-container {
            margin-bottom: 8mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 10px;
        }

        table th,
        table td {
            padding: 5px 6px;
            text-align: left;
            border: 1px solid var(--border-color);
        }

        table th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        table tr:nth-child(even) {
            background-color: var(--primary-light);
        }

        .currency {
            text-align: right;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            font-weight: 500;
            color: var(--primary);
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-top: 6mm;
            margin-bottom: 4mm;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 2mm;
        }

        .summary-box {
            border: 1px solid var(--border-color);
            margin-bottom: 3mm;
            padding: 3mm;
            background-color: var(--background-light);
        }

        .summary-title {
            font-size: 10px;
            margin-bottom: 2px;
            color: var(--text-light);
            font-weight: 500;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0;
        }

        .profit {
            color: #10b981;
        }

        .print-footer {
            margin-top: 10mm;
            text-align: right;
            color: var(--text-light);
            font-size: 9px;
        }

        .deleted-item {
            color: var(--danger);
            font-style: italic;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm 15mm;
                /* Standard print margins for A4 */
            }

            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                height: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            table th {
                background-color: var(--primary) !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table tr:nth-child(even) {
                background-color: var(--primary-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-box {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="company-name">TOKO PERTANIAN JOYO LANGGENG SEJAHTERA</h1>
            <h2 class="report-title">Laporan Penjualan</h2>
            <p class="report-subtitle">Bulan
                {{ Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y') }}</p>
        </div>

        <!-- Tabel Rekap Penjualan Barang Bulanan -->
        <h3 class="section-title">Penjualan Barang Bulanan</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="35%">NAMA BARANG</th>
                        <th width="15%">UKURAN</th>
                        <th width="20%">JUMLAH TERJUAL (PCS)</th>
                        <th width="15%">TOTAL HARGA</th>
                        <th width="15%">TOTAL LABA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Proses untuk mengumpulkan data rekap
                        $rekap = [];
                        $rekapTotal = 0;
                        $rekapTotalLaba = 0;
                        $rekapTotalQuantity = 0;

                        foreach ($items as $item) {
                            $name = $item['nama_barang'];
                            $size = $item['ukuran'];
                            $key = $name . '_' . $size;

                            if (!isset($rekap[$key])) {
                                $rekap[$key] = [
                                    'name' => $name,
                                    'size' => $size,
                                    'quantity' => 0,
                                    'total_harga' => 0,
                                    'total_laba' => 0,
                                ];
                            }

                            $rekap[$key]['quantity'] += $item['jumlah'];
                            $rekap[$key]['total_harga'] += $item['total_harga'];
                            $rekap[$key]['total_laba'] += $item['laba_total'];

                            $rekapTotal += $item['total_harga'];
                            $rekapTotalLaba += $item['laba_total'];
                            $rekapTotalQuantity += $item['jumlah'];
                        }

                        // Sort by name
                        usort($rekap, function ($a, $b) {
                            return strcmp($a['name'], $b['name']);
                        });
                    @endphp

                    @foreach ($rekap as $item)
                        <tr>
                            <td class="{{ $item['name'] === 'Barang Terhapus' ? 'deleted-item' : '' }}">
                                {{ $item['name'] }}
                            </td>
                            <td>{{ $item['size'] ?? '-' }}</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_laba'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $rekapTotalQuantity }}</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($rekapTotal, 0, ',', '.') }}</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($rekapTotalLaba, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel Laporan Penjualan Detail -->
        <h3 class="section-title">Detail Penjualan</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="15%">ID PENJUALAN</th>
                        <th width="10%">TANGGAL PENJUALAN</th>
                        <th width="20%">NAMA BARANG (SKU)</th>
                        <th width="8%">UKURAN</th>
                        <th width="8%">JUMLAH TERJUAL (PCS)</th>
                        <th width="15%">TOTAL HARGA</th>
                        <th width="15%">LABA PER BARANG</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $totalLaba = 0;
                    @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td><span class="badge">{{ $item['id_penjualan'] }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                            <td>
                                <span class="{{ $item['nama_barang'] === 'Barang Terhapus' ? 'deleted-item' : '' }}">
                                    {{ $item['nama_barang'] }}
                                </span>
                                <div style="font-size: 9px; color: #64748b;">{{ $item['sku'] }}</div>
                            </td>
                            <td>{{ $item['ukuran'] }}</td>
                            <td class="text-center">{{ $item['jumlah'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item['laba_total'], 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $total += $item['total_harga'];
                            $totalLaba += $item['laba_total'];
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalLaba, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3 class="section-title">Ringkasan Penjualan</h3>

        <div class="summary-box">
            <div class="summary-title">Total Penjualan Bulanan</div>
            <div class="summary-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-title">Total Laba</div>
            <div class="summary-value profit">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-title">Produk Terlaris</div>
            <div class="summary-value {{ $produkTerlaris === 'Barang Terhapus' ? 'deleted-item' : '' }}">
                {{ $produkTerlaris }}
            </div>
        </div>

        <h3 class="section-title">Penjualan per Kategori Produk</h3>

        @if (empty($penjualanPerKategori))
            <div class="summary-box">
                <div class="summary-title">Tidak ada data kategori</div>
                <div class="summary-value">0 pcs (Rp 0)</div>
            </div>
        @else
            @foreach ($penjualanPerKategori as $kategori => $data)
                <div class="summary-box">
                    <div class="summary-title">{{ $kategori ?? 'Tidak Tersedia' }}</div>
                    <div class="summary-value">{{ $data['jumlah'] }} pcs (Rp
                        {{ number_format($data['total'], 0, ',', '.') }})</div>
                </div>
            @endforeach
        @endif

        <div class="print-footer">
            <p>Laporan dicetak pada: {{ Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }}</p>
        </div>
    </div>
</body>

</html>
