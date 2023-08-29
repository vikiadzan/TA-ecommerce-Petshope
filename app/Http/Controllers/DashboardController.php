<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $totalEarnings = Order::where('status', 'Selesai')
            ->join('order_details', 'orders.id', '=', 'order_details.id_order')
            ->sum('order_details.total');

        $totalQty = Order::where('status', 'Selesai')
            ->join('order_details', 'orders.id', '=', 'order_details.id_order')
            ->sum('order_details.jumlah');
        

         return view('dashboard', compact('totalEarnings','totalQty'));
    }

    
    public function grafik(Request $request)
    {
        DB::statement("SET SQL_MODE=''");
        $report = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.id_order')
            ->select(
                DB::raw('DATE_FORMAT(order_details.created_at, "%M %Y") AS tanggal'),
                DB::raw('SUM(order_details.total) AS total_pendapatan'),
                // DB::raw('SUM(order_details.jumlah) AS total_jumlah')
            )
            ->where('orders.status', 'Selesai')
            ->groupBy(DB::raw('YEAR(order_details.created_at), MONTH(order_details.created_at)'))
            ->orderBy(DB::raw('YEAR(order_details.created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(order_details.created_at)'), 'asc')
            ->get();

                // dd($report);

        return response()->json([
            'total' => $report,
        ]); 
    } 

//     public function grafik(Request $request)
// {
//     $startDate = Carbon::now()->subYear(); // Tanggal awal untuk rentang 1 tahun ke belakang

//     $allMonths = [
//         'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
//         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
//     ];

//     $report = DB::table('order_details')
//             ->join('orders', 'orders.id', '=', 'order_details.id_order')
//             ->select(
//                 DB::raw('DATE_FORMAT(order_details.created_at, "%M %Y") AS tanggal,
//                 SUM(order_details.total) AS total_pendapatan'
//             ))
//             ->where('orders.status', 'Selesai')
//             ->whereDate('order_details.created_at', '>=', $startDate)
//             ->groupBy('tanggal')
//             ->orderBy('tanggal', 'desc')
//             ->get();

//     $monthlyData = [];

//     foreach ($allMonths as $month) {
//         $monthlyData[$month] = 0;
//     }

//     foreach ($report as $item) {
//         $monthYear = $item->tanggal;
//         $totalPendapatan = $item->total_pendapatan;

//         // Ambil nama bulan dan tahun dari string "NamaBulan Tahun"
//         $split = explode(' ', $monthYear);
//         $month = $split[0];
//         $year = $split[1];

//         $monthlyData[$month] = $totalPendapatan;
//     }

//     $formattedReport = [];
//     foreach ($monthlyData as $month => $total) {
//         $formattedReport[] = [
//             'tanggal' => $month,
//             'total_pendapatan' => $total,
//         ];
//     }

//     return response()->json([
//         'total' => $formattedReport,
//     ]);
// }

    
 
}