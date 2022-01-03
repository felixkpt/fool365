<?php
use Goutte\Client;

use \Symfony\Component\DomCrawler\Crawler;
use App\Models\ResultsModel;

class Results {

    protected $model;
    protected $data;

    public function __construct()
    {
        $this->model = New ResultsModel();
    }

    public function xscores(){
        ?>
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="row">
                    <h1>Source details</h1>
                </div>
                <form action="" method="post">
                    <div class="form-group">
                        <input type="url" name="url" class="form-control" placeholder="Input url">
                    </div>
                    <input type="hidden" name="action" value="fetch">
                    <button class="btn btn-outline-info">Fetch</button>
                </form>
            </div>
        </div>
        <?php

        if (@$_GET['cron'] == 'true'){
            $_POST = $_GET;
        }


        if (@$_POST['action'] == 'fetch') {

            require_once(helpers_dir() . "/fabpot_goutte/vendor/autoload.php");

            $source = 'xscores';

            $url = $_POST['url'];

            $url = $url ?: "http://localhost/files/Football%20&%20Soccer%20Scores%20-%20XSCORES2.html";

            $d = $url;
            if (!$_POST['url']){
                $d = '27-01';
            }

            preg_match_all("#[0-9]{2}#", $d, $matches);

            $date_input = @$matches[0][0];
            $month_input = @$matches[0][1];

            echo "Source: $url<br>";

            if (!is_numeric($date_input) || !is_numeric($month_input)){
                die('Invalid url.');
            }

            $date = $month_input.'-'.$date_input;

            $month = date('m');
            $year = date('Y');
            if ($month > 6 && $month_input < 6){
                $year += 1;
            }elseif ($month < 6 && $month_input > 6){
                $year -= 1;
            }

            $date = $year.'-'.$date;
//var_dump($date);die();

            $client = new Client();

            $crawler = null;
            try {
                $crawler = $client->request('GET', $url);

            }
            catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage(). "<br>";
            }


            $links = [];
            if ($crawler) {

                $games = $crawler->filter('a.match_line.score_row.other_match.e_true')->each(function ($node) {
                    return $node->html();
                });

                $counter = 0;
                foreach ($games as $game) {

                    ++$counter;

                    $game = New Crawler($game);

                    $kick_off_time = @$game->filter('#ko_time')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($ko_time);die();
                    $status = @$game->filter('.score-status')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($status);die();
                    $competition = @$game->filter('.score_league .score_league_txt')->each(function ($node){
                        return $node->attr('title');
                    })[0];
//                    var_dump($competition);die();
                    $home_team = @$game->filter('.score_home .score_home_txt')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_team);die();
                    $home_team_league_position = @$game->filter('.score_home .lp')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_league_position);die();
                    $away_team = @$game->filter('.score_away .score_away_txt')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($away_team);die();
                    $away_team_league_position = @$game->filter('.score_away .lp')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($away_league_position);die();
                    $home_team_scores_ht = @$game->filter('.score_ht .scoreh_ht')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_team_scores_ht);die();
                    $away_team_scores_ht = @$game->filter('.score_ht .scorea_ht')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_team_scores_ht);die();
                    $home_team_scores_ft = @$game->filter('.score_score .scoreh_ft')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_team_scores_ft);die();
                    $away_team_scores_ft = @$game->filter('.score_score .scorea_ft')->each(function ($node){
                        return $node->text();
                    })[0];
//                    var_dump($home_team_scores_ft);die();

                        $date_time = date('Y-m-d H:i:s', strtotime($date.' '.$kick_off_time));

                        $halftime_scores = $home_team_scores_ht.'-'.$away_team_scores_ht;
                        $fulltime_scores = $home_team_scores_ft.'-'.$away_team_scores_ft;

                    $data = ['source' => $source, 'date_time' => $date_time, 'status' => $status, 'competition' => $competition, 'home_team' => $home_team,
                        'away_team' => $away_team, 'home_team_league_position' => $home_team_league_position, 'away_team_league_position' => $away_team_league_position,
                        'halftime_scores' => $halftime_scores, 'fulltime_scores' => $fulltime_scores];

                    $proceed = true;
                    foreach ($data as $datum){
                        if (!isset($datum)){
                            $proceed = false;
                        }
                    }

//                    && !preg_match("#SCH|FTR#i", $status);

                    if ($proceed == true){
                        $this->data = $data;

                        $res = $this->save();

                        if ($res == 'true'){
                            ?>
                            <div class="alert alert-success">
                                <?= $counter ?>. Successfully saved data.
                            </div>
                            <?php
                        }elseif ($res == 'exists'){
                            ?>
                            <div class="alert alert-warning">
                                <?= $counter ?>. Updated existing results.
                            </div>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-danger">
                                <?= $counter ?>. Error saving data.
                            </div>
                            <?php
                        }
                    }

//                    die();

                }
//                end loop


            }


            }


    }


    function save(): string
    {

//		create table if missing
        $this->create_table();

        $slug = url_title($this->data['home_team'].'-vs-'.$this->data['away_team'], '-', TRUE);

        $data = array_merge($this->data, array('slug' => $slug));

        $this->model->where('date_time', $data['date_time']);
        $exists = @$this->model->where(['slug' => $data['slug']])->get()->getResult()[0];


        if (!$exists){

        try {
                $this->model->insert($data);
            } catch (ReflectionException $e) {
            }

            return 'true';
        }else{
//            update results
            try {
                $this->model->update($exists->id, $data);
            } catch (ReflectionException $e) {
            }

            return 'exists';
        }


    }

    function create_table(){

        @$this->model->query("CREATE TABLE if not exists `results`
			(
			    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			source varchar(50) NOT NULL,
date_time varchar(100) NOT NULL,
status varchar(10) NOT NULL,
    competition varchar(100) NOT NULL,
home_team varchar(100) NOT NULL,
away_team varchar(100) NOT NULL,
slug varchar(255) NOT NULL,
home_team_league_position varchar(10) NULL,
away_team_league_position varchar(20) NULL,
halftime_scores varchar(50) NULL,
    fulltime_scores varchar(50) NULL,
    prediction_id varchar(50) NULL

			)");

    }

}

$results = (New Results())->xscores();