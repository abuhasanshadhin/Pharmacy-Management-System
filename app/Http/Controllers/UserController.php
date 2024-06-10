<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $users = User::with('roles:id,name')->latest('id')
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($perPage);

        return view('user.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::all()->toArray();
        return view('user.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            DB::beginTransaction();
            $data = $request->except('roles');
            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            $user->assignRole($request->input('roles'));
            DB::commit();
            return redirect()->route('user.index')->with('success','User created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('user.create')->with('error',$e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all('id','name','display_name')->toArray();
        return view('user.edit', compact('roles','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validateInputs($request, $id);
        try {
            DB::beginTransaction();
            $data = $request->except('password','roles');
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user = User::find($id);
            $user->update($data);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
            DB::commit();
            return redirect()->route('user.index')->with('success','User updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('user.edit')->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            $user->roles()->detach();
            $user->delete();

            DB::commit();
            return response()->json(['message' => 'Successfully deleted.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate incoming request inputs
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function validateInputs($request, $id = null)
    {
        $validateArray = [
            'username' => 'required | unique:users,username,' . $id . ',id',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'roles' => 'required',
        ];
        if (!$id){
            $validateArray =  array_merge($validateArray,[
                'password' => 'required | min:6',
            ]);
        }
        $request->validate($validateArray);
    }


    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Change current password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        if ($request->method() == 'POST') {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            try {
                $user = User::find(Auth::id());

                throw_unless(
                    Hash::check($request->current_password, $user->password),
                    Exception::class,
                    'Current password not matched.',
                    422
                );

                $user->update(['password' => bcrypt($request->password_confirmation)]);
                Auth::logout();
                return redirect()->route('user.profile')->with('success', 'Passwrod reset successfully, Please login again!');
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        return view('user.change_password');
    }

    /**
     * Update profile information
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $id = Auth::id();
        $request->validate([
            'name' => 'required',
            'username' => 'unique:users,username,' . $id . ',id',
        ]);

        try {
            $data = $request->except('profile_image');
            $user = User::find($id);
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $data['profile_image'] = $this->upload($image, 'profile_image');
            }
            $user->update($data);
            return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
