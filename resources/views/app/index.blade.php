@extends('layouts.default')

@section('head')
    <script type="text/javascript">var condition_number = "{{ $condition_number }}"</script>
    @component('components.head')
        @slot('title')
            auto-proof-reading
        @endslot
        @slot('css')
            app
        @endslot
        @slot('js')
            app
        @endslot
    @endcomponent
@endsection

@section('header')
    @component('components.header')
        @slot('h1')
            自動校正サービス
        @endslot
    @endcomponent
@endsection

@section('content')
    <form method="POST" action="/">
        {{ csrf_field() }}
        <div id="sentence">
            <p>校正する文章を入力してください。</p>
            <label><textarea name="sentence" placeholder="ここに入力" cols="100px" rows="10px">{{ old('sentence') }}</textarea></label>
        </div>
        <div id="conditions">
            <div class="description">
                <p>校正したい文字を入力してください。</p>
                <div>
                    <p id="erase" class="button">校正条件を全消去</p>
                </div>
                <div>
                    <p>校正前</p>
                    <p>校正後</p>
                </div>
            </div>
            @for ($i = 0; $i < $condition_number; $i++)
                <label class="before_str"><input type="text" name="before_str{{$i}}" value="{{ old('before_str' . $i) }}"></label>
                <label class="after_str"><input type="text" name="after_str{{$i}}" value="{{ old('after_str' . $i) }}"></label>
            @endfor
            <p id="add" class="button">入力ボックス追加</p>
        </div>
        <input type="submit" value="校正する">
    </form>

    <p>ここに校正前の文章が出ます。</p>
    <p id="before_rep">{!! nl2br($before_rep) !!}</p>
    <p>ここに校正後の文章が出ます。</p>
    <p id="after_rep">{!! nl2br($after_rep) !!}</p>
@endsection
