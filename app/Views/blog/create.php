<h2><?= esc($title); ?></h2>

<?php
if (@$validation){
 ?>
    <div class="alert alert-danger">
        <?= \Config\Services::validation()->listErrors() ?>
    </div>
<?php
}
?>

<form action="<?= site_url('/blog/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="input" name="title" /><br />
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control w-100" name="content"></textarea><br />

    </div>
    <input class="btn btn-outline-success" type="submit" name="submit" value="Create news item" />

</form>