<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System1 extends Settings {

    use CommonTraits;

    public $system = 1;
    public $title = "Flat Betting";
    public $description = "<p>
The most popular kind of bet is without a doubt, flat betting. Every
Saturday night or every Monday night, an astronomic number of
people place a bet on their favorite team. Soccer, Hockey, Football, on
any sports. They just go for a 10$ bet or higher if they can afford to
do it.
</p>
<p>
They watch the game with some friends and they have a lot of fun.
Sometime they win and sometime they lose. Over a year, they win
half of their bets.</p>";
    public $min_odds = '1.50';
    public $max_odds = '10.00';
    public $is_published = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): array{

        $games = $this->games();
        helper('functions');

        $bankroll = $this->bankroll;
        $profit_to_make = $bankroll * ($this->stake_percentage / 100);
        $arr = [];
        $total_lost = 0;
        $summary_after_x_games = 0;
        $total_odds = 1;
        $status = 'Won';
        $games_temp = [];
        $betslips = 1;
        $level = 1;
        $sub_level = 0;
        $sub_level_max = false;
        $gain = 0;
        $gain_temp = 0;
        $bankroll_init = $bankroll_after_res = $bankroll;
        $deposits = [];
        $withdrawals = [];

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

                $stake = number_format_strict($profit_to_make, $this->decimal_points);
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

                $total_lost += $stake;

                $reset = false;
//                gain / bankroll actions
                if ($game['status'] == 'Won'){
                    $bankroll_after_res += $expected_win;
                    $g = $expected_win - $stake;
                    $gain_temp += $g;
                    $total_lost -= $stake;

                    $gain += $g;
                    $total_lost = $total_lost >= 0 ? $total_lost : 0;

                    $reset = true;

                }else{
                    $gain_temp -= $stake;
                    $gain -= $stake;

                }

                //                Pushing bet_slip to all games arr
                $this->arr_push($arr, get_defined_vars());
                if ($this->builder){
                    $this->body(get_defined_vars());
                }

//                increments
                if ($game['status'] == 'Won'){
                    $level = 1;
                    $total_lost = 0;

                }else{
                    $level ++;
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

                //resets
                $summary_after_x_games = 0;
                $total_odds = 1;
                $lost = false;
                $games_temp = [];

                if ($reset){
                    $level = 1;
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