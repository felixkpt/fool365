<!doctype html>
<html>
<head>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="699921945088-7kvtvb6c08b0jac53jjo6c7u52jt60cq.apps.googleusercontent.com">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= esc(word_limiter($description, 140)) ?>">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('android-icon-36x36.png') ?>">
    <!--    Including JS-->
    <script src="<?= base_url() ?>/public/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>/public/js/bootstrap.bundle.min.js"></script>
    <!--    Including styles-->
    <link rel="stylesheet" href="<?= base_url() ?>/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/public/font-awesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="<?= base_url() ?>/public/css/style.css">
    <script>
        let site_url = <?= json_encode(site_url()) ?>;
    </script>
</head>
<body style="padding: 0!important;">

<?php if (!@$hide_navbars): ?>

    <div class="skippy d-none overflow-hidden">
        <div class="container-xl">
            <a class="d-inline-flex p-2 m-1" href="#content">Skip to main content</a>
            <a class="d-none d-md-inline-flex p-2 m-1" href="#kpt-docs-nav">Skip to left navigation</a>
        </div>
    </div>


    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="<?= site_url() ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                    <img class="rounded-circle" style="width: 55px; height: auto" src="<?= base_url('public/images/users/Franz-Joseph-Haydn.jpg') ?>" alt="Site Logo">
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 link-secondary">Overview</a></li>
                    <li><a href="#" class="nav-link px-2 link-dark">Inventory</a></li>
                    <li><a href="#" class="nav-link px-2 link-dark">Customers</a></li>
                    <li><a href="#" class="nav-link px-2 link-dark">Products</a></li>
                </ul>

                <?php if(!session('user')):
                    ?>
                    <div class="col-md-3 text-end">
                        <a href="<?= site_url('user/login?redirect_to='.current_url()) ?>" class="btn btn-outline-primary me-2">Login</a>
                        <a href="<?= site_url('user/register') ?>" class="btn btn-primary">Sign-up</a>
                    </div>
                <?php else: ?>
                    <div class="dropdown text-end" style="z-index: 1021;">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="true">
                            <img src="<?= profile_photo() ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 28px);" data-popper-placement="bottom-start">
                            <li><a class="dropdown-item" href="<?= site_url('/user/account') ?>">Account</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('/user/logout') ?>">Sign out</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <nav class="kpt-subnavbar navbar navbar-expand-sm navbar-dark bg-dark py-2" aria-label="Secondary navigation">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url() ?>"><?= site_name() ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#secondaryNav" aria-controls="secondaryNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="secondaryNav">
                <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-none">
                    <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                </form>

                <button class="btn kpt-sidebar-toggle text-primary d-md-none py-0 px-1 ms-3 order-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kpt-docs-nav" aria-controls="kpt-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="bi bi-expand" fill="currentColor" viewBox="0 0 16 16">
                        <title>Expand</title>
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8zM7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10z"></path>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="bi bi-collapse" fill="currentColor" viewBox="0 0 16 16">
                        <title>Collapse</title>
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8zm7-8a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 4.293V.5A.5.5 0 0 1 8 0zm-.5 11.707l-1.146 1.147a.5.5 0 0 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 11.707V15.5a.5.5 0 0 1-1 0v-3.793z"></path>
                    </svg>

                </button>
            </div>
        </div>
    </nav>

<?php endif; ?>

<div class="container-xxl my-md-4 <?php if (!@$hide_navbars) { echo 'kpt-layout';} ?>">

    <?php require APPPATH.'Views/templates/left-nav.php' ?>

    <main>
        <?php if (!@$hide_title): ?>
            <div class="border border-light px-1">
                <div class="d-md-flex flex-md-row align-items-center justify-content-between">
                    <h1 class="kpt-title <?php if (!isset($system)): { echo 'w-100';} endif; ?>" id="content"><?= esc($title) ?></h1>
                    <?php if (isset($system)): ?>
                        <span class=" text-muted rounded px-1">
                            <select name="system" id="system" class="border border-2 border-warning">
                                <?php foreach($systems as $item) {
                                    ?>
                                    <option value="<?= site_url('betting-system/'.$item->number.'/'.url_title($item->title, '-', true)) ?>" <?php if ($item->number == $system) echo 'selected' ?>>System #<?= $item->number ?></option>
                                        <?php
                                    } ?>
                            </select>
                            <script>
                                jQuery(document).ready(function ($){
                                    $('#system').on('change', function (){
                                        var href = $(this).val();
                                        window.location.href = href;
                                    })
                                })
                            </script>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="kpt-lead">
                    <?php require 'flash-data.php' ?>
                </p>
                <script>
                    $(".alert").delay(7000).slideUp(200, function() {
                        $(this).alert('close');
                    });
                </script>
            </div>
        <?php endif; ?>
        <!--Begin CONTENT AND RIGHT NAV Row-->
        <div class="<?php if (@$hide_navbars) { echo 'd-flex justify-content-center';}else{ echo 'row';} ?>">
            <!--    BEGIN Main CONTENT-->
            <div class="col-12 col-lg-10">
                <div class="ps-lg-0" id="mainContent">