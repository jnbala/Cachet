Plase confirm your subscription to {{ Setting::get('app_name') }}.

{{ $link }}

Thank you,
{{ Setting::get('app_name') }}
@if(Setting::get('show_support'))
{!! trans('cachet.powered_by', ['app' => Setting::get('app_name')]) !!}
@endif
