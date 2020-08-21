<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <style>
        body { font-family: verdana, sans-serif;} 
        table {
            margin-bottom: 2em;
        }

        thead {
            background-color: #eeeeee;
        }

        tbody {
            background-color: #ffffee;
        }

        th,td {
            padding: 3pt;
        }

        table.separate {
            border-collapse: separate;
            border-spacing: 5pt;
            border: 3pt solid #33d;
        }

        table.separate td {
            border: 2pt solid #33d;
        }

        table.collapse {
            border-collapse: collapse;
            border: 1pt solid black;  
        }

        table.collapse td {
            border: 1pt solid black;
        }
    </style>
</head>
<body>
	<h3>Customers</h3>
	<table width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>File Number</th>
                <th>Order Type</th>
                <th>Submitted Date</th>
                <th>Closed Date</th>
                <th>Closer</th>
                <th>Property Address</th>
                <th>Status</th>
                <th>Sales Rep</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ ucwords($row->customer) }}</td>
                    <td>{{ $row->file_num }}</td>
                    <td>{{ $row->order_type }}</td>
                    <td>{{ $row->submitted_date }}</td>
                    <td>{{ $row->closed_date }}</td>
                    <td>{{ $row->closer }}</td>
                    <td>{{ $row->property_addr }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>