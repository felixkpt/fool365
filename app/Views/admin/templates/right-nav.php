<div class="row justify-content-center">
    <div class="col-10">

        <?php
if ($page == 'prediction'):

$teams = (New \App\Models\TeamsModel())->where('competition_id', $prediction->competition_id)->orderBy('position', 'asc')->get()->getResult();

$name_echo = explode(' ', $prediction->competition)[0];
if (strlen($name_echo) < 3){
$name_echo = $prediction->competition;
}

?>
        <div class="row justify-content-center border-top">
            <h5 class="text-secondary">
                <?= $name_echo ?> Table
            </h5>
        </div>
        <table class="table table-striped table-hover">
            <thead>
            <tr class="row table-warning">
                <td class="col-1" title="League position">#</td>
                <td class="col-8">Team</td>
                <td class="col-2">Points</td>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 0;
            foreach ($teams as $team){
                ++$counter;
                ?>
                <tr class="row">
                    <td class="col-1">
                        <div class="text-info border-left">
                            &nbsp;<?= $counter ?>
                        </div>
                    </td>
                    <td class="col-8">
                        <div class="pl-2 text-nowrap overflow-hidden" style="text-overflow: ellipsis" title="Click to view more about <?= $team->team ?>"><a href="<?= base_url('teams/'.$team->slug) ?>" rel="bookmark" class="text-dark"><?= $team->team ?></a></div>
                    </td>
                    <td class="col-2"><?= $team->points ?></td>
                </tr>
                <?php
            }
            ?>
            <tr class="row justify-content-end">
                <td>
                    <span class="text-muted d-md-none">Go to full</span><span class="text-muted d-none d-md-inline">Go to</span>&nbsp;<a class="text-dark" href="<?= site_url('competitions/'.$prediction->competition_slug) ?>"><?= $prediction->competition ?> table&nbsp;<span class="fa fa-arrow-circle-right" style="font-size: 80%"></span></a>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
                else:
            ?>
        Ad section
        <?php
        endif;
        ?>
    </div>
</div>