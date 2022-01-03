<div class="container-fluid my-3">
    <div class="d-flex justify-content-center">
        <div class="col-12 bg-light shadow p-2">

            <style>

                #profileImage
                {
                    cursor: pointer;
                }

                #profileImage .text
                {
                    background: rgba(70, 44, 66, 0.6);
                    position: absolute;
                    color: white!important;
                }


                #profile-container {
                    width: 150px;
                    height: 150px;
                    overflow: hidden;
                    -webkit-border-radius: 50%;
                    -moz-border-radius: 50%;
                    -ms-border-radius: 50%;
                    -o-border-radius: 50%;
                    border-radius: 50%;
                }

                #profile-container:hover{
                    opacity:0.7;
                }

                #profile-container img {
                    width: 150px;
                    height: 150px;
                }
            </style>

            <script>
                jQuery(document).ready(function (){

                    $("#profileImage").click(function(e) {
                        $("#imageUpload").click();
                    });

                    function fasterPreview( uploader ) {
                        console.log(uploader.files[0])
                        if ( uploader.files && uploader.files[0] ){
                            $('#profileImage img').attr('src',
                                window.URL.createObjectURL(uploader.files[0]) );
                        }
                    }

                    $("#imageUpload").change(function(){
                        fasterPreview( this );
                    });
                })
            </script>

            <form action="<?= base_url() ?>/user/account/update" method="post" enctype="multipart/form-data">
                <div class="mb-3">
<div class="d-flex justify-content-center">

    <div id="profile-container" class="rounded-circle border border-1 border-dark">
        <div id="profileImage" class="d-flex justify-content-center">
            <img src="<?= profile_photo() ?>">
            <div class="text border border-1 border-light px-1 mt-5 rounded text-white-50">Change Pic</div>

        </div>
    </div>
    <input class="d-none" id="imageUpload" type="file" name="profile_photo" placeholder="Photo" capture accept="image/*">

</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email:&nbsp;</label><input class="form-control" id="email" type="email" name="email" readonly value="<?= $user->email ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="username">Username:&nbsp;</label><input class="form-control" id="username" type="text" name="username" value="<?= old('username') ?? $user->username ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="country">Country:&nbsp;</label><input class="form-control" id="country" type="text" name="country" value="<?= old('country') ?? $user->country ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">Phone:&nbsp;</label><input class="form-control" id="phone" type="text" name="phone" value="<?= old('phone') ?? $user->phone ?>">
                </div>
                    <div class="mb-3">
                        <span class="btn btn-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#collapsePass" aria-expanded="false" aria-controls="collapsePass">
                            Change password
                        </span>
                        <div class="collapse" id="collapsePass">
                            <div class="mb-3">
                                    <label class="form-label" for="password_old">Old Password:&nbsp;</label> <input class="form-control" id="password_old" type="password" name="password_old">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="password">New Password:&nbsp;</label> <input class="form-control" id="password" type="password" name="password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="password_c">Confirm Password:&nbsp;</label> <input class="form-control" id="password_c" type="password" name="password_c">
                                </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end">
                        <button class="btn btn btn-success mb-3 p-2" type="submit" value="save" name="action">Save changes</button>
                </div>

                <input type="hidden" name="redirect_to" value="<?= @$_GET['redirect_to'] ?: site_url() ?>">

            </form>

        </div>
    </div>
</div>