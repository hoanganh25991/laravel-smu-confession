@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Post</div>

                    <form action="{{ url('post') }}" method="POST" enctype="multipart/form-data">
                        <input type="text" name="status" placeholder="What in your mind?">
                        <input type="file" name="photo" placeholder="Upload the most meaningful photo">
                        <button>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
