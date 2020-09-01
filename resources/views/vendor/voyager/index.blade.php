@extends('voyager::master')

@section('content')
    <style>
        .-title>a, .-title>a:active{
            display:block;
            padding:15px;
        color:#555;
        font-size:16px;
        font-weight:bold;
            text-transform:uppercase;
            letter-spacing:1px;
        word-spacing:3px;
            text-decoration:none;
        }
        .panel-heading  a:before {
        font-family: 'Glyphicons Halflings';
        content: "\e114";
        float: right;
        transition: all 0.5s;
        }
        .panel-heading.active a:before {
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            transform: rotate(180deg);
        } 
    </style>
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-pie-graph"></i> Reports
        </h1>
    </div>
    <div class="container-fluid">
        <div class="page-content">
            <div class="col-md-3 {{ Auth::user()->role_id == 1 || Auth::user()->role_id == 3 ? '' : 'hide' }}">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach ($company_name as $item)
                        <div class="panel panel-primary">
                            <div class="panel-heading" role="tab" id="headingOne-{{$item}}">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$item}}" aria-expanded="true" aria-controls="collapse-{{$item}}">
                                    {{ ucwords($item) }}
                                </a>
                            </h4>
                            </div>
                            <div id="collapse-{{$item}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne-{{$item}}">
                                <div class="panel-body">
                                    @php
                                        $coaches = \App\User::where(['company' => $item])->whereIn('role_id', array(2,4))->get(); // as much as I want to put this on Model or create a relationship for this, it takes time hahaha sorry next time will do
                                    @endphp
                                    @if (count($coaches) == 0)
                                        <div class="text-center">
                                            <p>No Clients/Coaches for this Organization.<br>Click <a href="{{route('voyager.users.create')}}"><strong>here</strong></a> add new Coach.</p>
                                        </div>
                                    @else
                                        @foreach ($coaches as $coach)
                                            <div class="accordion" id="accordionExample">
                                                <div class="card" style="box-shadow: none!important;">
                                                    <div class="card-header" id="heading-{{$coach->id}}">
                                                        <h2 style="margin:0px!important;">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{$coach->id}}" aria-expanded="true" aria-controls="collapse-{{$coach->id}}">
                                                            {{ $coach->name }}
                                                            </button>
                                                        </h2>
                                                    </div>

                                                    <div id="collapse-{{$coach->id}}" class="collapse" aria-labelledby="heading-{{$coach->id}}" data-parent="#accordionExample">
                                                        <div class="card-body" style="padding: 0px!important;">
                                                            @php
                                                                $reps = \App\User::where(['company' => $item, 'coach_id' => $coach->id,'role_id' => 5])->get(); // as much as I want to put this on Model or create a relationship for this, it takes time hahaha sorry next time will do
                                                            @endphp
                                                            @if (count($reps) > 0)
                                                                <table class="table table-striped table-bordered" width="100%">
                                                                    <tbody>
                                                                        @foreach ($reps as $rep)
                                                                            <tr>
                                                                                <td><a href="{{url('/admin/users/'.$rep->id)}}">{{$rep->name}}</a></td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <div class="text-center">
                                                                    <p>No Sales Reps for this Coach/Client.<br>Click <a href="{{route('voyager.users.create')}}"><strong>here</strong></a> add new Sales Rep.</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3 {{ Auth::user()->role_id == 2 || Auth::user()->role_id == 4 ? '' : 'hide' }}">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach ($sales_reps as $item)
                        <div class="panel panel-primary">
                            <div class="panel-heading" role="tab" id="headingOne-{{$item->id}}">
                                <h4 class="panel-title" style="margin:0px!important;">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$item->id}}" aria-expanded="true" aria-controls="collapse-{{$item->id}}">
                                        {{ ucwords($item->name) }}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse-{{$item->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne-{{$item->id}}">
                                <div class="panel-body">
                                    @php
                                        $customers = \App\Customer::where(['sales_rep_id' => $item->id])->get(); // as much as I want to put this on Model or create a relationship for this, it takes time hahaha sorry next time will do
                                    @endphp
                                    @if (count($customers) > 0)
                                        <table class="table table-striped table-bordered" width="100%">
                                            <tbody>
                                                @foreach ($customers as $customer)
                                                    <tr>
                                                        <td><a href="{{url('/admin/customers/'.$customer->id)}}">{{$customer->first_name.' '.$customer->last_name}}</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="text-center">
                                            <p>No customers for this Sales Rep.<br>Click <a href="{{route('voyager.customers.create')}}"><strong>here</strong></a> add new Customer.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="{{ Auth::user()->role_id != 5 ? 'col-md-9' : '' }}">
                <div class="col-md-12">
                    <div class="panel widget" style="margin-top:0px!important;">
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

            $('.panel-collapse').on('show.bs.collapse', function () {
                $(this).siblings('.panel-heading').addClass('active');
            });

            $('.panel-collapse').on('hide.bs.collapse', function () {
                $(this).siblings('.panel-heading').removeClass('active');
            });

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
                        console.log(result);
                        $("#customer_tbl tbody").html("");
                        $("#order_tbl tbody").html("");

                        // Customer Report
                        $("#title-customer").html(' - ' + ucwords($('#company').val()) + ' as of ' + $('input[name="year_range"]').val());
                        removeDataset(cutomersChart);
                        addDataset(cutomersChart, result.customers_data, result.customers_label, 'customers');
                        cutomersChart.update();
                        var customers_tbl = result.customers_tbl;
                        var orders_tbl = result.orders_tbl;
                        // console.log(result.data);
                        if(customers_tbl != undefined) {
                            for (let i = 0; i < customers_tbl.length; i++) {
                                const e = customers_tbl[i];
                                $("#customer_tbl tbody").append('<tr> <td>'+e.customer_name+'</td> <td>'+e.email+'</td> <td>'+e.name+'</td> <td>'+e.customer_type+'</td> </tr>');
                            }
                        }

                        // Order Report
                        $("#title-order").html(' - ' + ucwords($('#company').val()) + ' as of ' + $('input[name="year_range"]').val());
                        removeDataset(orderChart);
                        addDataset(orderChart, result.years_cnt, result.years, 'orders');
                        orderChart.update();
                        if(orders_tbl != undefined) {
                            for (let i = 0; i < orders_tbl.length; i++) {
                                const e = orders_tbl[i];
                                $("#order_tbl tbody").append('<tr> <td>'+e.file_num+'</td> <td>'+e.closer+'</td> <td>'+e.property_addr+'</td> <td>'+moment(e.submitted_date).format('MM/DD/YYYY')+'</td> <td>'+moment(e.closed_date).format('MM/DD/YYYY')+'</td> </tr>');
                            }
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
            var data = (data == undefined || data == []) ? 0 : data;
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