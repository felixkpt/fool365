<?php namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Page extends Controller
{
    public function _remap($method, ...$params)
    {
        $__method = preg_replace("#-#", "_", $method);

        if (method_exists($this, $__method)) {
            return $this->$__method(...$params);
        }

        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function index(){
        // Whoops, we don't have a page for that!
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function fetcher(){

        $page = "Fetcher";
        $file = APPPATH.'/Controllers/Admin/'.$page.'.php';
        $data['content'] = '';
        if (is_file($file)){
            ob_start();
            include $file;
            $data['content'] = ob_get_clean();

        }else{
            // Whoops, we don't have a page for that!
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        render_admin_page('admin/page', $data);

    }
    public function users(){
        echo 'the ki';
    }

    public function results(){

        $page = "Results";
        $file = APPPATH.'/Controllers/Admin/'.$page.'.php';
        $data['content'] = '';
        if (is_file($file)){
            ob_start();
            include $file;
            $data['content'] = ob_get_clean();

        }else{
            // Whoops, we don't have a page for that!
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        render_admin_page('admin/page', $data);

    }

    public function matcher(){

        $page = "Matcher";
        $file = APPPATH.'/Controllers/Admin/'.$page.'.php';
        $data['content'] = '';
        if (is_file($file)){
            ob_start();
            include $file;
            $data['content'] = ob_get_clean();

        }else{
            // Whoops, we don't have a page for that!
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        render_admin_page('admin/page', $data);

    }

    public function tips_settings(){

        $page = "TipsSettings";
        $file = APPPATH.'/Controllers/Admin/'.$page.'.php';
        $data['content'] = '';
        if (is_file($file)){
            ob_start();
            include $file;
            $data['content'] = ob_get_clean();

        }else{
            // Whoops, we don't have a page for that!
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        render_admin_page('admin/page', $data);

    }

}
