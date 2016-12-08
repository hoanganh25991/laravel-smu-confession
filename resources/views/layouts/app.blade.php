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
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <div class="row">
            <a href="{{ route('home') }}">
                <img class="img-responsive" src="{{ url('images/smu_confessions_header.png') }}">
            </a>
        </div>
        <div class="row white-template">
            <div class="col-md-10 col-md-offset-1 col-xs-12">
                <div class="row" style="margin-top: 50px">
                    <form method="POST" action="">
                        <div class="form-group">
                            <textarea name="confessing-in" rows="10"
                                      class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" class="form-control">
                                <span class="input-group-addon">Upload</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8 col-xs-12">
                                    <div class="g-recaptcha" data-sitekey="6LfRNA4UAAAAACu43cey18hR5OxgHJ40ebtRpOA8"></div>
                                </div>
                                <div class="col-md-4 pull-right">
                                    <div class="pull-right" style="position: relative">
                                        <button type="submit" class="btn btn-default" style="height: 74px;">SUBMIT LIAO*</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="row">
                </div>
            </div>
        </div>

    </div>
    <div class="row" id="confessions-header">
        <h3 class="col-xs-12 col-md-8 col-md-offset-2">Past confessions</h3>
    </div>
    <div class="row" id="confessions">

    </div>
</div>
</body>
</html>