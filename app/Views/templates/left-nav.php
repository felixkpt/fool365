<?php if (!@$hide_navbars): ?>
<aside class="kpt-sidebar">
    <nav class="kpt-links collapse" id="kpt-docs-nav" aria-label="Docs navigation"><ul class="list-unstyled mb-0 py-3 pt-md-1">

            <li class="mb-1">

                <?php
                $db = db_connect();
                $builder = $db->table('systems');
                $systems = $builder->orderBy('number')->get()->getResult();

                ?>
                <button class="btn d-inline-flex align-items-center rounded" data-bs-toggle="collapse" data-bs-target="#components-collapse" aria-expanded="true" aria-current="true">
                    <span class="fa fa-basketball-ball"></span>&nbsp;Betting Systems
                </button>

                <div class="collapse show" id="components-collapse">
                    <ul class="list-unstyled fw-normal pb-1 small bg-light rounded">

                        <?php
                        foreach ($systems as $item):
                            $uri = 'betting-system/'.$item->number.'/'.url_title($item->title, '-', true);
                            ?>
                            <li><a class="d-inline-flex align-items-center rounded" href="<?= site_url($uri) ?>">
                                    <span class="bg-success text-white px-1 rounded me-1"><?= $item->number ?></span><?= $item->title ?></a></li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button class="btn d-inline-flex align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#helpers-collapse" aria-expanded="false">
                    <span class="fa fa-user"></span>&nbsp;User Section
                </button>

                <div class="collapse" id="helpers-collapse">
                    <ul class="list-unstyled fw-normal pb-1 small">
                        <?php
                        $classes = 'd-inline-flex align-items-center rounded';

                        if (!@session('user')):
                        ?>
                        <li><?= anchor('/user/login?redirect_to='.current_url(), '<span class="fa fa-sign-in-alt"></span>&nbsp;Login', 'title="Login now!" class="'.$classes.'"') ?></li>
                        <li><?= anchor('/user/register', '<span class="fa fa-user"></span>&nbsp;Register', 'title="Register now!" class="'.$classes.'"') ?></a></li>
                        <?php else: ?>
                            <li><?= anchor('/user/logout', '<span class="fa fa-sign-out-alt"></span>&nbsp;Logout', 'class="'.$classes.'"') ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>

            <li class="my-3 mx-4 border-top"></li>
            <li>
                <?= anchor('about-us', 'About Us', 'class="'.$classes.'"') ?>
            </li>
        </ul>
    </nav>

</aside>
<?php endif; ?>