@extends('layouts.app')

@section('style')
    <style>
        .currency {
            text-align: right;
        }

        .currency:before {
            content: 'Rp ';
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Barang Kadaluarsa dan Kerugian Harga</h1>

        <!-- Tabel Barang Kadaluarsa -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="text-white">Nama Barang</th>
                        <th class="text-white">Jumlah Stok Kadaluarsa</th>
                        <th class="text-white">Harga Beli (Per Unit)</th>
                        <th class="text-white">Kerugian (Total)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kerugian as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['stok_kadaluarsa'] }}</td>
                            <td class="currency">{{ number_format($item['harga_beli'], 2, ',', '.') }}</td>
                            <td class="currency">{{ number_format($item['kerugian'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <!-- Tambahkan script khusus jika diperlukan -->
@endsection
