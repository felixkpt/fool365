<?php namespace App\Controllers\User;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use Google_Client;

class Login extends Controller
{
    public $model;
    public function __construct(){
        $this->model = new UserModel();
    }
    public function index()
    {

        helper(['functions', 'text']);
        $data['title'] = 'Sign in to '.site_name();
        $data['description'] = 'Sign in to '.site_name();
        $data['hide_flashdata'] = true;
        $data['hide_title'] = true;
        $data['hide_navbars'] = true;

        $redirect_to = site_url();
        if (session('user') && !session()->getFlashdata()){
            $redirect_to = $this->request->getVar('redirect_to') ?? $redirect_to;
            if (preg_match("#/user/register|/user/login#", $redirect_to)){
                $redirect_to = site_url();
            }

            return redirect()->to($redirect_to);
        }

        render_page('user/login', $data);
    } 

    public function auth($username = null, $password = null)
    {

           $session = session();

        if ($this->request){
            $user_login = $this->request->getVar('email');
            $password = $this->request->getVar('password');
        }else{
            $user_login = $username;
            $password = $password;
        }

        $data = $this->model->getWhere(['email' => $user_login])->getResult();

        if($data){
            $data = $data[0];
            $pass = $data->password;
            $verify_pass = password_verify($password, $pass);
            if($verify_pass){
                $ses_data = ['user' => $data];
                $session->set($ses_data);

                if ($this->request){

                    $redirect_to = $this->request->getVar('redirect_to');
                    if (preg_match("#/user/register|/user/login#", $redirect_to)){
                        $redirect_to = site_url();
                    }
                    return redirect()->to($redirect_to);

                }else{
                    return true;
                }

            }else{
                $message = 'Wrong Password';
                $session->setFlashdata('danger', $message);
                if ($this->request){
                    return redirect()->back();
                }else{
                    return $message;
                }

            }
        }else{
            $message = 'Email or Phone not Found';
            $session->setFlashdata('danger', $message);
            if ($this->request){
                return redirect()->back();
            }else{
                return $message;
            }

        }
    }

    public function tokensignin(){

        helper(['functions', 'text']);

        header('Content-Type: application/json');

        $id_token = $_REQUEST['idtoken'];
        $CLIENT_ID = '699921945088-7kvtvb6c08b0jac53jjo6c7u52jt60cq.apps.googleusercontent.com';

// Get $id_token via HTTPS POST.

        $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);

        $json = json_encode(['response' => 'success', 'message' => 'failed']);

        if ($payload) {
            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];

            $email = $payload['email'];
            $name = $payload['name'];
            $picture = $payload['picture'];


            $data = @$this->model->getWhere(['email' => $email])->getResult()[0];

//            Registration
//            lets check if user exists
            if (!$data){
//echo 'ddd';
                $country = session('geoplugin')['geoplugin_countryName'] ?? 'United States';
//           var_dump($country);die();

                $data = [
                    'username' 	=> $name,
                    'email' 	=> $email,
                    'phone' 	=> null,
                    'country' 	=> $country,
                    'created_at' => now(),
                    'last_login' => now(),
                    'profile_photo' => $picture

                ];
//            dd($this->model);
                $this->model->save($data);

                    $data = @$this->model->getWhere(['email' => $email])->getResult()[0];

            }

            if ($data){
//                Login the user
                $session = session();
                $ses_data = ['user' => $data];
                $session->set($ses_data);

                $redirect_to = $this->request->getVar('redirect_to');

                if (preg_match("#/user/register|/user/login#", $redirect_to)){
                    $redirect_to = site_url();
                }

                $json = json_encode(['response' => 'success', 'message' => 'true', 'redirect_to' => $redirect_to]);

            }


        } else {
            $json = json_encode(['response' => 'error', 'message' => 'none', 'redirect_to' => null]);
        }

        echo $json;



    }

    public function logout(): RedirectResponse
    {
        $session = session();
        $session->destroy();
        return redirect()->to(site_url());
    }
} 