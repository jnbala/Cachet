<html>
<head>
    <title>{{ Setting::get('app_name') }}</title>
</head>
<body>
    <p>Plase confirm your subscription to {{ Setting::get('app_name') }}.</p>

    <p>{{ $link }}</p>

    Thank you,
    <br />
    {{ Setting::get('app_name') }}
    @if(Setting::get('show_support'))
    <p>{!! trans('cachet.powered_by', ['app' => Setting::get('app_name')]) !!}</p>
    @endif
</body>
</html>
