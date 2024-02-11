<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){ $q->where('name', 'Admin');})
        ->where('active', true)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
        return view('admin.users.index')->with(
			compact(['users'])
		);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('admin.users.create')->with(
			compact(['user'])
		);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->performValidation($request);
		$this->validate($request, [
			'email' => $this->emailRegEx(),
			'password' => $this->passwordRegEx()
        ]);
        $user = new User($request->toArray());
        $user->password = Hash::make($request->input('password'));
        $user->user_name = $request->input('email');
        $user->__set('role', Role::getKey(Role::ADMIN));
        $user->assignRole('Admin');
        $user->save();
        return redirect('/admin/adminusers')->with('success', 'User Created');
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit')->with(
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
    public function update(User $user, Request $request)
    {
        $this->performValidation($request);
		$this->validate($request, [
			'email' => $this->emailRegEx($user),
        ]);
        if ($request->input('password')) {
            $this->validate($request, [
                'password' => $this->passwordRegEx()
            ]);
            $user->password = Hash::make($request->input('password'));
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->update();
        return redirect('/admin/adminusers')->with('success', 'User Updated');
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
		return redirect('/admin/adminusers')->with('success', 'Admin User Removed');
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
