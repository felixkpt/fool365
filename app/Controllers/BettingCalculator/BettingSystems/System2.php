<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System2 extends Settings {

    use CommonTraits;

    public $system = 2;
    public $title = "33% straight bet system";
    public $description = "<p>I will show you a nice
system to use when using straight bet. This simple system will help
you to improve your winning ratio when you experience some bad
days.
</p>
<p>
The system is very easy to use and understand. In order to make a
profit, all you have to do is to win only 2 games out of 6. Yes , that’s
right, only 33% of your bets.
</p>
<p>
Also, all your bets will be predetermined before you start a new series.
You will know exactly how much to bet on each game.
This system works better when you bet against the spread in football
and basketball simply because the amount of your bets are
predetermined.</p>
<p>In the 33% straight bet system, you will place a series of up to 6 bets.
You will use the predetermined amount of x, 2x, 4x, 6x, 8x, 12.5x. All
you have to do is replace the X by your unit size. So, if you start with
$10, the predetermined series will look like:
</p>
<p>
$10, $20, $40, $60, $80, $125.
</p>
<p>You will place only one bet at a time. So, one bet a day is the
average. When you reach a profit in the series you collect your
winnings and add it to your bankroll. Then, you start another series. 
</p>";
    public $min_odds = '1.80';
    public $max_odds = '2.00';
    public $is_published = true;

    private $series = [1, 2, 4, 6, 8, 12.5];

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

                    $stake = $initial_stake * @$this->series[$level - 1];
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

                    $reset = true;
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


//                increments
                if ($game['status'] == 'Won'){

                    if ($gain > 0){
                        $level = 1;
                    }else{
                        $level ++;
                    }

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

                // Reset if max levels reached
                if ($level > $max_levels){
                    $reset = true;
                }

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