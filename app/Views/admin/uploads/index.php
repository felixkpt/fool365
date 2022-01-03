@extends($folder.'layouts.layout')
@section('title')
    Media Libray
@endsection('title')
@section('action')
    <a class="btn btn-outline-success btn-sm" href="{{ url('') }}{{ dash_uri() }}/uploads?action=upload">Add New</a>
@endsection('action')

@include($folder.'layouts.sections')
@include($folder.'notifications')
@include($folder.'tools.uploads-library')


@section('content')

    @yield('header')

    <div class="row">
        <div class="col-12">
            @yield('notifications')
            @yield('uploads')
        </div>
    </div>

@endsection('content')
