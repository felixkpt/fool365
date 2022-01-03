<?php
namespace App\Controllers\Admin;
use App\Models\FetcherModel;
use App\Models\PredictionsModel;

class Fetcher {

    protected $model;
    protected $data = [];

    public function __construct()
    {
        $this->model = New FetcherModel();
    }

    public function index(){

        if (@$_GET['action'] == 'create'){
            $this->model->setTable('predictions2');
            $games = $this->model->get()->getResult('array');

            foreach ($games as $data){
                unset($data['id']);
                $table = (string) 'predictions-'.date('m', strtotime($data['date_time']));
                $this->model = (New PredictionsModel())->setTable($table);

                (New Fetcher())->create_predictions_table($table);

                $exists = $this->model->where('slug', $data['slug']);
                $exists = @$this->model->like('date_time', date('Y-m-d', strtotime($data['date_time'])))->get()->getResult()[0];

                if (!$exists){
                    $this->model->insert($data);
                }

                echo "Succeed.";
                echo "<br>";
            }

        }
        ?>
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="row">
                    <h1>Souce details</h1>
                </div>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-control" placeholder="Input date">
                        <label for="type">Type</label>
                        <select id="type" name="type" class="form-control">
                            <option value="1x2">1X2</option>
                            <option value="uo">UO</option>
                            <option value="bts">BTS</option>
                        </select>

                    </div>
                    <input type="hidden" name="action" value="fetch">
                    <button class="btn btn-outline-info">Fetch</button>
                </form>
            </div>
        </div>
        <?php
        require_once(helpers_dir() . "/fabpot_goutte/vendor/autoload.php");

        if (@$_GET['cron'] == 'true'){
            $_POST = $_GET;
        }

        $type = @$_POST['type'];
        if (!$type){
            $type = '1x2';
        }

        $types = [$type];

        if (@$_POST['action'] == 'fetch-all'){
            $db = db_connect();
            $fetcher = 'fetcher-'.$type;
            $query = $db->query("select * from options where name = '$fetcher' ");
            $exists = @$query->getResult()[0];

            if (!$exists){
                $db->table('options')->insert(['name' => $fetcher, 'value' => '2019-06-03']);

                $query = $db->query("select * from options where name = '$fetcher' ");
                $exists = $query->getResult()[0];
            }

            $fetcher_details = $exists;
            $_POST['date'] = $exists->value;
            $_POST['action'] = 'fetch';

        }


//        $_POST['action'] = 'fetch';
        if (@$_POST['action'] == 'fetch'){

//            Begin Node fetcher
            $date = $_POST['date'];
            if ($date < 5){
                echo "No date specified.";die();
            }else{
                echo "Date: $date, Type: $type<br>";
            }

//Junior assisted in debugging this code on 2021-04-09 at around 20:45 hrs
            $node_server = "http://localhost:3000";
            if (!is_localhost()){
                $node_server = "https://sportperfected.herokuapp.com";
            }

            $res = @file_get_contents($node_server."/admin/pages/fetcher-fore?date=$date&type=$type");
            if ($res){
                echo "Successfully contacted node site.<br>";
            }else{
                echo "Error contacting node site.<br>";
            }

            sleep(5);
//            let node finish saving....

//           End node fetcher

//            Saving data
            foreach ($types as $type)
            {

                ?>
    <h4 class="alert alert-danger" style="font-size: x-large">Type: <?= $type ?>:</h4>
                <?php

            $url = $node_server.'/public/predictions/'.$date.'-'.$type.'.json';

            $crawler = @file_get_contents($url);

            $data = [];
            if ($crawler) {
                $data = json_decode($crawler);
            }
            $all_data = $data;

                shuffle($all_data);

                $type = false;
            $counter = 0;
            foreach ($all_data as $data){

                $counter ++;
                $data = json_decode(json_encode($data), true);

//                remove NULL items
                $data = array_filter($data);

                if (!$type){

                    if (key_exists('id', $data)){
                        $type = 'other';
                    }else{
                        $type = '1x2';
                    }
                }

            $proceed = true;


            if ($proceed){
                $this->data = $data;

                $res = $this->save($type);

                ?>
                <div class="response_section">
                    <div>
                        <?= $counter ?>#
                    </div>
                <?php
                if ($res == 'true'){
                    ?>
                    <div class="alert alert-success">
                        Successfully saved data.
                    </div>
                    <?php
                }elseif ($res == 'exists'){
                    ?>
                    <div class="alert alert-warning">
                        Already saved data.
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="alert alert-danger">
                        Error saving data.
                    </div>
                    <?php
                }
                ?>
                </div>
                    <?php
            }

            }
//        end all data loop


}
//        End types loop

            if (@$fetcher_details){
                $db = db_connect();
                $new_date = date('Y-m-d', strtotime($fetcher_details->value."-1 day"));
                $query = $db->query("UPDATE `options` SET `value` = '$new_date' WHERE `options`.`id` = $fetcher_details->id;");

            }

    }
//    endif post

}



function save($type = null)
{

    $data = $this->data;

    $table = (string) 'predictions-'.date('m', strtotime($data['date_time']));
    $this->model->setTable($table);
    //		create table if missing
    $this->create_predictions_table($table);

    if ($type == '1x2'){

        preg_match('/-([0-9]+)$/', $data['source'], $matches);

        $data['source_id'] = $matches[1];
        $data['competition_logo_link'] = 'default.png';

        $exists = $this->model->where('slug', $data['slug']);
        $exists = @$this->model->like('date_time', date('Y-m-d', strtotime($data['date_time'])))->get()->getResult()[0];

        if (!$exists){

//        $dir = './public/images/flags';
//        if (!is_dir($dir)) {
//            mkdir($dir, 0777, TRUE);
//        }

//			$data['competition_logo_link'] = "http://localhost/old-files/ana-barbara_0_20.jpg";

//        $filename = strtolower(basename($data['competition_logo_link']));
//        $filename = @explode("?", $filename)[0];


            $proceed = true;
////        lets check if similar image already saved else we fetch and save it
//        if (is_file($dir.'/'.$filename)){
//            $proceed = true;
//        }elseif (@copy($data['competition_logo_link'], $dir.'/'.$filename))
//        {
//            $proceed = true;
//         }

            if ($proceed){
                $this->model->insert($data);
                return 'true';
            }

            return false;
        }

        if (@is_numeric($exists->id)){
            $this->model->update($exists->id, $data);
        }

        return 'exists';
    }else{

        $exists = @$this->model->like('source', '-'.$data['id'])->get()->getResult()[0];

        if ($exists) {
            unset($data['id']);
            $this->model->update($exists->id, $data);

            return true;
        }

    }


}

