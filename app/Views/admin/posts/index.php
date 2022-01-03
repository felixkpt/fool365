<a class="btn btn-outline-success btn-sm" href="<?= admin_url('/posts?action=create&post_type='.$post_type) ?>">Add New</a>

    <div class="row">
        <div class="col-12">
            <div class="row justify-content-start d-flex flex-row">
                <a class="px-2" href="<?= admin_url('/posts?post_type='.$post_type) ?>">All&nbsp;<span class="text-dark">(<?= count($items) ?>)</span></a>
                <?php if(@$published[0]): ?>
                    |<a class="px-2" href="<?= admin_url('/posts?post_status=publish&post_type='.$post_type) ?>">Published&nbsp;<span class="text-dark">(<?= count($published) ?>)</span></a>
                <?php endif ?>
                    <?php if(@$drafts[0]): ?>
                    |<a class="px-2" href="<?= admin_url('/posts?post_status=draft&post_type='.$post_type) ?>">Draft&nbsp;<span class="text-dark">(<?= count($drafts) ?>)</span></a>
                <?php endif ?>
                    <?php if(@$trashed[0]): ?>
                    |<a class="px-2" href="<?= admin_url('/posts?post_status=trash&post_type='.$post_type) ?>">Trashed&nbsp;<span class="text-dark">(<?= count($trashed) ?>)</span></a>
                <?php endif ?>
            </div>
        </div>
        <div class="col-12">

<?php

                $options = [['name' => 'edit', 'name_echo' => 'Edit'],
                    ['name' => 'trash', 'name_echo' => 'Move to Trash']];

                $items = $published;
                if ($post_status == 'publish'){
                    $items = $published;
                    $options = [['name' => 'edit', 'name_echo' => 'Edit'],
                        ['name' => 'draft', 'name_echo' => 'Save as draft'],
                        ['name' => 'trash', 'name_echo' => 'Move to trash']];

                }
                if ($post_status == 'trash'){
                    $items = $trashed;
                    $options = [['name' => 'untrash', 'name_echo' => 'Restore'],
                        ['name' => 'delete', 'name_echo' => 'Delete Permanently']];

                }
                if ($post_status == 'draft'){
                    $items = $drafts;
                    $options = [['name' => 'publish', 'name_echo' => 'Publish'],
                        ['name' => 'trash', 'name_echo' => 'Move to trash']];

                }

                $ids = array_column(json_decode(json_encode($items), true), 'id');

                ?>


    <form method="post" id="the_form">
        <?= csrf_field() ?>
        <div class="py-2 d-flex flex-row">
    <select name="action" class="actions mr-1">
        <option value="">Bulk Actions</option>
        <?php foreach($options as $option): ?>
            <option value="<?= $option['name'] ?>"><?= $option['name_echo'] ?></option>
        <?php endforeach ?>

    </select> <button class="submit-btn btn btn-outline-dark btn-sm" value="submit" name="submit">Apply</button>

</div>

        <div class="d-none m-2 mb-4" id="edit_section">
            <div class="p-2 shadow">
                <div class="m-3 pb-3">

                    <div class="row" style="min-height: 140px">
                        <div class="col-4">
                            <?php if ($post_type == 'post') : ?>
                                <?php

                            $cats = [];
//                            $terms = \App\Models\Cloned\Term::with(['term_taxonomy'])->get();;
$terms = [];
                            ?>
                            <h5>Categories</h5>
                            <div class="overflow-auto" style="max-height: 150px">
                                <ul class="list-unstyled">
                                    <?php foreach($terms as $term): ?>
                                        <li>
                                            <div class="form-group form-check">
                                                <input id="cat-<?= $term->id ?>" name="cat_IDS[]" class="form-check-input" type="checkbox" value="<?= $term->id ?>" <?php if(in_array($term->id, $cats)): ?>checked="checked"<?php endif ?>>
                                                <label for="cat-<?= $term->id ?>" class="form-check-label"><?= $term->name ?></label>
                                            </div>

                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            <?php else: ?>
                                <h5>Page Options</h5>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris.
                            <?php endif ?>
                        </div>
                        <div class="col-4">
                            <h5>Other Options</h5>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris.
                        </div>
                        <div class="col-4">
                            <h5>Even More Options</h5>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi...
                        </div>
                    </div>
                </div>
<div class="row">
        <div class="col-6">
            <div class="d-flex justify-content-start">
                <button class="btn-outline-dark btn-sm cancel-button">Cancel</button>
            </div>
        </div>
    <div class="col-6">
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn-outline-primary btn-sm">Save</button>

        </div>
    </div>

