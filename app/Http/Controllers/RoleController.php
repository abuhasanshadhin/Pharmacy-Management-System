<?php

namespace App\Http\Controllers;

use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest('id')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::select('id', 'name', 'label', 'module')
            ->get()
            ->groupBy('module')
            ->toArray();
        return view('roles.create',compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        try {
            DB::beginTransaction();
            $name = $request->input('name');
            $roleName = str_replace(' ','_', $name);
            $data['display_name'] = $name;
            $data['name'] = strtolower($roleName);
            $permissions = $request->input('permissions');
            $role = Role::create($data);
            $role->syncPermissions($permissions);
            DB::commit();
            return redirect()->route('role.index')->with('success','Role created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('role.create')->with('error',$e->getMessage());
        }
    }


    public function edit(Role $role)
    {
        $role->load('permissions');
        $permitteds = $role->permissions->groupBy('module')->toArray();
        $permissions = Permission::select('id', 'name', 'label', 'module')
            ->get()
            ->groupBy('module')
            ->toArray();
        return view('roles.edit',compact('role','permissions','permitteds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
        ]);
        try {
            DB::beginTransaction();
            $name = $request->input('name');
            $roleName = str_replace(' ','_', $name);
            $role = Role::findOrFail($id);
            $data['display_name'] = $name;
            $data['name'] = strtolower($roleName);
            $permissions = $request->input('permissions');
            $role->update($data);
            $role->syncPermissions($permissions);
            DB::commit();
            return redirect()->route('role.index')->with('success','Role updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->delete();
            DB::commit();
            return redirect()->route('role.index')->with('success','Role deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('role.index')->with('error',$e->getMessage());
        }
    }
}
