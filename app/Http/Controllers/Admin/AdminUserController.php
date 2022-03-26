<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('superAdmin');
    }

    public function index()
    {
        $users = User::all();

        return view("admin.user.index", compact("users"));
        # code...
    }

    public function update(Request $request, $id)
    {
        $validator =  Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'staff_id' => ['required', 'string', 'max:255'],
            'unit_location' => ['required', 'string', 'max:255'],
            'role' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $u = User::where('id', '=', $id)->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'staff_id' => $request['staff_id'],
            'email' => $request['email'],
            'unit_location' => $request['unit_location'],
            'p' => $request['role'],
        ]);

        if ($u) {
            $user = User::find($id);
            return redirect('admin/users?i=' . $user->id . '')->with('success', 'User details has been updated');
        } else {
            return back()->with('error', 'User details are not updated. Try again.');
        }
    }

    public function destroy($id)
    {
        $m = User::where('id', '=', $id)->delete();

        if ($m) {
            return redirect('admin/users?i=' . $id . '')->with('success', 'One User has been deleted');
        } else {
            return back()->with('error', 'Failed to delete User, Try again.');
        }
    }
}
