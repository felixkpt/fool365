<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogModel;

class Uploads extends BaseController
{
    protected $folder = 'admin/';

    public function __construct()
    {
//            $this->middleware('permission:upload_files');
    }

    public function _remap($method, ...$params)
    {
        $method = $this->request->getVar('action') ?? preg_replace("#-#", "_", $method);


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

    public function index()
    {

        $action = $this->request->getVar('action');
        if ($action) {
            return self::parameters();
        }
        $data = ['title' => 'Uploads'];

        render_admin_page($this->folder.'uploads/index', $data);
    }

    public function upload()
    {

//        var_dump($_GET,$_POST);die;

        $data = ['title' => 'New upload'];

        render_admin_page($this->folder.'uploads/create', $data);
    }

    public  static function store() {


        if (@$_POST['action'] == 'upload') {
            
            $files = $this->request->getVar('file') ?: [];

            $counts = 0;
            foreach ($files as $file) {


                //Display File Name
                echo 'File Name: '.$file->getClientOriginalName();
                echo '<br>';

                //Display File Extension
                echo 'File Extension: '.$file->getClientOriginalExtension();
                echo '<br>';

                //Display File Real Path
                echo 'File Real Path: '.$file->getRealPath();
                echo '<br>';

                //Display File Size
                echo 'File Size: '.$file->getSize();
                echo '<br>';

                //Display File Mime Type
                echo 'File Mime Type: '.$file->getMimeType();

                $title = $description = $type = $slug = $url = $user_id = '';

                $extension = $file->getClientOriginalExtension();
                $title = ucfirst(basename($file->getClientOriginalName(), '.'.$extension));

                $type = $file->getMimeType();

                $user_id = @\Illuminate\Support\Facades\Auth::user()->id ?: '0';

                $destinationPath = 'uploads/images';
                $slug = '/'.$destinationPath.'/'.str_slug($title).'.'.$extension;


//            Create a new attachment
                if (Upload::create(['title' => $title, 'type' => $type, 'slug' => $slug, 'url' => $url, 'user_id' => $user_id]))
                    //Str_Slug_Name && Move Uploaded File

                    $file->move($destinationPath,str_slug($title).'.'.$extension, 0777);

                $counts ++;
            }

            $name = "image";
            if ($counts > 1) {
                $name = "images";
            }
            if ($files) {

                $msg = $counts.' '."$name uploaded.";

                return redirect()->back()->with('success', $msg);
            }else{
                $msg = 'No '."$name uploaded.";
                return  redirect()->back()->with('warning', $msg);
            }

        }
    }

}
