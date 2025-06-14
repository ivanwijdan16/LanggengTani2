<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pembelian;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get the month and year from the request, default to current month and year if not provided
        $month = $request->input('bulan', now()->month);  // Default to current month if not set
        $year = $request->input('tahun', now()->year);    // Default to current year if not set

        // Total Stock
        $totalStock = Stock::sum('quantity');

        // Total Pembelian per Bulan
        $totalPembelian = Pembelian::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('purchase_price');

        // Omset Penjualan per Bulan
        $totalPenjualan = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_price');

        // Total Keuntungan
        $totalKeuntungan = TransactionItem::join('stocks', 'transaction_items.product_id', '=', 'stocks.id')
            ->whereMonth('transaction_items.created_at', $month)
            ->whereYear('transaction_items.created_at', $year)
            ->sum(DB::raw('(stocks.selling_price - stocks.purchase_price) * transaction_items.quantity'));

        // Grafik Penjualan Harian
        $dailySales = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total_sales')
        )
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        return view('home.index', compact('totalStock', 'totalPembelian', 'totalPenjualan', 'dailySales', 'totalKeuntungan', 'month', 'year'));
    }
}
