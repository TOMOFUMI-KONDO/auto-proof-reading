<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{$title}}</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@if(app('env') === 'local')
    <link rel="stylesheet" href="{{ asset('css') }}/{{$css}}.css">
    <script type="text/javascript" src="{{ asset('js') }}/{{$js}}.js"></script>
    <script src="{{ asset('js/jquery.cookie.js') }}"></script>
@endif
@if(app('env') === 'production')
    <link rel="stylesheet" href="{{ secure_asset('css') }}/{{$css}}.css">
    <script type="text/javascript" src="{{ secure_asset('js') }}/{{$js}}.js"></script>
    <script src="{{ secure_asset('js/jquery.cookie.js') }}"></script>
@endif
