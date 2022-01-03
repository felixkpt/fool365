<div class="row mb-2">
    <div class="col-12 bg-light shadow-sm border p-0">
        <div class="jumbotron h-100 pt-3 pb-0">
            <h5>Welcome to the Dashboard!</h5>
            <p>
                We’ve assembled some links to get you started:

            </p>
            <div class="row">
                <div class="col-3">
                    <h6 class="font-weight-bold">Get Started</h6>
                    <ul class="nav d-block flex-column">
                        <li class="nav-item">
                            <a class="nav-link p-1 px-0" href="<?= admin_url() ?>/posts?action=create&post_type=post"><span class="fa fa-paste text-dark"></span> Write your first blog post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1 px-0" href="<?= admin_url() ?>/posts?action=create&post_type=page"><span class="fa fa-tag text-dark"></span> Add Contact us page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1 px-0" href="<?= admin_url() ?>/appearance?action=customize"><span class="fa fa-random text-dark"></span> Set up your homepage</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1 px-0" href="<?= site_url() ?>"><span class="fa fa-eye text-dark"></span> View your site</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>


    </div>
</div>

<div class="row mb-2">
    <div class="col-lg-6 col-md-12 p-0 pr-1">
        <div class="col-12 p-2 bg-light shadow-sm border">
            <h5>At a Glance</h5>
            <hr>
            <div class="row">
                <?php
                $posts = count((new \App\Models\BlogModel())->where('id > ', '0')->get()->getResult());
                $posts_name = 'Post';
                if ($posts > 1)
                    $posts_name = 'Posts';

                $pages = count((new \App\Models\BlogModel())->where('id > ', '0')->get()->getResult());
                $pages_name = 'Page';
                if ($posts > 1)
                    $pages_name = 'Pages';

                ?>
                <div class="col-6">
                    <a href="<?= admin_url() ?>/posts?post_type=post"><span class="fa fa-pencil-alt text-dark"></span> <?= $posts.' '.$posts_name ?></a>
                </div>
                <div class="col-6">
                    <a href="<?= admin_url() ?>/posts?post_type=page"><span class="fa fa-paste text-dark"></span> <?= $pages.' '.$pages_name ?></a>
                </div>
            </div>
        </div>

        <div class="col-12 p-2 bg-light shadow-sm border">
            <h5>Activity</h5>
            <hr>
            <div class="row">
                <div class="col-6">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
                <div class="col-6">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-6 col-md-12 p-0 pr-1">
        <div class="col-12 p-2 bg-light shadow-sm border">
            <h5>Quick Notes</h5>
            <hr>
            <div class="col-12">
                <form method="post" action="<?= admin_url() ?>/posts?action=create&post_type=post">
                    <div class="form-group">
                        <label for="quickTitle">Title</label>
                        <input type="text" id="quickTitle" name="content_title" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="quickContent">Content</label>
                        <textarea type="text" id="quickContent" name="content_area" placeholder="What’s on your mind?" class="form-control">
                                </textarea>
                    </div>
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="post_status" value="draft">
                    <button type="submit" class="btn btn-outline-info btn-sm">Save</button>
                </form>
            </div>
            <?php
            $drafts = count((new \App\Models\BlogModel())->where('id > ', '0')->get()->getResult());
            ?>
            <?php if(@$drafts[0]):
            ?>
            <hr>
            <h6>Recent Drafts</h6>
            <div class="col-12 pt-2">
                <ul class="list-unstyled">
                    <?php foreach($drafts as $item): ?>
                    <li class="">
                        <div class="row">
                            <div class="w-75"><a class="" href="<?= admin_url() ?>/posts?post={{ $item->id }}&action=edit">{{ $item->title }}</a>
                            </div>
                            <div class="w-25 text-muted">{{ date('M d, Y', strtotime($item->updated_at)) }}</div>
                        </div>
                        <div class="row">
                            <div class="w-100">
                                {!! $item->content !!}
                            </div>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>
        </div>

        <div class="col-12 p-2 bg-light shadow-sm border">
            <h5>More Actions</h5>
            <hr>
            <div class="col-12 p-0">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
        </div>

    </div>



</div>

<div class="row mb-2">

</div>