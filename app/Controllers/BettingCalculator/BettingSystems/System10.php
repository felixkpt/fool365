<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System10 extends Settings {

    use CommonTraits;

    public $system = 10;
    public $title = "Combination of parlay and straight bet";
    public $description = "<p>This system is another system where we’ll use parlay. In fact this
system is a combination of parlay and straight bet. It is also called
“The Reverse System”. This system is designed to be used for football
and basketball when you bet against the spreads and when the odds
are always 1.91 (-110) or 1.93 (-107).
</p>
<p>You can play this system with 3 to 6 games a day. The beauty of this
system is that even if you have a bad day, you can still make money.
As example, if you bet on 3 games a day with flat betting, you need to
win at least 2 games to make money. But with this system, even with
a poor record of 0-3, you’ll make money. The only way you can lose
money is only if you go 1-2 (1 win out of 3). In any other situation,
you’ll come ahead.
</p>
<p>
Ok, here’s how it work.
</p>
<p>The system is the same for 3, 4, 5, 6 or games. What you will do is
simply bet 3 units on each team you think will win. Than, you will also
bet 2 units on a parlay with the other 3 teams.
Got it ?
</p>
<p>Ok, here’s how the parlay pays at odds 1.93 (-107)
<ul class='list-unstyled'>
<li>
2 Teams Pays 3.74
</li>
<li>
3 Teams Pays 7.24
</li>
<li>
4 Teams Pays 14.01
</li>
<li>
5 Teams Pays 27.10
</li>
<li>
6 Teams Pays 52.42
</li>
</ul>
</p>
<p>
Now, if you go with a 3 team parlay, you will bet 3 units on each team
you think will win and 2 units on a reverse parlays with the 3 other
teams.
</p>
<p>
<ul class='list-unstyled'>
<li>
3 units at odds 1.93 on team #1
</li>
<li>
3 units at odds 1.93 on team #2
</li>
<li>
3 units at odds 1.93 on team #3
</li>
<li>
2 units at odds 6.24 on the 3 other teams
</li>
</ul>
</p>";
    public $min_odds = '1.70';
    public $max_odds = '2.50';
    public $is_published = false;

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