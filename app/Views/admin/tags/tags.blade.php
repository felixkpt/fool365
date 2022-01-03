@extends($folder.'layouts.layout')
@include($folder.'notifications')

@section('title')
    Tags
@endsection('title')
@section('action')
@endsection('action')

@include($folder.'layouts.sections')


@section('content')

    @yield('header')

    @yield('notifications')

    <form method="post" id="the_form" action="" enctype="multipart/form-data">
            @csrf

            <div class="row">
        <div class="col-4 bg-light">
            <h4>Add New Category</h4>
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" id="name" name="name">
                <small>The name appears on your site.</small>
            </div>
            <div class="form-group">
                <label for="category_slug">Slug</label>
                <input class="form-control" type="text" id="category_slug" name="category_slug">
                <small>The "slug" is URL friendly version of above name.</small>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" type="text" id="description" name="description"></textarea>
                <small>Provide a brief Description of this category.</small>
            </div>
            <input type="hidden" name="action" value="create">
<button type="submit" class="btn btn-outline-primary">Save Category</button>
        </div>
        <div class="col-8">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Name</th><th>Description</th><th>Slug</th><th>Count</th>
                </tr>
                </thead>
                <tbody>
            @forelse($items as $item)
                <tr>
                    <td>
                    {{ $item->name }}
                        <div class="row-actions">
                                <span class="edit"><a class="btn btn-outline-primary btn-xs p-0 pl-1 pr-1" href="{{ url('') }}{{ dash_uri() }}/tags?taxonomy=category&tag={{ $item->id }}&amp;action=edit" aria-label="Edit">Edit</a>&nbsp;|&nbsp;</span>
                                <span class="trash"><a class="btn btn-outline-danger btn-xs p-0 pl-1 pr-1" href="{{ url('') }}{{ dash_uri() }}/tags?taxonomy=category&tag={{ $item->id }}&amp;action=delete" aria-label="Delete Tag">Delete</a>&nbsp;|&nbsp;</span>
                                <span class="view"><a class="btn btn-outline-secondary btn-xs p-0 pl-1 pr-1" href="{{ url('') }}/categories/{{ $item->slug }}" rel="bookmark" aria-label="View">View</a></span>
                        </div>
                    </td>
                    <td>
                        {{ @$item->term_taxonomy->description }}
                    </td>
                    <td>
                        {{ $item->slug }}
                    </td>
                    <td>
                        {{ @$item->term_taxonomy->count }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="bg-light">No Categories yet.</td>
                </tr>
            @endforelse
                </tbody>
            </table>
        </div>
            </div>
        </form>

@endsection('content')
