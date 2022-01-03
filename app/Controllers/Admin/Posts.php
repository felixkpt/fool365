<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogModel;

class Posts extends BaseController
{

    protected $model;
protected $folder = 'admin/';

    public function __construct(){
        $this->model = New BlogModel();
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

    public function index() {

//        Lets see if current Request has action then we redirect to action method
        $action = $this->request->getVar('action');
        if ($action) {
            return self::_remap();
        }


        $post_type = $this->request->getVar('post_type') ?: 'post';
        $post_status = $this->request->getVar('post_status');
        $this->model->where(['post_type' => $post_type, 'post_status' => 'publish']);
            $items = $this->model->orWhere(['post_type' => $post_type, 'post_status' => 'draft'])->get()->getResult();

        $published = $this->model->where(['post_type' => $post_type, 'post_status' => 'publish'])->get()->getResult();
        $drafts = $this->model->where(['post_type' => $post_type, 'post_status' => 'draft'])->get()->getResult();
        $trashed = $this->model->where(['post_type' => $post_type, 'post_status' => 'trash'])->get()->getResult();

        $l = admin_url('/posts?post_type=post');
        $link = $l;

//        redirect to post index if no category items
        if ($post_status == 'trash' && !@$trashed[0]) {
            return redirect()->to($link);
        }
        if ($post_status == 'draft' && !@$drafts[0]) {
            return redirect()->to($link);
        }

        $data = ['post_type' => $post_type, 'post_status' => $post_status, 'items' => $items, 'published' => $published, 'drafts' => $drafts, 'trashed' => $trashed];
        $data['title'] = 'Posts';

        return render_admin_page($this->folder.'posts/index', $data);

    }


        public function create() {

        $this->create_table();

            $post_status = $this->request->getVar('post_status') ?: 'publish';
            $post_type = $this->request->getVar('post_type') ?: 'post';
            $data['folder'] = $this->folder;

            // Insert new
        if (@$_POST['action'] == 'create') {

            $title = ucfirst(trim($this->request->getVar('content_title')));

            $slug = $this->request->getVar('slug');
            $slug = ltrim($slug, '/');
            $slug = url_title($slug ?: $title, '-', true);
            $slug = rtrim($slug, '-');

            $content = trim(html_entity_decode($this->request->getVar('content_area')));
            $author = 1;


            if (!$this->model->where(['title' => $title, 'post_type' => $post_type])->first()) {


                if ($title && $content) {

                    $res = $this->model->save(['title' => $title,
                        'content' => $content, 'author' => $author, 'slug' => $slug,
                        'post_type' => $post_type, 'post_status' => $post_status]);

                    if ($res) {

                        $post_id = $this->model->where('slug', $slug)->first()['id'];

                        $l = site_url($slug);
                        if ($post_type == 'post'){
                            $l = blog_url($slug);
                        }
                        $link = '<a href="' . $l . '" class="btn btn-dark btn-sm">View ' . $post_type . '</a>';


                        if ($post_status == 'draft') {
                            return redirect()->to(admin_url('/posts?post=' . $post_id . '&action=edit&post_type=' . $post_type))->with('success', 'Draft saved!');
                     }

                        return redirect()->to(admin_url('/posts?post='.$post_id.'&action=edit&post_type='.$post_type))->with('success', 'Successfully saved! '.$link);
                    }else{
                        return redirect()->back()->with('error', 'Oops! We are encountering a problem while saving the data.');
                    }

                }else{
                    return redirect()->back()->with('error', 'Oops! Title or content cannot be empty.');
                }


            }else{
                return redirect()->back()->with('error', 'Oops! A similar title exists.');
            }
        }


        $data = ['folder' => $this->folder,'post_type' => $post_type, 'action' => $this->request->getVar('action'), 'full_width' => 0];
        $data['title'] = 'Create New '.$post_type;

		render_admin_page($this->folder.'posts/create', $data);

	}

    public function edit() {

        $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type') ?: 'post';
        $post_status = $this->request->getVar('post_status') ?: 'publish';
        $post_status_new = 'publish';
        $post_status_opposite = 'draft';

        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1){
            $post_type_echo = $post_type_echo.'s';
        }

        $regexp = "^".implode('|',$ids)."$";


        $msg = $counts.' '.$post_type_echo.' edited. ';

        if ($this->request->getVar('ids')) {

            if ($post_type == 'page') {
                return redirect()->back()->with('light', $msg);
            }

            $categories = $this->request->getVar('cat_IDS');

            //            Mass editing if cats
            if ($categories) {

//            We need a couple of loops to accomplish categories setting for multiple posts
//            Lets loop over each post and get its current cats
            foreach ($this->model->where('id', 'regexp', $regexp)->get() as $item) {

                $categories_active = [];
                if ($item->categories) {
                    $categories_active = explode(',', $item->categories);
                }

                //                Loop Selected categories and build append array
                $append = [];
                foreach ($categories as $category) {

                    if (!in_array($category, $categories_active)) {
                        $append[] = $category;
                    }
                }

                $categories_new = array_merge($categories_active, $append);

                $this->model->where('id', $item->id)->update(['categories' => implode(',', $categories_new)]);
            }

            return redirect()->back()->with('light', $msg);

        }
            return redirect()->back();


        }

        $items = @$this->model->where('id', $this->request->getVar('post'))->get()->getResult()[0];
        if (!$items) {
            abort(404);
        }
        $post_type = $items->post_type;

        // Update existing record
        if (@$_POST['action'] == 'edit') {
            $post_id = $items->id;

            $title = ucfirst(trim($this->request->getVar('content_title')));

            $slug = $this->request->getVar('slug');
            $slug = ltrim($slug, '/');
            $slug = url_title($slug ?: $title, '-', true);
            $slug = rtrim($slug, '-');

            $content = trim(html_entity_decode($this->request->getVar('content_area')));
            $author = 1;

            $categories = @implode(',', $this->request->getVar('cat_IDS'));

            $arr = ['title' => $title, 'slug' => $slug, 'content' => $content, 'author' => $author, 'post_status' => $post_status, 'categories' => $categories];
            $updated = $this->model->update($post_id, $arr);

            if ($updated) {

                if ($post_status == 'draft') {
                    return redirect()->back()->with('success', 'Draft saved!');
                }
                $l = site_url($slug);
                if ($post_type == 'post'){
                    $l = blog_url($slug);
                }
                $link = '<a href="'.$l.'" class="btn btn-dark btn-sm">View '.$post_type.'</a>';
                return redirect()->back()->with('success', 'Successfully saved! '.$link);


            }else{
                return redirect()->back()->with('error', 'Oops! We are encountering a problem while saving the data.');
            }
        }

        $data = ['folder' => $this->folder,'post_type' => $post_type, 'items' => $items, 'update' => 1, 'action' => $this->request->getVar('action'), 'full_width' => 0];
        $data['title'] = 'Edit '.$post_type;

        render_admin_page($this->folder.'posts/edit', $data);

    }


