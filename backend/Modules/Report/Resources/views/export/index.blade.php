<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$list['title']}}</title>
    <style>
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }
    </style>
</head>

<body>
<div class="container">
    <h2 style='text-align: center; padding-bottom: 5px; text-decoration: underline'>{{$list['title']}}</h2>
    <h3 style='text-align: center; padding-bottom: 5px;'>
        Start : {{$list['start']}} <span style='margin-left: 15px'>End : {{$list['end']}}</span>
    </h3>
    <table class="styled-table">
        <thead>
        <tr class="active-row">
            @foreach(array_keys($list['data'][0]) as $columns)
                <th scope="col">{{$columns}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>

        @foreach($list['data'] as $key=> $data)
            <tr>
                @foreach($data as $key2 => $columns)
                    <th scope="row" style='min-width: 50px'>{{ $columns }}</th>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>

</html>
