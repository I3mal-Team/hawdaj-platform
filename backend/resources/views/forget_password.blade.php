<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        strong {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }

        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin: 10px 0;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            background-color: #3498db;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a.button:hover {
            background-color: #2980b9;
        }

        .instruction {
            font-size: 14px;
            color: #888;
            margin-top: 10px;
        }

        .thank-you {
            margin-top: 20px;
            font-size: 18px;
            /*color: #2ecc71;*/
        }
    </style>
</head>
<body>
<div class="container">

    <p>Hi, {{ $data['userName'] }}</p>

    <p>Please use this code to reset your password</p>

    <strong>{{ $data['code'] }}</strong>

    <p style="font-weight: bold" class="thank-you">Hawdaj Team!</p>

    <h6>Best Regards</h6>
</div>
</body>
</html>
