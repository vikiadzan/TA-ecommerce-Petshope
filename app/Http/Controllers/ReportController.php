<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index']);
        $this->middleware('auth:api')->only(['get_reports']);
    }

    public function index()
    {

        return view('report.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //ini diambil dari data order detail
    public function get_reports(Request $request)
    {
        $report = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.id_produk')
            ->join('orders', 'orders.id', '=', 'order_details.id_order')
            ->select(DB::raw('
            products.id as id_produk,
            nama_produk, 
            COUNT(*) as jumlah_dibeli,
            harga,
            SUM(total) as total_pendapatan, 
            SUM(jumlah) as total_qty'))
            ->where('orders.status', 'Selesai')
            ->whereRaw("date(order_details.created_at) >= '$request->dari'")
            ->whereRaw("date(order_details.created_at) <=' $request->sampai'")
            ->groupBy('id_produk', 'nama_produk', 'harga', 'products.id')
            ->get();

            // dd($request->dari);
            // dd($request->sampai);
            // dd($report);

       

        return response()->json([
            'data' => $report
        ]);
    }

  

    public function export(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $report = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.id_produk')
            ->select(DB::raw('
            products.id as id_produk,
            nama_produk, 
            COUNT(*) as jumlah_dibeli,
            harga,
            SUM(total) as total_pendapatan, 
            SUM(jumlah) as total_qty'))
            ->whereRaw("date(order_details.created_at) >= ?", [$dari])
            ->whereRaw("date(order_details.created_at) <= ?", [$sampai])
            ->groupBy('id_produk', 'nama_produk', 'harga', 'products.id')
            ->get();
    
        $pdf = PDF::loadView('report.laporan_pdf', compact('report'));
        
        return $pdf->stream('Laporan_Penjualan.pdf');

    }

    // public function cetakExcel(Request $request)
    // {
    //     // Ambil data dari permintaan AJAX
    //     $dari = $request->input('dari');
    //     $sampai = $request->input('sampai');

    //     // Ambil data penjualan dari database berdasarkan rentang tanggal
    //     $report = Penjualan::whereBetween('tanggal_penjualan', [$dari, $sampai])->get();

    //     // Load library PHPSpreadsheet (ganti nama library sesuai dengan library yang Anda gunakan)
    //     require 'path_to_your_phpexcel_or_phpspreadsheet_library/PHPExcel.php';

    //     // Buat objek spreadsheet
    //     $spreadsheet = new \PHPExcel();

    //     // Atur properties pada spreadsheet
    //     $spreadsheet->getProperties()
    //         ->setCreator("Your Name")
    //         ->setTitle("Laporan Penjualan");

    //     // Buat objek worksheet
    //     $worksheet = $spreadsheet->getActiveSheet();

    //     // Set judul pada worksheet
    //     $worksheet->setTitle('Penjualan');

    //     // Menambahkan header pada worksheet
    //     $worksheet->setCellValue('A1', 'No');
    //     $worksheet->setCellValue('B1', 'Nama Produk');
    //     $worksheet->setCellValue('C1', 'Harga');
    //     $worksheet->setCellValue('D1', 'Jumlah Dibeli');
    //     $worksheet->setCellValue('E1', 'Total qty');
    //     $worksheet->setCellValue('F1', 'Total Pendapatan');

    //     // Menambahkan data dari report
    //     $row = 2;
    //     $nomor = 1;
    //     foreach ($report as $r) {
    //         $worksheet->setCellValue('A' . $row, $nomor);
    //         $worksheet->setCellValue('B' . $row, $r->nama_produk);
    //         $worksheet->setCellValue('C' . $row, $r->harga);
    //         $worksheet->setCellValue('D' . $row, $r->jumlah_dibeli);
    //         $worksheet->setCellValue('E' . $row, $r->total_qty);
    //         $worksheet->setCellValue('F' . $row, $r->total_pendapatan);
    //         $row++;
    //         $nomor++;
    //     }

    //     // Mengatur lebar kolom agar sesuai dengan kontennya
    //     foreach (range('A', 'F') as $column) {
    //         $worksheet->getColumnDimension($column)->setAutoSize(true);
    //     }

    //     // Set header agar file yang diunduh adalah file Excel
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="laporan_penjualan.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     // Simpan spreadsheet ke output file
    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    //     exit;
    // }
}
