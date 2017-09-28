@extends('layout.default')
@section('title','静态页首页')
@section('content')
    @if(\Illuminate\Support\Facades\Auth::check())
        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('status.create')
                </section>
                <h3>微博列表</h3>
                @include('shared.feed',['feed_items'=>$feed_items])
            </div>
            <aside class="col-md-4">
                <section class="user_info">
                    @include('shared.user_info',['user'=>\Illuminate\Support\Facades\Auth::user()])
                </section>
            </aside>
        </div>
    @else
        <div class="jumbotron">
            <h1>Hello Laravel</h1>
            <p class="lead">
                你现在所看到的是 <a href="https://laravel-china.org/laravel-tutorial/5.1">Laravel 入门教程</a> 的项目主页。
            </p>
            <p>
                一切，将从这里开始。
            </p>
            <p>
                <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
            </p>
        </div>
    @endif
@endsection