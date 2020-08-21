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
                <div class="col-md-12">
                    <div class="panel widget">
                        <div class="panel-content">
                            <div class="panel-body">
                                <div class="form-group col-md-6">
                                    <label for="">Company</label>
                                    <select class="form-control select2" name="company" id="company">
                                        <option value="" disabled selected>Select Company</option>
                                        @foreach ($company_name as $company)
                                            <option value="{{ $company }}">{{ ucwords($company) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Year Range</label>
                                    <input type="text" class="form-control" name="year_range" id="year_range">
                                </div>
                                <div class="form-group col-md-6">
                                    <button class="btn btn-primary" id="btn_filter">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

        $(document).ready(function(){

            var start = moment().subtract(29, 'days');
            var end = moment();
            $('input[name="year_range"]').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'MMM DD, YYYY'
                },
                "showDropdowns": true,
                "opens": 'right',
                "linkedCalendars": false,
                "showCustomRangeLabel": true,
            }, function(start, end) {

                var start_yr = start.format('MMM DD, YYYY');
                var end_yr = end.format('MMM DD, YYYY');

            });

            $('#btn_filter').on('click', function(){
                $.ajax({
                    url: "{{ url('/admin/get-reports-data') }}",
                    type: 'POST',
                    data: {'year_range': $('input[name="year_range"]').val(), 'company': $('#company').val(),'_token': $('meta[name="csrf-token"]').attr('content')},
                    success: function (result){
                        $("#customer_tbl tbody").html("");
                        $("#order_tbl tbody").html("");

                        // Customer Report
                        $("#title-customer").html(' - ' + ucwords($('#company').val()) + ' as of ' + $('input[name="year_range"]').val());
                        removeDataset(cutomersChart);
                        addDataset(cutomersChart, result.customers_data, result.customers_label, 'customers');
                        cutomersChart.update();
                        for (let i = 0; i < result.customers_tbl.length; i++) {
                            const e = result.customers_tbl[i];
                            $("#customer_tbl tbody").append('<tr> <td>'+e.customer_name+'</td> <td>'+e.email+'</td> <td>'+e.name+'</td> <td>'+e.customer_type+'</td> </tr>');
                        }

                        // Order Report
                        $("#title-order").html(' - ' + ucwords($('#company').val()) + ' as of ' + $('input[name="year_range"]').val());
                        removeDataset(orderChart);
                        addDataset(orderChart, result.years_cnt, result.years, 'orders');
                        orderChart.update();
                        for (let i = 0; i < result.orders_tbl.length; i++) {
                            const e = result.orders_tbl[i];
                            $("#order_tbl tbody").append('<tr> <td>'+e.file_num+'</td> <td>'+e.closer+'</td> <td>'+e.property_addr+'</td> <td>'+moment(e.submitted_date).format('MM/DD/YYYY')+'</td> <td>'+moment(e.closed_date).format('MM/DD/YYYY')+'</td> </tr>');
                        }
                    }
                });
            });
        });

        function ucwords(str)
        {
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

            return str;
        }

        function removeDataset(chart) {
            chart.data.datasets.data = [];
        };

        function addDataset(chart, data, label, chart_name) {
            if(chart_name == 'customers') {
                chart.data.datasets = [{
                    label: 'Customers',
                    data: data,
                    backgroundColor: 'rgb(54, 162, 235)'
                }];
            } else {
                chart.data.labels = label;
                chart.data.datasets = [{
                    label: 'Orders',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: data
                }]
            }
        };

    </script>
@endsection