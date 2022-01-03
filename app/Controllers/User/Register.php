<?php namespace App\Controllers\User;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\Debug\Exceptions;

class Register extends Controller
{

    protected $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function index()
    {
        //include helper
        helper(['functions', 'text']);
        $data['title'] = 'Register on '.site_name();
        $data['description'] = 'Register on '.site_name();
        $data['hide_flashdata'] = true;
        $data['hide_title'] = true;
        $data['hide_navbars'] = true;

        render_page('user/register', $data);
    }

    public function save()
    {
        //include helper form
        helper(['form', 'functions']);
        $session = session();
        //set rules validation form
        $rules = [
            'name' 			=> ['label' => 'Username', 'rules' => 'required|min_length[3]|max_length[20]'],
            'email' 		=> ['label' => 'Email', 'rules' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]'],
            'password' 		=> ['label' => 'Password', 'rules' => 'required|min_length[6]|max_length[50]'],
            'password_c' 	=> ['label' => 'Confirm Password', 'rules' => 'required|matches[password]']
        ];

        if($this->validate($rules)){
            $verification_key = md5(rand());

           $country = session('geoplugin')['geoplugin_countryName'] ?? 'United States';
//           var_dump(session('geoplugin')['geoplugin_credit'], $country);die();

            $data = [
                'username' 	=> $this->request->getVar('name'),
                'email' 	=> $this->request->getVar('email'),
                'phone' 	=> $this->request->getVar('phone'),
                'country' 	=> $country,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'verification_key' 	=> $verification_key,
                'created_at' => now(),
                'last_login' => now(),

            ];
//            dd($this->model);
            $this->model->save($data);

            $email = \Config\Services::email();

            $subject = "Please verify email for login";
            $message = "
    <p>Hi ".$this->request->getVar('name')."</p>
    <p>This is email verification mail from Sportperfected Login/Register system. For complete registration process and login into system. First you want to verify you email by click this <a href='".site_url()."user/register/verify_email/".$verification_key."'>link</a>.</p>
    <p>Once you click this link your email will be verified and you can login into system.</p>
    <p>Thanks,</p>
    ";

            $email->setFrom('admin@sportperfected.com', 'SportPerfected');
            $email->setTo($this->request->getVar('email'));
//        $email->setCC('another@another-example.com');
//            $email->setBCC('felixkpt@gmail.com');

            $email->setSubject($subject);
            $email->setMessage($message);


            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            $from = "admin@sportperfected.com";
            $to = $this->request->getVar('email');
            $subject = "Checking email";
            $headers = "From:" . $from;

            $test_mode = false;

            $mail_send = true;


//            try {
//                mail($to,$subject,$message, $headers);
//            }catch (Exception $exception){
//
////                echo  "An error occurred trying to send mail::" . $exception;
//
//            }


            if($test_mode || $mail_send) {

                $session->setFlashdata('success', 'Successfully registered.');
                return redirect('user/login');
            }else{
                $this->model->where('email', $this->request->getVar('email'));
                $this->model->delete();
                $session->setFlashdata('danger', 'Error sending verification mail.');
                return redirect()->back()->withInput();
            }


        }else{
            $session->setFlashdata('danger', $this->validator->listErrors());

            return redirect()->back()->withInput();

        }

    }

    public function verify_email($verification_key){

        $user = @$this->model->where('verification_key', $verification_key)->get()->getResult()[0];

        if ($user){

            $this->model->update($user->id, ['is_email_verified' => 1]);
//            auto login user
            $session = session();
            $ses_data = ['user' => $user];
            $session->set($ses_data);

            $session->setFlashdata('success', 'Email verification completed successfully.');
            return redirect()->to(site_url());
        }else{
            echo "No user is associated with the verification key";
        }

    }

}