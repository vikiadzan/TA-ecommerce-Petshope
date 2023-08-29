<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimoni;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $categories = Category::all();
        $products = Product::skip(0)->take(12)->get();
        $testimonis = Testimoni::all();
        return view('home.index', compact('sliders', 'categories', 'products', 'testimonis'));
    }

    public function products($id_subcategory)
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }

        $products = Product::where('id_subkategori', $id_subcategory)->get();
        return view('home.products', compact('products'));
    }

    public function add_to_cart(Request $request)
    {
        $input = $request->all();
        Cart::create($input);
    }

    public function delete_from_cart(Cart $cart)
    {
        $cart->delete();
        return redirect('/cart');
    }

    public function product($id_product)
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }

        $product = Product::find($id_product);

        $latest_products = Product::orderByDesc('created_at')->offset(0)->limit(10)->get();

        return view('home.product', compact('product', 'latest_products'));
    }

    public function cart()
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 8418ca62ecbb66aaa958928ba1efb7bc"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        $provinsi = json_decode($response);
        $carts = Cart::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->get();
        $cart_total = Cart::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->sum('total');

        return view('home.cart', compact('carts', 'provinsi', 'cart_total'));
    }

    public function get_kota($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 8418ca62ecbb66aaa958928ba1efb7bc"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);



        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function get_ongkir($destination, $weight)
    {
        $curl = curl_init();
        // $weight = intval($weight); // Mengubah string menjadi angka (integer)

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=421&destination=" . $destination . "&weight=" . $weight . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 8418ca62ecbb66aaa958928ba1efb7bc"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function checkout_orders(Request $request)
    {

        // Mengatur zona waktu ke "Asia/Jakarta"
        date_default_timezone_set('Asia/Jakarta');

        // Mendapatkan tanggal dan waktu saat ini
        $currentDateTime = date('Y-m-d H:i:s');
        $invoice = date('ymds');

        $id = DB::table('orders')->insertGetId([
            'id_member' => $request->id_member,
            'invoice' => $invoice,
            'grand_total' => $request->grand_total,
            'status' => 'Baru',
            'created_at' => $currentDateTime
        ]);

        $orderDetails = [];

        for ($i = 0; $i < count($request->id_produk); $i++) {
            $productId = $request->id_produk[$i];
            $jumlah = $request->jumlah[$i];
            $total = $request->total[$i];

            // Tambahkan data pesanan ke dalam array
            $orderDetails[] = [
                'id_order' => $id,
                'id_produk' => $productId,
                'jumlah' => $jumlah,
                'total' => $total,
                'created_at' => $currentDateTime
            ];


            // Mengurangi qty produk di tabel products
            $product = Product::find($productId);
            if ($product) {
                $product->qty -= $jumlah;
                $product->save();
            }
        
        }
        DB::transaction(function () use ($orderDetails) {
            DB::table('order_details')->insert($orderDetails);
        });
       
        Cart::where('id_member', Auth::guard('webmember')->user()->id)->update([
            'is_checkout' => 1
        ]);

        $order = Order::where('invoice', $invoice)->first();

        // Mengirimkan nomor invoice kembali dalam respons JSON
        return response()->json(['invoice' => $invoice, 'order' => $order]);
    }


    public function checkout(Request $request,$invoice)
    {
        $about = About::first();
        $orders = Order::where('invoice', $invoice)->first();
        // $orders = Order::where('id_member', Auth::guard('webmember')->user()->id)->first();
        $curl = curl_init();
        

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 8418ca62ecbb66aaa958928ba1efb7bc"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        $provinsi = json_decode($response);
        $orders->grand_total;

        return view('home.checkout')->with(compact('about'))->with(compact('orders'))->with(compact('provinsi'));
    }


    public function payments(Request $request)
    {

        if ($request->has('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . '_' . preg_replace('/\s+/', '_', $gambar->getClientOriginalName());
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }


        Payment::create([
            'id_order' => $request->id_order,
            'id_member' => Auth::guard('webmember')->user()->id,
            'jumlah' => $request->jumlah,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'detail_alamat' => $request->detail_alamat,
            'status' => 'MENUNGGU',
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
            // 'gambar' => $request->gambar,
            'gambar' => $input['gambar']
        ]);
        // dd($request);?

        return redirect('/orders');
    }

    public function orders()
    {
        $orders = Order::with('orderDetails')->where('id_member', Auth::guard('webmember')->user()->id)->get();

        $carts = Cart::where('id_member', Auth::guard('webmember')->user()->id)->get();
        $payments = Payment::where('id_member', Auth::guard('webmember')->user()->id)->get();
        $order_details = OrderDetail::where('id_order', Auth::guard('webmember')->user()->id)->get();
        // dd($orders);
        return view('home.orders', compact('orders', 'carts', 'payments','order_details'));
    }

    public function pesanan_selesai(Order $order)
    {
        $order->status = 'Selesai';
        $order->save();

        return redirect('/orders');
    }

    public function about()
    {
        $about = About::first();
        $testimonis = Testimoni::all();
        return view('home.about', compact('about', 'testimonis'));
    }

    public function contact()
    {
        $about = About::first();
        return view('home.contact', compact('about'));
    }

    public function faq()
    {
        return view('home.faq');
    }
}
