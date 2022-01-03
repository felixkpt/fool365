<?php

if (!@session('user')):
    ?>
    <li class="nav-item active">
        <a class="nav-link" href="<?= site_url() ?>user/register">Register&nbsp;<span style="font-size: small" class="fa fa-user"></span> <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url() ?>user/login<?php if (!preg_match("#/user/login#", current_url())){
            echo '?redirect_to='.urlencode(current_url());
        }elseif (!empty($_GET['redirect_to'])){
            echo '?redirect_to='.urlencode($_GET['redirect_to']);
        } ?>">Login&nbsp;<span style="font-size: small" class="fa fa-sign-in-alt"></span></a>
    </li>
<?php
endif;
?>


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

    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= $user_image ?>" alt="" width="25px" class="user-avatar-md rounded-circle"></a>
    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
        <div class="nav-user-info">
            <h5 class="mb-0 text-white nav-user-name"><?= session('user')->username ?></h5> <span class="status"></span><span class="ml-2">Available</span>
        </div> <a class="dropdown-item" href="<?= site_url().'user/account' ?>"><i class="fas fa-user mr-2"></i>Account</a> <a class="dropdown-item" href="<?= site_url().'user/settings' ?>"><i class="fas fa-cog mr-2"></i>Settings</a> <a class="dropdown-item" href="<?= site_url().'user/logout' ?>"><i class="fas fa-power-off mr-2"></i>Logout</a>
    </div>
</li>

<?php endif; ?>
