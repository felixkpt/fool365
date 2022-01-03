<?php if($post_type == 'post'):
    ?>
Add New Post
<?php
elseif($post_type == 'page'):
?>

    Add New Page
<?php
endif;
?>

        <div class="row">
            <div class="col-12">
               <?php include(APPPATH.'Views/'.$folder.'tools/editor.php'); ?>
            </div>
        </div>
