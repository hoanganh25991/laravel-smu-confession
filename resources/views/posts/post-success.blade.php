@extends('layouts.app')

@section('content')
    <div style="text-align: center">
        <div class="row">
            <h1>Thank you for your confession.</h1>
            <p>May the almighty GPA Deity smile kindly upon you.</p>
        </div>

        <div class="row" style="padding: 20px 0px;">
            <div style="display: block; width: 264px; margin: 0px auto 20px auto;">
                <a class="btn btn-lg btn-default btn-block"
                   role="button"
                   href="https://www.facebook.com/SMUConfessionsPage/">
                    Visit SMU Confessions Page</a>
            </div>

            <div style="display: block; width: 264px; margin: 0px auto 20px auto;">
                <a class="btn btn-lg btn-default btn-block"
                   role="button"
                   href="{{ route('post') }}">
                    Confess Again</a>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div style="background-color: white; text-align: center;">
        <p><a href="by.originally.us/busleh/redir">Download</a> SG BusLeh and get to your presentation on time!</p>
        <img class="img-responsive" src="{{ url('images/sg-busleh.png') }}">
    </div>
@endsection