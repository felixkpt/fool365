<?php

namespace App\Controllers;

use CodeIgniter\CodeIgniter;
use CodeIgniter\Controller;

class Pages extends Controller
{

    public function _remap($method, ...$params){

        $__method = preg_replace("#-#", "_", $method);

        if (method_exists(Pages::class, $__method)){

            $this->{$__method}();

        }else{
            throw new \CodeIgniter\Exceptions\PageNotFoundException($__method);
        }

    }

    public function index()
    {
        return view('welcome_message');
    }

    public function view($page = 'home')
    {
        if (! is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        echo view('templates/header', $data);
        echo view('pages/' . $page, $data);
        echo view('templates/footer', $data);
    }


}