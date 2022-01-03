<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Enqeue scripts-->
	<script src="<?= base_url(); ?>/public/js/jquery.min.js"></script>
	<script src="<?= base_url(); ?>/public/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>/public/js/gijgo.min.js"></script>

	<!--    Enqeue styles-->
	<link rel="stylesheet" href="<?= base_url(); ?>/public/css/admin/bootstrap.css?v=ds<?= now() ?>" />
	<link rel="stylesheet" href="<?= base_url(); ?>/public/font-awesome/css/fontawesome-all.css">
	<link rel="stylesheet" href="<?= base_url(); ?>/public/css/style.css?ver=wes2Q">

    <!-- include summernote css/js -->
    <link rel="stylesheet" href="<?= base_url(); ?>/public/css/admin/summernote-bs.min.css?wd=s">
    <script src="<?= base_url(); ?>/public/js/summernote-bs4.min.js"></script>

    <!--    The page title-->
	<title><?= strip_tags($title) ?></title>

</head>
<!--End header-->

<body>
<?php
include_once 'nav.php';
?>
