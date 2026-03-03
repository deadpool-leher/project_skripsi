<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ice Cream Treman</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f7fb;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 50px 20px;
            text-align: center;
        }

        .hero-title {
            font-size: 52px;
            font-weight: bold;
            background: linear-gradient(90deg,#ff00aa,#5a5cff);
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #555;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 30px;
            background: linear-gradient(90deg,#ff00aa,#5a5cff);
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
        }

        .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            width: 240px;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .card p {
            margin: 0;
            color: #666;
        }
    </style>
</head>

<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
