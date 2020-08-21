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
                <th>Name</th>
                <th>City</th>
                <th>State</th>
                <th>Company</th>
                <th>Email</th>
                <th>Customer Type</th>
                <th>Sales Rep</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ ucwords($row->first_name) . ' ' . ucwords($row->last_name) }}</td>
                    <td>{{ $row->city }}</td>
                    <td>{{ $row->state }}</td>
                    <td>{{ $row->company }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->customer_type }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>