<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartmentUsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin');
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){ $q->where('name', 'Facility');})
        ->where('active', true)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
        return view('admin.facilities.departments.users.index')->with(
			compact(['users'])
		);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function create(Department $department)
    {
        $user = new User();
        return view('admin.facilities.departments.users.create')->with(
			compact(['user','department'])
		);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Department $department)
    {
        $this->performValidation($request);
		$this->validate($request, [
			'email' => $this->emailRegEx()
        ]);
        $user = new User($request->toArray());
        $user->password = Hash::make(Str::random(10));
        $user->user_name = $request->input('email');
        $user->__set('role', Role::getKey(Role::FACILITY));
        $user->assignRole('Facility');
        if ($user->save()) {
            $user->departments()->attach($department->id);
		}
        $redirect = $request->input('url');
        if(isset($redirect) && $redirect){
            return redirect($redirect)->with('success', 'User Created');
        }
        return redirect()->back()->with('success', 'User Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.facilities.departments.users.edit')->with(
			compact(['user'])
		);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->performValidation($request);
		$this->validate($request, [
			'email' => $this->emailRegEx($user),
        ]);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->update();
        $redirect = $request->input('url');
        if(isset($redirect) && $redirect){
            return redirect($redirect)->with('success', 'User Updated');
        }
        return redirect()->back()->with('success', 'User Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User Removed');
    }

    private function performValidation($request)
	{
		$this->validate($request, [
			'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'mobile' => 'required|min:10|max:15',
		]);
	}
}
