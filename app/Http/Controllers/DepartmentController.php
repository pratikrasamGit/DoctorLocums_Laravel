<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    
    public function __construct()
    {
        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-show', ['only' => ['index']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('auth');
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin|FacilityAdmin');
        $this->middleware('access.department');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = $this->departmentSelection()->paginate(10);
        return view('admin.facilities.departments.index')->with(
			compact(['departments'])
		);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facilities = $this->facilitySelection()->pluck('name', 'id');
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        $department = new Department();
        return view('admin.facilities.departments.create')->with(
			compact(['facilities','department','specialities'])
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
        $department = new Department($request->toArray());
        $department->save();
        return redirect('/admin/departments')->with('success', 'Department Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $facilities = $this->facilitySelection()->pluck('name', 'id');
        $specialities = $this->getSpecialities()->pluck('title', 'id');
        return view('admin.facilities.departments.edit')->with(
			compact(['facilities','department','specialities'])
		);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $this->performValidation($request);
        $department->update($request->toArray());
        $redirect = $request->input('url');
        if(isset($redirect) && $redirect){
            return redirect($redirect)->with('success', 'Department Updated');
        }
        return redirect()->back()->with('success', 'Department Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Department $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
		return redirect('/admin/departments')->with('success', 'Department Removed');
    }

    /**
	 * Delete mapping between user and department
	 *
	 * @param User $user
	 * @param Department $department
	 *
	 * @throws \Exception
	 *
	 * @return Redirect
	 */
	public function detachUser(User $user, Department $department)
	{
        $department->users()->detach($user->id);
        $user->delete();
		return redirect()->back()->with('success', 'User Removed');
	}

    private function performValidation($request)
	{
		$this->validate($request, [
            'facility_id' => 'required|string|uuid',
			'department_name' => 'max:255',
			'department_specialties' => 'max:255',
			'department_phone' => 'max:15',
            'department_numbers' => 'max:10',            
		]);
	}
}
