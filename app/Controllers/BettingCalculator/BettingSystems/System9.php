<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System9 extends Settings {

    use CommonTraits;

    public $system = 9;
    public $title = "Win more by adding amount after each bet";
    public $description = "<p>This system is almost the same system as the parlay system.
However, there is a little difference with system #9. In fact, you will
make more money with this parlay system simply because you’ll add a
specific amount after each lost. Usually, this amount is the target you
want to make.
</p>
<p>
The goal of the system is the that the other parlay. It’s to win just one
parlay in a ten bet series. This is only a 10% winning rate. So, if you
can pick a winner within 10 picks (or more if you have a bigger
bankroll, or you use underdogs), you make a profit.
</p>
<p>
First of all, stay away from parlay of more than 2 games. The more
games you have in your parlay, the lower will be the payout
proportionally to your bet. This is normal and this is how the
sportsbooks are making their money. 
</p>";
    public $min_odds = '1.80';
    public $max_odds = '2.50';
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
        $this->limit = 2;
        $multiple_lost = false;
        $max_levels = 10;


        $main_status = 'Won';

        if ($this->builder){
            $this->head();
        }

        foreach ($games as $game){
            $summary_after_x_games ++;
            $odds = $game['odds'];
            $total_odds *= $odds;
            $total_odds = number_format_strict($total_odds, 2);

            $status =  'Won';
            if ($game['status'] !== 'Won'){
                $status = 'Lost';
                $multiple_lost = true;
            }

            $games_temp[] = $game;
//            Combining games to meet limit requirements
            if ($summary_after_x_games == $this->limit){
                $summary_after_x_games = 0;
                $betslips ++;

                if ($betslips == 1){
                    $deposits[] = ['amount' => $bankroll_init, 'date' => date('d-m-Y', strtotime($game['date'])), 'betslip' => $betslips];
                }


                $main_status = 'Lost';
                if (!$multiple_lost) {
                    $main_status = 'Won';
                }
//                The formula is: B = (TL+(NB X P)) / ((GAME 1 X GAME 2) - 1)
//                                B = Your bet
//                TL= Total lost
//                P= Profit you want to make
//                GAME 1= Odds for game 1 (International odds)
//                GAME 2= Odds for game 2 (International odds)
//                NB= Number of bet
                $NB = $level;
                $stake = number_format_strict(($total_lost + ($NB * $profit_to_make)) / ($total_odds - 1), $this->decimal_points);

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
                if ($status == 'Won' && !$multiple_lost){
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
                if ($status == 'Won' && !$multiple_lost){
                     $level = 1;
                    $total_lost = 0;

                }else{

                    //                reset level if max reached
                    if ($level == $max_levels){
                        $level = 1;
                        $total_lost = 0;

                    }else{
                        $level ++;
                    }

                    $multiple_lost = false;
                    $ever_lost = true;
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