<script>
    $(document).ready(function (){
        $('.sideMenuToggler').click(function (){
            $('.wrapper').toggleClass('active');
            $('.wrapper').toggleClass('in-active');

            var obj = $('.wrapper .sideMenuToggler .fa');

            if (obj.hasClass('fa-chevron-circle-left')) {
                obj.removeClass('fa-chevron-circle-left');
                obj.addClass('fa-chevron-circle-right');
            }else if (obj.hasClass('fa-chevron-circle-right')) {
                obj.removeClass('fa-chevron-circle-right');
                obj.addClass('fa-chevron-circle-left');
            }

        })

    })


</script>

<?php
$collapse['sideMenu'] = '';
$collapse['content'] = 'content';
$competitions = [];

if (@$full_width)
{
    $collapse['sideMenu'] = 'collapsed';
    $collapse['content'] = '';

}
?>
<style>

    .wrapper .sideMenu{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        margin: 97px auto auto;
        width: 230px;
        transition: ease all 0.20s;
        transform: translateX(-100%);
        z-index: 1;
        overflow-x: hidden;

    }

    @media (min-width: 768px) {
        .wrapper .sideMenu{
            margin: 67px auto auto;
        }
    }
    .wrapper .content{
        margin-left: 0px;
        transition: ease all 0.25ms;
    }

    .wrapper.active .sideMenu{
        transform: translateX(0);
    }

    .wrapper .nav-link{
        display: block;
        white-space: nowrap;

    }

    .wrapper .sideMenu .nav-link:hover{
        color: white;
    }

    .wrapper .sideMenu .fa{
        margin-right: 4px;
        font-size: 25px;
        vertical-align: middle;
        height: 32px;
        width: 32px;
        display: inline-flex;
        justify-content: center;
        align-items: center;

    }
    .wrapper .text{
        font-size: 14px;
    }

    .wrapper .sideMenu .nav-link{
        color: lightgray;
    }

    .wrapper.in-active .text{
        display: inline;
    }


    @media (min-width: 992px) {
        .wrapper.in-active .sideMenu {
            width: 220px;
        }
        .wrapper.in-active .content {
            margin-left: 220px;
        }

        .wrapper.active .sideMenu {
            width: 80px;
        }
        .wrapper.active .content {
            margin-left: 80px;
        }
        .wrapper.active .text{
            display: none;
        }
    }

    @media (max-width: 992px) {

        .wrapper.in-active .sideMenu {
            width: 80px;
        }
        .wrapper.in-active .content {
            margin-left: 80px;
        }
        .wrapper.in-active .text{
            display: none;
        }

    }

    @media (min-width: 768px) {

        .collapsed{
            display: none;
        }
        .wrapper .sideMenu {
            transform: translateX(0);
        }

        .wrapper .content {
            margin-left: 200px;
        }

    }

    @media (max-width: 768px) {
        .wrapper.in-active .content {
            margin-left: 0px;
        }

        .wrapper.in-active .text{
            display: inline;
        }

    }

    .font-size-1 {
        font-size: 10px!important;
    }
    .font-size-2 {
        font-size: 12px!important;
    }
    .font-size-3 {
        font-size: 14px!important;
    }
    .font-size-4 {
        font-size: 16px!important;
    }
    .font-size-5 {
        font-size: 20px!important;
    }

</style>

<div class="wrapper in-active d-flex">

    <div class="sideMenu bg-dark <?= $collapse['sideMenu'] ?>">
        <div class="sidebar sidebar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= admin_url() ?>">
                        <span class="fa fa-cog"></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/posts?post_type=post') ?>">
                        <span class="fa fa-paste"></span>
                        <span class="text">Posts</span>
                    </a>
                    <?php if(@$request->slug == 'post' || @$request->slug == 'tags'):
                    ?>
                    <div class="navbar-nav">
                        <a class="nav-link active" href="<?= admin_url('/posts?post_type=post') ?>">All posts</a>


                        <a class="nav-link" href="<?= admin_url('/tags?taxonomy=category') ?>">Categories</a>


                    </div>
                    <?php endif ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/uploads') ?>">
                        <span class="fa fa-building"></span>
                        <span class="text">Uploads</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/posts?post_type=page') ?>">
                        <span class="fa fa-pallet"></span>
                        <span class="text">Pages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/users') ?>">
                        <span class="fa fa-user"></span>
                        <span class="text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/page/fetcher') ?>">
                        <span class="fa fa-gem"></span>
                        <span class="text">Fetch Fixtures</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/page/results') ?>">
                        <span class="fa fa-qrcode"></span>
                        <span class="text">Get Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/page/matcher') ?>">
                        <span class="fa fa-gamepad"></span>
                        <span class="text">Match Fix/Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('/page/tips-settings') ?>">
                        <span class="fa fa-futbol"></span>
                        <span class="text">Tips Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link sideMenuToggler"><span class="fa fa-chevron-circle-right"></span><span class="text">Resize</span></a>
                </li>
            </ul>

        </div>
    </div>

    <div id="content-and-footer-div" class="<?= $collapse['content'] ?> pl-0">
        <main style="min-height: 500px;" class="container-fluid">

            <h1 class="text-success <?= @$title_class ?>"><?= $title ?></h1>

            <hr class="my-4">

            <?php if (!@$hide_flashdata){ include_once APPPATH.'Views/admin/templates/flash-data.php'; } ?>
