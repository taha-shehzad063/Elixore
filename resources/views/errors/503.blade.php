{{-- resources/views/errors/503.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #71cd14;
        }
        .message {
            font-size: 24px;
            margin-top: -20px;
            color: #333;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #71cd14;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        a:hover {
            background-color: #5ab211;
        }
    </style>
</head>
<body>
    <div class="error-code">503</div>
    <div class="message">Service is temporarily unavailable. Please try again later.</div>
    <a href="{{ url('/') }}">Go Back Home</a>
</body>
</html>
