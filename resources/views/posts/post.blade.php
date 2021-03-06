@extends('layouts.app')

@section('content')
<div class="row">
    <form method="POST" action="{{ route('post') }}" enctype="multipart/form-data">
        <div class="form-group">
                            <textarea
                                    name="content"
                                    rows="10"
                                    class="form-control"
                                    placeholder="Your little confession here"
                            ></textarea>
        </div>
        <div class="form-group">
            <div class="input-group">
                <input type="file"
                       name="photo"
                       class="form-control">
                <span class="input-group-addon">Upload</span>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-8">
                    <div class="g-recaptcha"
                         data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE') }}"></div>
                </div>
                <div class="col-md-4">
                    <div class="pull-right" style="position: relative">
                        <button type="submit" class="btn btn-default" style="height: 74px;">SUBMIT
                            LIAO*
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div style="margin-top: 75px">
    <hr>
    <div class="row">
        <p>*By submitting, you agree to allow your content to be reposted and distributed on SMU Confessions
            Page and its affiliates. Otherwise, what's the point of submitting, ah?</p>
    </div>
</div>
@endsection