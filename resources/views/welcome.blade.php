@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <h3>{{ Auth::user()->name }}さん、おかえりなさい。</h3>
        {!! link_to_route('tasks.index', 'タスク一覧へ', [], ['class' => 'btn btn-lg btn-primary']) !!}
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Tasklist</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection