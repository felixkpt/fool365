<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function _remap($method, ...$params)
    {
        $__method = preg_replace("#-#", "_", $method);

 if (method_exists($this, $__method)) {
            return $this->$__method(...$params);
        }elseif (is_date($method)){
            return $this->view($method);
        }elseif (preg_match("#[0-9]-[a-z].+#", $method)){
            return $this->single_prediction($method);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }


    public function index(){

        $data['title'] = 'Welcome to site\'s Admin page';

        render_admin_page('admin/index', $data);

    }

}
