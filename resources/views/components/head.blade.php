@if(app('env') === 'production')

        <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-153292489-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-153292489-3');
    </script>
@endif
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="keywords" content="校正,文章校正,校正 自動,校正 無料,校閲,文章 チェック,文章 添削,word 校正,word スペルチェック,ワード スペルチェック,ワード 文章 校正,テキスト 校正,文書 校正,校正 ツール,原稿 校正,文章 校正 ソフト,文章 チェック ツール,web 校正">
<meta name="description" content="文章を自動で校正するサービスです。任意の校正条件に従って文章を校正することができます。テキストファイルやWordファイルの内容を校正することも可能です。">
<meta property="og:title" content="自動校正サービス">
<meta property="og:description" content="文章を自動で校正するサービスです。任意の校正条件に従って文章を校正することができます。テキストファイルやWordファイルの内容を校正することも可能です。">
<title>{{$title}}</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@if(app('env') === 'local')
    @foreach ($cssFiles as $cssFile)
        <link rel="stylesheet" href="{{ asset('css') }}/{{$cssFile}}.css">
    @endforeach
    @foreach ($jsFiles as $jsFile)
        <script type="text/javascript" src="{{ asset('js') }}/{{$jsFile}}.js"></script>
    @endforeach
    <script src="{{ asset('js/jquery.cookie.js') }}"></script>
@endif
@if(app('env') === 'production')
    @foreach ($cssFiles as $cssFile)
        <link rel="stylesheet" href="{{ secure_asset('css') }}/{{$cssFile}}.css">
    @endforeach
    @foreach ($jsFiles as $jsFile)
        <script type="text/javascript" src="{{ secure_asset('js') }}/{{$jsFile}}.js"></script>
    @endforeach
    <script src="{{ secure_asset('js/jquery.cookie.js') }}"></script>
@endif
