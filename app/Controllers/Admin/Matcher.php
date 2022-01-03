<?php
use App\Models\PredictionsModel;
use App\Models\ResultsModel;

class Matcher{

    public function index(){

        ?>
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="row">
                    <h1>Matcher details</h1>
                </div>
                <form action="" method="post">
                    <div class="form-group">
                        <input type="date" name="date" class="form-control">
                    </div>
                    <input type="hidden" name="action" value="match">
                    <button class="btn btn-outline-info">Match</button>
                </form>
            </div>
        </div>
        <?php

        if (@$_GET['cron'] == 'true'){
            $_POST = $_GET;
        }

        if (@$_POST['action'] == 'match'){

            $limit = 10;

            $SmithWatermanGotoh = new SmithWatermanGotoh();

            for ($i=0;$i<$limit;$i++){

                $date = date('Y-m-d', strtotime($_POST['date']." +$i days"));

                echo "Date: ".$date."<br>";

                $predictions = (New PredictionsModel())->like('date_time', $date)->get()->getResultArray();
                $results = (New ResultsModel());

                $results_matched = $results->like('date_time', $date)->where('prediction_id > ', 0)->get()->getResultArray();
                $results = $results->like('date_time', $date)->get()->getResultArray();


                echo "Total Matches: ".count($predictions)."<br>";
                echo "Total Results: ".count($results)."<br>";
                echo "Pre-matched results: ".count($results_matched)."<br>";

                $counter = 0;
                foreach ($predictions as $prediction){

                    foreach ($results as $result){

                        $home_team = url_title($prediction['home_team'], '-', true);
                        $away_team = url_title($prediction['away_team'], '-', true);

                        $pred = $home_team.' vs '.$away_team;

                        $home_team_res = url_title($result['home_team'], '-', true);
                        $away_team_res = url_title($result['away_team'], '-', true);

                        $res = $home_team_res.' vs '.$away_team_res;

                        $percentage = $SmithWatermanGotoh->compare($home_team, $home_team_res);
                        $percentage2 = $SmithWatermanGotoh->compare($away_team, $away_team_res);

                        if ($percentage > .7 && $percentage2 > .7){
                            ++$counter;

                            ?>
                            <div class="row">
                                <div class="col-12">
                                    <?= $counter.'. '.$pred.'<---->'.$res.'('.number_format_strict($percentage, 2).')' ?>
                                    <?php
                                    //update res with prediction ID
                                    $updated = (New PredictionsModel())->update($prediction['id'], ['halftime_scores' => $result['halftime_scores'], 'fulltime_scores' => $result['fulltime_scores']]);

                                    $updated2 = (New ResultsModel())->update($result['id'], ['prediction_id' => $prediction['id']]);
                                    if ($updated && $updated){

                                        echo "<span class='text-success'>Updated results.</span>";
                                    }else{
                                        echo "<span class='text-danger'>Error updating results.</span>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php


                        }

                    }

                }

                ?>
                <div class="row">
                    <div class="col-7"><?= 'Matched: '.$counter.'/'.count($predictions).' ('. @number_format_strict(($counter/count($predictions)) * 100).'%)' ?></div>
                </div>
                <?php

                echo "<br><hr>";
            }
//            end for
        }
//        endif post

    }
}

$matcher = (New Matcher())->index();


    class SmithWatermanGotoh
    {
    private $gapValue;
    private $substitution;

/**
* Constructs a new Smith Waterman metric.
*
* @param gapValue
*            a non-positive gap penalty
* @param substitution
*            a substitution function
*/
public function __construct($gapValue=-0.5,
$substitution=null)
{
if($gapValue > 0.0) throw new Exception("gapValue must be <= 0");
//if(empty($substitution)) throw new Exception("substitution is required");
if (empty($substitution)) $this->substitution = new SmithWatermanMatchMismatch(1.0, -2.0);
else $this->substitution = $substitution;
$this->gapValue = $gapValue;
}

public function compare($a, $b)
{
if (empty($a) && empty($b)) {
return 1.0;
}

if (empty($a) || empty($b)) {
return 0.0;
}

$maxDistance = min(mb_strlen($a), mb_strlen($b))
* max($this->substitution->max(), $this->gapValue);
return $this->smithWatermanGotoh($a, $b) / $maxDistance;
}

private function smithWatermanGotoh($s, $t)
{
$v0 = [];
$v1 = [];
$t_len = mb_strlen($t);
$max = $v0[0] = max(0, $this->gapValue, $this->substitution->compare($s, 0, $t, 0));

for ($j = 1; $j < $t_len; $j++) {
$v0[$j] = max(0, $v0[$j - 1] + $this->gapValue,
$this->substitution->compare($s, 0, $t, $j));

$max = max($max, $v0[$j]);
}

// Find max
for ($i = 1; $i < mb_strlen($s); $i++) {
$v1[0] = max(0, $v0[0] + $this->gapValue, $this->substitution->compare($s, $i, $t, 0));

$max = max($max, $v1[0]);

for ($j = 1; $j < $t_len; $j++) {
$v1[$j] = max(0, $v0[$j] + $this->gapValue, $v1[$j - 1] + $this->gapValue,
$v0[$j - 1] + $this->substitution->compare($s, $i, $t, $j));

$max = max($max, $v1[$j]);
}

for ($j = 0; $j < $t_len; $j++) {
$v0[$j] = $v1[$j];
}
}

return $max;
}
}

class SmithWatermanMatchMismatch
{
private $matchValue;
private $mismatchValue;

/**
* Constructs a new match-mismatch substitution function. When two
* characters are equal a score of <code>matchValue</code> is assigned. In
* case of a mismatch a score of <code>mismatchValue</code>. The
* <code>matchValue</code> must be strictly greater then
* <code>mismatchValue</code>
*
* @param matchValue
*            value when characters are equal
* @param mismatchValue
*            value when characters are not equal
*/
public function __construct($matchValue, $mismatchValue) {
if($matchValue <= $mismatchValue) throw new Exception("matchValue must be > matchValue");

$this->matchValue = $matchValue;
$this->mismatchValue = $mismatchValue;
}

public function compare($a, $aIndex, $b, $bIndex) {
return ($a[$aIndex] === $b[$bIndex] ? $this->matchValue
: $this->mismatchValue);
}

public function max() {
return $this->matchValue;
}

public function min() {
return $this->mismatchValue;
}
}
