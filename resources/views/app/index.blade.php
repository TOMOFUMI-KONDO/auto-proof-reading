@extends('layouts.default')

@section('head')
    <script type="text/javascript">var condition_number = "{{ $condition_number }}"</script>
    @component('components.head')
        @slot('title')
            自動校正サービス
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

@section('main')
    <p class="bold">ここに校正前の文章が出ます。</p>
    <p id="before_rep" class="before_rep">{!! nl2br($before_rep) !!}</p>
    <p class="bold">ここに校正後の文章が出ます。</p>
    <p id="after_rep" class="after_rep">{!! nl2br($after_rep) !!}</p>
    <form method="POST" action="/" enctype="multipart/form-data">
        {{ csrf_field() }}
        <p class="bold">校正する文章の提出形式を選択</p>
        <div id="submit_type" class="submit_type">
            {{ Form::radio('submit_type', 'file', true, ['id' => 'submit_type_file'])}}
            {{ Form::label('submit_type_file', 'ファイルをアップロードする') }}
            {{ Form::radio('submit_type', 'text', false, ['id' => 'submit_type_text'])}}
            {{ Form::label('submit_type_text', 'テキストを入力する') }}
        </div>
        <div id="file_upload" class="file_upload {{ $hide_upload_file }}">
            {{ Form::file('sentence', ['id' => 'file']) }}
        </div>
        <div id="sentence" class="sentence {{ $hide_sentence }}">
            {{ Form::textarea('sentence', old('sentence' ), ['placeholder' => 'ここに入力', 'cols' => '100px', 'rows' => '10px']) }}
        </div>
        <div id="conditions" class="conditions">
            <p class="bold">校正条件を入力してください。</p>
            <div class="input_conditions">
                <div class="buttons">
                    <p id="erase" class="button">校正条件を全消去</p>
                    <p id="add" class="add button">入力ボックス追加</p>
                    <p id="delete" class="delete button">入力ボックス削除</p>
                </div>
                <div id="inputs" class="inputs">
                    <div>
                        <p>校正前</p>
                        <p>校正後</p>
                    </div>
                    @for ($i = 1; $i <= $condition_number; $i++)
{{--                        <p class="input_number">{{ $i }}</p>--}}
                        {{ Form::text("before_str$i", old("before_str$i")) }}
                        <i class="fas fa-arrow-right" style="20px"></i>
                        {{ Form::text("after_str$i", old("after_str$i")) }}
                        <br />
                    @endfor
                </div>
            </div>
            <div class="csv_upload">
                <p>
                    校正条件を指定するcsvファイルのアップロード
                    <span id="modal_open" class="modal_open">（csvファイルの形式について）</span>
                </p>
                <div id="modal" class="modal">
                    <div id="modal_bg" class="modal_bg"></div>
                    <div id="modal_content" class="modal_content">
                        <p>【csvファイルの入力形式について】</p>
                        <p>区切り文字は「,」、囲み文字は「"」でファイルを作成してください。<br/>
                            下記の例だと、「ぼく」が「おれ」に変換されます。</p>
                        <img src="{{asset('img/csv_example.jpg')}}" alt="csvファイルの例"/>
     :                   <p id="modal_close" class="modal_close">閉じる</p>
                    </div>
                </div>
                {{ Form::file('condition_file', ['id' => 'condition_file', 'class' => 'condition_file']) }}
            </div>
        </div>
        {{ Form::submit('校正する', ['class' => 'submit button'])}}
    </form>
@endsection
