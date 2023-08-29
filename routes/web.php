<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\TestimoniController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();

//auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('login_member', [AuthController::class, 'login_member']);
Route::post('login_member', [AuthController::class, 'login_member_action']);
Route::get('logout_member', [AuthController::class, 'logout_member']);

Route::get('register_member', [AuthController::class, 'register_member']);
Route::post('register_member', [AuthController::class, 'register_member_action']);

Route::get('email', [EmailController::class, 'kirim']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


// Route::get('/forgot_password', [AuthController::class, 'forgot_password']);

// Route::post('/forgot_password', [AuthController::class, 'sendResetLinkEmail'])
//     ->middleware('guest')
//     ->name('password.email');

// Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
//     ->middleware('guest')
//     ->name('password.reset');

// Route::post('/reset-password', [AuthController::class, 'resetMemberPassword'])->name('password.update');
// //kategori
Route::middleware(['auth','level:1'])->group(function () {
    // Rute-rute yang memerlukan otentikasi (login)
    Route::get('/kategori', [CategoryController::class, 'list']);
    Route::get('/subkategori', [SubcategoryController::class, 'list']);
    Route::get('/slider', [SliderController::class, 'list']);
    Route::get('/produk', [ProductController::class, 'list']);
    Route::get('/testimoni', [TestimoniController::class, 'list']);
    Route::get('/review', [ReviewController::class, 'list']);
    Route::get('/payment', [PaymentController::class, 'list']);
    Route::get('/member', [MemberController::class, 'list']);

    Route::get('/pesanan/baru', [OrderController::class, 'list']);
    Route::get('/pesanan/dikonfirmasi', [OrderController::class, 'dikonfirmasi_list']);
    Route::get('/pesanan/dikemas', [OrderController::class, 'dikemas_list']);
    Route::get('/pesanan/dikirim', [OrderController::class, 'dikirim_list']);
    Route::get('/pesanan/diterima', [OrderController::class, 'diterima_list']);
    Route::get('/pesanan/selesai', [OrderController::class, 'selesai_list']);

    Route::get('/laporan', [ReportController::class, 'index']);
    Route::get('/laporan/cetak', [ReportController::class, 'export']);
    // Route::get('/laporan/cetak-pdf', 'ReportController@export')->name('export');

    Route::get('/tentang', [TentangController::class, 'index']);
    Route::post('/tentang/{about}', [TentangController::class, 'update']);

});

Route::middleware(['auth','level:2'])->group(function () {
    Route::get('/laporan', [ReportController::class, 'index']);
    Route::get('/laporan/cetak', [ReportController::class, 'export']);
    // Route::get('/laporan/cetak-pdf', 'ReportController@export')->name('export');

    Route::get('/tentang', [TentangController::class, 'index']);
    Route::post('/tentang/{about}', [TentangController::class, 'update']);

});


// route home
Route::get('/', [HomeController::class, 'index']);
Route::get('/products/{category}', [HomeController::class, 'products']);
Route::get('/product/{id}', [HomeController::class, 'product']);
Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/checkout/{invoice}', [HomeController::class, 'checkout']);
Route::get('/orders', [HomeController::class, 'orders']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/faq', [HomeController::class, 'faq']);

Route::post('/add_to_cart', [HomeController::class, 'add_to_cart']);
Route::get('/delete_from_cart/{cart}', [HomeController::class, 'delete_from_cart']);
Route::get('/get_kota/{id}', [HomeController::class, 'get_kota']);
Route::get('/get_ongkir/{destination}/{weight}', [HomeController::class, 'get_ongkir']);
Route::post('/checkout_orders', [HomeController::class, 'checkout_orders']);
Route::post('/payments', [HomeController::class, 'payments']);
Route::post('/pesanan_selesai/{order}', [HomeController::class, 'pesanan_selesai']);
Route::get('/grafik', [DashboardController::class, 'grafik']);



// Route::middleware(['login_member'])->group(function () {
//     // Semua route dalam grup ini akan menggunakan middleware login_member
//     Route::get('/',[HomeController::class,'index']);
//     Route::get('/products/{category}',[HomeController::class,'products']);
//     Route::get('/product/{id}',[HomeController::class,'product']);
//     Route::get('/cart',[HomeController::class,'cart']);
//     Route::get('/checkout',[HomeController::class,'checkout']);
//     Route::get('/orders',[HomeController::class,'orders']);
//     Route::get('/about',[HomeController::class,'about']);
//     Route::get('/contact',[HomeController::class,'contact']);
//     Route::get('/faq',[HomeController::class,'faq']);
    
//     Route::post('/add_to_cart',[HomeController::class,'add_to_cart']);
//     Route::get('/delete_from_cart/{cart}', [HomeController::class, 'delete_from_cart']);
//     Route::get('/get_kota/{id}', [HomeController::class, 'get_kota']);
//     Route::get('/get_ongkir/{destination}/{weight}',[HomeController::class,'get_ongkir']);
//     Route::post('/checkout_orders',[HomeController::class,'checkout_orders']);
//     Route::post('/payments',[HomeController::class,'payments']);
//     Route::post('/pesanan_selesai/{order}', [HomeController::class, 'pesanan_selesai']);
// });


Route::middleware(['checkLevel:1'])->group(function () {
    // Tempatkan route yang perlu dibatasi di sini
    Route::get('/restricted-page', function () {
        return 'Hanya pengguna dengan level 1 yang bisa mengakses halaman ini.';
    });
});