<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Controllers\BettingCalculator\Settings;
class System6 extends Settings {

    use CommonTraits;

    public $system = 6;
    public $title = "The stress free parlay system";
    public $description = "<p>The stress free parlay system is a very funny and powerful system.
This system is also used by pro gamblers because on the long run, it
produce very great results.
</p>
<p>
Why is this a stress free system ? Because you will be able to place
your bets on a particular game and no matter which team wins, there
would be a very high probability for you to make a profit. This will
eliminate any stress you may have when you bet on your favorite
team to win.
</p>
<p>
Here, I’m not talking about the kind of system where you place a bet
on opposite team at different sportsbooks and where you are
guarantee to win. I’m talking about a system where you’ll place your
bets at the same sportsbook.
</p>
<p>
Now, you may ask, is it possible ?
Well, I will show you this little known secret system right now!
First, this system produce best results during the baseball season. Of
course, you can use it with any kind of sports where you have the
possibility to bet on the money lines and against the spread. </p>";
    public $min_odds = '2.10';
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

        $this->limit = 2;

        $bankroll = $this->bankroll;
        $profit_to_make = $bankroll * ($this->stake_percentage / 100);
        $arr = [];
        $total_lost = 0;
        $summary_after_x_games = 0;
        $total_odds = 1;
        $games_temp = [];
        $betslips = 1;
        $level = 1;
        $gain = 0;
        $bankroll_init = $bankroll_after_res = $bankroll;
        $deposits = [];
        $withdrawals = [];

        $multiple_lost = false;
        $max_levels = 10;

        $main_status = $status =  'Won';
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

                $stake = number_format_strict(($total_lost + $profit_to_make) / ($total_odds - 1), $this->decimal_points);

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


                $main_status = 'Lost';
                if (!$multiple_lost) {
                    $main_status = 'Won';
                }
                    $ar = ['betslips' => $betslips, 'level' => $level,
                    'total_lost' => $total_lost,
                    'total_odds' => number_format_strict($total_odds, 2),
                    'stake' => $stake,
                    'bankroll' => $bankroll,
                    'final_bankroll' => $bankroll_after_res,
                    'gain' => $gain,
                    'status' => $main_status];
                $arr[] = ['games' => $games_temp, 'summary' => $ar];

                $total_lost += $stake;

//                increments
                $betslips ++;

                if ($betslips == 1){
                    $deposits[] = ['amount' => $bankroll_init, 'date' => date('d-m-Y', strtotime($game['date'])), 'betslip' => $betslips];
                }

                if ($status == 'Won' && !$multiple_lost){
                    $main_status = 'Won';
                    $multiple_lost = false;
                    $bankroll_after_res += $expected_win;
                    $level = 1;
                    $total_lost = 0;
                    $gain += $expected_win - $stake;

                }else{

                    //                reset level if max reached
                    if ($level == $max_levels){
                        $level = 1;
                        $total_lost = 0;

                    }else{
                        $level ++;
                    }

                    $multiple_lost = false;
                    $gain -= $stake;
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

                // At the end $bankroll set same as $bankroll_after_res
                $bankroll = $bankroll_after_res;


            }

        }

        $grand_summary = $this->conclusion($arr, $deposits, $withdrawals);
        return ['games_and_summary' => array_reverse($arr), 'grand_summary' => $grand_summary];
    }

}