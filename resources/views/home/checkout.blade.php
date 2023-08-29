@extends('layout.home')

@section('title','Chekout')

@section('content')

<!-- Checkout -->
<section class="section-wrap checkout pb-70">
  <div class="container relative">
    <div class="row">

      <div class="ecommerce col-xs-12">

      <h2 class="heading uppercase bottom-line ">Formulir Pemesanan</h2>

      <form name="checkout" class="checkout ecommerce-checkout row" method="POST" action="/payments" enctype="multipart/form-data" onsubmit="return validateForm();">
          @csrf
          <input type="hidden" name="id_order" value="{{$orders->id}}">
          <div class="col-md-8" id="customer_details">
            <div>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Provinsi
                  <abbr class="required" title="required">*</abbr>
                </label>
                <select name="provinsi" id="provinsi" class="country_to_state provinsi" rel="calc_shipping_state">
                  <option value="">Pilih Provinsi</option>
                  @foreach ($provinsi->rajaongkir->results as $provinsi)
                  <option value="{{$provinsi->province_id}}">{{$provinsi->province}}</option>
                  @endforeach
                </select>
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Kota
                  <abbr class="required" title="required">*</abbr>
                </label>
                <select name="kabupaten_kota" id="kota" class="country_to_state kota" rel="calc_shipping_state">
                  <option value="">Pilih Kota</option>
                </select>
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Kecamatan
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="text" class="input-text" placeholder value name="kecamatan" id="billing_first_name">
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Detail Alamat
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="text" class="input-text" placeholder value name="detail_alamat" id="billing_first_name">
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Atas Nama
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="text" class="input-text" placeholder value name="atas_nama" id="billing_first_name">
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">No Rekening
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="text" class="input-text" placeholder value name="no_rekening" id="billing_first_name">
              </p>

              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Nominal Transfer
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="text" class="input-text" value="{{ $orders->grand_total }}" name="jumlah" id="billing_first_name" readonly>
              </p>

              <!-- Tambahkan input untuk menampung gambar -->
              <p class="form-row form-row-first validate-required ecommerce-invalid ecommerce-invalid-required-field" id="billing_first_name_field">
                <label for="billing_first_name">Gambar Bukti Pembayaran
                  <abbr class="required" title="required">*</abbr>
                </label>
                <input type="file" class="input-file" name="gambar" id="gambar">
                <span class="file-extensions">(jpeg, png)</span>
              </p>
              <div class="clear"></div>

            </div>


            <div class="clear"></div>

          </div> <!-- end col -->

          <!-- Your Order -->
          <div class="col-md-4">
            <div class="order-review-wrap ecommerce-checkout-review-order" id="order_review">
              <h2 class="heading uppercase bottom-line full-grey">Pesanan Kamu</h2>
              <table class="table shop_table ecommerce-checkout-review-order-table">
                <tbody>
                  </td>
                  </tr>
                  <tr class="order-total">
                    <th><strong>Total Pembayaran</strong></th>
                    <td>
                      <strong><span class="amount">Rp.{{number_format($orders->grand_total)}}</span></strong>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div id="payment" class="ecommerce-checkout-payment">
                <h2 class="heading uppercase bottom-line full-grey">Metode Pembayaran</h2>
                <ul class="payment_methods methods">

                  <li class="payment_method_bacs">
                    <input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="bacs" checked="checked">
                    <label for="payment_method_bacs">Transfer Bank Langsung</label>
                    <div class="payment_box payment_method_bacs">
                      <p>Lakukan pembayaran langsung ke rekening bank kami. Pesanan Anda tidak akan dikirim sampai dana masuk ke rekening kami. Jangan lupa mengisi form pembayaran terlebih dahulu.</p>
                      <p><strong>Atas Nama : {{$about -> atas_nama}}</p></strong>
                      <p><strong>BRI :{{$about -> no_rekening}}</p></strong>
                      <p><strong>Atas Nama : {{$about -> atas_nama}}</p></strong>
                      <p><strong>BNI :{{$about -> no_rekening}}</p></strong>

                    </div>
                  </li>



                </ul>
                <div class="form-row place-order">
                  <input type="submit" name="ecommerce_checkout_place_order" class="btn btn-lg btn-dark" id="place_order" value="Place order">
                </div>
              </div>
            </div>
          </div> <!-- end order review -->
        </form>

      </div> <!-- end ecommerce -->

    </div> <!-- end row -->
  </div> <!-- end container -->
</section> <!-- end checkout -->


@endsection


@push('js')
<script>
  $(function() {
    $('.provinsi').change(function() {
      $.ajax({
        url: '/get_kota/' + $(this).val(),
        success: function(data) {
          data = JSON.parse(data)
          option = ""
          data.rajaongkir.results.map((kota) => {
            option += `<option value=${kota.city_id}>${kota.city_name}</option>`
          })
          $('.kota').html(option)
        }
      });
    });
  });

 function validateForm() {
        const provinsi = document.getElementById('provinsi').value;
        const kota = document.getElementById('kota').value;
        const kecamatan = document.getElementById('billing_first_name').value;
        const detailAlamat = document.getElementById('billing_first_name').value;
        const atasNama = document.getElementById('billing_first_name').value;
        const noRekening = document.getElementById('billing_first_name').value;

        const gambar = document.getElementById('gambar').value;

        if (provinsi === "" || kota === "" || kecamatan === "" || detailAlamat === "" || atasNama === "" || noRekening === "") {
            alert("Semua data harus diisi sebelum melanjutkan.");
            return false;
        }

        if (gambar === "") {
            alert("Anda harus memilih gambar bukti pembayaran.");
            return false;
        }

        const fileExtension = ['jpeg', 'jpg', 'png'];
        const fileName = gambar.split('.').pop().toLowerCase();

        if (!fileExtension.includes(fileName)) {
            alert("Format file gambar tidak valid. Hanya file JPEG, JPG, dan PNG yang diperbolehkan.");
            return false;
        }

        return true;
        return true;
    }

</script>

@endpush