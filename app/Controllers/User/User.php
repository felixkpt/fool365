<?php namespace App\Controllers\User;

use CodeIgniter\Controller;
use App\Models\UserModel;

class User extends Controller
{
    private $model;
    public function __construct(){
        $this->model = new UserModel();
    }

	public function account(){
        //        load helper
        helper(['functions', 'text']);

        $user = session('user');
        $res = $this->model->getWhere(['id' => $user->id])->getResult()[0];

        $data['user'] = $res;
        $data['title'] = 'Your account information';
        $data['description'] = 'Your account information';

        render_page('/user/account', $data);
	}
    public function update(): \CodeIgniter\HTTP\RedirectResponse
    {

        $file = $this->request->getFile('profile_photo');

        if ($file->isValid()) {

            $newName = $file->getRandomName();
            $asserts = FCPATH.'public';
            $moved = $file->move($asserts.'/images/users', $newName);

            if ($moved){
                $newName = site_url('public/images/users/'.$newName);
                $data = ['profile_photo' => $newName];
                $user_id = session('user')->id;

                $updated = $this->model->update(['id' => $user_id], $data);

                $user  = $this->model->where('id', $user_id)->get()->getResult('object')[0];
//                dd($user->id);
                session()->set(['user' => $user]);
            }


        }

        //include helper form
        helper(['form', 'functions']);
        $session = session();
        //set rules validation form
        $rules = [
            'username' 			=> 'required|min_length[3]|max_length[20]',
            'country' 		=> 'required|min_length[3]|max_length[40]',
        ];
        $change_pass = false;
        if (strlen($this->request->getVar('password')) > 0){
            $rules = array_merge($rules, [
                'password' 		=> 'required|min_length[6]|max_length[50]',
                'password_c' 	=> 'matches[password]'
            ]);
            $change_pass = true;
        }
//        var_dump($rules);die;


        if($this->validate($rules)) {

            $data = [
                'username' => $this->request->getVar('username'),
                'phone' => $this->request->getVar('phone'),
                'country' => $this->request->getVar('country'),
                'updated_at' => now(),

            ];

            if ($change_pass){

                $user = $this->model->getWhere(['id' => session('user')->id])->getResult()[0];

                $verify_pass = password_verify($this->request->getVar('password_old'), $user->password);

                if($verify_pass || $user->password == null){
                    $data = array_merge($data, ['password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)]);

                    $this->model->update(['id' => session('user')->id], $data);
                    session()->remove('user');
                    return redirect()->to('user/login')->with('success', 'Account information updated, login again.');

                }else{
                    return redirect()->back()->with('danger', 'Old Password is not valid')->withInput();
                }

            }

            $this->model->update(['id' => session('user')->id], $data);
            return redirect()->back()->with('success', 'Account information updated');

        }else{
            $session->setFlashdata('danger', $this->validator->listErrors());

            return redirect()->back()->withInput();

        }

    }

	public function settings(){
        return redirect()->back();
	}

}
