</div>
</div>
<!--    END Main CONTENT-->

<?php require APPPATH."Views/templates/right-nav.php" ?>
</div>
<!--End CONTENT AND RIGHT NAV Row-->
</main>
</div>

<footer id="footer" class="kpt-footer py-5 mt-5 bg-dark">
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="mainModalLabel2" aria-hidden="true">
    </div>
    <script>
        jQuery(document).ready(function ($){
            let modal = `<?php require 'modal.php' ?>`;
            $('#modal1,#modal2').html(modal)
        })
    </script>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3 mb-3">
                <a class="d-inline-flex align-items-center mb-2 link-dark text-decoration-none" href="<?= site_url() ?>" aria-label="<?= site_name() ?>">
                    <img class="rounded-circle" style="width: 45px; height: auto" src="<?= base_url('public/images/users/Franz-Joseph-Haydn.jpg') ?>" alt="Site Logo">
                   <span class="fs-5 ms-2 text-white-50 text-decoration-underline"><?= site_name() ?></span>
                </a>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2">Designed and built with all the love <a href="https://diplomatsolutions.com">Diplomat Solutions</a></li>
                    <li class="mb-2">Websites designed and published 114+</li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1 mb-3">
                <h5 class="text-white-50 text-decoration-underline">Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= site_url() ?>">Home</a></li>
                    <li class="mb-2"><a href="<?= site_url('betting-system') ?>">Betting Systems</a></li>
                    <li class="mb-2"><a href="<?= site_url('about-us') ?>">About Us</a></li>
                    <li class="mb-2"><a href="<?= site_url('privacy-policy') ?>">Privacy Policy</a></li>
                    <li class="mb-2"><a href="<?= site_url('blog') ?>">Blog</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 mb-3">
                <h5 class="text-white-50 text-decoration-underline">Guides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= site_url('blog/how-to-subscribe') ?>">How to subscribe</a></li>
                    <li class="mb-2"><a href="<?= site_url('blog/bankroll') ?>">Bankroll</a></li>
                    <li class="mb-2"><a href="<?= site_url('blog/betting-rules') ?>">Betting rules</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 mb-3">
                <h5 class="text-white-50 text-decoration-underline">User</h5>
                <ul class="list-unstyled">
                    <?php if (!session('user')): ?>
                    <li class="mb-2"><a href="<?= site_url('user/login') ?>">Login</a></li>
                    <li class="mb-2"><a href="<?= site_url('user/register') ?>">Register</a></li>
                    <?php else: ?>
                    <li class="mb-2"><a href="<?= site_url('user/account') ?>">Account</a></li>
                    <li class="mb-2"><a href="<?= site_url('user/logout') ?>">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-6 col-lg-2 mb-3">
                <h5 class="text-white-50 text-decoration-underline">Social Media</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="https://facebook.com/fool365"><span class="fa fa-facebook"></span>Facebook</a></li>
                    <li class="mb-2"><a href="https://twitter.com/fool365"><span class="fa fa-facebook"></span>Twitter</a></li>
                    <li class="mb-2"><a href="https://instagram.com/fool365"><span class="fa fa-facebook"></span>Instagram</a></li>
                    <li class="mb-2"><a href="https://snapchat.com/fool365"><span class="fa fa-facebook"></span>Snapchat</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>


</body>
</html>