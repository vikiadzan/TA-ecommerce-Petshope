@extends('layout.home')

@section('title','cart')

@section('content')

<!-- Cart -->
<section class="section-wrap shopping-cart">
  <div class="container relative">
  @php
    $total_berat = 0; // Inisialisasi variabel total_berat sebelum perulangan
    @endphp
    @foreach($carts as $cart)
      @php
      $total_berat += $cart->product->berat * $cart->qty; // Tambahkan berat produk ke total_berat
      @endphp
      <!-- ... (kode yang sudah ada) ... -->
    @endforeach
    <form class="form-cart">
      <input type="hidden" name="id_member" value="{{Auth::guard('webmember')->user()->id}}">
      <div class="row">
        <div class="col-md-12">
          <div class="table-wrap mb-30">
            <table class="shop_table cart table">
              <thead>
                <tr>
                  <th class="product-name" colspan="2">Product</th>
                  <th class="product-price">Harga</th>
                  <th class="product-quantity">Quantity</th>
                  <th class="product-weight">Berat(gram)</th>
                  <th class="product-subtotal" colspan="2">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($carts as $cart)
                <input type="hidden" name="id_produk[]" value="{{$cart->product->id}}">
                <input type="hidden" name="jumlah[]" value="{{$cart->qty}}">
                <input type="hidden" name="total[]" value="{{$cart->total}}">
                <tr class="cart_item">
                  <td class="product-thumbnail">
                    <a href="#">
                      <img src="/uploads/{{$cart->product->gambar}}" alt="">
                    </a>
                  </td>
                  <td class="product-name">
                    <a href="#">{{$cart->product->nama_produk}}</a>
                  </td>
                  <td class="product-price">
                    <span class="amount">Rp. {{number_format($cart->product->harga)}}</span>
                  </td>
                  <td class="product-quantity">
                    <span class="amount">{{$cart->qty}}</span>
                  </td>
                  <td class="product-weight">
                    <span class="amount">{{$cart->product->berat * $cart->qty}}</span>
                  </td>
                  <td class="product-subtotal">
                    <span class="amount">Rp. {{number_format($cart->total)}}</span>
                  </td>
                  <td class="product-remove">
                    <a href="/delete_from_cart/{{$cart->id}}" class="remove" title="Remove this item">
                      <i class="ui-close"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- <div class="row mb-50">
            <div class="row mb-50">
              <div class="col-md-5 col-sm-12">
              </div>
              <div class="col-md-7">
                <div class="actions">
                  <div class="wc-proceed-to-checkout">
                    <a href="#" class="btn btn-lg btn-dark checkout"><span>proceed to
                        checkout</span></a>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
           <!-- end col -->
        </div> <!-- end row -->
        <div class="row">
        <div class="col-md-6 shipping-calculator-form">
          <div class="shipping-calculator-content">
            <h2 class="heading relative uppercase bottom-line full-grey mb-30">HITUNG PENGIRIMAN</h2>
            <p class="form-row form-row-wide">
              <label for="provinsi">Pilih Provinsi:</label>
              <select name="provinsi" id="provinsi" class="country_to_state provinsi" rel="calc_shipping_state">
                <option value="">Pilih Provinsi</option>
                @foreach ($provinsi->rajaongkir->results as $provinsi)
                <option value="{{$provinsi->province_id}}">{{$provinsi->province}}</option>
                @endforeach
              </select>
            </p>

            <p class="form-row form-row-wide">
              <label for="kota">Pilih Kota:</label>
              <select name="kabupaten_kota" id="kota" class="country_to_state kota" rel="calc_shipping_state">
                <option value="">Pilih Kota</option>
              </select>
            </p>

            <div class="form-row form-row-wide">
              <label for="Berat">Berat (gram):</label>
              <input type="text" class="input-text berat" placeholder="Berat (gram)" name="berat" id="Berat" value="{{$total_berat}}">
            </div>

            <div class="form-row">
              <button name="calc_shipping" class="btn btn-lg btn-stroke update-total">Perbarui Total</button>
            </div>
          </div>
        </div> <!-- end col shipping calculator -->


          <div class="col-md-6">
            <div class="cart_totals">
              <h2 class="heading relative bottom-line full-grey uppercase mb-30">TOTAL KERANJANG</h2>

              <table class="table shop_table">
                <tbody>
                  <tr class="cart-subtotal">
                    <th>Subtotal Keranjang</th>
                    <td>
                      <span class="amount cart-total">{{$cart_total}}</span>
                    </td>
                  </tr>
                  <tr class="shipping">
                    <th>Biaya Pengiriman</th>
                    <td>
                      <span class="shipping-cost">0</span>
                    </td>
                  </tr>
                  <tr class="order-total">
                    <th>Total Pesanan</th>
                    <td>
                      <input type="hidden" name="grand_total" class="grand_total">
                      <strong><span class="amount grand-total">0</span></strong>
                    </td>
                  </tr>
           
                </div> <!-- end col -->
                </tbody>
              </table>
            </div>
            <div class="row mb-50">
            <div class="row mb-50">
              <div class="col-md-5 col-sm-12">
              </div>
              <div class="col-md-7">
                <div class="actions">
                  <div class="wc-proceed-to-checkout">
                    <a href="#" class="btn btn-lg btn-dark checkout"><span>lanjutkan ke pembayaran</span></a>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end col -->
          </div> <!-- end col cart totals -->

        </div> <!-- end row -->
    </form>
  </div> <!-- end container -->
</section> <!-- end cart -->




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

    $('.update-total').click(function(e) {
      e.preventDefault()
      $.ajax({
        url: '/get_ongkir/' + $('.kota').val() + '/' + $('.berat').val(),
        success: function(data) {
          data = JSON.parse(data)
          grandtotal = parseInt(data.rajaongkir.results[0].costs[0].cost[0].value) + parseInt($('.cart-total').text())
          $('.shipping-cost').text(data.rajaongkir.results[0].costs[0].cost[0].value)
          $('.grand-total').text(grandtotal)
          $('.grand_total').val(grandtotal)
        }
      });
    });

    $('.checkout').click(function(e) {
      e.preventDefault();
      
      // Cek apakah grand total masih nol
      var grandTotal = parseInt($('.grand-total').text());
      if (grandTotal === 0) {
        alert('Isi grand total terlebih dahulu sebelum melakukan checkout.');
        return; // Hentikan proses checkout
      }

          
      // Ambil nilai provinsi dan kota yang dipilih
      var selectedProvinsi = $('.provinsi').val();
      var selectedKota = $('.kota').val();

      // Siapkan data yang akan dikirim
      var formData = $('.form-cart').serialize() + '&selected_provinsi=' + selectedProvinsi + '&selected_kota=' + selectedKota;
          
      $.ajax({
        url: '/checkout_orders',
        method: 'POST',
        data: $('.form-cart').serialize(),
        headers: {
          'X-CSRF-TOKEN': "{{csrf_token()}}",
        },
        success: function(data) {
          // Tangkap nomor invoice dari respons
          var invoice = data.invoice;
          // Arahkan pengguna ke halaman checkout dengan nomor invoice
          location.href = '/checkout/' + invoice;
        }
      });
    });

    function perbaruiTotalBerat() {
      var totalBerat = 0;
      $('.product-weight .amount').each(function() {
        totalBerat += parseInt($(this).text());
      });
      $('#Berat').val(totalBerat);
    }

    perbaruiTotalBerat(); // Perhitungan awal saat halaman dimuat

    $('.product-quantity .amount').on('change', function() {
      perbaruiTotalBerat(); // Perbarui total berat saat kuantitas berubah
    });

   
  });
</script>

@endpush