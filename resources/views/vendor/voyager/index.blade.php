@extends('voyager::master')

@section('content')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-pie-graph"></i> Reports
        </h1>
    </div>
    <div class="container-fluid">
        <div class="page-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel widget">
                        <div class="panel-content">
                            <canvas id="customers_chart"></canvas>
                            <hr>
                            <label><strong>Customers</strong>&nbsp;<span id="title-customer"></span></label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped" width="100%" id="customer_tbl">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Sales Rep</th>
                                            <th>Customer Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers_tbl as $row)
                                            <tr>
                                                <td>{{ $row->customer_name }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->customer_type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel widget">
                        <div class="panel-content">
                            <canvas id="orders_chart"></canvas>
                            <hr>
                            <label><strong>Orders</strong>&nbsp;<span id="title-order"></span></label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped" width="100%" id="order_tbl">
                                    <thead>
                                        <tr>
                                            <th>File Number</th>
                                            <th>Closer</th>
                                            <th>Property Address</th>
                                            <th>Submitted Date</th>
                                            <th>Closed Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders_tbl as $row)
                                            <tr>
                                                <td>{{ $row->file_num }}</td>
                                                <td>{{ $row->closer }}</td>
                                                <td>{{ $row->property_addr }}</td>
                                                <td>{{ date('m/d/Y', strtotime($row->submitted_date)) }}</td>
                                                <td>{{ date('m/d/Y', strtotime($row->closed_date)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var customers = document.getElementById('customers_chart').getContext('2d');
        var orders = document.getElementById('orders_chart').getContext('2d');
        
        var orderChart = new Chart(orders, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: [
                    @foreach($years as $row)
                        '{{ $row }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Orders',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [
                        @foreach($years_cnt as $row)
                            '{{ $row }}',
                        @endforeach
                    ]
                }]
            },

            // Configuration options go here
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Orders'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var cutomersChart = new Chart(customers, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: [
                    @foreach($customers_label as $row)
                        '{{$row}}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Customers',
                    backgroundColor: 'rgb(54, 162, 235)',
                    data: [
                        @foreach($customers_data as $row)
                            '{{$row}}',
                        @endforeach
                    ]
                }]
            },

            // Configuration options go here
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Customers'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        function ucwords(str)
        {
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

            return str;
        }

    </script>
@endsection