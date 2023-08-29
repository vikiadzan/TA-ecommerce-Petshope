@extends('layout.app')

@section('title', 'Data Pesanan Baru')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data Pesanan Baru
        </h4>
    </div>
    <div class="card-body">
        <!-- Gunakan grid system dari Bootstrap untuk menata elemen -->


        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-condensed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pesanan</th>
                        <th>Invoice</th>
                        <th>Member</th>
                        <th>Nama Produk</th>
                        <th>Detail Alamat</th>
                        <th>Staus Pembayaran</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Tambahkan baris lainnya sesuai data yang ingin ditampilkan -->
                </tbody>
            </table>
        </div>
    </div>
</div>




@endsection

@push('js')
<script>
    $(function() {

        function rupiah(angka) {
            const format = angka.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            return 'Rp ' + convert.join('.').split('').reverse().join('')
        }

        function date(date) {
            var date = new Date(date);
            var day = date.getDate(); //Date of the month: 2 in our example
            var month = date.getMonth(); //Month of the Year: 0-based index, so 1 in our example
            var year = date.getFullYear()


            return `${day}-${month}-${year}`;
        }

        const token = localStorage.getItem('token')


        $.ajax({
            url: '/api/pesanan/baru',
            headers: {
                "Authorization": 'Bearer ' + token
            },
            success: function({
                data
            }) {
                console.log({
                    data
                })

                let row = ''; // Inisialisasi sebagai string kosong

                data.map(function(val, index) {
                    let detailAlamat = val.payment ? val.payment.detail_alamat : 'N/A';

                    // Membuat string kosong untuk menampung data produk
                    let productNames = '';
                    console.log(val.order_details);

                    // Memeriksa apakah orderDetails ada sebelum menggunakan map
                    if (val.order_details) {
                        val.order_details.map(function(detail) {
                            productNames += detail.product.nama_produk + ', ';
                            // console.log(detail.product.nama_produk);
                        });
                        

                        // Menghilangkan koma dan spasi terakhir
                        productNames = productNames.slice(0, -2);
                    }

                   

                    row += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${date(val.created_at)}</td>
                        <td>${val.invoice}</td>
                        <td>${val.member.nama_member}</td>
                        <td>${productNames}</td>
                        <td>${detailAlamat}</td>
                        <td>${val.payment ? val.payment.status : 'N/A'}</td>
                        <td>${rupiah(val.grand_total)}</td>
                        <td>
                            <a href="#" data-id="${val.id}" class="btn btn-success btn-aksi">Konfirmasi</a>
                        </td>
                    </tr>
                `;

                });

                $('tbody').append(row);

                // console.log(val);

            }

        });

        $(document).on('click', '.btn-aksi', function() {
            const id = $(this).data('id')

            $.ajax({
                url: '/api/pesanan/ubah_status/' + id,
                type: 'POST',
                data: {
                    status: 'Dikonfirmasi'
                },
                headers: {
                    "Authorization": 'Bearer ' + token
                },
                success: function(data) {
                    window.location = "/pesanan/baru"
                }
            })
        })

    });
</script>
@endpush