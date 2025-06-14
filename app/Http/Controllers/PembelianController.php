<?php

namespace App\Http\Controllers;

use App\Models\MasterPembelian;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        // Load stok yang sudah dihapus juga
        $pembelians = MasterPembelian::with(['pembelians' => function ($query) {
            $query->with(['stock' => function ($query) {
                $query->withTrashed()->with('masterStock');
            }]);
        }])
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->get();

        $totalPerHari = [];
        foreach ($pembelians as $pembelian) {
            $tanggal = date('d-m-Y', strtotime($pembelian->date));
            if (!isset($totalPerHari[$tanggal])) {
                $totalPerHari[$tanggal] = 0;
            }
            $totalPerHari[$tanggal] += $pembelian->total;
        }

        $barangsKadaluarsa = Stock::with('masterStock')
            ->where('expiration_date', '<', now())
            ->whereYear('expiration_date', $tahun)
            ->whereMonth('expiration_date', $bulan)
            ->get();

        // Menghitung kerugian berdasarkan jumlah dan harga beli
        $kerugian = [];
        foreach ($barangsKadaluarsa as $barang) {
            $kerugian[] = [
                'name' => $barang->masterStock ? $barang->masterStock->name : 'Barang tidak ditemukan',
                'stock_id' => $barang->stock_id,
                'stok_kadaluarsa' => $barang->quantity,
                'harga_beli' => $barang->purchase_price,
                'kerugian' => $barang->purchase_price * $barang->quantity,
            ];
        }
        return view('pembelian.index', compact('pembelians', 'totalPerHari', 'bulan', 'tahun', 'kerugian'));
    }

    // Menampilkan riwayat pembelian per bulan
    public function riwayatPembelianPerBulan($bulan, $tahun)
    {
        $pembelians = Pembelian::withTrashed()
            ->with(['stock' => function ($query) {
                $query->withTrashed()->with('masterStock');
            }])
            ->whereYear('purchase_date', $tahun)
            ->whereMonth('purchase_date', $bulan)
            ->get();

        return view('pembelian.riwayat', compact('pembelians'));
    }

    // Menampilkan grafik pembelian per bulan
    public function grafikPembelianPerBulan($bulan, $tahun)
    {
        $pembelians = Pembelian::withTrashed()
            ->whereYear('purchase_date', $tahun)
            ->whereMonth('purchase_date', $bulan)
            ->get();

        $totalPerHari = [];
        foreach ($pembelians as $pembelian) {
            $tanggal = date('d-m-Y', strtotime($pembelian->purchase_date));
            if (!isset($totalPerHari[$tanggal])) {
                $totalPerHari[$tanggal] = 0;
            }
            $totalPerHari[$tanggal] += $pembelian->purchase_price * $pembelian->quantity;
        }

        return view('pembelian.grafik', compact('totalPerHari', 'bulan', 'tahun'));
    }


    // Cetak laporan per bulan
    public function cetakLaporan($bulan, $tahun)
    {
        $pembelians = Pembelian::withTrashed()
            ->with(['stock' => function ($query) {
                $query->withTrashed()->with('masterStock');
            }])
            ->whereYear('purchase_date', $tahun)
            ->whereMonth('purchase_date', $bulan)
            ->get();

        // Hitung total kerugian dari barang kadaluwarsa
        $barangsKadaluarsa = Stock::with('masterStock')
            ->where('expiration_date', '<', now())
            ->whereYear('expiration_date', $tahun)
            ->whereMonth('expiration_date', $bulan)
            ->get();

        // Menghitung total kerugian
        $totalKerugian = 0;
        foreach ($barangsKadaluarsa as $barang) {
            $totalKerugian += $barang->purchase_price * $barang->quantity;
        }

        $pdf = PDF::loadView('pembelian.laporan', compact('pembelians', 'totalKerugian'));
        return $pdf->download("laporan_pembelian_{$bulan}_{$tahun}.pdf");
    }
    public function barangKadaluarsaPerBulan($bulan, $tahun)
    {
        // Ambil barang yang kadaluarsa untuk bulan dan tahun tertentu
        $barangsKadaluarsa = Stock::with('masterStock')
            ->where('expiration_date', '<', now())
            ->whereYear('expiration_date', $tahun)
            ->whereMonth('expiration_date', $bulan)
            ->get();

        // Menghitung kerugian berdasarkan jumlah dan harga beli
        $kerugian = [];
        foreach ($barangsKadaluarsa as $barang) {
            $kerugian[] = [
                'name' => $barang->masterStock ? $barang->masterStock->name : 'Barang tidak ditemukan',
                'stock_id' => $barang->stock_id,
                'stok_kadaluarsa' => $barang->quantity,
                'harga_beli' => $barang->purchase_price,
                'kerugian' => $barang->purchase_price * $barang->quantity,
            ];
        }

        return view('pembelian.kadaluarsa', compact('kerugian', 'bulan', 'tahun'));
    }
}
