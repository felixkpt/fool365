<?php
namespace App\Controllers\BettingCalculator;

use App\Controllers\BettingCalculator\BettingSystems\Builder;

class Settings {

   protected $decimal_points = 0;
    protected $start_date;
    protected $end_date;
    protected $limit = 1;
    protected $lower_limit = 1;
    protected $upper_limit = 3;
    protected $min_odds = 1;
    protected $max_odds = 10;
    protected $level = 1;
    protected $counts;
    protected $odds;
    protected $bankroll = 1000;
    protected $currency = "US";
    protected $stake_percentage = 10;
    protected $double_profit_to_make = true;
    protected $increase_stakes_on_win = true;
    protected $stake;
    protected $status = 'U';
    protected $bankroll_topped_up = false;
    protected $withdrawn_amount = 0;
    protected $withdrawals = [];
    public $period = 90;
    public $min_percentage = 60;

    public function __construct(){

        $session = session();
        $a = $session->get('bankroll');
        $b = $session->get('stake_percentage');
        $c = $session->get('period');

        if ($a > 0){
            $this->setBankroll($a);
        }else{
            session()->set(['bankroll' => $this->bankroll]);
        }
        if ($b > 0){
            $this->setStakePercentage($b);
        }else{
            session()->set(['stake_percentage' => $this->stake_percentage]);
        }
        if ($c > 0){
            $this->setPeriod($c);
        }else{
            session()->set(['period' => $this->period]);
        }
    }

    /**
     * @param int $bankroll
     */
    public function setBankroll(int $bankroll): void
    {
        $this->bankroll = $bankroll;
    }

    /**
     * @param int $stake_percentage
     */
    public function setStakePercentage(int $stake_percentage): void
    {
        $this->stake_percentage = $stake_percentage;
    }

    /**
     * @param int $period
     */
    public function setPeriod(int $period): void
    {
        $this->period = $period;
    }


}