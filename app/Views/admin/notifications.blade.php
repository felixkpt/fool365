<?php global $request; ?>
@section('notifications')
    @if (\Session::has('light'))
    <div class="alert alert-light border">
        {!! \Session::get('light') !!}
    </div>
@endif
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    @if (\Session::has('info'))
        <div class="alert alert-info">
            {!! \Session::get('info') !!}
        </div>
    @endif
@if (Session::has('warning'))
    <div class="alert alert-warning" role="alert">
        {!! \Session::get('warning') !!}
    </div>
@endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There is a problem.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection('notifications')
