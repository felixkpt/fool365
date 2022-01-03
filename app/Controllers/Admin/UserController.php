<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:list_users|create_users|edit_users|delete_users', ['only' => ['index','store']]);
        $this->middleware('permission:create_users', ['only' => ['create','store']]);
        $this->middleware('permission:edit_users', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_users', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('dashboard.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('dashboard.users.create',compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['nicename'] = str_slug($input['name']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->to(dash_uri().'/users')
            ->with('success','User created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('dashboard.users.show',compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::orderBy('id', 'asc')->get()->pluck('name','name');
        $userRole = $user->roles->pluck('name','name')->all();

        return view('dashboard.users.edit',compact('user','roles','userRole'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255',
            ],
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        if (User::where([['id', '!=', $request->id], ['name', '=', $request->name]])->first()) {
            return redirect()->back()->with('error', 'Name already taken.');
        }

        $input = $request->all();
        $input['nicename'] = str_slug($input['name']);

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }


        if (@$input['password']) {
            $input = ['name' => $input['name'],
                'nicename' => $input['nicename'],
                'email' => $input['email'],
                'password' => $input['password'],
            ];
        }else{
            $input = ['name' => $input['name'],
                'nicename' => $input['nicename'],
                'email' => $input['email'],
            ];
        }

        $user = User::find($id);
        User::where('id', $id)->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->to(dash_uri().'/users')
            ->with('success','User updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->to(dash_uri().'/users')
            ->with('success','User deleted successfully');
    }
}
