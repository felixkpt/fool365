<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Appearance extends BaseController
{

    public function __construct()
    {

        $this->middleware('permission:update_themes', ['only' => ['index']]);

    }


    public static function index(Request $request)
    {

//        Lets see if current Request has slug after current parent slug ie any other slug after the slug[0]
        $slugs = explode('/', $request->slug);
//        Removing first element of the array
//        array_shift($slugs);
        $slug = @$slugs[0];

        if ($slug) {
            $request->slug = implode('/', $slugs);
            return SlugToClass($request, __NAMESPACE__);
        }

//        Lets see if current Request has action then we redirect to action method
        $action = $request->action;
        if ($action) {
            return self::parameters($request);
        }

//        return view($this->folder.'appearance.index');
    }

    private static function parameters(Request $request)
    {
        $action = $request->action;

        if (method_exists(self::class, $action)) {
            $methodName = $action;
            return self::$methodName($request);
        } else {
            abort(404);
        }

    }

}
