    <div class="container-fluid my-3">
    <a href="<?= site_url('betting-system/'.$system.'/'.$slug) ?>" class="btn btn-light mb-2">Back to system</a>
    <div class="d-flex justify-content-center">
        <div class="col-12 bg-light shadow">
            <h3>Fill games details</h3>
            <script>

                let DateDiff = {

                    inDays: function (d1, d2) {
                        var t2 = d2.getTime();
                        var t1 = d1.getTime();

                        return Math.floor((t2 - t1) / (24 * 3600 * 1000));
                    }
                }

                jQuery(document).ready(function($){

                    $('#form').submit(function (e){
                        e.preventDefault();
                        let btn = $(this).find('button')
                        btn.attr('disabled', 'disabled');
                        // console.log(btn)
                        let form = $(this);

                        let data = form.serialize();
                        let start_date = form.find('[name=start_date]').val();
                        let end_date = form.find('[name=end_date]').val();

                        start_date = new Date(start_date);
                        end_date = new Date(end_date ? end_date : start_date);

                        let diff = DateDiff.inDays(start_date, end_date);

                        let array = [];
                        if (diff >= 0){
                            for (let i=0; i<=diff;i++){
                                array.push(i);
                            }

                        }else {
                            console.log('Start date and end date error.');
                        }


                        Promise.all(array.map(
                            add =>

                            fetch(`${site_url}pull`, {
                                method: "POST",
                                mode: "no-cors",
                                cache: "no-cache",
                                credentials: "same-origin",
                                headers: {
                                    "Content-Type": "form-data"
                                },
                                body: new URLSearchParams(data + '&add=' + add),
                            }).then((response) => {

                                return response.json();
                            }).then((json) => {
                                let response;
                                response = json.response;

                                if (response == 'success'){
                                    // console.log(json.message);

                                    let message;
                                    message = json.message;

                                    let node;
                                    node = `<div class="alert alert-${response} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                                    $('#alertSection').append(node);

                                }
                            }).catch((err) => {
                                let node;
                                node = `<div class="alert alert-danger alert-dismissible fade show" role="alert">${err}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                                $('#alertSection').append(node);
                                btn.removeAttr('disabled');

                            })

                        )).then(() => {
                            let message = `Finished fetching for specified dates!`;
                                let node;
                                node = `<div class="alert alert-dark text-white alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                                $('#alertSection').append(node);
                                btn.removeAttr('disabled');


                                let modal = $('#modal1');
                                let mod = new bootstrap.Modal(modal);
                                mod.show();

                            let title = 'Background process';
                            let body = message;
                            let close = 'Close';
                            let confirm = 'Back to System';

                            modal.find('.modal-title').html(title);
                            modal.find('.modal-body').html(body);
                            modal.find('.modal-close').text(close).addClass('btn-secondary')
                            modal.find('.modal-confirm').text(confirm).addClass('btn-primary');

                            modal.find('.modal-confirm').click(function (){

                                window.location.href = `<?= site_url('betting-system/'.$system.'/'.$slug) ?>`;
                            })


                        }).catch((err) => {

                        })


                    })

                })


            </script>
            <form action="<?= site_url('/pull') ?>" id="form" method="post">
                <input type="hidden" name="system" value="<?= $system ?>">
<!--                <input type="hidden" name="add" value="2">-->

                <div class="mb-3">
                    <?php $users = (new \App\Models\UserModel())->get()->getResult(); ?>
                    <label for="user_id">Setting for user:&nbsp;</label>
                    <select name="user_id" id="user_id">
                        <?php
                        foreach ($users as $user): ?>
                            <option value="<?= $user->id ?>" <?php if ($user->id == session('user')->id) echo 'selected' ?>><?= $user->username.' ('.$user->email.')' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="min_percentage" class="form-label">Min Percentage:&nbsp;</label><input class="form-control" type="number" id="min_percentage" name="min_percentage" value="<?= @old('min_percentage') ?? $min_percentage ?>" required="">
                </div>
                <div class="mb-3">
                    <label for="min_odds" class="form-label">Min Odds:&nbsp;</label><input class="form-control" type="text" id="min_odds" name="min_odds" value="<?= @old('min_odds') ?? $min_odds ?>" required="">
                </div>
                <div class="mb-3">
                    <label for="max_odds" class="form-label">Max Odds:&nbsp;</label><input class="form-control" type="text" id="max_odds" name="max_odds" value="<?= @old('max_odds') ?? $max_odds ?>">
                </div>
                <div class="mb-3">
                    <label for="start_date">Start date:&nbsp;</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= old('start_date') ?>" required="">
                </div>
                <div class="mb-3">
                    <label for="end_date">End date:&nbsp;</label>
                    <input type="date" class="form-control" id="end_date" value="<?= old('end_date') ?>" name="end_date">
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label for="end_date">Limit:&nbsp;</label>
                            <input type="text" class="form-control" id="limit" value="<?= old('limit') ?>" name="limit">
                        </div>
                        <div class="col-8 pt-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="include_unsettled" value="<?= old('include_unsettled') ?>" name="include_unsettled">
                                <label for="include_unsettled" class="form-check-label">&nbsp;Include unsettled</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <label for="bet" class="form-label">Bet:&nbsp;</label>
                    <select class="form-control" id="bet" name="bet[]" multiple>
                        <option value="random" selected>Random</option>
                            <?php foreach (markets() as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex">
                    <div class="col-12 p-1">
                        <button class="float-right btn btn btn-success mb-3 p-2" type="submit" value="save" name="action">Pull Now!</button>
                    </div>
                </div>
            </form>
            <div id="alertSection"></div>

        </div>

    </div>
</div>