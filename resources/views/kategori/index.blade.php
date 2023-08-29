@extends('layout.app')

@section('title', 'Data Kategori')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data Kategori
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-end mb-4">
            <a href="#modal-form" class="btn btn-primary modal-tambah">Tambah Data</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-condensed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
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
                <h5 class="modal-title">Form Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-kategori">


                            <div class="form-group">
                                <label for="">Nama Kategori</label>
                                <input type="text" class="form-control" name="nama_kategori" placeholder="Nama Kategori" required>
                            </div>

                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="" cols="30" rows="10" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="">Gambar</label>
                                <input type="file" class="form-control" name="gambar">
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

        $.ajax({
            url: '/api/categories',
            success: function({
                data
            }) {
                //  console.log({data})

                let row;
                // untuk mengiterasi setiap elemen dalam sebuah array dan mengembalikan array /perulanagn
                data.map(function(val, index) {
                    // console.log(val)
                    row += `
                <tr>
                   <td>${index + 1}</td>
                    <td>${val.nama_kategori}</td>
                    <td>${val.deskripsi}</td>
                    <td><img src="/uploads/${val.gambar}" width="150"></td>
                    <td>
                        <a data-toggle="modal" href="modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah ">Edit</a>
                        <a href="#" data-id="${val.id}" class="btn btn-danger  btn-hapus">Hapus</a>
                    </td>


                </tr>
                 `;

                });
                $('tbody').append(row)
            }

        });
        // Meng-handle tombol Hapus
        $(document).on('click', '.btn-hapus', function() {
        // Menampilkan konfirmasi sebelum menghapu
            const id = $(this).data('id')
            const token = localStorage.getItem('token')

            confirm_dialog = confirm('Apakah anda yakin menghapus data ini?');

            if (confirm_dialog) {
                $.ajax({
                    url: '/api/categories/' + id,
                    type: "DELETE",
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    success: function(data) {
                        if (data.message == 'success') {
                            alert('Data berhasil dihapus')
                            location.reload();
                            console.log(data.message);
                        }
                    }
                });
            }


        });

        $('.modal-tambah').click(function() {
            $('#modal-form').modal('show')
            $('input[name="nama_kategori"]').val('')
            $('textarea[name="deskripsi"]').val('')
            
            
            // Meng-handle pengiriman data kategori baru menggunakan for
            $('.form-kategori').submit(function(e) {
                e.preventDefault()
                const token = localStorage.getItem('token')
                const frmdata = new FormData(this);

            // Melakukan permintaan POST menggunakan AJAX
                $.ajax({
                    url: 'api/categories',
                    type: 'POST',
                    data: frmdata,
                    cache: false,  //mengambil respons terbaru dari server
                    contentType: false, //engirim data dalam bentuk apa pun tanpa mengatur tipe konten secara eksplisit.
                    processData: false, //emberi tahu jQuery untuk tidak mengubah data yang dikirimkan melalui FormData.
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    success: function(data) {
                        if (data.success) {
                            alert('Data berhasil ditambah')
                            location.reload();
                        }
                    },
                    fail: function(data) {
                        console.log(data)
                    }
                })
            });
        });

        // Meng-handle tombol Edit pada modal
        $(document).on('click', '.modal-ubah', function() {
            $('#modal-form').modal('show');
            const id = $(this).data('id');

             // Mengisi form modal dengan data kategori yang akan diubah
            $.get('/api/categories/' + id, function({
                data
            }) {
                $('input[name="nama_kategori"]').val(data.nama_kategori);
                $('textarea[name="deskripsi"]').val(data.deskripsi);
            });

            // Melakukan permintaan POST dengan metode PUT menggunakan AJAX
            $('.form-kategori').submit(function(e) {
                e.preventDefault()
                const token = localStorage.getItem('token')
                const frmdata = new FormData(this);

                $.ajax({
                    url: `api/categories/${id}?_method=PUT`,
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