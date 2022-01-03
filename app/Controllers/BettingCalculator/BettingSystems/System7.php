<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System7 extends Settings {

    use CommonTraits;

    public $system = 7;
    public $title = "Play for a couple of years without losing";
    public $description = "<p>This system is a bit different than any other system you may have
seen. If fact, this system was initially created for the slot machine. It
has been modified to use at sports betting with great success.
</p>
<p>The particularity of this system is that you can play for a couple of
years without losing a single session. Yes, the progression is very low
but it keeps your bankroll safe.
</p>
<p>The concept is very simple. You will use 4 predetermined series which
include another predetermined number of bets in each series.
</p>";
    public $min_odds = '1.90';
    public $max_odds = '2.30';
    public $is_published = true;

    private $series = ['1' => ['level' => 1, 'multiplier' => 1, 'sub_levels' => 7],
        '2' => ['level' => 2, 'multiplier' => 2, 'sub_levels' => 6],
        '3' => ['level' => 3, 'multiplier' => 4, 'sub_levels' => 5],
        '4' => ['level' => 4, 'multiplier' => 6, 'sub_levels' => 4]];

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): array{

        $games = $this->games();
        helper('functions');

        //        Set games array to empty if is not is_active
        if (!$this->is_published){
            $games = [];
        }

        $bankroll = $this->bankroll;
        $profit_to_make = $bankroll * ($this->stake_percentage / 100);
        $arr = [];
        $total_lost = 0;
        $total_lost = 0;
        $summary_after_x_games = 0;
        $total_odds = 1;
        $status = 'Won';
        $games_temp = [];
        $betslips = 0;
        $level = 1;
        $sub_level = 1;
        $won_at_level = 1;
        $wins = 0;
        $won_consecutively = 0;
        $gain = 0;
        $gain_temp = 0;
        $bankroll_init = $bankroll_after_res = $bankroll;
        $deposits = [];
        $withdrawals = [];

        $expected_win = 0;

        $initial_stake = $stake = number_format_strict($profit_to_make, $this->decimal_points);


        $level = $this->series[1]['level'];
        $multiplier = $this->series[1]['multiplier'];
        $sub_level_max = $this->series[1]['sub_levels'];
        $sub_level = 0;

        if ($this->builder){
            $this->head();
        }

        foreach ($games as $game){
            $summary_after_x_games ++;
            $odds = $game['odds'];
                        $total_odds *= $odds;
            $total_odds = number_format_strict($total_odds, 2);

            $status = 'Won';
            if ($game['status'] !== 'Won'){
                $status = 'Lost';
            }

            $games_temp[] = $game;
//            Combining games to meet limit requirements
            if ($summary_after_x_games == $this->limit){
                $summary_after_x_games = 0;
                $betslips ++;

                if ($betslips == 1){
                    $deposits[] = ['amount' => $bankroll_init, 'date' => date('d-m-Y', strtotime($game['date'])), 'betslip' => $betslips];
                }


//                checking if gain is less than 1 and max levels reached
                    if ($sub_level == $sub_level_max){

                        $index = $level + 1;
                        if ($index <= count($this->series)){
                            $level = $this->series[$index]['level'];
                            $multiplier = $this->series[$index]['multiplier'];
                            $sub_level_max = $this->series[$index]['sub_levels'];

                        }else{
                            $level = $this->series[1]['level'];
                            $multiplier = $this->series[1]['multiplier'];
                            $sub_level_max = $this->series[1]['sub_levels'];

                        }
                        $sub_level = 0;

                    }

                    $stake = $initial_stake * $multiplier;
                            $this->updates($bankroll, $bankroll_after_res, $expected_win, $stake, $total_odds);
                /**
                Bankroll withdrawal on doubling of initial bankroll
                 */
                if ($bankroll / $bankroll_init >= 2 && $gain > 0){
                    $withdrawn_amount = $bankroll - $bankroll_init;
                    $withdrawals[] = ['amount' => $withdrawn_amount, 'date' => date('d-m-Y', strtotime($game['date'])), 'betslip' => $betslips];
                    $bankroll = $bankroll_init;
                    $bankroll_after_res = $bankroll - $stake;
                }


                $reset = false;
//                if status is won we need to check if we have gain from this level else we continue with the level
                if ($status == 'Won'){
                    $bankroll_after_res += $expected_win;
                    $g = $expected_win - $stake;
                    $gain_temp += $g;
                    $total_lost -= $stake;

                    $gain += $g;
                    $total_lost = $total_lost >= 0 ? $total_lost : 0;


//                    if current win is showing a gain
                    if ($gain_temp > $total_lost){
                        $reset = true;
                    }
                    $sub_level ++;

                }else{
                    $sub_level ++;
                    $gain_temp -= $stake;
                    $gain -= $stake;
                    $total_lost += $stake;
                    /**
                    Bankroll top up on depletion we do a series of tricks here.
                    User's max bet is only limited to initial bankroll
                     */
                    if ($bankroll == 0){
                        $deposits[] = ['amount' => $bankroll_init, 'date' => date('d-m-Y', strtotime($game['date'])), 'betslip' => $betslips];
                        $bankroll = $bankroll_init;
                        $bankroll_after_res = $bankroll;
                        $reset = true;
                    }
                }

//                Pushing bet_slip to all games arr
                $this->arr_push($arr, get_defined_vars());
                if ($this->builder){
                    $this->body(get_defined_vars());
                }

                //resets
                $summary_after_x_games = 0;
                $total_odds = 1;
                $lost = false;
                $games_temp = [];

                if ($reset){
                    $index = $level - 1;
                    $index = $index < 1 ? 1 : $index;
                    $max = count($this->series);
                    $index = $index > $max ? $max : $index;


                    $level = $this->series[$index]['level'];
                    $multiplier = $this->series[$index]['multiplier'];
                    $sub_level_max = $this->series[$index]['sub_levels'];
                    $sub_level = 0;
                    $total_lost = 0;
                    $gain_temp = 0;
                }
                // At the end $bankroll set same as $bankroll_after_res
                $bankroll = $bankroll_after_res;

            }

        }

        if ($this->builder){
            $this->footer();
        }

        $grand_summary = $this->conclusion($arr, $deposits, $withdrawals);
        return ['games_and_summary' => array_reverse($arr), 'grand_summary' => $grand_summary];
    }

}