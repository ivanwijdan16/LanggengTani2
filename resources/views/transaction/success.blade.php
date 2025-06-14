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
            --border-color: #e2e8f0;
            --background-light: #f8fafc;
            --white: #ffffff;
        }

        .success-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .success-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .success-icon {
            background-color: var(--primary-light);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .success-subtitle {
            font-size: 1rem;
            color: var(--text-medium);
            margin-bottom: 0;
        }

        .item-name {
            font-size: 10pt;
        }

        .item-size {
            font-size: 8pt;
            display: block;
        }

        .receipt-card {
            background-color: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .receipt-header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 1.5rem;
            text-align: center;
        }

        .receipt-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        .receipt-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .receipt-body {
            padding: 1.5rem;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .receipt-table th {
            background-color: var(--background-light);
            color: var(--text-medium);
            font-weight: 600;
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-color);
        }

        .receipt-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .receipt-table tr:last-child td {
            border-bottom: none;
        }

        .receipt-table .item-name {
            font-weight: 500;
        }

        .receipt-table .item-price,
        .receipt-table .item-subtotal {
            text-align: right;
        }

        .receipt-table .item-qty {
            text-align: center;
        }

        .receipt-summary {
            background-color: var(--background-light);
            padding: 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: var(--text-medium);
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-row.total {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            border-top: 1px dashed var(--border-color);
            padding-top: 0.75rem;
            margin-top: 0.5rem;
        }

        .summary-row.change {
            font-weight: 600;
            color: var(--primary-color);
        }

        .summary-label {
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            border: none;
        }

        .btn i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 114, 79, 0.2);
        }

        .btn-secondary {
            background-color: var(--background-light);
            color: var(--text-medium);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--white);
            color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .store-info {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-light);
        }

        /* Styles for printing */
        @media print {
            body {
                background-color: white;
                font-family: 'Courier New', monospace;
                font-size: 12pt;
                color: black;
                width: 80mm;
                /* Standard thermal receipt width */
                margin: 0 auto;
            }

            .action-buttons,
            .print-hide,
            .success-header {
                display: none !important;
            }

            .success-container {
                margin: 0 auto;
                padding: 0;
                max-width: 100%;
            }

            .receipt-card {
                box-shadow: none;
                border: none;
                width: 100%;
            }

            .receipt-header {
                color: black;
                background-color: white;
                border-bottom: 1px dashed #000;
                text-align: center;
                padding: 0.5rem 0;
            }

            .receipt-title {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 0.3rem;
            }

            .receipt-details {
                justify-content: center;
                flex-direction: column;
                font-size: 0.8rem;
            }

            .receipt-details span {
                display: block;
                margin: 0.1rem 0;
            }

            .receipt-body {
                padding: 0.5rem 0;
            }

            .receipt-table {
                font-size: 0.9rem;
                border-collapse: collapse;
            }

            .receipt-table th {
                background-color: white;
                border-bottom: 1px dashed #000;
                padding: 0.3rem;
                text-align: left;
                font-size: 0.8rem;
            }

            .receipt-table td {
                border-bottom: none;
                padding: 0.2rem 0.3rem;
                font-size: 0.8rem;
            }

            .receipt-table .item-name {
                width: 50%;
            }

            .receipt-table .item-qty {
                width: 10%;
                text-align: center;
            }

            .receipt-table .item-price,
            .receipt-table .item-subtotal {
                width: 20%;
                text-align: right;
            }

            .receipt-summary {
                background-color: white;
                border: none;
                border-top: 1px dashed #000;
                padding: 0.5rem 0;
                margin-bottom: 0.5rem;
            }

            .summary-row {
                margin-bottom: 0.2rem;
                font-size: 0.8rem;
            }

            .summary-row.total {
                font-size: 0.9rem;
                border-top: 1px dashed #000;
            }

            .store-info {
                text-align: center;
                border-top: 1px dashed #000;
                padding-top: 0.5rem;
                font-size: 0.8rem;
            }

            .store-info p {
                margin: 0.2rem 0;
            }

            /* Add a tear-off line at the bottom */
            .store-info:after {
                content: "--------------------------------";
                display: block;
                text-align: center;
                padding-top: 0.5rem;
            }
        }

        /* Responsive Styling */
        @media (max-width: 576px) {
            .receipt-header {
                padding: 1.25rem 1rem;
            }

            .receipt-body {
                padding: 1rem;
            }

            .receipt-table th,
            .receipt-table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="success-container">
        <div class="success-header print-hide">
            <div class="success-icon">
                <i class="bx bx-check"></i>
            </div>
            <h1 class="success-title">Transaksi Berhasil!</h1>
            <p class="success-subtitle">Pembayaran telah berhasil diproses</p>
        </div>

        @php
            // Group items by master stock name and size
            $groupedItems = [];
            foreach ($transaction->items as $item) {
                $key = '';
                if ($item->product && $item->product->masterStock) {
                    $key = $item->product->masterStock->name . ' (' . $item->product->size . ')';
                } elseif ($item->product) {
                    $key = $item->product->stock_id . ' (' . $item->product->size . ')';
                } else {
                    $key = 'Barang';
                }

                if (!isset($groupedItems[$key])) {
                    $groupedItems[$key] = [
                        'quantity' => 0,
                        'price' => $item->price,
                        'subtotal' => 0,
                    ];
                }

                $groupedItems[$key]['quantity'] += $item->quantity;
                $groupedItems[$key]['subtotal'] += $item->subtotal;
            }
        @endphp

        <div class="receipt-card" id="receipt-container">
            <div class="receipt-header">
                <h2 class="receipt-title mb-4 mt-3">Toko Pertanian Joyo Langgeng Sejahtera</h2>
                <div class="receipt-details">
                    <span>ID: {{ $transaction->id_penjualan }}</span>
                    <span>Tanggal:
                        {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }}</span>
                </div>
            </div>

            <div class="receipt-body">
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th style="width: 40%">Nama Barang</th>
                            <th class="item-qty" style="width: 15%">Qty</th>
                            <th class="item-price" style="width: 20%">Harga</th>
                            <th class="item-subtotal" style="width: 25%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedItems as $key => $item)
                            <tr>
                                <td class="item-name">
                                    <strong>{{ $key }}</strong>
                                </td>
                                <td class="item-qty">{{ $item['quantity'] }}</td>
                                <td class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="item-subtotal">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="receipt-summary">
                    <div class="summary-row">
                        <span class="summary-label">Total Harga</span>
                        <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Total Bayar</span>
                        <span>Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row change">
                        <span class="summary-label">Kembalian</span>
                        <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="store-info">
                    <p>Terima kasih telah berbelanja di Toko Pertanian Joyo Langgeng Sejahtera</p>
                    <p>Mojorejo, Jetis, Mojokerto</p>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <button class="btn btn-primary" id="print-btn" onclick="printReceipt()">
                <i class="bx bx-printer"></i> Cetak Struk
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function printReceipt() {
            // Use a more traditional approach for thermal receipt style
            var printWindow = window.open('', '', 'height=600,width=300');

            // Write the receipt content with thermal receipt styling
            printWindow.document.write('<html><head><title>Struk Pembayaran</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
      body {
        font-family: 'Courier New', monospace;
        width: 80mm;
        margin: 0 auto;
        padding: 5mm;
        font-size: 12pt;
        line-height: 1.2;
      }

      .receipt-header {
        text-align: center;
        border-bottom: 1px dashed #000;
        padding-bottom: 10px;
        margin-bottom: 10px;
      }

      .receipt-title {
        font-size: 14pt;
        font-weight: bold;
        margin-bottom: 5px;
      }

      .receipt-details {
        font-size: 10pt;
        margin-bottom: 5px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
      }

      th, td {
        text-align: left;
        padding: 3px 0;
        font-size: 10pt;
      }

      th {
        border-bottom: 1px dashed #000;
      }

      .right {
        text-align: right;
      }

      .center {
        text-align: center;
      }

      .summary {
        border-top: 1px dashed #000;
        margin-top: 10px;
        padding-top: 10px;
      }

      .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 10pt;
      }

      .total {
        font-weight: bold;
        font-size: 12pt;
      }

      .store-info {
        text-align: center;
        margin-top: 15px;
        border-top: 1px dashed #000;
        padding-top: 10px;
        font-size: 10pt;
      }

      .thanks {
        text-align: center;
        margin-top: 15px;
        font-size: 10pt;
      }

      .dotted-line {
        border-bottom: 1px dashed #000;
        margin: 15px 0;
      }
    `);
            printWindow.document.write('</style></head><body>');

            // Create receipt content with grouped items
            printWindow.document.write(`
      <div class="receipt-header">
        <div class="receipt-title">Toko Pertanian Joyo Langgeng Sejahtera</div>
        <div class="receipt-details">Mojorejo, Jetis, Mojokerto</div>
        <div class="receipt-details">Telp: 085645185577</div>
      </div>

      <div class="receipt-details">
        <div>No: {{ $transaction->id_penjualan }}</div>
        <div>Tanggal: {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }}</div>
      </div>

      <div class="dotted-line"></div>

      <table>
        <tr>
          <th style="width: 50%">Item</th>
          <th class="center" style="width: 10%">Qty</th>
          <th class="right" style="width: 20%">Harga</th>
          <th class="right" style="width: 20%">Total</th>
        </tr>

        @foreach ($groupedItems as $key => $item)
        <tr>
          <td>
            <span class="item-name">{{ $key }}</span>
          </td>
          <td class="center">{{ $item['quantity'] }}</td>
          <td class="right">{{ number_format($item['price'], 0, ',', '.') }}</td>
          <td class="right">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </table>

      <div class="summary">
        <div class="summary-row">
          <span>Total</span>
          <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
          <span>Tunai</span>
          <span>Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
          <span>Kembali</span>
          <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
        </div>
      </div>

      <div class="store-info">
        Terima Kasih Atas Kunjungan Anda
      </div>

      <div class="thanks">
        Barang yang sudah dibeli tidak dapat dikembalikan
      </div>
    `);

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for content to load before printing
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                // printWindow.close(); // Uncomment if you want the print window to close after printing
            };
        }
    </script>
@endsection
