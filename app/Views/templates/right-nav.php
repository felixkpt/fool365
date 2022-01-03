<?php if (!@$hide_navbars): ?>
<!--    Begin Right Nav-->
<div class="col-12 col-lg-2 p-1">
    <div class="kpt-toc rounded mx-1 px-1 mt-2 mt-lg-0 text-muted">
    <strong class="d-block h6 my-2 pb-2 border-bottom">More content</strong>
    <nav id="TableOfContents">
        <ul>
            <li><a href="https://sportperfected.com/predictions/">Predictions</a>
                <ul>
                    <li><a href="https://sportperfected.com/predictions/today">Today</a></li>
                    <li><a href="https://sportperfected.com/predictions/tomorrow">Tomorrow</a></li>
                    <li><a href="https://sportperfected.com/predictions/yesterday">Yesterday</a></li>
                </ul>
            </li>
            <li><a href="<?= site_url('blog') ?>">Blog</a>
                <ul>
                    <li><a href="<?= site_url('blog') ?>#1">Article#1</a></li>
                    <li><a href="<?= site_url('blog') ?>#2">Article#2</a></li>
                    <li><a href="<?= site_url('blog') ?>#3">Article#3</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
</div>
<!--    End Right Nav-->
<?php endif; ?>