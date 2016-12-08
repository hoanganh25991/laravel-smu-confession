<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confessing In SMU Confessions</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/main.css') }}">
    <script src="https://www.google.com/recaptcha/api.js?render=onload"></script>
    <script src="{{ url('js/jquery.min.js') }}"></script>
</head>
<body>
<div class="container">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <a href="{{ url('') }}">
                <img class="img-responsive" src="{{ url('images/smu_confessions_header.png') }}">
            </a>
        </div>
        <div class="row" style="background-color: white">
            <div class="col-md-10 col-md-offset-1" style="padding-top: 30px">
                @yield('content')
            </div>
        </div>
        <div class="row">
            @yield('footer')
        </div>
    </div>
</div>
</body>
</html>