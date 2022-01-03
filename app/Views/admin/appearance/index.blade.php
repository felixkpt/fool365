@extends($folder.'layouts.layout')
@include($folder.'notifications')

@section('title')
Appearance
@endsection('title')
@section('action')
@endsection('action')

@include($folder.'layouts.sections')


@section('content')

        @yield('header')

        @yield('notifications')

        <div class="row">
            <div class="col-12">
                Abs Abs Abs Abs Abs Abs

                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="{{ url('').dash_uri() }}/appearance/menus">Menus</a></li>
                </ul>

            </div>
        </div>

@endsection('content')
