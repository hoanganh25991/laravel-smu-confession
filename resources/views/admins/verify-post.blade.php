@extends('layouts.app')

@section('content')
    <h1>Loop all status post here</h1>
    <div>
        @foreach($posts as $post)
            <div>
                <p>{{ $post->content }}</p>
                @if(!empty($post->photo_path))
                    <img src="{{ asset($post->photo_path) }}" height="100">
                @endif
                <input type="hidden" name="postId" value="{{ $post->id }}">
                <div>
                    <button id="btnAcceptPost" post-id="{{ $post->id }}">accept</button>
                    <button id="btnRemovePost">remove</button>
                </div>
            </div>
        @endforeach
    </div>
    {{ $posts->links() }}
    <li>List with paging</li>
    <li>List with excert, brief content</li>
    this just the javascript code, not php code
    on client side, hide/unhid to read all the content
    <li>Action accept|pending|trash</li>
    explain accept: let this post on page, review here this post ID
                    how it look when on page
    pending       : it current status
    trash         : never look back again

    <script>
        let btnAcceptPost = $('#btnAcceptPost');
        let btnRemovePost = $('#btnRemovePost');

        let postId = $('input[name="postId"]').val();

        btnAcceptPost.on('click', function(){
            console.log('btnAcceptPost click');
            $.post({
                url: '{{ url('admin') }}',
                data: {
                    action: 'accept',
                    postId
                },
                success(res){
                    console.log(res);
                },
                error(err){
                    console.log(err);
                }
            });
        });

        btnRemovePost.on('click', function(){
            $.post({
                url: '{{ url('admin') }}',
                data: {
                    action: 'remove',
                    postId
                },
                success(res){
                    console.log(res);
                },
                error(err){
                    console.log(err);
                }
            });
        });
    </script>
@endsection