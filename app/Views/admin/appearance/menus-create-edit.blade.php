@extends($folder.'layouts.layout')
@include($folder.'notifications')

@section('title')
    @if(@$items)
        Edit Menu
    @else
        Add Menu
    @endif
@endsection('title')

@include($folder.'layouts.sections')


@section('content')

    @yield('header')

    @yield('notifications')

    <?php
    $action = 'create';
    if (@$items) {
        $action = 'edit';
    }

    ?>
    @if (!@$items)
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="d-flex w-100 justify-content-start">
                    <form method="post">
                        @csrf
                        <input type="hidden" name="action" value="{{ $action }}">
                        <div class="form-group">
                            <label for="menu_name">Name</label>
                            <input type="text" name="menu_name" id="menu_name" placeholder="Menu name" class="form-control" value="{{ @$items->name }}" required>
                            <small>The menu name helps distinguish your menus.</small>
                        </div>
                        <button class="btn btn-outline-primary">Submit</button>

                    </form>
                </div>
            </div>

        </div>
    </div>
    @else

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete this Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are sure you want to delete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button class="btn btn-danger" id="confirmDelete">Delete</button>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <form method="post" id="the_form">
                    <h4><input type="text" name="menu_name" value="{{ $items->name }}"></h4>
                    @csrf
                    <input type="hidden" name="action" id="action" value="edit">

                    <div class="mb-2">Content:<br>
                        <div class="col-lg-12 nopadding" style="max-width: 1000px">

                            <textarea name="content_area" id="summernote">{{ @$items->content }}</textarea>

                        </div>
                    </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-row">
                                    <div class="m-1"><button type="submit" id="btn-submit-save" class="btn btn-outline-primary btn-sm">Save</button></div>
                                    <div class="m-1"><button aria-label="Delete"
                                                             data-toggle="modal" data-target="#exampleModal" type="submit" id="btn-submit-delete" class="btn btn-outline-danger btn-sm">Delete</button></div>

                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>



        <script language="javascript" type="text/javascript">
            $(document).ready(function() {

                $('#confirmDelete').click(function (){
                $('#the_form').submit();
                })

                $('#btn-submit-delete').click(function(e){
                    e.preventDefault();
                    $('#action').val('delete');
                });
                $('#btn-submit-save').click(function(e){
                    $('#action').val('edit');
                });

                $('#summernote').summernote({
                    placeholder: 'Start writing...',
                    tabsize: 2,
                    height: 320,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
                $('#the_form').submit(function(e){
                    if ($('.note-codable').is(':visible')){
                        e.preventDefault();
                        $('.note-btn.btn-codeview.note-codeview-keep').trigger('click');;
                        $(this).submit();
                    }
                })

            });
</script>
    @endif

@endsection('content')
