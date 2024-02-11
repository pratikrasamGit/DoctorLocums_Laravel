<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Facility;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacilityUsersController extends Controller
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
        $users = User::whereHas('roles', function($q){ $q->where('name', 'FacilityAdmin');})
        ->where('active', true)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
        return view('admin.facilities.users.index')->with(
			compact(['users'])
		);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Facility $facility
     * @return \Illuminate\Http\Response
     */
    public function create(Facility $facility)
    {
        $user = new User();
        return view('admin.facilities.users.create')->with(
			compact(['user','facility'])
		);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Facility $facility
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Facility $facility)
    {
        $this->performValidation($request);
		$this->validate($request, [
			'email' => $this->emailRegEx(),
            'password' => $this->passwordRegEx()
        ]);
        $user = new User($request->toArray());
        $user->password = Hash::make($request->input('password'));
        $user->user_name = $request->input('email');
        $user->__set('role', Role::getKey(Role::FACILITYADMIN));
        $user->assignRole('FacilityAdmin');
        if ($user->save()) {
            $user->facilities()->attach($facility->id);
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
        return view('admin.facilities.users.edit')->with(
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
