@extends('layout.home')

@section('title','Checkout')


@section('content')

<!-- Checkout -->
<section class="section-wrap checkout pb-70">
    <div class="container relative">
        <div class="row">

            <div class="ecommerce col-xs-12">
                <h2>My Payments</h2>
                <table class="table table-ordered table-hover table-striped">
                    <thead class="table-dark">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nominal Transfer</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($payments as $index => $payment)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$payment->created_at}}</td>
                            <td>Rp. {{number_format($payment->jumlah)}}</td>
                            <td>{{$payment->status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2>My Orders</h2>
                <table class="table table-ordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Quantity</th>
                            <th>Harga Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                 </thead>
                
                <tbody>
                    @php
                    $index = 0;
                    @endphp
                        @foreach ($orders as $order)
                        @foreach ($order->orderDetails as $orderDetail)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    <div>
                                        <img src="/uploads/{{ $orderDetail->product->gambar}}" alt="" width="100" height="100">
                                    </div><!-- Mengakses data detail pesanan -->
                                </td>
                                <td>{{ $orderDetail->product->nama_produk }} <!-- Mengakses data detail pesanan --></td>
                                <td>Rp. {{ number_format($orderDetail->product->harga) }}</td>
                                <td class="text-center">{{ $orderDetail->jumlah }}<!-- Mengakses data detail pesanan --></td>
                                <td>Rp. {{ number_format($orderDetail->product->harga* $orderDetail->jumlah ) }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    @if ($order->status !== 'Selesai' && $order->status !== 'Baru')
                                        <form action="/pesanan_selesai/{{$order->id}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">SELESAI</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @endforeach
                </tbody>





                  



                </table>



                <a href="/" class="btn btn-success">Kembali ke Halaman Home</a>

            </div> <!-- end ecommerce -->

        </div> <!-- end row -->
    </div> <!-- end container -->
</section> <!-- end checkout -->


@endsection