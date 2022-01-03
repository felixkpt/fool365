<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System8 extends Settings {

    use CommonTraits;

    public $system = 8;
    public $title = "Bet the total of your last two bets";
    public $description = "<p>The magic of this system is that you only need to win 36% of your bet
( 1/3 ) to make a profit. You can select your own game or you can
find free picks from a lot of handicappers online. Reaching a 50%
winning rate is very easy with good free picks. Go to Google and
search for “Free Picks”. You’ll find a lot of handicappers who are
offering daily free picks.
</p>
<p>
Ok, Now the system.
First, you need to know how to calculate your bet. So the formula is
AMOUNT TO BET / (INTERNATIONAL ODDS – 1).
</p>
<p>
Let’s say you want to bet $50 at 2.20. $50 / (2.20-1) = $50 / 1.20 =
$41.66
You need to bet $41.66 to win $50. $41.66 X 2.20 = $91.66 – $41.66
= $50
</p>
<p>
The system is very simple. When you lose a game, you will always bet
the total of your last two bets. 
</p>";
    public $min_odds = '1.90';
    public $max_odds = '2.85';
    public $is_published = true;

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
        $max_levels = 8;
        $stakes = [];

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
                    $stakes[] = $stake;
                }
//                using series to increase stakes
                else{

//                    Reset level
                    if ($level > $max_levels){
                        $level = 1;
                    }

                    $index = count($stakes);
                    $previous_but_1 = $stakes[$index - 2] ?? $initial_stake;
                    $previous = $stakes[$index - 1];
                    $last2 = $previous + $previous_but_1;

                    $stake = number_format_strict(($last2) / ($total_odds - 1), $this->decimal_points);

//                    if ($betslips == 5){
//                        var_dump($total_odds, $previous_but_1, $previous, $last2, $stake);die;
//                    }

                    $stakes[] = $stake;

                }

                $previous = $stake;

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

//                increments---> bankroll, level, gain, loses
                //                if status is NOT won
                if ($status !== 'Won'){
                    $level ++;
                    $sub_level ++;
                }else{
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

                    $index = count($stakes);

                    if ($index > 1){
                        $stakes = array_slice($stakes, 0, $index - 2);
                    }else{
                        $stakes = array_slice($stakes, 0, $index - 1);
                    }

//                    var_dump($stakes);die;
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