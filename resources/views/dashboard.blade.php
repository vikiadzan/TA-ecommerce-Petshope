@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Heading -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <!-- Add your CSS link and JavaScript scripts here -->
</head>

<body>
    <div class="container">
        <!-- Header -->


        <!-- Content Row -->
        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Monthly)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                        </div>
                    </div>
                </div> -->

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Income
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp.{{number_format($totalEarnings, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tasks Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total items sold
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalQty}}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>

                            <!-- <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div> -->
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <!-- Add your pie chart code here -->
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-6 mb-4">
                <!-- Add content here -->
            </div>

            <div class="col-lg-6 mb-4">
                <!-- Add content here -->
            </div>
        </div>
    </div>
    <!-- /.container -->

    <!-- Add your JavaScript scripts here -->

</body>

</html>

<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
@endsection

<!-- 
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  fetch('/grafik')
    .then(response => response.json())
    .then(data => {
        const totalData = data.total;

        const labels = [];
        const dataset = {
            label: 'Total Pendapatan',
            data: [],
            borderColor: getRandomColor(),
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color for the fill area
            borderWidth: 2,
            fill: true // Set to true to show a filled area chart
        };

        totalData.forEach(item => {
            const { tanggal, total_pendapatan } = item;

            dataset.data.push(total_pendapatan);
            labels.push(tanggal); // Using "tanggal" as the label
        });

        new Chart(document.getElementById('myAreaChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [dataset]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

function getRandomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}
</script>

@endpush -->
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    if (typeof myChart !== 'undefined') {
    myChart.destroy();
}
    fetch('/grafik')
        .then(response => response.json())
        .then(data => {
            const totalData = data.total;

            const allMonths = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const monthlyData = {};

            allMonths.forEach(month => {
                monthlyData[month] = 0;
            });

            totalData.forEach(item => {
                const {
                    tanggal,
                    total_pendapatan
                } = item;

                const month = tanggal.split('-')[1];
                monthlyData[allMonths[parseInt(month) - 1]] += total_pendapatan;
            });

            const labels = allMonths;
            const dataset = {
                label: 'Total Pendapatan',
                data: labels.map(label => monthlyData[label]),
                borderColor: getRandomColor(),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true
            };

            new Chart(document.getElementById('myAreaChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [dataset]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });

    function getRandomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }
</script>
@endpush