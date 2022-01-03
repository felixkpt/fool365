<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title')&nbsp;|&nbsp;{{ config('app.name', 'Laravel') }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('') }}css/cloned/bootstrap-4.css?v=sdweddsdsaed">
  <script src="{{ asset('') }}js/cloned/jquery.min.js?v=sdweddsdsaed"></script>
  <script src="{{ asset('') }}js/cloned/popper.min.js"></script>
      <script src="{{ asset('') }}js/cloned/bootstrap-4.js?v=sdweddsdsaed"></script>

      <!-- include font awesome css -->
    <link rel="stylesheet" href="{{ asset('') }}font-awesome/css/fontawesome-all.css?wd=s">

  <!-- include summernote css/js -->
    <link rel="stylesheet" href="{{ asset('') }}css/cloned/summernote-bs4.min.css?wd=s">
    <script src="{{ asset('') }}js/cloned/summernote-bs4.min.js"></script>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Pacifico">

<!-- Custom styles for this template -->
{{--    <link href="{{ asset('') }}css/cloned/dashboard.css" rel="stylesheet">--}}
    <style>
        .wrapper .sideMenu{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            margin: 56px auto auto;
            width: 200px;
            transition: ease all 0.30s;
            transform: translateX(-100%);
            z-index: 1;
            overflow-x: hidden;


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
                width: 180px;
            }
            .wrapper.in-active .content {
                margin-left: 180px;
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
</head>
<body>
<?php global $request; ?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="d-flex justify-content-start">
        <button class="navbar-toggler sideMenuToggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand mr-0 px-3" href="{{ url('') }}/"><span class="fa fa-home"></span> Home</a>



    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">New</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ url('') }}{{ dash_uri() }}/posts/?action=create&post_type=post">Post</a>
                    <a class="dropdown-item" href="{{ url('') }}{{ dash_uri() }}/posts/?action=create&post_type=page">Page</a>
                    <a class="dropdown-item" href="{{ url('') }}{{ dash_uri() }}/uploads/create">Media</a>

                </div>
            </li>

            <li class="nav-item text-nowrap dropdown">

                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">
                    Hi, {{ @explode(" ", Auth()->user()->name)[0] }}
                </a>

                <div class="dropdown-menu">
                    <a class="nav-link text-dark" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </li>

        </ul>

    </div>
</nav>
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

if (@$full_width)
{
    $collapse['sideMenu'] = 'collapsed';
    $collapse['content'] = '';

}
?>
<div class="wrapper in-active d-flex" style="margin-top: 56px">

    <div class="sideMenu bg-dark {!! $collapse['sideMenu'] !!}">
        <div class="sidebar sidebar">
            <ul class="navbar-nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('') }}{{ dash_uri() }}">
                        <span class="fa fa-cog"></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/posts?post_type=post">
                        <span class="fa fa-pencil-alt"></span>
                        <span class="text">Posts</span>
                    </a>
                    @if($request->slug == 'post' || $request->slug == 'tags')
                        <div class="navbar-nav">
                            <a class="nav-link active" href="{{ url('') }}{{ dash_uri() }}/posts/?post_type=post"><span class="text">All posts</span></a>


                            <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/tags/?taxonomy=category"><span class="text">Categories</span></a>


                        </div>
                    @endif
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/appearance">
                        <span class="fa fa-building"></span>
                        <span class="text">Appearance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/posts?post_type=page">
                        <span class="fa fa-paste"></span>
                        <span class="text">Pages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/uploads">
                        <span class="fa fa-folder-open"></span>
                        <span class="text">uploads</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/users">
                        <span class="fa fa-user"></span>
                        <span class="text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/roles">
                        <span class="fa fa-chart-bar"></span>
                        <span class="text">Role Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="fa fa-industry"></span>
                        <span class="text">Integrations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link sideMenuToggler"><span class="fa fa-chevron-circle-right"></span><span class="text">Resize</span></a>
                </li>
            </ul>

        </div>
    </div>

    <div class="{!! $collapse['content'] !!} w-100 pl-2">
        <main style="min-height: 500px;">
            <div class="container-fluid">
                @if (@$full_width)
                    <div><a href="{{ url('').dash_uri() }}/posts?post_type=post">
                        <span class="fa fa-reply"></span>
                        </a>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

        <footer>
            <div class="container-fluid">
                <hr class="my-4">
                <div class="row bg-light" style="min-height: 300px">

                        <div class="col-4">
                            <h5>Dash Services</h5>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                        <div class="col-4">
                            <h5>Dash Info</h5>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                        <div class="col-4">
                            <h5>Dash News & Updates</h5>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>

            </div>

                <div class="row bg-light justify-content-center" style="min-height: 40px">

                    <div class="col-4">

                        Laravel Dashboard &copy;&nbsp;{{ date('Y') }}
                    </div>
                </div>

            </div>
        </footer>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="../dashboard.js"></script>
</body>
</html>
