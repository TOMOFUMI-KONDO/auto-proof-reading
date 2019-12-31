@extends('layouts.default')

@section('head')
    @component('components.head')
        @slot('title')
            auto-proof-reading
        @endslot
        @slot('css')
            app
        @endslot
    @endcomponent
@endsection

@section('header')
    @component('components.header')
        @slot('h1')
            auto-proof-reading
        @endslot
    @endcomponent
@endsection

@section('content')
    <p>校正する文章を入力してください。</p>
    <form method="POST" action="/">
        {{ csrf_field() }}
        <label>
            <textarea name="sentence" placeholder="ここに入力" cols="100px" rows="10px"></textarea>
        </label>
        <input type="submit" value="送信">
    </form>
    <p>{!! $sentence !!}</p>
@endsection
