<?php

/**
 * Application dashboard by Felix Kiptoo Biwott.
 * @url https://sharasolutions.com
 *
 * @return \App\Http\Controllers\Controller
 */

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {

        $slugs = explode('/', $request->slug);

        $slug = $slugs[0];

        if (!$slug) {
            return view($this->folder.'.index', [
'folder' => $this->folder
            ]);

        } else {
            if (method_exists($this, $slug)) {
                $methodName = $slug;
                return $this->$methodName($request);
            } else {
                abort(404);
            }
        }

    }

    public function posts(Request $request)
    {
        return \App\Http\Controllers\Dashboard\PostController::index($request);

    }


}
