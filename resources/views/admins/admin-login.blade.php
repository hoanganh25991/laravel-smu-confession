@extends('layouts.app')

@section('content')
    <div style="text-align: center">
        <h1>Login</h1>
        <div style="display: block; margin: 0px auto 20px auto;">
            <a
                class="btn btn-default"
                role="button"
                href="{{ $loginUrl }}">Connect Facebook</a>
        </div>
    </div>
@endsection