    private function publish(Request $request) {
        $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type') ?: 'post';
        $post_status = $this->request->getVar('post_status');
        $post_status_new = 'publish';
        $post_status_opposite = 'draft';

        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1){
            $post_type_echo = $post_type_echo.'s';
        }

        $regexp = "^".implode('|',$ids)."$";

        $msg = $counts.' '.$post_type_echo.' published. ';
        $href = url('').dash_uri().'/posts?ids='.implode(',', $ids).'&action='.($post_status ?: $post_status_opposite).'&post_type='.$post_type;
        $link = '<a href="'.$href.'" class="btn btn-link btn-sm">Undo&nbsp;<span class="fa fa-undo font-size-3"></span></a>';

        if ($this->model->where('id', 'regexp', $regexp)->update(['post_status' => $post_status_new])) {

//            Lets check if more similar records exits so as we can determine redirect rule
            if ($this->model->where([['post_type', $post_type], ['post_status', $post_status]])->first()) {
                return redirect()->back()->with('light', $msg.$link);
            }
            //        @empty redirect(path)
            else{
                return redirect()->to(url('').dash_uri().'/posts?post_type='.$post_type)->with('light', $msg.$link);
            }

        }else{
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }


    }

    private function draft(Request $request) {
        $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type') ?: 'post';
        $post_status = $this->request->getVar('post_status');
        $post_status_new = 'draft';
        $post_status_opposite = 'publish';

        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1){
            $post_type_echo = $post_type_echo.'s';
        }

        $regexp = "^".implode('|',$ids)."$";

        $msg = $counts.' '.$post_type_echo.' saved as draft. ';
        $href = url('').dash_uri().'/posts?ids='.implode(',', $ids).'&action='.($post_status ?: $post_status_opposite).'&post_type='.$post_type;
        $link = '<a href="'.$href.'" class="btn btn-link btn-sm">Undo&nbsp;<span class="fa fa-undo font-size-3"></span></a>';

        if ($this->model->where('id', 'regexp', $regexp)->update(['post_status' => $post_status_new])) {

//            Lets check if more similar records exits so as we can determine redirect rule
            if ($this->model->where([['post_type', $post_type], ['post_status', $post_status]])->first()) {
                return redirect()->back()->with('light', $msg.$link);
            }
            //        @empty redirect(path)
            else{
                return redirect()->to(url('').dash_uri().'/posts?post_type='.$post_type)->with('light', $msg.$link);
            }

        }else{
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }


    }

    public function trash() {
	    $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type');
        $post_status = $this->request->getVar('post_status');
        $post_status_new = 'trash';
        $post_status_opposite = 'untrash';

        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1){
            $post_type_echo = $post_type_echo.'s';
        }