    function create_predictions_table($table){
        @$this->model->query("CREATE TABLE if not exists `{$table}`
	(
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        source varchar(255) NOT NULL,
        source_id int(11) NOT NULL,
        home_team varchar(50) NOT NULL,
        home_team_logo varchar(100) NULL,
        away_team varchar(50) NOT NULL,
        away_team_logo varchar(100) NULL,
        slug varchar(255) NOT NULL,
        date_time varchar(50) NOT NULL,
        competition varchar(100) NOT NULL,
        competition_logo_link varchar(255) NULL,
        home_win_percentage varchar(10) NULL,
        draw_percentage varchar(10) NULL,
        away_win_percentage varchar(10) NULL,
        cs varchar(50) NULL,
        average_goals varchar(10) NULL,
        home_win_odds varchar(10) NULL,
        draw_odds varchar(10) NULL,
        away_win_odds varchar(10) NULL,
        fulltime_results varchar(50) NULL,
        halftime_results varchar(50) NULL,
        under_25_percentage varchar(10) NULL,
        over_25_percentage varchar(10) NULL,
        under_25_odds varchar(10) NULL,
        over_25_odds varchar(10) NULL,
        ng_percentage varchar(10) NULL,
        gg_percentage varchar(10) NULL,
        ng_odds varchar(10) NULL,
        gg_odds varchar(10) NULL,
        stadium varchar(100) NULL,
        is_international_club_cup varchar(10) NULL,
        is_nationalteam_cup varchar(10) NULL
    )
DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
");

}
}

$fetch = (New Fetcher)->index();

?>
