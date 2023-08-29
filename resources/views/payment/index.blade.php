@extends('layout.app')

@section('title', 'Data Pembayaran')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data Pembayaran
        </h4>
    </div>
    <div class="card-body">
           <!-- Gunakan grid system dari Bootstrap untuk menata elemen -->
           <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterStatus">Filter berdasarkan Status</label>
                        <select id="filterStatus" class="form-control">
                            <option value="">Semua</option>
                            <option value="DITERIMA">DITERIMA</option>
                            <option value="DITOLAK">DITOLAK</option>
                            <option value="MENUNGGU">MENUNGGU</option>
                        </select>
                    </div>
                </div>
            </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-condensed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>ID Order</th>
                        <th>Jumlah Pembayaran</th>
                        <th>No Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Bukti</th>
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

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-pembayaran">


                            <form class="form-Pembayaran">
                                <div class="form-group">
                                    <label for="">Tanggal</label>
                                    <input type="text" class="form-control" name="tanggal" placeholder="Tanggal" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Jumlah</label>
                                    <input type="text" class="form-control" name="jumlah" placeholder="Jumlah" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">No Rekening</label>
                                    <input type="text" class="form-control" name="no_rekening" placeholder="No Rekening" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Atas Nama</label>
                                    <input type="text" class="form-control" name="atas_nama" placeholder="Atas Nama" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="DITERIMA">DITERIMA</option>
                                        <option value="DITOLAK">DITOLAK</option>
                                        <option value="MENUNGGU">MENUNGGU</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Bukti Pembayaran</label>
                                    <input type="file" class="form-control" name="gambar"  placeholder="Bukti Pembayaran" readonly>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>

                            </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
    $(function() {

        function formatDate(date) {
            var dateObj = new Date(date);
            var day = dateObj.getDate();
            var month = dateObj.getMonth() + 1;
            var year = dateObj.getFullYear();
            var hours = dateObj.getHours();
            var minutes = dateObj.getMinutes();
            var seconds = dateObj.getSeconds();


            var formattedDate = `${day}-${month}-${year}`;
            var formattedTime = `${hours}:${minutes}:${seconds}`;

            return `Tanggal: ${formattedDate}, Jam: ${formattedTime}`
        }
        function formatRupiah(angka) {
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            return formatter.format(angka);
        }
        

        function filterTableData(status) {
            const rows = $('tbody').find('tr');

            // Show all rows if status is empty or not provided
            if (!status) {
                rows.show();
            } else {
                // Hide rows that do not match the selected status
                rows.each(function() {
                    const rowStatus = $(this).find('td:nth-child(7)').text();
                    if (rowStatus !== status) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            }
        }

        // Handle filter change event
        $('#filterStatus').on('change', function() {
            const selectedStatus = $(this).val();
            filterTableData(selectedStatus);
        });     
        
        $.ajax({
            url: '/api/payments',
            success: function({ data }) {
                // Populate the table on initial load
                let row;
                data.map(function(val, index) {
                    row += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${formatDate(val.created_at)}</td>
                            <td>${val.id_order}</td>
                            <td>${formatRupiah(val.jumlah)}</td>
                            <td>${val.no_rekening}</td>
                            <td>${val.atas_nama}</td>
                            <td>${val.status}</td>
                            
                            <td><img src="/uploads/${val.gambar}" width="150"></td>
                            <td>
                                    <a href="{{url('/uploads')}}/${val.gambar}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="ti-image"></i> Lihat File
                                    </a>
                                    <a data-toggle="modal" href="modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>
                            </td>
                        </tr>
                    `;
                });
                $('tbody').append(row);

                // Initialize the filter with all data shown
                filterTableData('');

                // Set default filter value to "Semua" (empty)
                $('#filterStatus').val('');
            }


        });
             

        $(document).on('click', '.modal-ubah', function() {
            $('#modal-form').modal('show');
            const id = $(this).data('id');

            $.get('/api/payments/' + id, function({
                data
            }) {
                $('input[name="tanggal"]').val(formatDate(data.created_at));
                $('input[name="jumlah"]').val(data.jumlah);
                $('input[name="no_rekening"]').val(data.no_rekening);
                $('input[name="atas_nama"]').val(data.atas_nama);
                $('select[name="status"]').val(data.status);
            });

            $('.form-pembayaran').submit(function(e) {
                e.preventDefault()
                const token = localStorage.getItem('token')
                const frmdata = new FormData(this);

                $.ajax({
                    url: `api/payments/${id}?_method=PUT`,
                    type: 'POST',
                    data: frmdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    success: function(data) {
                        if (data.success) {
                            alert('Data berhasil diubah')
                            location.reload();
                        }
                    },
                    fail: function(data) {
                        console.log(data)
                    }
                })
            });

        });
       
    });
</script>
@endpush