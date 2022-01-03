<?php
namespace App\Models;

use CodeIgniter\Model;

class BettingCalculatorModel extends Model{
    protected $table = 'games';

    protected $allowedFields = ['user_id', 'system', 'date', 'home_team', 'away_team', 'bet', 'odds',
        'status'];

}

