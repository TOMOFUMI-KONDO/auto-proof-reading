<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-153292489-3"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-153292489-3');
</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
