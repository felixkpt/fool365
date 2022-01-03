<div class="container-fluid my-3">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-4 bg-light shadow p-2">
            <?php require APPPATH.'Views/templates/flash-data.php' ?>
            <form action="<?= base_url() ?>/user/login/auth" method="post">
                <div class="text-center">
                    <a href="<?= site_url() ?>"><img class="mb-4 rounded-circle" style="width: 55px; height: auto"  src="<?= base_url('public/images/users/Franz-Joseph-Haydn.jpg') ?>" alt="" width="72" height="57"></a>
                    <h1 class="h3 mb-3 fw-normal"><?= $title ?></h1>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email:&nbsp;</label><input class="form-control" id="email" type="email" name="email"  value="<?= @old('email') ?>">

                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password:&nbsp;</label> <input class="form-control" id="password" type="password" name="password">
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <button class="btn btn btn-success mb-3 p-2" type="submit" value="save" name="action">Login</button>

                    </div>
                    <div class="col-12 col-md-8 p-1">
                        Not Registered? <a href="<?= base_url() ?>/user/register">Register <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

                <input type="hidden" name="redirect_to" value="<?= @$_GET['redirect_to'] ?: site_url() ?>">

            </form>

            <div id="my-signin2" data-onsuccess="onSignIn"></div>
            <script type="text/javascript">

                const urlSearchParams = new URLSearchParams(window.location.search);
                const params = Object.fromEntries(urlSearchParams.entries());
                let redirect_to = params.redirect_to ? params.redirect_to : '';

                function onSignIn(googleUser){
                    onSuccess(googleUser);
                }

                function onSuccess(googleUser) {

                    let id_token = googleUser.getAuthResponse().id_token;
                    // console.log(id_token)
                    let xhr = new XMLHttpRequest();
                    let url = site_url + 'user/tokensignin';
                    xhr.open('POST', url);
                    xhr.overrideMimeType("application/json");
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        let resp = JSON.parse(xhr.responseText);
                        let message = resp.message;
                        let redirect_to = resp.redirect_to;
                        console.log('Message: ' + message);
                        console.log(redirect_to);
                        window.location.href = redirect_to;
                    };
                    // console.log(redirect_to);
                    xhr.send('idtoken=' + id_token + '&redirect_to=' + (redirect_to));

                }
                function onFailure(error) {
                    console.log(error);
                }
                function renderButton() {
                    gapi.signin2.render('my-signin2', {
                        'scope': 'profile email',
                        'width': 350,
                        'height': 30,
                        'longtitle': true,
                        'theme': 'dark',
                        'onsuccess': onSuccess,
                        'onfailure': onFailure
                    });
                }
            </script>

            <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

        </div>
    </div>
</div>