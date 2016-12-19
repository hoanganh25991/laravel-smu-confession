@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ url('admin/post') }}" enctype="multipart/form-data" id="postForm">
        <input type="hidden" name="role" value="admin">
        <div class="form-group">
            <textarea
                    name="content"
                    rows="3"
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
                <div class="pull-right" style="position: relative">
                    <button type="submit" class="btn btn-default">SUBMIT</button>
                </div>
            </div>
        </div>
    </form>
    @if($posts->count() == 0)
        <h1>Hi admin</h1>
        <h1>No post ☺, <a href="https://www.google.com/search?q=Atari+Breakout&tbm=isch">ping pong?</a></h1>
    @endif
    @foreach($posts as $post)
        <div style="margin-bottom: 20px; display: block; padding: 5px;" class="row post-row">
            <div class="row">
                <span class="h4">Confession #{{$nextConfessionId++}}</span>
                <span style="margin-left: 20px">Posted on {{ $post->created_at }}</span>
            </div>
            <div class="row">
                @php
                    $postContent = htmlspecialchars($post->content);
                    $postContentDiv = "<textarea name='post_content' class='post-content'>{$postContent}</textarea>";
                @endphp
                @if(!empty($post->photo_path))
                    <div style="position: relative; width: 350px" class="pull-left">
                        {!! $postContentDiv !!}
                    </div>
                    <div style="position: relative; width: 184px" class="pull-right">
                        <img class="img-responsive" src="{{ asset($post->photo_path) }}" height="150">
                    </div>
                @else
                    {!! $postContentDiv !!}
                @endif
            </div>
            <div class="row">
                <input type="hidden" name="postId" value="{{ $post->id }}">
                <button class="btn btn-default" action="approve" role="verifyPost">Approve</button>
                <button class="btn btn-default" action="discard" role="verifyPost">Discard</button>
            </div>
        </div>
    @endforeach
    <div class="row" style="text-align: center">
        {{ $posts->links() }}
    </div>
    <style>
        .post-row {
            background-color: #eee;
        }

        p {
            word-wrap: break-word;
        }

        .post-content {
            width: 100%;
            outline: none;
            resize: none;
            overflow: auto;
            white-space: normal;
        }
    </style>
    <script>
        window.addEventListener('click', verifyPost);
        $('textarea').on('change keyup keydown paste cut', function () {
            $(this).height(0).height(this.scrollHeight);
        }).change();
        function verifyPost(e){
            let btn = $(e.target);
            if(!btn.is('button[role="verifyPost"]')){
                return;
            }
            console.log('btn verifyPost click');
            let action = btn.attr('action');
            let parentDiv = btn.parents('div.post-row');
            let postId = btn.siblings('input[name="postId"]').val();
            $.post({
                url: '{{ url('admin') }}',
                data: {
                    action,
                    postId
                },
                success(res){
                    console.log(res);
                    parentDiv.remove();
                },
                error(err){
                    console.log(err);
                }
            });
        }

        let postFormOptions = {
            url: "{{ url('admin/post') }}",
            type: 'post',
            succes(res){
                console.log(res);
            },
            error(res){
                console.log(res);
            }
        };
    </script>
@endsection