    <form method="post" id="the_form" action="" enctype="multipart/form-data">
    <div class="row">

        <div class="col-10">

    <?php

            if ($action == 'create') {
                    $action = 'create';
                    $items = new stdClass();

				}else{
                $action = 'edit';
            }

				?>
            <?= csrf_field() ?>
            <input type="hidden" name="post_type" value="<?= @$post_type ?>">
            <input type="hidden" name="post_id" value="<?= @$items->post_id ?>">
		<div class="jumbotron">
            <div class="d-flex justify-content-end">
                    <div class="p-0 pb-2 pr-5 d-none" id="btn-submit-draft-container">
                    <input type="hidden" name="action" value="<?= $action ?>">

                    <button id="btn-submit-draft" type="submit" class="btn border-0 btn-default btn-outline-dark">Save draft
                    </button>
                </div>
                <div class="p-0 pb-2">
                    <input type="hidden" id="post_status" name="post_status" value="publish">
                    <button id="btn-submit" type="submit" class="btn btn-outline-primary">Publish
                    </button>
                </div>
            </div>

					<div class="mb-2">Title:<br>
				<input type="text" name="content_title" value="<?= @$items->title ?>" class="btn bg-white text-left border" style="min-width: 30%;border: solid 1px black!important;">

				</div>
				<div class="mb-2">Content:<br>
				<div class="col-lg-12 nopadding" style="max-width: 1000px">

                    <textarea name="content_area" id="summernote"><?= @$items->content ?></textarea>

                </div>

			</div>

		</div>

			<script language="javascript" type="text/javascript">
				jQuery(document).ready(function($) {
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

				$('#the_form').on('keyup', function(e){
                            $('#btn-submit-draft-container').removeClass("d-none");
                });
                    $('#the_form').on('change', function(e){
                        $('#btn-submit-draft-container').removeClass("d-none");
                    });

                    $('#btn-submit-draft').click(function(e){
                        $('#post_status').val('draft');
                    });


			</script>


        </div>
        <div class="col-2">
<?php global $request; ?>
                <div class="overflow-auto mt-2 p-1 shadow">
                    <!-- Nav tabs -->

                    <ul class="nav" role="tablist" style="visibility: visible;">
                        <li class="nav-item">

                            <div class="tab-content p-0 pb-2 border-bottom">

                                <h5>Status</h5>
                                <ul class="list-unstyled">

                                    <li>
                                        <a class="btn btn-outline-danger btn-xs p-0 pl-1 pr-1" href="<?= @admin_url('/posts?post='.$items->id.'&amp;action=trash&post_type='.$post_type) ?>" aria-label="Move to the Trash">Trash</a>

                                    </li>
                                </ul>
                            </div>

                            <?php if ($post_type == 'post'): ?>
                                <div class="tab-content p-0 pb-2 border-bottom">

                                        <?php
                                        //                    current post categories
                                        $cats = @explode(',', $items->categories);
//                                        $terms = \App\Models\Cloned\Term::with(['term_taxonomy'])->get();;
                                        $terms = [];
                                        ?>
                                        <h5>Categories</h5>
                                            <div class="overflow-auto" style="max-height: 150px">
                                            <ul class="list-unstyled">
                                            <?php foreach($terms as $term): ?>
                                                <li>
                                                    <div class="form-group form-check">
                                                        <input id="cat-<?= $term->id ?>" name="cat_IDS[]" class="form-check-input" type="checkbox" value="<?= $term->id ?>" @if(in_array($term->id, $cats)) checked="checked" @endif>
                                                        <label for="cat-<?= $term->id ?>" class="form-check-label"><?= $term->name ?></label>
                                                    </div>

                                                </li>
                                            <?php endforeach ?>
                                        </ul>
                                            </div>

                                            <a class="" href="<?= admin_url('/tags?tag=category&action=create') ?>">Create Category</a>
                            </div>
                            <?php endif ?>

                        </li>

                    </ul>

                    <div class="tab-content p-0 pb-2 border-bottom">

                        <h5>Permalink</h5>
                    <ul class="list-unstyled">

                        <li>
                            <div class="form-group">
                                <input value="<?= @$items->guid ?>" name="guid" class="form-control">


                            </div>
                            <?php if (@$itmes->guid): ?>
                            <div class="overflow-auto small pt-2">
                                <h6>View <?= $post_type  ?></h6>
                                <a href="<?= site_url($items->guid) ?>" class="btn btn-link btn-sm"><?= site_url($items->guid) ?><span class="fa fa-external-link-alt"></span></spa></a>
                            </div>
                                <?php endif ?>
                        </li>
                    </ul>
                    </div>

                </div>

        </div>


    </div>
    </form>
