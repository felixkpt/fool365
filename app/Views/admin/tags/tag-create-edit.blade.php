@extends($folder.'layouts.layout')
@section('title'){{ 'New tag' }}@endsection('title')
@include($folder.'layouts.sections')
@include($folder.'notifications')
@include($folder.'tools.editor')

@section('content')

    @yield('header')

    @yield('notifications')

    <?php

        global $request;

        $action = 'edit';
        if ($request->action == 'create')
        {
            $action = 'create';
            $items = new stdClass();
            }
    ?>
    <div>
        <form method="post" id="the_form" action="" enctype="multipart/form-data">
            @csrf
            <h4>Add New Category</h4>
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ @$items->name }}">
                <small>The name appears on your site.</small>
            </div>
            <div class="form-group">
                <label for="category_slug">Slug</label>
                <input class="form-control" type="text" id="category_slug" name="category_slug" value="{{ @$items->slug }}">
                <small>The "slug" is URL friendly version of above name.</small>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" type="text" id="description" name="description">{{ @$items->description }}</textarea>
                <small>Provide a brief Description of this category.</small>
            </div>
            <input type="hidden" name="action" value="{{ $action }}">
            <button type="submit" class="btn btn-outline-primary">Save Category</button>
        </form>

    </div>


@endsection('content')
