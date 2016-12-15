@extends('layouts.app')

@section('content')
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
                @if(!empty($post->photo_path))
                    <div style="position: relative; width: 350px" class="pull-left">
                        <p>{{ $post->content }}</p>
                    </div>
                    <div style="position: relative; width: 184px" class="pull-right">
                        <img class="img-responsive" src="{{ asset($post->photo_path) }}" height="150">
                    </div>
                @else
                    {{--<p>{{ $breakNewLineContent }}</p>--}}
                    <?php $breakNewLineContent = preg_replace("/\r\n|\r|\n/", '<br/>', $post->content); ?>
                    <p><?php echo $breakNewLineContent; ?></p>
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
    </style>
    <script>
        window.addEventListener('click', verifyPost);
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
    </script>
@endsection