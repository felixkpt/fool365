<?php
namespace App\Controllers\BettingCalculator;

use App\Models\BettingCalculatorModel;
use App\Controllers\BettingCalculator\BettingSystems\BettingSystemsChooser;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Helpers\Scraper\Scraper;
use CodeIgniter\HTTP\RedirectResponse;

class BettingCalculator extends Controller
{

    protected $model;

    public function __construct(){

        //        load helper
        helper(['functions', 'text']);

        $this->model = new BettingCalculatorModel();


    }

    public function index(){

    $data['title'] = 'About Betting Calculator';
    $data['description'] = 'About Betting Calculator';

        render_page('betting-calculator/index', $data);

}

    public function system($system_no, $system_slug){

        $page = "betting-calculator";
        $data = $this->basics($system_no, $system_slug);

        $data['currency'] = '$';


//dd($data['games']);

//        $this->basics($system_no, $system_slug);

        $requested_page = pagination('page');
        $per_page = pagination('per_page');

        $start = ($per_page * ($requested_page - 1)) + 1;
        $start = $start > 0 ? $start : 0;
//        dd($start);
        if ($requested_page == 1){
            $start = 0;
        }
        $length = $per_page;

        $data['grand_summary'] = $data['games']['grand_summary'];
        $games = $data['games']['games_and_summary'];

        $ct = count($games);

        $data['end_date'] = $data['start_date'] = null;
        if ($games){
            $data['end_date'] = $games[0]['games'][0]['date'];
            $data['start_date'] = $games[$ct - 1]['games'][0]['date'];
        }

        $last_page = ceil((count($games) ) / $per_page);
//dd($games);
//        dd($last_page, $start, $length, array_slice($games, 0, $length));

        $data['last_page'] = $last_page;

        $data['games'] = array_slice($games, $start, $length);
        $data['requested_page'] = $requested_page;
        $data['per_page'] = $per_page;

        render_page('betting-calculator/' . $page, $data);

    }

    /**
     * @throws \ReflectionException
     */
    private function basics($system_no, $system_slug): array{
        $obj = new BettingSystemsChooser($system_no, $system_slug);
        return $obj->index();
    }

    public function add($system_no, $system_slug){

        $data = $this->basics($system_no, $system_slug);

        $page = 'add';

        $data['title'] = "Add a game to system ".$system_no;

        render_page('betting-calculator/action/' . $page, $data);

    }

    public function pull_externally($system_no, $system_slug){

        $data = $this->basics($system_no, $system_slug);


        $page = 'pull-externally';

        $data['title'] = "Pull games externally to system ".$data['system'];
        $data['description'] = "Pull games externally to system ".$data['system'];

        render_page('betting-calculator/action/' . $page, $data);

    }

    /**
     * @throws \ReflectionException
     */
    public function save($id = null, $__REQUEST = null): object{

        if ($__REQUEST){
            $_REQUEST = $__REQUEST;
        }

        unset($_REQUEST['action']);


        if (preg_match("/ vs /", $_REQUEST['home_team'])){
            $k = explode( ' vs ', $_REQUEST['home_team']);

            $_REQUEST['home_team'] = trim($k[0]);
            $_REQUEST['away_team'] = trim($k[1]);
        }

        $_REQUEST['home_team'] = ucwords($_REQUEST['home_team']);
        $_REQUEST['away_team'] = ucwords($_REQUEST['away_team']);

        if (!$id){

            $query = ['user_id' => $_REQUEST['user_id'], 'date' => $_REQUEST['date'], 'home_team' => $_REQUEST['home_team'], 'away_team' => $_REQUEST['away_team']];
            $exists = $this->model->where($query)->get()->getResultArray();

            if (!$exists){
                $this->model->save($_REQUEST);
            }
            return redirect()->back()->with('success', 'Game saved!');

        }else{
            $res = @$this->model->where('id', $id)->get()->getResultArray()[0];

            $res = $this->model->update(['id' => $id], $_REQUEST);
            if ($res){
                return redirect()->back()->with('success', 'Game updated successfully.');
            }else{
                return redirect()->back()->with('danger', 'Error updating game.');
            }
        }

    }

