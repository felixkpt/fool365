@extends($folder.'layouts.layout')
@include($folder.'notifications')

@section('title')
    Users
@endsection('title')

@section('action')
    <a class="btn btn-outline-success btn-sm" href="{{ url('') }}{{ dash_uri() }}/users?action=create">Add New</a>
@endsection('action')

@include($folder.'layouts.sections')


@section('content')

        @yield('header')

        @yield('notifications')

        <div class="row">
            <div class="col-12">
                <?php
                $post_status = '';
                $post_type = '';

                if ($post_status == 'trash') {
                    $items = $trashed;
                }
                ?>

                <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this user</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are sure you want to delete?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                    <a href="#" id="link" type="submit" class="btn btn-danger">Delete</a>

                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="post">
                        @csrf
                        <?php
                        $ids = array_column(json_decode(json_encode($items), true), 'id');

                        global $request;

                        $options = [['name' => 'delete', 'name_echo' => 'Delete']];

                        if ($request->post_status == 'trash'){
                            $options = [['name' => 'untrash', 'name_echo' => 'Restore'],
                                ['name' => 'delete', 'name_echo' => 'Delete Permanently']];

                        }
                        ?>
                        <div class="py-2 d-flex flex-row">
                            <select name="action" id="action1" class="mr-1">
                                <option value="">Bulk Actions</option>
                                @foreach($options as $option)
                                    <option value="multiple_{{ $option['name'] }}">{{ $option['name_echo'] }}</option>
                                @endforeach

                            </select> <button class="btn btn-outline-dark btn-sm" value="submit" name="submit">Apply</button>

                        </div>

                        <table class="table table-striped table-hover">
                            <tr class="row">
                                <th class="col-1"><input type="checkbox" class="bulk-selection bulk-selection-item" name="ids" value="{{ implode(',', $ids) }}"></th>
                                <th class="col-3">Title</th>
                                <th class="col-2">Author</th>
                                <th class="col-4">Categories</th>
                                <th class="col-2">Date</th>
                            </tr>
                    <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><input class="bulk-selection-item" type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->name }}
<script>
    $(document).ready(function (){
      $('.delete-btn').click(function (){
          var link = $(this).attr('href');
          $('#link').attr('href', link);
      })
    })
</script>
                                <div class="row-actions">
                                    @if($post_status !== 'trash')
                                        <span class="edit"><a class="btn btn-outline-primary btn-xs p-0 pl-1 pr-1" href="{{ url('') }}{{ dash_uri() }}/users?user={{ $item->id }}&amp;action=edit" aria-label="Edit">Edit</a>&nbsp;|&nbsp;</span>
                                        <span class="delete">
                                            <a class="btn btn-outline-danger btn-xs p-0 pl-1 pr-1 delete-btn"
                                               href="{{ url('') }}{{ dash_uri() }}/users?user={{ $item->id }}&amp;action=delete"
                                               aria-label="Delete"
                                                                data-toggle="modal" data-target="#exampleModal"
                                            >Delete</a>&nbsp;|&nbsp;</span>
                                        <span class="view"><a class="btn btn-outline-secondary btn-xs p-0 pl-1 pr-1" href="{{ url('') }}/users/{{ $item->nicename }}" rel="bookmark" aria-label="View">View</a></span>
                                    @endif


                                </div>

                            </td><td>{{ $item->email }}</td>
                            <td>{{ $item->created_at }}</td><td><div>Published</div>{{ $item->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="bg-light">No {{ $post_type }}s found.</td>
                        </tr>
                    @endforelse
                    <tr class="row">
                        <th class="col-1"><input type="checkbox" class="bulk-selection bulk-selection-item" name="ids" value="{{ implode(',', $ids) }}"></th>
                        <th class="col-3">Title</th>
                        <th class="col-2">Author</th>
                        <th class="col-4">Categories</th>
                        <th class="col-2">Date</th>
                    </tr>
                    </tbody></table>

                    </form>
                <script>
                    $(document).ready(function (){
                        // Bulk actions UI switch checker script
                        $('.actions').on('change', function (){
                            $('.actions').val($(this).val());
                        })

                        $('.bulk-selection').click(function (){

                            var obj = $(this).parents('form').find('.bulk-selection-item');

                            if ($(this).prop('checked') == true)
                            {
                                obj.prop('checked', 'checked');

                            }
                            else
                            {
                                obj.prop('checked', '');
                            }

                        })

                    })
                </script>
            </div>
        </div>

@endsection('content')
