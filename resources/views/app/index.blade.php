@extends('layouts.default')

@section('head')
    @component('components.head',
      ['title' => '自動校正サービス',
      'cssFiles' => ['app', 'plugin/toastr/toastr.min'],
      'jsFiles' => ['app', 'plugin/toastr/toastr.min']]
    )
    @endcomponent

    {{--        コントローラから渡された変数をjavascriptに渡すために変数を定義して値を代入--}}
    <script type="text/javascript">
        let $app_env = "{{ $app_env }}";
        let $condition_number = "{{ $condition_number }}";
        let $file_name = "{{ $file_name }}";
        let $is_docx = "{{ $is_docx }}";
    </script>
@endsection

@section('header')
    @component('components.header',
      ['h1' => '自動校正サービス']
    )
    @endcomponent
@endsection

@section('main')
    <article>
        @if ($file_name !== '')
            <section class="calibrated">
                <h2>校正前後の文章</h2>
                <section class="before_rep">
                    <h3>校正前</h3>
                    <p>{!! nl2br($before_rep) !!}</p>
                </section>
                <section id="after_rep" class="after_rep">
                    <h3>校正後</h3>
                    <p>{!! nl2br($after_rep) !!}</p>
    {{--                文章を校正した時だけ表示--}}
                        <div>
                            <button type="button" id="copy">クリップボードにコピー</button>
                            <button type="button" id="download">テキストファイルをダウンロード</button>
                        </div>
                </section>
            </section>
        @endif
        <section class="form">
            <h2>文章を校正する</h2>
            @if (app('env') === 'production')
                <form method="POST" action="https://tomo-kon-service.com/auto-proof-reading/" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
            @endif
            @if (app('env') === 'local')
                {{ Form::open(['files' => true]) }}
            @endif
                <section class="sentence">
                    <h3>校正する文章の入力<br class="smp" /><span>（ファイルサイズは1.5MBまで）</span></h3>
                    @if ($errors->has('sentence'))
                        <p class="error bold">{{ $errors->first('sentence') }}</p>
{{--                        <p class="error bold">{{ $errors->first('sentence') }}</p>--}}
                    @endif
                    <div id="submit_type" class="submit_type">
                        <div>
                            {{ Form::radio('submit_type', 'file', true, ['id' => 'submit_type_file']) }}
                            {{ Form::label('submit_type_file', 'ファイルを提出') }}
                        </div>
                        <div>
                            {{ Form::radio('submit_type', 'text', false, ['id' => 'submit_type_text']) }}
                            {{ Form::label('submit_type_text', 'テキストを入力') }}
                        </div>
                    </div>
                    <div id="file_upload" class="file_upload {{ $hide_file_upload }}">
                        {{ Form::file('sentence', ['id' => 'file']) }}
                    </div>
                    <div id="text_upload" class="text_upload {{ $hide_text_upload }}">
                        {{ Form::textarea('sentence', old('sentence' ), ['placeholder' => 'ここに入力']) }}
                    </div>
                </section>
                <section class="conditions">
                    <h3>校正条件の指定</h3>
                    <p>※テキストとcsvの両方で指定することもできます。</p>
                    <section class="text_conditions">
                        <h4>テキストで指定</h4>
                        <div class="regex_text">
                            {{ Form::checkbox('regex_text', 'yes', null, ['id' => 'regex_text']) }}
                            {{ Form::label('regex_text', '正規表現を使う') }}
                        </div>
                        <div class="edit">
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
                                    <i class="fas fa-arrow-right"></i>
                                    {{ Form::text("after_str$i", old("after_str$i")) }}
                                    <br />
                                @endfor
                            </div>
                        </div>
                    </section>
                    <section class="csv_conditions">
                        <h4>csvファイルで指定</h4>
                        <div class="regex_csv">
                            {{ Form::checkbox('regex_csv', 'yes', null, ['id' => 'regex_csv']) }}
                            {{ Form::label('regex_csv', '正規表現を使う') }}
                        </div>
                        <p id="modal_open" class="modal_open">（ <span>csvファイルの形式について</span> ）</p>
                        <div id="modal" class="modal">
                            <div id="modal_bg" class="modal_bg"></div>
                            <div id="modal_content" class="modal_content">
                                <p>【<span class="bold">csvファイルの入力形式】</span></p>
                                <div>
                                    <p>区切り文字は「,」、囲み文字は「"」でファイルを作成してください。<br/>
                                        下記の例だと、「ぼく」が「おれ」に変換されます。</p>
                                    <img src="{{asset('img/csv_example.jpg')}}" alt="csvファイルの記入例"/>
                                </div>
                                <p id="modal_close" class="modal_close"><span>閉じる</span></p>
                            </div>
                        </div>
                        <div class="condition_file">
                            {{ Form::file('condition_file') }}
                        </div>
                    </section>
                </section>
                {{ Form::submit('校正する', ['class' => 'submit']) }}
            {{ Form::close() }}
        </section>
    </article>
@endsection
