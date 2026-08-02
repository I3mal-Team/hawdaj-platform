<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Car Model File</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container mt-5">
    <h2 class="text-center mb-3">Car Model File</h2>

    <div class="d-flex justify-content-end mb-4">
        <a class="btn btn-primary" href="{{ URL::to('#') }}">Export to PDF</a>
    </div>

    <table class="table table-bordered mb-5">
        <thead>
        <tr class="table-danger">
            <th scope="col">#</th>
            <th scope="col">siteID</th>
            <th scope="col">CamID</th>
            <th scope="col">Car</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
        </tr>
        </thead>
        <tbody>

        @foreach($list as $data)
            <tr>
                <th scope="row">{{ $data['id'] }}</th>
                <td>{{ $data['site_id'] }}</td>
                <td>{{ $data['camID'] }}</td>
                <td>{{ $data['car'] }}</td>
                <td>{{ $data['date'] }}</td>
                <td>{{ $data['time'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>

</html>
