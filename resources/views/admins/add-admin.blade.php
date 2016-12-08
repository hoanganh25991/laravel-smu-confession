@extends('layouts.app')

@section('content')
    @if($userRoles->count() == 0)
        <h1>Hi admin</h1>
        <h1>No admin request ☺, <a href="https://www.google.com/search?q=Atari+Breakout&tbm=isch">ping pong?</a></h1>
    @endif
    @foreach($userRoles as $userRole)
        <div style="margin-bottom: 20px; display: block; padding: 5px;" class="row post-row">
            <div class="row">
                <span class="h4">Name: {{$userRole->name}}</span>
            </div>
            <div class="row">
                <span ckass="show">Facebook Id: {{ $userRole->provider_id }}</span>
                <span class="show">Current role: {{ $userRole->role }}</span>
                <span class="show">Created at: {{ $userRole->created_at }}</span>
            </div>
            <div class="row">
                <input type="hidden" name="postId" value="{{ $userRole->id }}">
                <button class="btn btn-default" action="approve" role="verifyPost">Approve</button>
                <button class="btn btn-default" action="discard" role="verifyPost">Discard</button>
            </div>
        </div>
    @endforeach
    <style>
        .post-row {
            background-color: #eee;
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
            let userRoleId = btn.siblings('input[name="postId"]').val();
            $.post({
                url: '{{ url('admin/add') }}',
                data: {
                    action,
                    userRoleId
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