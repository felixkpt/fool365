    <div class="row">

                <div class="col-12 py-1">
                    <div class="row p-0 m-0">
                        <div class="col-12 col-md-6 py-1">
                            <div class="btn-group-vertical">
                                <div class="btn-group">
                                    <?php if (@Session('user')->role == 'administrator'): ?>
                                        <a class="btn btn-success btn-sm me-1 <?php if (!$is_published) echo 'disabled' ?>" href="<?= site_url('betting-system/'.$system.'/'.$slug.'/add') ?>"><span class="fa fa-plus"></span>&nbsp;Add a game</a>
                                        <a class="btn btn-success btn-sm <?php if (!$is_published) echo 'disabled' ?>" href="<?= site_url('betting-system/'.$system.'/'.$slug.'/pull-externally') ?>"><span class="fa fa-plus"></span>&nbsp;Pull from other site</a>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>
                        <?php if ($games || 1 == 1): ?>

                        <div class="col-12 col-md-6">
                            <div class="d-flex justify-content-end">

                                <div class="bg-light px-3">
                                    <span>See how much you can make&nbsp;</span>
                                    <!-- Button trigger modal -->
                                    <a href="#" type="button" class="btn btn-dark text-white btn-sm how-much-i-can-make" data-bs-toggle="modal" data-bs-target="#modal2">Calculate</a>
                                </div>

                                <div class="d-none" id="modalBody">
                                    <form id="calculate" action="<?= site_url('/see-how-much') ?>" method="post">
                                        <div class="how-much">
                                            <div class="mb-4">
                                                <label class="form-label" for="bankroll">Set Bankroll:&nbsp;</label>
                                                <input type="number" required class="form-control" min="1" name="bankroll" id="bankroll" value="<?= session('bankroll') ?>">
                                            </div>
                                            <div class="mb-4">
                                                <div class="row">
                                                    <div class="col-6"><label class="form-label" for="stake_percentage">Initial
                                                            stake:&nbsp;</label>
                                                        <select class="form-select" name="stake_percentage"
                                                                id="stake_percentage">
                                                            <?php $percentages = $p = [1, 2, 3, 5, 7, 10];
                                                            for ($i = 0; $i < count($p); $i++): ?>
                                                                <option value="<?= $p[$i] ?>" <?php if (session('stake_percentage') == $p[$i]) {echo 'selected';} ?>><?= $p[$i] ?>
                                                                    %
                                                                </option>
                                                            <?php endfor ?>
                                                        </select></div>
                                                    <div class="col-6">
                                                        <label class="form-label" for="period">Period:&nbsp;</label>
                                                        <select class="form-select" name="period" id="period">
                                                            <?php
                                                            $p = [30, 60, 90, 180, 360];
                                                            if (@session('user')->role == 'administrator'){
                                                                $p = array_merge($p, [360 * 2, 360 * 3]);
                                                            }
                                                            $periods = $p;
                                                            for ($i = 0; $i < count($p); $i++): ?>
                                                                <option value="<?= $p[$i] ?>" <?php if (session('period') == $p[$i]) {echo 'selected';} ?>><?= $p[$i] / 30 ?> month<?php if ($i > 0){echo 's';} ?></option>
                                                            <?php endfor ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn-warning btn-sm">Calculate</button>
                                                <div class="text-secondary my-2" id="simulatorProfit"><span class="fw-bold">Profit:</span>&nbsp;<span class="fst-italic content">Please tap/click Calculate</span></div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row"><div class="btn btn-light cursor-default" id="paypalButton">
                                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                                <input type="hidden" name="cmd" value="_s-xclick">
                                                <input type="hidden" name="hosted_button_id" value="2X29UUJXPLE8S">
                                                <table>
                                                    <tr><td><input type="hidden" name="on0" value="Dedicated Plan Tips">Dedicated Plan Tips</td></tr><tr><td>
                                                            <select name="os0">
                                                                <option value="2 Weeks">2 Weeks : $12.00 USD - monthly</option>
                                                                <option value="1 month">1 month : $20.00 USD - monthly</option>
                                                                <option value="2 months">2 months : $35.00 USD - monthly</option>
                                                                <option value="3 months">3 months : $50.00 USD - monthly</option>
                                                                <option value="6 months">6 months : $80.00 USD - monthly</option>
                                                                <option value="12 months">12 months : $100.00 USD - monthly</option>
                                                            </select> </td></tr>
                                                </table>
                                                <input type="hidden" name="currency_code" value="USD">
                                                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                            </form>
                                        </div></div>
                                </div>

                                <script>

                                    jQuery(document).ready(function ($){

                                        let period = $('form#calculate').find('[name="period"]').find(':selected').text();
                                        $(`select[name="os0"] option[value="${period}"]`).attr('selected', 'selected');

                                        $('.how-much-i-can-make').click(function (e){
                                            e.preventDefault();

                                            let modal = $('#modal2');
                                            let title = '<span class="fa fa-warehouse"></span>&nbsp;Simulate<div class="text-muted" style="font-size:80%"><?= $title ?></div>';
                                            let body = $('#modalBody').html();
                                            let close = 'Cancel';

                                            modal.find('.modal-dialog').removeClass('modal-sm');
                                            modal.find('.modal-title').html(title);
                                            modal.find('.modal-body').html(body);
                                            modal.find('.modal-close').text(close).addClass('d-none')
                                            modal.find('.modal-confirm').addClass('d-none');
                                            modal.find('.modal-confirm').click(function (){

                                                alert('Is to subscribe');

                                            })

                                            $('form#calculate').submit(function (e){
                                                e.preventDefault();
                                                let form = $(this);
                                                let url = $(this).attr('action');
                                                // console.log(url);return
                                                let data = form.serialize();
                                                // console.log(data)

                                                let period = $(this).find('[name="period"]').find(':selected').text();


                                                $(`select[name="os0"] option[value="${period}"]`).attr('selected', 'selected');


                                                let results = $('#simulatorProfit .content');

                                                results.text('Please wait...');

                                               // send a fetch request to the server
                                               fetch(url ,
                                                   {
                                                       method: 'POST',
                                                       mode: 'no-cors',
                                                       cache: 'no-cache',
                                                       credentials: 'same-origin',
                                                       headers: {
                                                           'Content-type': 'form-data'
                                                       },
                                                       body: new URLSearchParams(data)
                                                   }
                                               ).then((response) => {
                                                    return response.text()
                                               }).then((text) => {
                                                   // console.log(text)
                                                   let parsedResponse = (new window.DOMParser()).parseFromString(text, "text/html");
                                                   let mainContent = parsedResponse.getElementById("mainContent").innerHTML;
                                                   // console.log(mainContent);return
                                                   $('#mainContent').html(mainContent);
                                                   let profit = $('#profit span').text();
                                                   console.log(profit)
                                                   results.text(profit)

                                               }).catch((err) => {
                                                   console.log(err)
                                               })

                                            })

                                        })


                                    })
                                </script>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="col-12">
                    <blockquote class="px-1 border border-1">
                        <?= $description ?>
                    </blockquote>
                </div>
            </div>

    <?php if ($games): ?>
    <div class="row mx-1 rounded p-1 bg-light">
        <div class="col">
            Hello <?php if (session('user')){
                echo word_limiter(session('user')->username, 10);
            }else{ echo 'Guest';} ?>, you are viewing tips for:&nbsp;<?= date('d/m/y', strtotime($start_date)).' to '.date('d/m/y', strtotime($end_date)).', ';
    $diff = ceil(date_diff2($end_date, $start_date, 'months'));
    echo 'Approx '.$diff.' month'.($diff > 1 ? 's' : '').'.';
    ?>
        </div>
    </div>
    <?php endif; ?>
        <div class="row mx-1 rounded p-1 bg-warning">
            <div class="col-6 col-lg-3">
                Set bankroll:&nbsp;<?= currency($grand_summary['initial_bankroll'], $currency, true) ?>
            </div>
            <div class="col-6 col-lg-3">
                Betslips:&nbsp;#<?= $grand_summary['betslips'] ?>
            </div>
            <div class="col-6 col-lg-3" id="currentBankroll">
                Current bankroll:&nbsp;<span><?= currency($grand_summary['current_bankroll'], $currency, true) ?></span>
            </div>
            <div class="col-6 col-lg-3" id="profit">
                Profit:&nbsp;<span><?= currency($grand_summary['gain'], $currency, true) ?></span>
            </div>
        </div>

        <div class="row px-1 my-1">
            <div class="col-12 col-lg-6">

                <div class="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deposits" aria-expanded="false" aria-controls="deposits">
                                View Deposits (<?= count($grand_summary['deposits']) ?>)
                            </button>
                        </h2>
                        <div id="deposits" class="accordion-collapse collapse" aria-labelledby="deposits" data-bs-parent="#deposits">
                            <div class="accordion-body">

                                <table class="table table-sm table-danger table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th><th>Date</th><th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total_deposits = 0;
                                    $ct = 0;
                                    foreach (array_reverse($grand_summary['deposits']) as $key => $deposit):
                                        $total_deposits += $deposit['amount'];
                                        $ct ++;
                                        ?>
                                        <tr>
                                            <td><?= $ct ?>.</td>
                                            <td><?= date('d/m/y', strtotime($deposit['date'])) ?></td>
                                            <td><?= currency($deposit['amount'], $currency, true) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="fw-bold">
                                        <td colspan="2">Totals:</td>  <td><?= currency($total_deposits, $currency, true) ?></td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
                <div class="col-12 col-lg-6">
                    <div class="accordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#withdrawals" aria-expanded="false" aria-controls="withdrawals">
                                    View Withdrawals (<?= count($grand_summary['withdrawals']) ?>)
                                </button>
                            </h2>
                            <div id="withdrawals" class="accordion-collapse collapse" aria-labelledby="Withdrawals" data-bs-parent="#withdrawals">
                                <div class="accordion-body">


                                    <table class="table table-sm table-success table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th><th>Date</th><th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $total_withdrawals = 0;
                                        $ct = 0;
                                        foreach (array_reverse($grand_summary['withdrawals']) as $key => $withdrawal):
                                            $total_withdrawals += $withdrawal['amount'];
                                            $ct ++;
                                            ?>
                                            <tr>
                                                <td><?= $ct ?>.</td>
                                                <td><?= date('d/m/y', strtotime($withdrawal['date'])) ?></td>
                                                <td><?= currency($withdrawal['amount'], $currency, true) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="fw-bold">
                                            <td colspan="2">Totals:</td>  <td><?= currency($total_withdrawals, $currency, true) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>



    <div class="row justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-1 p-1 h-100" id="betslips">
                <?php

                $counter = 0;
                //var_dump($games);die;
                foreach ($games as $game):

                    ?>
                    <div class="col mb-4">
                        <div class="card border-2 border-success h-100 overflow-hidden px-1">


                            <?php
                            $summary = $game['summary'];
                            $games_temp = $game['games'];

                            $counter ++;
                            $games_counter = 0;
                            $heading_echoed = false;

                            //                Start loop betslip games
                            foreach ($games_temp as $game):
                                $games_counter ++;
                                $bg = 'bg-dark';
                                if ($game['status'] == 'Won'){
                                    $bg = 'bg-success';
                                }elseif ($game['status'] == 'Lost'){
                                    $bg = 'bg-danger';
                                }

                                ?>

                                <div class="bd-placeholder-img card-img-top bg-light ">

                                    <div class="row  px-1 h-100">

                                        <?php if ( !$heading_echoed ): $heading_echoed = true; ?>
                                            <div class="col-12">
                                                <div class="row bg-success font-italic py-1">

                                                    <div class="col-7 ps-2" style="font-size: 90%">
                                                        <span class="bg-dark px-1 px-0 rounded border border-light rounded text-light">Betslip #<?= $summary['betslips'] ?></span>
                                                    </div>
                                                    <div class="col-5" style="font-size: 80%">
                                                        <div class="d-flex justify-content-end">
                                                            <div class="row p-0 pe-2">
                                                                <div class="col-12 px-1 px-0 rounded border border-light text-light">Level #<?= $summary['level'] ?></div>
                                                                <?php if ($system == 7):
                                                                    ?>
                                                                    <div class="col-12 mt-1 px-1 px-0 rounded border border-white text-white">Sub level #<?= $summary['sub_level'] ?></div>
                                                                <?php
                                                                endif;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-12 p-0 betting-systems">
                                            <table class="table h-100 table-light table-hover responsive-table-height">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="col-12">
                                                            <div class="border-bottom border-secondary w-100">
                                                                Game <?php if (count($games_temp) > 1); echo '# '.$games_counter ?>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>Bet</th>
                                                    <th title="Status">W/L</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>

                                                        <div class="row p-0 m-0">
                                                            <div class="col-12 text-muted" style="font-size: small"><?= date('d/m/y H:i', strtotime($game['date'])) ?></div>
                                                            <div class="col-12">
                                                                <div class="responsive-width" class="text-dark">

                                                                    <?= $game['home_team'] ?> vs <?= $game['away_team'] ?>

                                                                </div>


                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td style="font-size: 80%"><?= strtoupper(preg_replace('/_/',  ' ', $game['bet'])) ?> @ <?= $game['odds'] ?></td>
                                                    <td title="<?= $game['status'] ?>"><div style="width: 27px" class="px-1 rounded rounded-lg text-white text-center <?= $bg ?>"><?= $game['status'][0] ?></div></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <?php if (@Session('user')->role == 'administrator'): ?>
                                            <div class="col-12 p-0" style="font-size: 80%">
                                                <div class="row justify-content-center">
                                                    <div class="btn-group px-4">
                                                        <a class="btn btn-sm btn-info" href="<?= site_url('betting-system/'.$system.'/'.$slug.'/edit/'.$game['id']) ?>">Edit</a>
                                                        <a class="btn btn-sm btn-danger delete" data-bs-toggle="modal" data-bs-target="#modal1" href="<?= site_url('betting-system/'.$system.'/'.$slug.'/delete/'.$game['id']) ?>">Delete</a>
                                                    </div>
                                                    <div class="col-10 border-bottom border-2 border-dark mx-3 pb-1">
                                                        &nbsp;
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                </div>
                            <?php
                            endforeach;
                            //end loop betslip games
                            ?>
                            <div class="card-body mt-1 h-100">
                                <h5 class="card-title">Betting details</h5>
                                <p class="card-text">
                                <div class="row">

                                    <?php if (count($games_temp) > 1) {
                                        ?>
                                        <div class="col-12">
                                            <div class="row border-bottom mb-1 pb-1">
                                                <div class="col-8">
                                                    Multi. Odds: <?= $summary['total_odds'] ?>

                                                </div>
                                                <div class="col-4" title="<?= $summary['status'] ?>">
                                                    <?php
                                                    $bg = 'bg-dark';
                                                    if ($summary['status'] == 'Won'){
                                                        $bg = 'bg-success';
                                                    }elseif ($summary['status'] == 'Lost'){
                                                        $bg = 'bg-danger';
                                                    }
                                                    ?>
                                                    <div style="width: auto" class="rounded rounded-lg text-white text-center <?= $bg ?>"><?= $summary['status'] ?></div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                    } ?>

                                    <div class="col-6 p-0 ps-2">
                                        <div class="row">
                                            <div class="px-0 col-6 col-md-12">
                                                Bankroll:
                                            </div>
                                            <div class="px-0 col-6 col-md-12">
                                                <span class="rounded border border-1 border-warning px-1"><?php echo $summary['bankroll'] ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 p-0">
                                        <div class="row">
                                            <div class="col-6 col-md-12">
                                                Stake:
                                            </div>
                                            <div class="col-6 col-md-12">
                                                <span class="rounded border border-1 border-warning px-1"><?php echo $summary['stake'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-12 mt-1" style="cursor: help" title="The Bankroll After Bet Results like Win or Lose">
                                        Bankroll After W/L:&nbsp;<span class="rounded border border-1 border-warning px-1"><?= currency($summary['bankroll_after_res'], $currency, 'true') ?></span>
                                    </div>
                                </div>
                                <hr>

                                <div class="row border border-3 border-success">
                                    <div class="col-12">
                                        <span class="text-decoration-underline">Net Profit:</span>&nbsp;<small class="font-italic"><?= currency($summary['gain'], $currency, 'true') ?></small>
                                    </div>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>

                <?php
                endforeach;
                ?>
            </div>
            <?php
            if (!$games):

                 if (!$is_published): ?>
                <div class="row">
                    <div class="alert alert-info">This system is not yet published.</div>

                </div>
            <?php else: ?>
                <div class="row">
                    <div class="alert alert-warning text-center text-dark">Oops! Seems there are no games yet.</div>
                </div>
            <?php
                 endif;

            else:


                $prev_page = $requested_page - 1;
            $next_page = $requested_page + 1;

            $disabled_prev = '';
            if ($prev_page < 1){
                $prev_page = 1;
                $disabled_prev = ' disabled';
            }
                $disabled_next = '';
                if ($next_page > $last_page){
                    $next_page = $last_page;
                    $disabled_next = ' disabled';
                }
                ?>

            <div class="d-flex justify-content-between">
                <div class="w-100 border-bottom border-2 border-dark mx-3 p-1 my-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <?php  $url = site_url('betting-system/'.$system.'/'.url_title($title, '-', true)); ?>
                        <a class="btn btn-light btn-sm<?= $disabled_prev ?>" href="<?= $url.'?page='.$prev_page.'&per_page='.$per_page ?>"><span class="fa fa-arrow-left"></span>&nbsp;Previous page</a>
                        <a class="btn btn-light btn-sm<?= $disabled_next ?>" href="<?= $url.'?page='.$next_page.'&per_page='.$per_page ?>"><span class="fa fa-arrow-right"></span>&nbsp;Next page</a>
                    </div>
                </div>
            </div>
            <?php

                if (@Session('user')->role == 'administrator'): ?>
                <div class="col-12 p-0" style="font-size: 80%">
                    <div class="row justify-content-center">
                        <div class="btn-group px-4">



                            <!-- Button trigger modal -->
                            <a href="<?= site_url('betting-system/'.$system.'/'.$slug.'/delete/all/'.session('user')->id) ?>" type="button" class="btn btn-danger delete-all" data-bs-toggle="modal" data-bs-target="#modal1">
                                Delete All games
                            </a>

                        </div>
                    </div>
                </div>
            <?php endif;
//            Endif admin
            ?>

            <?php endif;
//            Endif Games
            ?>

<script>

                jQuery(document).ready(function ($){

                    $('.delete, .delete-all').click(function (e){

                        e.preventDefault();

                        let href = $(this).attr('href');
                        
                        let modal = $('#modal1');
                        let title = 'Deleting games';
                        let body = 'Surely delete all games?';
                        let close = 'Nope';
                        let confirm = 'Yes, Delete';

                        modal.find('.modal-dialog').removeClass('modal-sm');
                        if ($(this).hasClass('delete')){
                            title = 'Deleting game';
                            body = 'Surely delete this game?';
                            modal.find('.modal-dialog').addClass('modal-sm');

                        }

                        modal.find('.modal-title').html(title);
                        modal.find('.modal-body').html(body);
                        modal.find('.modal-close').text(close).addClass('btn-info')
                        modal.find('.modal-confirm').text(confirm).addClass('btn-danger');

                        modal.find('.modal-confirm').click(function (){

                            window.location.href = href;
                        })
                    })

                })
            </script>