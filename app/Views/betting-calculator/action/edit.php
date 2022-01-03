    <div class="container-fluid my-3">
    <a href="<?= site_url('betting-system/'.$system.'/'.$slug) ?>" class="btn btn-light mb-2">Back to system</a>
    <div class="d-flex justify-content-center">
        <div class="col-12 bg-light shadow">
            <h3>Fill game details here</h3>
            <form action="<?= site_url('/save/'.$game['id']) ?>" method="post">
                <input type="hidden" name="user_id" value="<?= session('user')->id ?>">
                <input type="hidden" name="system" value="<?= $system ?>">
                <div class="mb-3">
                    <label for="home_team" class="form-label">Home team:&nbsp;</label><input class="form-control" type="text" id="home_team" name="home_team" value="<?= $game['home_team'] ?>" required="">
                </div>
                <div class="mb-3">
                    <label for="away_team" class="form-label">Away team:&nbsp;</label><input class="form-control" type="text" id="away_team" name="away_team" value="<?= $game['away_team'] ?>">
                </div>
                <div class="mb-3">
                    <label for="date">Kick off:&nbsp;</label>
                    <input type="text" id="date" name="date" value="<?= $game['date'] ?>" required="">
                </div>

                <div class="mb-3">
                    <label for="bet" class="form-label">Bet:&nbsp;</label>
                    <select class="form-control" id="bet" name="bet">
                        <?php foreach (markets() as $key => $value): ?>
                            <option <?php if ($game['bet'] == $value) {echo "selected";} ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="odds">Odds:&nbsp;</label>
                    <input type="text" required="" name="odds" value="<?= $game['odds'] ?>">
                </div>

                <div class="mb-3">
                    <label for="status">Status:&nbsp;</label>
                    <select name="status" id="stats">
                        <option <?php $k = 'Unsettled'; if ($game['status'] == $k) { echo 'selected';} ?>><?= $k ?></option>
                        <option <?php $k = 'Won'; if ($game['status'] == $k) { echo 'selected';} ?>><?= $k ?></option>
                        <option <?php $k = 'Lost'; if ($game['status'] == $k) { echo 'selected';} ?>><?= $k ?></option>
                    </select>
                </div>

                <div class="d-flex">
                    <div class="col-12 p-1">
                        <button class="float-right btn btn btn-success mb-3 p-2" type="submit" value="save" name="action">Save!</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>