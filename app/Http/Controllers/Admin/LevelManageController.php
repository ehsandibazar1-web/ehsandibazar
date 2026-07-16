<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Role;
use App\User;
use Illuminate\Http\Request;
use SEO;
use Alert;

class LevelManageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $users = User::latest()->with('roles')->paginate(20);
        $title = "مدیریت نقش کاربران";
        SEO::setTitle($title);
        return view('panel.users-level.index', compact('users', 'title'));
    }

    public function create()
    {
        $title = "ثبت نقش کاربر";
        $roles = Role::all();
        $users = User::all();
        SEO::setTitle($title);
        return view('panel.users-level.create', compact('title', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');
        $user = User::find($userId);

        $user->roles()->sync($roleId);
        toast()->success('با موفقیت انجام شد');
        return redirect(route('panel.LevelManage.index'));
    }

    public function edit(User $user)
    {
        $title = "ویرایش نقش کاربر";
        $roles = Role::all();
        SEO::setTitle($title);
        return view('panel.users-level.create', compact('roles', 'title', 'user'));
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            "role_id" => "required",
        ]);

        $roleId = $request->input('role_id');
        $user->roles()->sync($roleId);
        toast()->success('با موفقیت انجام شد');
        return redirect(route('panel.LevelManage.index'));
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        toast()->success('با موفقیت انجام شد');
        return redirect(route('panel.LevelManage.index'));
    }
}
