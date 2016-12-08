@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Post</div>
                    {{--for captcha come in--}}
                    <link href="{{ captcha_layout_stylesheet_url() }}" type="text/css" rel="stylesheet">

                    <form method="POST" enctype="multipart/form-data">
                        <input type="text" name="content" placeholder="What in your mind?"
                               value="{{ !empty($post) ? $post->content : ''  }}">
                        <input type="file" name="photo" placeholder="Upload the most meaningful photo">
                        {!! captcha_image_html('ExampleCaptcha') !!}
                        <input type="text" id="CaptchaCode" name="CaptchaCode">
                        <button>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
