<div class="container-fluid my-3">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-6 bg-light shadow p-2">
            <?php require APPPATH.'Views/templates/flash-data.php' ?>

            <form action="<?= base_url() ?>/user/register/save" method="post">
                <div class="text-center">
                    <a href="<?= site_url() ?>"><img class="mb-4 rounded-circle" style="width: 55px; height: auto"  src="<?= base_url('public/images/users/Franz-Joseph-Haydn.jpg') ?>" alt="" width="72" height="57"></a>
                    <h1 class="h3 mb-3 fw-normal"><?= $title ?></h1>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name:&nbsp;</label><input class="form-control" type="text" id="name" name="name" value="<?= @old('name') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email:&nbsp;</label><input class="form-control" id="email" type="email" name="email"  value="<?= @old('email') ?>">

                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">Phone:&nbsp;</label><input class="form-control" id="phone" type="number" name="phone"  value="<?= @old('phone') ?>">

                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password:&nbsp;</label> <input class="form-control" id="password" type="password" name="password">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password_c">Confirm Password:&nbsp;</label> <input class="form-control" id="password_c" type="password" name="password_c">
                </div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <button class="btn btn btn-success mb-3 p-2" type="submit" value="save" name="action">Register</button>

                    </div>
                    <div class="col-12 col-md-8">
                        Already Registered? <a href="<?= base_url() ?>/user/login">Login <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>