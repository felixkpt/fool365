<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System4 extends Settings {

    use CommonTraits;

    public $system = 4;
    public $title = "The up & down system";
    public $description = "<p>The up & down system is also a very good system for straight bet. It
is similar to the 33% straight bet system in a way that you only need
to win 36% of your bets to make a profit.
</p>
<p>
This system is very simple. You will simply follow a series of
predetermined amount. These amounts are $1X, $2X, $3X, $5X, $8.5X,
$14X, $23X, $39X.</p>
<p>
The reason why we call this the up & down system is because if you
lose your first two bets, you will start to move up or down into the
series depending upon if you have won or lost.
</p>
<p>You will bet only one game a day. If you lose your first 2 bets, you
will only need to win 2 games in any 3 games stretch to make a profit
and end the series. When this goal is reached, you start another
series.
</p>";
    public $min_odds = '1.80';
    public $max_odds = '2.85';
    public $is_published = true;

    private $series = [1, 2, 3, 5, 8.5, 14, 23, 39];

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
        $summary_after_x_games = 0;
        $total_odds = 1;
        $games_temp = [];
        $betslips = 0;
        $level = 1;
        $sub_level = 0;
        $sub_level_max = false;
        $won_at_level = 1;
        $wins = 0;
        $won_consecutively = 0;
        $gain = 0;
        $gain_temp = 0;
        $bankroll_init = $bankroll_after_res = $bankroll;
        $deposits = [];
        $withdrawals = [];

        $expected_win = 0;
        $max_levels = count($this->series);

        $initial_stake = $stake = number_format_strict($profit_to_make, $this->decimal_points);

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

                if ($level == 1){
                    $stake = $initial_stake;
                }
//                using series to increase stakes
                else{

//                    Reset level
                    if ($level > $max_levels){
                        $level = 1;
                    }

                    $index = $level - 1;

                    $stake = $initial_stake * $this->series[$index];
                }

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

//                    $reset = true;
                }else{
                    $gain_temp -= $stake;
                    $gain -= $stake;
                    $total_lost += $stake;

                }

                //                Pushing bet_slip to all games arr
                $this->arr_push($arr, get_defined_vars());
                if ($this->builder){
                    $this->body(get_defined_vars());
                }


//                increments---> bankroll, level, gain, loses
                if ($status == 'Won'){
                    $wins ++;
                    $won_consecutively ++;

                    if (($gain > 0 && $level ==1) || $wins == 3 || $won_consecutively == 2){
                        $reset = true;
                    }elseif ($level > 2){
                        // The Down statement in case current win is of more than level 1
                        $level --;
                    }else{
                        $level ++;
                    }

                    $total_lost = 0;

                }else{
                    $level ++;
                    $won_consecutively = 0;
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

                //resets level, loses
                $summary_after_x_games = 0;
                $total_odds = 1;
                $lost = false;
                $games_temp = [];

                // Reset if max levels reached
                if ($level > $max_levels){
                    $reset = true;
                }

                if ($reset){
                    $level = 1;
                    $sub_level = 0;
                    $total_lost = 0;
                    $gain_temp = 0;
                    $wins = 0;
                    $won_consecutively = 0;
                }


            }

        }

        if ($this->builder){
            $this->footer();
        }

        $grand_summary = $this->conclusion($arr, $deposits, $withdrawals);
        return ['games_and_summary' => array_reverse($arr), 'grand_summary' => $grand_summary];
    }

}