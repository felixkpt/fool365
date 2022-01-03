<div class="row justify-content-start">
    <div class="col-12">
        <?php
        if (session()->getFlashdata('light')) : ?>
            <div class="alert alert-light alert-dismissible fade show" role="alert">
                <?php echo session()->getFlashdata('light'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php
        if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo session()->getFlashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php
        if (session()->getFlashdata('warning')) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo session()->getFlashdata('warning'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php
        if (session()->getFlashdata('danger') || session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo (session()->getFlashdata('danger') ?? session()->getFlashdata('error')); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php
        if (@$validation) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $validation->listErrors() ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

    </div>
</div>