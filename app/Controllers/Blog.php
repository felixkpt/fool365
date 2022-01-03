<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BlogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Blog extends Controller
{

    public function __construct(){
        $this->model = New BlogModel();
        //        loading helper
        helper(['functions', 'text']);
    }

    public function index()
    {

        $data['posts'] = $this->model->findAll();
        $data['title'] = 'News archive';
        $data['description'] = 'News archive';

        render_page('blog/index', $data);
    }

    public function view($slug = NULL)
    {

        $data['post'] = $this->model->where('slug', $slug)->first();

        if (empty($data['post']))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = $data['post']['title'];
        $data['description'] = $data['post']['title'];

        render_page('blog/view', $data);
    }

    public function create()
    {

    }
}

