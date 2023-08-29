@extends('layout.app')

@section('title', 'Laporan Pesanan')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Laporan Pesanan
        </h4>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="form-group">
                        <label for="dari">Dari Tanggal</label>
                        <input type="date" name="dari" id="dari" class="form-control form-control-sm" value="{{ request()->input('dari') }}">
                    </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sampai">Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control form-control-sm" value="{{ request()->input('sampai') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Submit</button>
                </div>
            </div>
        </div>
        </form>
        <!-- <a class="btn btn-default" href="{{ url('/laporan/cetak?dari=${dari}&&sampai=${sampai}') }}" target="_blank" id="btnCetakPDF">
            <i class="fa fa-print"></i> Cetak PDF
        </a> -->
        <div class="col-md-2">
            <div class="form-group">
                <button class="btn btn-primary btn-block btn-sm" onclick="cetakPDF()" target="blank">Cetak PDF</button>
            </div>
        </div>
        <!-- <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-primary btn-block btn-sm" onclick="cetakExcel()" target="blank">Cetak Excel</button>
            </div>
        </div> -->



        @if (request()->input('dari'))
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-condensed" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah dibeli</th>
                        <th>Total Qty</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Tambahkan baris lainnya sesuai data yang ingin ditampilkan -->
                </tbody>
            </table>
            <div class="mt-5">
                <h5>Total Pendapatan: <span id="totalPendapatan"></span></h3>
            </div>
        </div>
        @endif
    </div>
</div>




@endsection

@push('js')

<script>
    $(function() {

        const dari = '{{ request()->input('dari') }}';
        const sampai = '{{ request()->input('sampai') }}';

        function rupiah(angka) {
            const format = angka.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            return 'Rp ' + convert.join('.').split('').reverse().join('')
        }

        const token = localStorage.getItem('token')

        $.ajax({
            url: `/api/reports?dari=${dari}&&sampai=${sampai}`,
            headers: {
                "Authorization": 'Bearer ' + token
            },
            success: function(response) {
                if (response && response.data && Array.isArray(response.data)) {
                    const data = response.data;
                    let row = '';
                    let totalPendapatan = 0;

                    data.forEach(function(val, index) {
                        const totalPendapatanAsNumber = parseInt(val.total_pendapatan, 10);

                        if (!isNaN(totalPendapatanAsNumber)) {
                            totalPendapatan += totalPendapatanAsNumber; // Akumulasi total pendapatan
                        } else {
                            console.log("Value of val.total_pendapatan is not a valid number:", val.total_pendapatan);
                        }

                        // Membangun baris tabel
                        row += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${val.nama_produk}</td>
                        <td>${rupiah(val.harga)}</td>
                        <td>${val.jumlah_dibeli}</td>
                        <td>${val.total_qty}</td>
                        <td>${rupiah(totalPendapatanAsNumber)}</td>
                    </tr>
                `;
                    });

                    $('tbody').append(row);
                    $('#totalPendapatan').text(rupiah(totalPendapatan));
                } else {
                    console.log("Data tidak valid atau kosong:", response);
                }
            }
        });


    });

    function cetakPDF() {
    const dari = document.getElementById('dari').value;
    const sampai = document.getElementById('sampai').value;

    // Redirect ke halaman cetak PDF dengan data tanggal yang dikirimkan sebagai parameter query
    window.location.href = '/laporan/cetak?dari=' + encodeURIComponent(dari) + '&sampai=' + encodeURIComponent(sampai);
}

</script>
@endpush