//        $regexp = "^".implode('|',$ids)."$";

        $msg = $counts.' '.$post_type_echo.' moved to trash. ';
        $href = admin_url('/posts?ids='.implode(',', $ids).'&action='.($post_status ?: $post_status_opposite).'&post_type='.$post_type);
        $link = '<a href="'.$href.'" class="btn btn-link btn-sm">Undo&nbsp;<span class="fa fa-undo font-size-3"></span></a>';

        $error = false;
        foreach ($ids as $id){
            $res = $this->model->update($id, ['post_status' => $post_status_new]);

            if (!$res){
                $error = true;
            }
        }

        if (!$error) {

//            Lets check if more similar records exits so as we can determine redirect rule
            if ($this->model->where(['post_type' => $post_type, 'post_status' => $post_status])->first()) {
                return redirect()->back()->with('light', $msg.$link);
            }
            //        @empty redirect(path)
            else{
                return redirect()->to(admin_url('/posts?post_type='.$post_type))->with('light', $msg.$link);
            }

        }else{
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }


    }

    public function untrash() {

	    $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type') ?: 'post';

        $post_status = $this->request->getVar('post_status');
        $post_status_new = 'publish';
        $post_status_opposite = 'trash';
        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1){
            $post_type_echo = $post_type_echo.'s';
        }

        $regexp = "^".implode('|',$ids)."$";

        $msg = $counts.' '.$post_type_echo.' restored from trash. ';
        $href = admin_url('/posts?ids='.implode(',', $ids).'&action='.($post_status ?: $post_status_opposite).'&post_type='.$post_type);
        $link = '<a href="'.$href.'" class="btn btn-link btn-sm">Undo&nbsp;<span class="fa fa-undo font-size-3"></span></a>';

        $error = false;
        foreach ($ids as $id){
            $res = $this->model->update($id, ['post_status' => $post_status_new]);
        if (!$res){
            $error = true;
        }


        }

        if (!$error) {

//            Lets check if more similar records exits so as we can determine redirect rule
            if ($this->model->where(['post_type' => $post_type, 'post_status' => $post_status])->first()) {
                return redirect()->back()->with('light', $msg.$link);
            }
            //        @empty redirect(path)
            else{
                return redirect()->to(admin_url('/posts?post_type='.$post_type))->with('light', $msg.$link);
            }

        }else{
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }

    }

    public function delete()
    {
        $post_id = $this->request->getVar('post');
        $post_type = $this->request->getVar('post_type') ?: 'post';
        $post_status = $this->request->getVar('post_status');
        $post_status_new = '';
        $post_status_opposite = '';

        $ids = @($this->request->getVar('post') ?: $this->request->getVar('ids'));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

//           Redirect back empty and non arrays We are dealing with arrays in multiple actions
        if (!is_array($ids)) {
            return redirect()->back();
        }

        $counts = count($ids);

        $post_type_echo = $post_type;
        if ($counts > 1) {
            $post_type_echo = $post_type_echo . 's';
        }

//        $regexp = "^" . implode('|', $ids) . "$";

        $msg = $counts . ' ' . $post_type_echo . ' deleted.';
        $href = admin_url('/posts?post=' . $post_id . '&action=' . $post_status_opposite . '&post_type=' . $post_type);
        $link = '';

        $res = $this->model->whereIn('id', $ids)->delete();

        if ($res) {

//            Lets check if more similar records exits so as we can determine redirect rule
            if ($this->model->where(['post_type' => $post_type, 'post_status' => $post_status])->first()) {
                return redirect()->back()->with('light', $msg . $link);
            } //        @empty redirect(path)
            else {
                return redirect()->to(admin_url('/posts?post_type=' . $post_type))->with('light', $msg . $link);
            }

        } else {
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }

    }

    function create_table(){

        @$this->model->query("CREATE TABLE if not exists `posts`
			(
			    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			title varchar(255) NOT NULL,
content 	longtext NOT NULL,
author int(11) NOT NULL,
    	post_type varchar(20) NOT NULL,
guid varchar(255) NOT NULL,
categories varchar(255) NOT NULL,
post_status varchar(20) NOT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

			)");

    }
}