</div>
            </div>
        </div>

            <div class="row pl-3 mr-1">
                <div class="col-12 p-0">
                    <table class="table table-striped table-hover m-0">
                        <tr class="row">
                            <th class="col-1"><input type="checkbox" class="bulk-selection bulk-selection-item" name="ids" value="<?= implode(',', $ids) ?>"></th>
                            <th class="col-3">Title</th>
                            <th class="col-2">Author</th>
                            <th class="col-4">Categories</th>
                            <th class="col-2">Date</th>
                        </tr>
                        <tbody>
                        <?php

                        foreach($items as $item): ?>
                            <tr class="row">
                                <td class="col-1">
                                    <input type="checkbox" class="bulk-selection-item" id="cat-<?= $item->id ?>" name="ids[]" value="<?= $item->id ?>" <?php if(in_array($item->id, [])): ?>checked="checked"<?php endif ?>>
                                </td>

                                <td class="col-3"><?= $item->title ?>

                                    <div class="row-actions">
                                        <?php if($post_status !== 'trash'): ?>
                                            <span class="edit"><a class="btn btn-outline-primary btn-xs p-0 pl-1 pr-1" href="<?= admin_url('/posts?post='.$item->id.'&amp;action=edit&post_type='.$post_type) ?>" aria-label="Edit">Edit</a>&nbsp;|&nbsp;</span>
                                            <span class="trash"><a class="btn btn-outline-danger btn-xs p-0 pl-1 pr-1" href="<?= admin_url('/posts?post='.$item->id.'&amp;action=trash&post_type='.$post_type) ?>" aria-label="Move to the Trash">Trash</a>&nbsp;|&nbsp;</span>
                                            <span class="view"><a class="btn btn-outline-secondary btn-xs p-0 pl-1 pr-1" href="<?= blog_url($item->slug) ?>" rel="bookmark" aria-label="View">View</a></span>
                                        <?php else: ?>
                                            <span class="untrash"><a class="btn btn-outline-primary btn-xs p-0 pl-1 pr-1" href="<?= admin_url('/posts?post='.$item->id.'&amp;action=edit&post_type='.$post_type) ?>/posts?post=<?= $item->id ?>&amp;action=untrash&post_type=<?= $post_type ?>&post_status=<?= $post_status ?>">Restore</a>&nbsp;|&nbsp;</span>
                                            <span class="delete"><a class="btn btn-outline-danger btn-xs p-0 pl-1 pr-1" href="<?= admin_url('/posts?post='.$item->id.'&amp;action=delete&post_type='.$post_type) ?>">Delete Permanently</a>&nbsp;&nbsp;</span>
                                        <?php endif ?>


                                    </div>
                                </td>
                                <td class="col-2"><?= @explode(' ', (New App\Models\UserModel())->where('id', $item->author)->first()['username'])[0] ?></td>
                                <td class="col-3">
                                    <div class="row d-flex">
                                        <div class="w-100">
                                            <?php

                                            $categories = $item->categories ?? [];
                                            if ($categories) {
                                                $categories = explode(',', $categories);
                                                foreach ($categories as $ke => $category) {
                                                    $items2 = \App\Terms::where('id', $category)->first();

                                            if ($items2) {
                                                $ech = substr($items2->name, 0 ,30);

                                                echo $ech;
//                        ellipsis
                                                if (strlen($ech) !== strlen($items2->name)) {
                                                    echo '...';
                                                }

                                                if (@$categories[$ke + 1]) {
                                                    echo ', ';
                                                }
                                            }
//                                            endif items2

                                                }

                                            }


                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-3"><div>Published</div><?= $item->created_at ?></td>
                            </tr>

                        <?php endforeach;
                        if (!$items){
                            ?>
                            <tr>
                                <td colspan="4" class="bg-light">No <?= $post_type ?>s found.</td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr class="row">
                            <th class="col-1"><input type="checkbox" class="bulk-selection bulk-selection-item" name="ids" value="<?= implode(',', $ids) ?>"></th>
                            <th class="col-3">Title</th>
                            <th class="col-2">Author</th>
                            <th class="col-4">Categories</th>
                            <th class="col-2">Date</th>
                        </tr>
                        </tbody></table>
                </div>
            </div>

                <div class="py-2 d-flex flex-row">
                    <select name="action" class="actions mr-1">
                        <option value="">Bulk Actions</option>
                        @foreach($options as $option)
                            <option value="<?= $option['name'] ?>"><?= $option['name_echo'] ?></option>
                        @endforeach

                    </select> <button class="submit-btn submit-btn-last btn btn-outline-dark btn-sm" value="submit" name="submit">Apply</button>

                </div>
    </form>
<script>
    $(document).ready(function (){

        // Show Bulk edit section
        $('.submit-btn').click(function (e){

            if ($(this).parents('form').find('.actions').val() == 'edit')
            {
                e.preventDefault();

                var form = $(this).parents('form').serialize();

                var all = $(this).parents('form').find('.bulk-selection-item');
                console.log(all);

                var ids = [];

                all.each(function (){

                    if ($(this).prop('checked') == true) {
                        ids.push($(this).val());
                    }
                })

                // show the edit section if some ids selected
                if (ids.length > 0) {
                    $('#edit_section').removeClass('d-none');

                    // scroll to edit_section

                    scrollIntoView('#edit_section');

                }

            }else{
                $('#edit_section').addClass('d-none');
            }

        })

        $('.cancel-button').on('click', function (e) {
            e.preventDefault()
        $(this).parents('#edit_section').addClass('d-none');
        })

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
