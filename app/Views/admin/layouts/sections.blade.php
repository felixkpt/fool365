<?php
global $request
?>

@section('header')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            @yield('title')
            @yield('action')
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
            </button>
        </div>
    </div>

@endsection('header')

@section('sidenav')
    <nav id="sidebarMenu" class="navbar navbar-dark col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
        <div class="sidebar-sticky pt-3">
            <ul class="navbar-nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('') }}{{ dash_uri() }}">
                        <span class="fa fa-cog"></span>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                                        <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/post?post_type=post">
                                            <span class="fa fa-paste"></span>
                                            Posts
                                        </a>
                    @if($request->slug == 'post' || $request->slug == 'tags')
                        <div class="navbar-nav">
                                <a class="nav-link active" href="{{ url('') }}{{ dash_uri() }}/post/?post_type=post">All posts</a>


                                <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/tags/?taxonomy=category">Categories</a>


                        </div>
                        @endif
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/appearance">
                        <span class="fa fa-building"></span>
                        Appearance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/post?post_type=page">
                        <span class="fa fa-pallet"></span>
                        Pages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('') }}{{ dash_uri() }}/users">
                        <span class="fa fa-user"></span>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="fa fa-chart-bar"></span>
                        Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="fa fa-industry"></span>
                        Integrations
                    </a>
                </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Saved reports</span>
                <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
                    <span data-feather="plus-circle"></span>
                </a>
            </h6>
            <ul class="navbar-nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="file-text"></span>
                        Current month
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="file-text"></span>
                        Last quarter
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="file-text"></span>
                        Social engagement
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="file-text"></span>
                        Year-end sale
                    </a>
                </li>
            </ul>
        </div>
    </nav>
@endsection('sidenav')
