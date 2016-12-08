<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confessing In SMU Confessions</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://confessing.in/css/normalize.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('css/main.css') }}">
    <script src="https://www.google.com/recaptcha/api.js?render=onload"></script>
</head>
<body>
<div class="container">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <a href="{{ route('home') }}">
                <img class="img-responsive" src="{{ url('images/smu_confessions_header.png') }}">
            </a>
        </div>
        <div class="row white-template">
            <div class="col-md-10 col-md-offset-1" style="padding-top: 20px">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>