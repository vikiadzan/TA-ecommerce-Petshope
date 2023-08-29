@extends('layout.app')

@section('title', 'Data Produk')

@section('content')


<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data Produk
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
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Nama Produk</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Berat</th>
                        <th>Tags</th>
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
                <h5 class="modal-title">Form Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-produk">


                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="id_kategori" id="id_kategori" class="form-control">
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->nama_kategori}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Subkategori</label>
                                <select name="id_subkategori" id="id_subkategori" class="form-control">
                                    @foreach ($subcategories as $category)
                                    <option value="{{$category->id}}">{{$category->nama_subkategori}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" placeholder="Nama Produk" required>
                            </div>

                            <div class="form-group">
                                <label for="">Gambar</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>


                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="" cols="30" rows="10" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="number" class="form-control" name="qty" placeholder="Quantity" required>
                            </div>

                            <div class="form-group">
                                <label for="">Harga</label>
                                <input type="number" class="form-control" name="harga" placeholder="Harga" required>
                            </div>
                            <div class="form-group">
                                <label for="">Berat</label>
                                <input type="number" class="form-control" name="berat" placeholder="berat" required>
                            </div>

                            <div class="form-group">
                                <label for="">Tags</label>
                                <input type="text" class="form-control" name="tags" placeholder="Tags" required>
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {

        $.ajax({
            url: '/api/products',
            success: function(response) {
                let data = response.data;
                let row = '';

                data.map(function(val, index) {
                    row += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${val.category && val.category.nama_kategori ? val.category.nama_kategori : ''}</td>
                    <td>${val.subcategory && val.subcategory.nama_subkategori ? val.subcategory.nama_subkategori : ''}</td>
                    <td>${val.nama_produk}</td>
                    <td><img src="/uploads/${val.gambar}" width="150"></td>
                    <td>${val.deskripsi}</td>
                    <td>${val.qty}</td>
                    <td>${val.harga}</td>
                    <td>${val.berat}</td>
                    <td>${val.tags}</td>
                    
                    <td>
                        <a data-toggle="modal" href="modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>
                        <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus</a>
                    </td>
                </tr>
            `;
                });

                $('tbody').append(row);

                // Inisialisasi DataTable dengan paginasi
                $('#dataTable').DataTable({
                    "paging": true,
                    "pageLength": 10,
                });
            }
        });

        $(document).on('click', '.btn-hapus', function() {



            const id = $(this).data('id')
            const token = localStorage.getItem('token')

            confirm_dialog = confirm('Apakah anda yakin menghapus data ini?');

            if (confirm_dialog) {
                $.ajax({
                    url: '/api/products/' + id,
                    type: "DELETE",
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    success: function(data) {
                        if (data.success == true) {
                            alert('Data berhasil dihapus')
                            location.reload()
                        }
                    }
                });
            }


        });

        $('.modal-tambah').click(function() {
            $('#modal-form').modal('show')
            $('select[name="id_kategori"]').val('');
            $('select[name="id_subkategori"]').val('');
            $('input[name="nama_produk"]').val('');
            $('textarea[name="deskripsi"]').val('');
            $('input[name="qty"]').val('');
            $('input[name="harga"]').val('');
            $('input[name="berat"]').val('');
            $('input[name="tags"]').val('');



            $('.form-produk').submit(function(e) {
                e.preventDefault()
                const token = localStorage.getItem('token')
                const frmdata = new FormData(this);

                $.ajax({
                    url: 'api/products',
                    type: 'POST',
                    data: frmdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    success: function(data) {
                        console.log(data);
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


        $(document).on('click', '.modal-ubah', function() {
            $('#modal-form').modal('show');
            const id = $(this).data('id');

            $.get('/api/products/' + id, function({
                data
            }) {
                $('select[name="id_kategori"]').val(data.id_kategori);
                $('select[name="id_subkategori"]').val(data.id_subkategori);
                $('input[name="nama_produk"]').val(data.nama_produk);
                $('textarea[name="deskripsi"]').val(data.deskripsi);
                $('input[name="qty"]').val(data.qty);
                $('input[name="harga"]').val(data.harga);
                $('input[name="berat"]').val(data.berat);
                $('input[name="tags"]').val(data.tags);

            });

            $('.form-produk').submit(function(e) {
                e.preventDefault()
                const token = localStorage.getItem('token')
                const frmdata = new FormData(this);

                $.ajax({
                    url: `api/products/${id}?_method=PUT`,
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