<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Subbidang;
class UserController extends Controller
{

    public function operate(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        $response = '';
        if ($type == 'new') {
            $response = $this->store($request);
        }
        if ($type == 'edit') {
            $response = $this->update($request, $id);
        } else
        if ($type == 'delete') {
            $response = $this->destroy($id);

        }
        return $response;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subbidang = Subbidang::pluck('nama', 'id');
        $subbidang[0] = "Tidak Ada Sub Bidang";
        $subbidang = $subbidang->sortBy(function($i, $v) {return $v;});
        $roles = Role::pluck('name','name')->all();

        $data = User::orderBy('id','DESC')->get();
        return view('users.index',compact('data','roles', 'subbidang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subbidang = Subbidang::pluck('nama', 'id');
        $subbidang->splice(0,0,"Tidak Ada Sub Bidang")->all();
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles', 'subbidang'));
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
            'name' => 'required',
            'subbidang_id' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('users.index')
        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::with('subbidang')->findOrFail($id);
        $user->hak_akses = $user->getRoleNames()->first();
        if ($request->wantsJson($id)) {

            return response()->json($user);
        }
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $subbidang = Subbidang::pluck('nama', 'id');
        $subbidang->splice(0,0,"Tidak Ada Sub Bidang");
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole', 'subbidang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('users.index')
        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->id == $id) {
            $arr = [
                'errors' => [
                    'pengguna' => 
                    [
                        "Tidak dapat Menghapus Data Sendiri"
                    ]
                ]
            ];
            return response()->json($arr, 422);
        }
        User::find($id)->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('users.index')
        ->with('success','User deleted successfully');
    }
}
