@extends('layouts.default')

@section('head')
{{--    コントローラから受け取った$condition_numberをjavascriptに渡す。--}}
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
    <article>
        <section class="calibrated">
            <h2>校正前後の文章</h2>
            <section class="before_rep">
                <h3>校正前</h3>
                <p>{!! nl2br($before_rep) !!}</p>
            </section>
            <section class="after_rep">
                <h3>校正後</h3>
                <p>{!! nl2br($after_rep) !!}</p>
            </section>
        </section>
        <section class="form">
            <h2>文章を校正する</h2>
            <form method="POST" action="/" enctype="multipart/form-data">
                @csrf
                <section class="sentence">
                    <h3>校正する文章の入力</h3>
                    <div id="submit_type" class="submit_type">
                        {{ Form::radio('submit_type', 'file', true, ['id' => 'submit_type_file'])}}
                        {{ Form::label('submit_type_file', 'ファイルをアップロードする') }}
                        {{ Form::radio('submit_type', 'text', false, ['id' => 'submit_type_text'])}}
                        {{ Form::label('submit_type_text', 'テキストを入力する') }}
                    </div>
                    <div id="file_upload" class="file_upload {{ $hide_file_upload }}">
                        {{ Form::file('sentence', ['id' => 'file']) }}
                    </div>
                    <div id="text_upload" class="text_upload {{ $hide_text_upload }}">
                        {{ Form::textarea('sentence', old('sentence' ), ['placeholder' => 'ここに入力', 'cols' => '100px', 'rows' => '10px']) }}
                    </div>
                </section>
                <section class="conditions">
                    <h3>校正条件の指定</h3>
                    <section class="text_conditions">
                        <h4>テキストで指定</h4>
                        <div>
                            <div class="buttons">
                                <button id="erase" type="button">校正条件を全消去</button>
                                <button id="add" type="button">入力ボックス追加</button>
                                <button id="delete" type="button">入力ボックス削除</button>
                            </div>
                            <div id="inputs" class="inputs">
                                <div>
                                    <p>校正前</p>
                                    <p>校正後</p>
                                </div>
                                @for ($i = 1; $i <= $condition_number; $i++)
                                    {{ Form::text("before_str$i", old("before_str$i")) }}
                                    <i class="fas fa-arrow-right" style="width: 20px"></i>
                                    {{ Form::text("after_str$i", old("after_str$i")) }}
                                    <br />
                                @endfor
                            </div>
                        </div>
                    </section>
                    <section class="csv_conditions">
                        <h4>csvファイルで指定</h4>
                        <p id="modal_open" class="modal_open">（<span>csvファイルの形式について</span>）</p>
                        <div id="modal" class="modal">
                            <div id="modal_bg" class="modal_bg"></div>
                            <div id="modal_content" class="modal_content">
                                <p>【<span class="bold">csvファイルの入力形式】</span></p>
                                <div>
                                    <p>区切り文字は「,」、囲み文字は「"」でファイルを作成してください。<br/>
                                        下記の例だと、「ぼく」が「おれ」に変換されます。</p>
                                    <img src="{{asset('img/csv_example.jpg')}}" alt="csvファイルの記入例"/>
                                </div>
                                <p id="modal_close" class="modal_close">閉じる</p>
                            </div>
                        </div>
                        {{ Form::file('condition_file', ['class' => 'condition_file']) }}
                    </section>
                </section>
                {{ Form::submit('校正する', ['class' => 'submit'])}}
            </form>
        </section>
    </article>
@endsection