    public function pull()
    {

        header('Content-Type: application/json');

        unset($_REQUEST['action']);

        $user_id = $_REQUEST['user_id'];
        $system = $_REQUEST['system'];
        $min_percentage = $_REQUEST['min_percentage'];
        $min_odds = $_REQUEST['min_odds'];
        $max_odds = $_REQUEST['max_odds'];
        $start_date = date('Y-m-d', strtotime($_REQUEST['start_date']));
        $end_date = date('Y-m-d', strtotime($_REQUEST['end_date']));;
        $add = $_REQUEST['add'] ?? null;
        $bets = $_REQUEST['bet'] ?? 'random';
        $limit = $_REQUEST['limit'] ?: 10000;
        $include_unsettled = @$_REQUEST['include_unsettled'];

        $client = (new Scraper())->client();

        if (in_array('random', $bets)){
            unset($bets[0]);
        }

        $bets_init = $bets;
//        dd($bets);
        $diff = 1;
        if ($add > 0){
            $start_date = date('Y-m-d', strtotime($start_date." +$add days"));
        }elseif ($end_date){
            $diff = date_diff2($end_date, $start_date, 'd');
        }

        $counter = 0;
        $prev_date_time = null;
        $date = $start_date;
        for ($i=0;$i<$diff;$i++){
            $date = date('Y-m-d', strtotime($start_date." +$i days"));

            $host = 'http://localhost/sportperfected';

            if ($_SERVER['HTTP_HOST'] != 'localhost'){
                $host = 'https://sportperfected.com';
            }

        $url = $host.'/predictions/'.$date.'?format=json';
//var_dump($url);die;

        $crawler = null;
        try {
            $crawler = $client->request('GET', $url);

        }
        catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage(). "<br>";
        }

        if ($crawler) {

            $games = json_decode($crawler->text(), true);

            $all_games_with_uo_odds = $games['all_games_with_uo_odds'];
            $all_games_with_bts_odds = $games['all_games_with_bts_odds'];

            $games = $games['predictions'];

            $games = order_by_key($games, 'date_time', 'asc');

            if ($all_games_with_uo_odds){


                $suffix = '_percentage';
                $arr = [];
                $count_all_games = 0;
                foreach ($games as $game){

                    $proceed = 1;
                    $fulltime_results = $game['fulltime_results'];
                    if (!$include_unsettled){
                        if (!preg_match('/[0-9]/', $fulltime_results)){
                            $proceed = 0;
                        }
                    }

//                    dd($game);
                    $date_time = $game['date_time'];

                    if (!$prev_date_time){
                        $prev_date_time = date('Y-m-d H:i:s', strtotime($date_time.' -4 hours'));
                    }

                    $diff2 = date_diff2($date_time, $prev_date_time, 'h');
//                    echo "$prev_date_time,___ $date_time __ $diff2<br>";

                    if ($diff2 < 3){
                        $proceed = 0;
                    }
//                    var_dump($proceed);

//                    dd($prev_date_time, $date_time, $diff);
//                    var_dump($fulltime_results, $proceed);die();
                    if ($proceed){

                        if (!$bets_init){
                            $bets = array_keys(markets());
                            shuffle($bets);
                            shuffle($bets);
                            shuffle($bets);
                            shuffle($bets);

                        }
//dd($bets);
//                        Trying this game with available bets
                        $taken = false;
                        for ($j=0;$j<count($bets);$j++){
                            $bet = $bets[$j];

                            if ($taken){break;}

                            if ($game[$bet.$suffix] > $min_percentage){

                                if ($game[$bet.'_odds'] > $min_odds && $game[$bet.'_odds'] < $max_odds){
                                    $count_all_games ++;

                                    $arr[] = ['user_id' => $user_id, 'system' => $system, 'bet' => $bet, 'game' => $game];
                                    $prev_date_time = $date_time;
                                    $taken = true;

                                }else{
                                    $taken = false;
                                }

                            }

                        }

                    }
//var_dump($count_all_games);die;
                    if ($count_all_games == $limit){
                        break;
                                }
                }
//                end games loop
//var_dump($arr);echo "<br>";
                $this->save_from_pull($arr);

            }else{
                $json = json_encode(['response' => 'danger', 'message' => 'No odds for '.$date]);
                echo $json;

            }
//die;
        }

        }
        //        days loop end
//die;

        $json = json_encode(['response' => 'success', 'message' => $count_all_games.' games pulled successfully for '.date('d/m/y', strtotime($date)).'.']);
        echo $json;

    }

    public function save_from_pull(array $array): bool
    {

        foreach ($array as $item){
        //id
        //user_id
        //system
        //date
        //home_team
        //away_team
        //bet
        //odds
        //status

        $bet = $_REQUEST['bet'] = $item['bet'];
        $_REQUEST['user_id'] = $item['user_id'];
        $_REQUEST['system'] = $item['system'];
        $_REQUEST['date'] = $item['game']['date_time'];
        $_REQUEST['home_team'] = $item['game']['home_team'];
        $_REQUEST['away_team'] = $item['game']['away_team'];
        $_REQUEST['odds'] = $item['game'][$bet.'_odds'];
        $fulltime_results = $item['game']['fulltime_results'];
        $_REQUEST['status'] = bet_status($fulltime_results, $bet);


        $this->save(null, $_REQUEST);

}

return true;

}

public function see_how_much(): RedirectResponse{
    $bankroll = $this->request->getVar('bankroll');
    $stake_percentage = $this->request->getVar('stake_percentage');
    $period = $this->request->getVar('period');

    $data = ['bankroll' => $bankroll, 'stake_percentage' => $stake_percentage, 'period' => $period];
    $session = session();
    $session->set($data);
    return redirect()->back()->with('info', 'Below is how much you could have made');

    }

    public function edit($system_no, $system_slug, $id){

        $data = $this->basics($system_no, $system_slug);

        $res = @$this->model->where('id', $id)->get()->getResultArray()[0];

        if ($res){
            $data['game'] = $res;
            $page = 'edit';

            $data['title'] = "Edit game on System ".$system_no;

            echo view('templates/header', $data);
            echo view('betting-calculator/action/' . $page, $data);
            echo view('templates/footer', $data);


        }else{
            return redirect()->back()->with('danger', 'Error finding game.');
        }


    }

    public function delete($system_no, $system_slug, $id, $user_id = null): RedirectResponse{

        $data = $this->basics($system_no, $system_slug);

//var_dump($system_no, $id);die();
if ($id == 'all'){
    $res = $this->model->where(['user_id' => $user_id, 'system' => $system_no])->delete();
}else{
    $res = $this->model->where(['id' => $id])->delete();
}

        if ($res){
            return redirect()->back()->with('info', 'Game was permanently deleted.');
        }else{
            return redirect()->back()->with('danger', 'Error deleting game.');
        }


    }



}