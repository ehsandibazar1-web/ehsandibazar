<?php

namespace App\Http\Controllers\Admin;

use App\Model\File;
use App\Model\Image;
use App\Model\Role;
use App\Model\Systeminfmanage;
use App\User;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Self_;
use Session;
use SEO;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        SEO::setTitle('مدیریت | لیست کاربران');
        $title = "مدیریت | لیست کاربران";
        $users = User::adminRole()->owner()->latest()->get();
        return view('panel.users.index', compact('users', 'title'));
    }

    public function create()
    {
        SEO::setTitle('مدیریت | ایجاد کاربر جدید');
        $allRole = Role::where('id', ">", 1)->get();
        $title = "مدیریت | ایجاد کاربر جدید";
        return view('panel.users.create', compact('title', 'allRole'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $family = $request->input('family');
        $nationalCode = $request->input('national_code');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role');
        $level = $request->input('level');


        $this->validate($request, [
            'name' => 'required|max:190',
            'family' => 'required|max:190',
            'level' => 'required',
            'password' => 'required|max:180',
            'mobile' => 'required', 'string', 'max:13',
            'role' => 'required'
        ]);

        $requestData = [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'email' => $email,
            'password' => Hash::make($password),
            'level' => $level,
            'reagent' => time(),
            'active' => Status::active,
            'status' => Status::active
        ];

        DB::beginTransaction();
        $saveData = User::create($requestData);
        if ($saveData instanceof User) {
            DB::commit();
            $saveData->roles()->sync($role);
            toast()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('panel.users.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('panel.users.index');
        }
    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $find = User::findOrFail($id);
            $name = $request->input('name');
            $family = $request->input('family');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $password = $request->input('password');
            $role = $request->input('role');
            $level = $request->input('level');

            $this->validate($request, [
                'name' => 'required|max:190',
                'family' => 'required|max:190',
                'level' => 'required',
                'password' => 'required|max:180',
                'mobile' => 'required', 'string', 'max:13',
                'role' => 'required'
            ]);

            $requestData = [
                'name' => $name,
                'family' => $family,
                'mobile' => $mobile,
                'email' => $email,
                'password' => Hash::make($password),
                'level' => $level,
                'active' => Status::active,
                'status' => Status::active
            ];

            DB::beginTransaction();
            $updateData = $find->update($requestData);
            if ($updateData) {
                DB::commit();
                $find->roles()->sync($role);
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('panel.users.index');
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('panel.users.index');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.users.index');
        }
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = "مدیریت | ویرایش اطلاعات کاربری";
            SEO::setTitle('مدیریت | ویرایش اطلاعات کاربری');
            $find = User::findOrFail($id);
            $allRole = Role::get();
            return view('panel.users.create', compact('title', 'find', 'allRole'));
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('panel.users.index');
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $findUser = User::owner()->findOrFail($id);
            $delete = $findUser->delete();
            DB::beginTransaction();
            if ($delete) {
                DB::commit();

                toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
                return redirect()->route('panel.users.index');
            } else {
                DB::rollBack();
                toast()->success(Message::errorMessageCreate, 'خطا!');
                return redirect()->route('panel.users.index');
            }
        } else {
            toast()->error(Message::illegalError, 'خطا!');
            return redirect()->route('panel.users.index');
        }
        /*$user = User::findOrFail($user);*/
        $user->delete();
        toast()->success('با موفقیت انجام شد');
        return back();
    }

    public function active($id)
    {
        if (is_numeric($id)) {
            $find = User::owner()->findOrFail($id);

            if ($find->mobile_verified_at == 0) {
                $data = [
                    'mobile_verified_at' => 1
                ];

            } elseif ($find->mobile_verified_at == 1) {
                $data = [
                    'mobile_verified_at' => 0
                ];
            }
            $update = $find->update($data);
            if ($update) {
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    /* for block user */
    public function status($id)
    {
        if (is_numeric($id)) {
            $find = User::owner()->findOrFail($id);
            if ($find->active == 0) {
                $data = [
                    'active' => 1
                ];

            } elseif ($find->active == 1) {
                $data = [
                    'active' => 0
                ];
            }
            $update = $find->update($data);
            DB::beginTransaction();
            if ($update) {
                DB::commit();
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function Search(Request $request)
    {
        $email = $request->input('email');
        $this->validate($request, [
            'email' => 'required'
        ]);
        $users = User::adminRole()->owner()->where('email', $email)->get();
        if (isset($users) && !empty($users) && count($users) > 0) {
            return view('panel.users.index', compact('users'));
        } else {
            toast()->error("کاربری با این مشخصات یافت نشد.", Lang::get('cms.error'));
            return back();
        }


    }


    public function showDetail(User $user)
    {
        SEO::setTitle("نمایش جزییات کاربر");
        $genders = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 13)->latest()->get();
        $degreeEducation = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 14)->get();
        $interests = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 15)->get();
        $maritalStatus = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 16)->get();
        $languages = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 17)->get();
        $cigarettes = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 18)->get();
        $drink = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 19)->get();
        $countries = Country::whereStatus(Status::active)->get();
        $posts = Post::where('user_id', $user->id)->latest()->paginate(10);

        return view('panel.users.show-detail', compact('user', 'posts', 'countries', 'genders', 'degreeEducation',
            'interests', 'maritalStatus', 'languages', 'cigarettes', 'drink'));

    }

    public function statusProfile(User $user)
    {
        $find = $user->detail;
        if ($find->count() > 0) {

            if ($find->status == Status::Wating) {
                $data = [
                    'status' => Status::active
                ];

            } elseif ($find->status == Status::active) {
                $data = [
                    'status' => Status::deActive
                ];
            } elseif ($find->status == Status::deActive) {
                $data = [
                    'status' => Status::Wating
                ];
            }

            $update = $find->update($data);

            if ($update) {
                toast()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return back();
            } else {
                toast()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }

        } else {
            toast()->error(Message::systemError, 'خطا');
            return back();
        }
    }


    public function updateUserDetail(User $user, Request $request)
    {
        $this->validate($request, [
              'slug' => ['required','min:8',Rule::unique('users')->where(function($query)use ($user) {
                $query->where('deleted_at', null);
                $query->where('id', '!=', $user->id);
            })],
            'name' => 'required',
            'family' => 'required',
            'mobile' => ['numeric'],
            'email' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'degree_education' => 'required',
            'interests' => 'required',
            'marital_status' => 'required',
            'language' => 'required',
            'height' => 'required',
            'cigarettes' => 'required',
            'drink' => 'required',
        ],[
            'unique' => 'آدرس پروفایل  وارد شده قبلا انتخاب شده',
            'min' => 'آدرس پروفایل  نباید کمتر از 8 کاراکتر باشد'

        ]);

        /* avatar */
        $avatar = $request->file('avatar');
        $cover = $request->file('cover');
        /* avatar */

        /* public information */
        $name = $request->input('name');
        $family = $request->input('family');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $slug = $request->input('slug');

        $countries = $request->input('countries');
        /* public information */

        $find = $user;
        if ($avatar && !empty($avatar)) {
            $path = "/avatar/" . $find->id;
            $imageStore = $avatar->store($path);
            Image::updateOrCreate([
                'user_id' => $user->id,
                'imageable_type' => get_class($find),
                'imageable_id' => $find->id,
            ], [
                'url' => "/storage/" . $imageStore,
                'user_id' => $user->id,
                'imageable_type' => get_class($find),
                'imageable_id' => $find->id,
            ]);
        }

        $saveData = [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'email' => $email,
            'slug' => $slug,
        ];


        if ($user->update($saveData)) {

            if ($cover && !empty($cover)) {
                $path = "/cover/" . $find->id;
                $coverStore = $cover->store($path);
            }
            $details = DetailUser::updateOrCreate(['user_id' => $find->id], [
                'birth_date' => $request->input('birth_date'),
                'gender' => $request->input('gender'),
                'degree_education' => $request->input('degree_education'),
                'interests' => $request->input('interests'),
                'marital_status' => $request->input('marital_status'),
                'language' => $request->input('language'),
                'height' => $request->input('height'),
                'cigarettes' => $request->input('cigarettes'),
                'drink' => $request->input('drink'),
                'slogan' => $request->input('slogan', null),
                'facebook' => $request->input('facebook', null),
                'instagram' => $request->input('instagram', null),
                'bio' => $request->input('bio', null),
                'place_of_birth' => $request->input('place_of_birth', null),
                'location' => $request->input('location', null),
                'education_place' => $request->input('education_place', null),
                'page_type' => $request->input('profileType', null),
                'status' => Status::active,
                'cover' => isset($coverStore) && !empty($coverStore) ?? "/storage/" . $coverStore,
            ]);

            if (isset($countries) && !empty($countries)) {
                $find->countries()->sync($countries);
            }


            toast()->success('موفقیت آمیز :)', 'اطلاعات پروفایل شما بروز شد ')->persistent('بستن');
            return redirect()->back();
        } else {
            toast()->error('خطایی رخ داده!', 'متاسفیم :(');
            return redirect()->back();
        }
    }

    public function deleteGallery(Image $image)
    {
        Storage::delete($image->url);
        $image->delete();
        toast()->success('با موفقیت حذف  شد');
        return back();
    }
}
