@extends($folder.'layouts.layout')
@include($folder.'notifications')

@section('title')
    Menus
@endsection('title')
@section('action')
    <a class="btn btn-outline-success btn-sm" href="{{ url('') }}{{ dash_uri() }}/appearance/menus?action=create">Add New</a>
@endsection('action')

@include($folder.'layouts.sections')


@section('content')

    @yield('header')

    @yield('notifications')

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    @if(@$items[0])

                        Select a menu&nbsp; <form>

                <div class="form-group">
                    <div class="form-control h-100">
                            <select name="menu">
                        @forelse($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @empty
                        @endforelse
                    </select>
                        <input type="hidden" name="action" value="edit">

                        <button type="submit" class="btn btn-outline-primary btn-sm">Select</button>

                    </div>
                </div>
            </form>
                    @else
                        <div class="col-12">
                            <div class="alert alert-warning w-100">No menus yet.</div>
                        </div>
                    @endif
        </div>
    </div>

@endsection('content')
