<?php
namespace App\Controllers\Admin;

use App\Models\UserModel;

class TipsSettings {

    protected $model;
    protected $data = [];

    public function index(){

        if (@$_GET['action'] == 'create'){
            $this->model->setTable('predictions2');
            $games = $this->model->get()->getResult('array');

            foreach ($games as $data){
                unset($data['id']);
                $table = (string) 'predictions-'.date('m', strtotime($data['date_time']));
                $this->model = (New PredictionsModel())->setTable($table);

                (New Fetcher())->create_predictions_table($table);

                $exists = $this->model->where('slug', $data['slug']);
                $exists = @$this->model->like('date_time', date('Y-m-d', strtotime($data['date_time'])))->get()->getResult()[0];

                if (!$exists){
                    $this->model->insert($data);
                }

                echo "Succeed.";
                echo "<br>";
            }

        }
        ?>
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="row">
                    <h1>Default account for premium tips</h1>
                </div>
                <?php
                    $users = @(new UserModel)->get()->getResult();

                    $db = db_connect();
                   $ever_saved = @$db->query("select * from `options` where name = 'default_user';")->getResult()[0];

                   $ever_saved_id = @$ever_saved->id;
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="type">All users</label>
                        <select id="type" name="default_user" class="form-control">
                            <?php
                            foreach ($users as $user):
                            ?>
                            <option value="<?= $user->id ?>" <?php if ($user->id == @$ever_saved->value): echo 'selected'; endif; ?>><?= $user->username.' ('.$user->email.')' ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <input type="hidden" name="action" value="save">
                    <button class="btn btn-outline-info">Save</button>
                </form>
            </div>
        </div>
        <?php

//        $_POST['action'] = 'fetch';
        if (@$_POST['action'] == 'save'){

//            Begin Node fetcher
            $default_user = $_POST['default_user'];

            if (@$default_user !=='empty'){

                //                delete_current_default_user

                 if ($ever_saved_id){
                     $query = $db->query("UPDATE `options` SET `value` = '$default_user' WHERE `options`.`id` = '$ever_saved_id';");
                 }else{
                     $query = $db->query("INSERT INTO `options` (`name`, `value`, `autoload`) VALUES ('default_user', '$default_user', NULL);");
                 }

                $session = session();
                $session->setFlashdata('success', 'Updated tips settings.');

                return redirect()->back();

            }

        }
//    endif post

    }

}

$tipsSettings = (New TipsSettings)->index();

?>
