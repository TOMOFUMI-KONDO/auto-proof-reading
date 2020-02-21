@extends('layouts.default')

@section('head')
    <script type="text/javascript">var condition_number = "{{ $condition_number }}"</script>
    @component('components.head')
        @slot('title')
            自動校正
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
            自動校正
        @endslot
    @endcomponent
@endsection

@section('main')
    <p>ここに校正前の文章が出ます。</p>
    <p id="before_rep" class="before_rep">{!! nl2br($before_rep) !!}</p>
    <p>ここに校正後の文章が出ます。</p>
    <p id="after_rep" class="after_rep">{!! nl2br($after_rep) !!}</p>
    <form method="POST" action="/" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div id="submit_type">
            {{ Form::radio('submit_type', 'file', true, ['id' => 'submit_type_file'])}}
            {{ Form::label('submit_type_file', 'ファイルをアップロードする') }}
            {{ Form::radio('submit_type', 'text', false, ['id' => 'submit_type_text'])}}
            {{ Form::label('submit_type_text', 'テキストを入力する') }}
        </div>
        <div id="upload_file" class="{{ $hide_upload_file }}">
            <p>↓校正するテキストファイルをアップロードしてください。</p>
            {{ Form::file('sentence', ['id' => 'file']) }}
        </div>
        <div id="sentence" class="{{ $hide_sentence }}">
            <p>校正する文章を入力してください。</p>
            {{ Form::textarea('sentence', old('sentence' ), ['placeholder' => 'ここに入力', 'cols' => '100px', 'rows' => '10px']) }}
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
            <div id="inputs" class="inputs">
                @for ($i = 1; $i <= $condition_number; $i++)
                    <p>{{ $i }}</p>
                    {{ Form::text("before_str$i", old("before_str$i")) }}
                    {{ Form::text("after_str$i", old("after_str$i")) }}
                    <br />
                @endfor
            </div>
            <p id="add" class="add button">入力ボックス追加</p>
            <p id="delete" class="delete button">入力ボックス削除</p>
            <p>↓校正条件を指定するcsvファイルをアップロードしてください。<span id="modal_open" class="modal_open">（csvファイルの形式について）</span></p>
            <div id="modal" class="modal">
                <div id="modal_bg" class="modal_bg"></div>
                <div id="modal_content" class="modal_content">
                    <p>【csvファイルの入力形式について】<br/>
                        区切り文字は「,」、囲み文字は「"」でファイルを作成してください。</p>
                    <img src="{{asset('img/csv_example.jpg')}}" alt="csvファイルの例"/>
                    <p id="modal_close" class="modal_close">閉じる</p>
                </div>
            </div>
            {{ Form::file('condition_file', ['id' => 'condition_file', 'class' => 'condition_file']) }}
        </div>
        {{ Form::submit('校正する') }}
    </form>
@endsection
