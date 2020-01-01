<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{$title}}</title>
<script src="//code.jquery.com/jquery-1.12.1.min.js"></script>
@if(app('env') === 'local')
    <link rel="stylesheet" href="{{ asset('css') }}/{{$css}}.css">
    <script type="text/javascript" src="{{ asset('js') }}/{{$js}}.js"></script>
@endif
@if(app('env') === 'production')
    <link rel="stylesheet" href="{{ secure_asset('css') }}/{{$css}}.css">
    <script type="text/javascript" src="{{ secure_asset('js') }}/{{$js}}.js"></script>
@endif
