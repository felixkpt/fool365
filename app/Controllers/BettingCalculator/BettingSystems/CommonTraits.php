<?php
namespace App\Controllers\BettingCalculator\BettingSystems;
use App\Models\BettingCalculatorModel;

trait CommonTraits {
    public $builder = 0;
    protected $model;


    public function conclusion($arr, $deposits, $withdrawals): array{
        $ct = count($arr);

        $betslips = $arr[$ct - 1]['summary']['betslips'] ?? 0;
        $bankroll_after_res = $arr[$ct - 1]['summary']['bankroll_after_res'] ?? $this->bankroll;
        $gain = $arr[$ct - 1]['summary']['gain'] ?? 0;

        $current_bankroll = $bankroll_after_res;

        $grand_summary = ['initial_bankroll' => $this->bankroll, 'betslips' => $betslips,
            'current_bankroll' => $current_bankroll, 'gain' => $gain, 'deposits' => array_reverse($deposits), 'withdrawals' => array_reverse($withdrawals)];
        return $grand_summary;
    }
    public function games(): array{

        if (@$_GET['builder'] == '1' || @$_GET['builder'] == 'true'){
            $this->builder = 1;
        }

        $this->model = new BettingCalculatorModel();
        $user_id = session('user')->id ?? 1;
        $period = $this->period;
        $after = date('Y-m-d H:i:s', strtotime('-'.$period.' days'));
//dd($user_id, $period, $after,$this->system);
//        dd($this->model);
        $where = ['user_id' => $user_id, 'system' => $this->system, 'date >' => $after];
        return $this->model->where($where)
            ->orderBy('date', 'ASC')
            ->limit(5000)->get()->getResultArray();
    }


    protected function arr_push(&$arr, $items){

        $ar = ['betslips' => $items['betslips'], 'level' => $items['level'], 'sub_level' => $items['sub_level'],
            'total_lost' => $items['total_lost'],
            'total_odds' => number_format_strict($items['total_odds'], 2),
            'stake' => $items['stake'],
            'bankroll' => $items['bankroll'],
            'bankroll_after_res' => $items['bankroll_after_res'],
            'gain' => $items['gain'],
            'status' => $items['main_status'] ?? $items['status']];
        $arr[] = ['games' => $items['games_temp'], 'summary' => $ar];

    }


    
    public static function head(){
        ?>
        <table style="width: 60%" border="3">
        <thead>
        <tr style="text-align: left">
            <th>#</th><th>Date</th><th>Level</th><th>Status</th><th>Sublevel/Prev1/Prev</th><th>Odds</th><th>Stake</th>
            <th>Exp. win</th><th>Gain (Temp/Main)</th><th>Lost temp</th><th>Bankroll (before---after)</th>
            <th>Deposit</th><th>Withdraw</th>
        </tr>
        </thead>

        <?php
    }

    public static function body($items){

        ?>
        <tr>
            <td title="betslips"><?= $items['betslips'] ?></td>
            <td title="Date"><?= date('d/m/y', strtotime($items['game']['date'])) ?></td>
            <td title="Level">Lvl:&nbsp;<?= $items['level'] ?><td title="Status"><?= $items['main_status'] ?? $items['status'] ?></td></td>
            <td title="Sublevel/Prev1/Prev"><?php if ($items['sub_level_max'] !== false):
                    {
                        echo $items['sub_level'] . '/' . $items['sub_level_max'];
                    } elseif (isset($items['stakes'])):
                    {
                        $index = count($items['stakes']);
                    echo (@$items['stakes'][$index - 2] ?? $items['initial_stake']).'&nbsp,&nbsp;'.$items['stakes'][$index - 1];
                }else:
                    { echo '-';}
                endif; ?></td>

            <td title="Odds"><?= $items['total_odds'] ?></td><td title="Stake"><?= $items['stake'] ?></td>
            <td title="Expected win"><?= $items['expected_win'] ?></td><td title="Gain/Gain Temp"><?= $items['gain_temp'].'&nbsp;&lt;____&gt;&nbsp;'.$items['gain'] ?></td>
            <td title="Total lost"><?= $items['total_lost'] ?></td><td title="Bankroll"><?= $items['bankroll'].'___'.$items['bankroll_after_res'] ?></td>
            <td title="Deposit"><?php
                $key = array_search(date('d-m-Y', strtotime($items['game']['date'])), array_column($items['deposits'], 'date'));
                if (is_numeric($key) && $items['betslips'] == @$items['deposits'][$key]['betslip']){
                    echo '@'.$items['deposits'][$key]['amount'];

                }?></td>
            <td title="Withdraw"><?php
                $key = array_search(date('d-m-Y', strtotime($items['game']['date'])), array_column($items['withdrawals'], 'date'));

                if (is_numeric($key) && $items['betslips'] == @$items['withdrawals'][$key]['betslip']){
                    echo '%'.$items['withdrawals'][$key]['amount'];

                }?></td>
            <td title="Withdrawal"><?php $items['bankroll'].'___'.$items['bankroll_after_res'] ?></td>
        </tr>
        <?php
    }

    public static function footer(){
        ?>
        </table>
        <?php
        die();
    }

    public function updates(&$bankroll, &$bankroll_after_res, &$expected_win, &$stake, $total_odds){

        if ($stake > $bankroll){
            $stake = $bankroll;
            $bankroll = 0;
            $bankroll_after_res = 0;
        }else{
            $bankroll = $bankroll_after_res;
            $bankroll_after_res -= $stake;

        }
        if ($stake < 1){
            $stake = 1;
        }


        $expected_win = number_format_strict($stake * $total_odds, $this->decimal_points);


    }

}