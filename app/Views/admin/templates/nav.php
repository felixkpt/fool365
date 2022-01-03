<div class="dashboard-header">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top border-0">

        <div class="d-flex justify-content-start">
            <button class="navbar-toggler sideMenuToggler" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
                <a class="navbar-brand mr-0 px-3 text-muted" href="<?= site_url() ?>">Dashboard&nbsp;<div class="d-md-inline text-white-50" style="font-size:70%"><?= site_name() ?></div></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url() ?>predictions#">Link 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url() ?>predictions#">Link 2</a>
                </li>
                <li class="nav-item dropdown nav-user">
                    <?php
                    if (session('user')):

                    $user = session('user');

                    if (@$user->image){
                        $user_image = base_url().'/public/images/users/'.$user->image;
                    }else{
                        $user_image = base_url().'/public/images/users/default.png';
                    }

                    ?>

                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= $user_image ?>" alt="" class="user-avatar-md rounded-circle"></a>
                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                        <div class="nav-user-info">
                            <h5 class="mb-0 text-white nav-user-name"><?= session('user')->username ?></h5> <span class="status"></span><span class="ml-2">Available</span>
                        </div> <a class="dropdown-item" href="<?= site_url().'user/account' ?>"><i class="fas fa-user mr-2"></i>Account</a> <a class="dropdown-item" href="<?= site_url().'user/settings' ?>"><i class="fas fa-cog mr-2"></i>Settings</a> <a class="dropdown-item" href="<?= site_url().'user/logout' ?>"><i class="fas fa-power-off mr-2"></i>Logout</a>
                    </div>
                </li>

            <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>