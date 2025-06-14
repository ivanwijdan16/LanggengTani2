<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian - Toko Pertanian Joyo Langgeng Sejahtera</title>
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
            margin: 0 auto;
            padding: 15mm 0;
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

        .warning {
            background-color: var(--danger-light);
            border-color: var(--danger);
        }

        .warning .summary-value {
            color: var(--danger);
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

        .deleted-item-label {
            color: var(--danger);
            font-size: 9px;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm 15mm;
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

            .warning {
                background-color: var(--danger-light) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="company-name">TOKO PERTANIAN JOYO LANGGENG SEJAHTERA</h1>
            <h2 class="report-title">Laporan Pembelian</h2>
            <p class="report-subtitle">Bulan
                {{ Carbon\Carbon::parse($pembelians->first()->purchase_date ?? now())->locale('id')->translatedFormat('F Y') }}
            </p>
        </div>

        <!-- Tabel Rekap Pembelian Barang Bulanan -->
        <h3 class="section-title">Pembelian Barang Bulanan</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="35%">NAMA BARANG</th>
                        <th width="15%">UKURAN</th>
                        <th width="20%">JUMLAH BELI (PCS)</th>
                        <th width="15%">HARGA BELI PER PCS</th>
                        <th width="15%">TOTAL HARGA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Proses untuk mengumpulkan data rekap
                        $rekap = [];
                        $rekapTotal = 0;
                        $rekapTotalQuantity = 0;

                        foreach ($pembelians as $pembelian) {
                            if ($pembelian->stock && $pembelian->stock->masterStock) {
                                $name = $pembelian->stock->masterStock->name;
                                $size = $pembelian->stock->size;
                                $key = $name . '_' . $size;

                                if (!isset($rekap[$key])) {
                                    $rekap[$key] = [
                                        'name' => $name,
                                        'size' => $size,
                                        'quantity' => 0,
                                        'purchase_price' => $pembelian->purchase_price,
                                        'total' => 0,
                                    ];
                                }

                                $rekap[$key]['quantity'] += $pembelian->quantity;
                                $rekap[$key]['total'] += $pembelian->purchase_price * $pembelian->quantity;

                                $rekapTotal += $pembelian->purchase_price * $pembelian->quantity;
                                $rekapTotalQuantity += $pembelian->quantity;
                            }
                        }

                        // Sort by name
                        usort($rekap, function ($a, $b) {
                            return strcmp($a['name'], $b['name']);
                        });
                    @endphp

                    @foreach ($rekap as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['size'] ?? '-' }}</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['purchase_price'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $rekapTotalQuantity }}</strong></td>
                        <td></td>
                        <td class="text-right"><strong>Rp {{ number_format($rekapTotal, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel Laporan Pembelian Detail -->
        <h3 class="section-title">Detail Pembelian</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="15%">ID PEMBELIAN</th>
                        <th width="10%">TANGGAL PEMBELIAN</th>
                        <th width="25%">NAMA BARANG (SKU)</th>
                        <th width="8%">UKURAN</th>
                        <th width="8%">JUMLAH BELI (PCS)</th>
                        <th width="17%">HARGA BELI PER PCS</th>
                        <th width="17%">TOTAL HARGA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $totalItemsByCategory = [];
                    @endphp
                    @foreach ($pembelians as $pembelian)
                        <tr>
                            <td><span class="badge">{{ $pembelian->purchase_code }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($pembelian->purchase_date)->format('d/m/Y') }}</td>
                            <td>
                                @if ($pembelian->stock && $pembelian->stock->masterStock)
                                    {{ $pembelian->stock->masterStock->name }}
                                    <div style="font-size: 9px; color: #64748b;">
                                        {{ $pembelian->stock->masterStock->sku }}</div>
                                @elseif ($pembelian->stock)
                                    <span class="deleted-item">Barang Terhapus</span>
                                    <div class="deleted-item-label">
                                        {{ $pembelian->stock->stock_id }}</div>
                                @else
                                    <span class="deleted-item">Barang Tidak Tersedia</span>
                                @endif
                            </td>
                            <td>{{ $pembelian->stock->size ?? '-' }}</td>
                            <td class="text-center">{{ $pembelian->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($pembelian->purchase_price, 0, ',', '.') }}</td>
                            <td class="text-right">Rp
                                {{ number_format($pembelian->purchase_price * $pembelian->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @php
                            $total += $pembelian->purchase_price * $pembelian->quantity;

                            // Count items by category
                            if ($pembelian->stock && $pembelian->stock->masterStock) {
                                $category = $pembelian->stock->masterStock->type;
                                if (!isset($totalItemsByCategory[$category])) {
                                    $totalItemsByCategory[$category] = 0;
                                }
                                $totalItemsByCategory[$category] += $pembelian->quantity;
                            }
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6" style="text-align: right;"><strong>Total</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3 class="section-title">Ringkasan Pembelian</h3>

        <div class="summary-box">
            <div class="summary-title">Total Pembelian Bulanan </div>
            <div class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
        </div>

        @foreach ($totalItemsByCategory as $category => $count)
            <div class="summary-box">
                <div class="summary-title">Jumlah Total {{ $category }} Masuk</div>
                <div class="summary-value">{{ $count }} pcs</div>
            </div>
        @endforeach

        @php
            // Total loss sudah dikirm langsung dari controller
            $totalLoss = $totalKerugian ?? 0;
        @endphp

        <div class="summary-box warning">
            <div class="summary-title">Kerugian Karena Barang Kadaluwarsa</div>
            <div class="summary-value">Rp {{ number_format($totalLoss, 0, ',', '.') }}</div>
        </div>

        <div class="print-footer">
            <p>Laporan dicetak pada: {{ Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }}</p>
        </div>
    </div>
</body>

</html>
