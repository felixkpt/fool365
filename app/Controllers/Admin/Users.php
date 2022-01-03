<?php

namespace App\Http\Controllers\Dashboard;

use App\Posts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\User;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class Users
{


//    View all users
    public function index(Request $request)
    {
        $action = $request->action;
        if ($action) {
            return self::parameters($request);
        }

        $items = User::all();
        return view($this->folder.'users')->with('items', $items);
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

//    Create a new user
    private function create(Request $request)
    {

        if (@$_POST['action'] == 'create') {

            $name = ucfirst(trim($request->name));
            $email = trim($request->email);
            $password = $request->password;
            $password_confirmation = $request->password_confirmation;

            $data = [
                'name' => $name,
                'nicename' => str_slug($name),
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password_confirmation
            ];

            Validator::make($data, [
                'name' => ['required', 'string', 'max:255', 'unique:users'],
                'nicename' => ['unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $k = User::create([
                'name' => $data['name'],
                'nicename' => $data['nicename'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if ($k) {
                return redirect()->to(url('').dash_uri().'/users')->with('success', 'Created user.');
            }else{
                return redirect()->back()->with('error', 'Error creating user.');
            }

        }else {
            return view($this->folder.'users-create');
        }

    }
//    Edit a new user
    private static function edit(Request $request)
    {
        echo 'Edit';
    }

    //    Delete a user
    private static function delete(Request $request)
    {
        $id = $request->user;
        if ($id < 2) {
        return  redirect()->back();
        }

            if (User::where('id', $id)->delete()){
                return redirect()->back()->with('warning', '1 User deleted.');
            }else{
                return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
            }

    }

    //    Delete a user
    private static function multiple_delete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()->back();
        }

        if (is_string($ids)) {
            $ids = json_decode($request->ids);
        }

//        We will not delete admin account
        if (in_array(1, $ids)) {

    $key = array_search(1, $ids);
            unset($ids[$key]);
        }

        $counts = count($ids);

        $page = 'User';
        if ($counts > 1){
            $page = $page.'s';
        }

        $regexp = "^".implode('|',$ids)."$";

        if ($counts < 1) {
            return  redirect()->back();
        }

        if (User::where('id', 'regexp', $regexp)->delete()){
            return redirect()->back()->with('warning', $counts.' '.$page.' deleted.');
        }else{
            return redirect()->back()->with('error', 'Oops! We are encountering a problem while performing the action.');
        }

    }
}
