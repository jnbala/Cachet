@extends('layout.master')

@section('content')
    @if($bannerImage = Setting::get('app_banner'))
    <div class="row app-banner">
        <div class="col-md-12 text-center">
            <?php $bannerType = Setting::get('app_banner_type') ?>
            @if($appUrl = Setting::get('app_domain'))
            <a href="{{ $appUrl }}"><img src="data:{{ $bannerType }};base64, {{ $bannerImage}}" class="banner-image img-responsive"></a>
            @else
            <img src="data:{{ $bannerType }};base64, {{ $bannerImage}}" class="banner-image img-responsive">
            @endif
        </div>
    </div>
    @endif

    @if($aboutApp)
    <div class="about-app">
        <h1>{{ trans('cachet.about_this_site') }}</h1>
        <p>{!! $aboutApp !!}</p>
    </div>
    @endif

    @include('partials.dashboard.errors')

    <h1>Subscribe to updates</h1>
    <form action="{{ route('subscribe') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label for="email">
            Subscribe to email updates
        </label>
        <input class="form-control" type="text" name="email">
        <input class="btn" type="submit" value="Subscribe">
    </form>
@stop
