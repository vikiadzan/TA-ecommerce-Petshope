<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h3>Laporan Penjualan</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah Dibeli</th>
            <th>Total qty</th>
            <th>Total Pendapatan</th>
        </tr>
        @php
        $nomor = 1; // Inisialisasi nomor urutan
        $totalPendapatan = 0; // Inisialisasi total pendapatan
        @endphp
        @foreach($report as $r) 
        <tr>
            <td>{{$nomor}}</td>
            <td>{{$r->nama_produk}}</td>
            <td>{{$r->harga}}</td>
            <td>{{$r->jumlah_dibeli}}</td>
            <td>{{$r->total_qty}}</td>
            <td>Rp {{ number_format($r->total_pendapatan, 0, ',', '.') }}</td>
        </tr>
        @php
        $totalPendapatan += $r->total_pendapatan; // Menambahkan total pendapatan
        $nomor++; // Increment nomor urutan setiap iterasi
        @endphp
        @endforeach
        <tr class="total-row">
            <td colspan="5" style="text-align: right;">Total Pendapatan Akumulasi:</td>
            <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
