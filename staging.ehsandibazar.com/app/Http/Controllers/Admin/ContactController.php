<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\User;
use App\Utility\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Session;

class ContactController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = Lang::get('cms.list-message');
        $contact = Contact::orderby('id', 'desc')->paginate(10);
//        foreach ($contact as $item){
//            $user = User::select('id','email')->whereEmail($item->email)->first();
//            if (isset($user) && !empty($user)){
//                $item->update(['user_id' => $user->id]);
//            }
//        }
        return view('panel.contact.index', compact('contact' , 'title'));
     /*   $count = Contact::where('status', '0')->count();
        return view('panel.layout.master', ['count' => $count]);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       if(is_numeric($id)){
           $update = Contact::find($id);
           $update->state = 1;
           if ($update->update()) {
               toast()->info('با موفقیت تایید شد');
               return redirect()->back();
           } else {
               toast()->error('مشکلی رخ داده است', 'ناموفق!');
               return redirect()->back();
           }
       }else{
           toast()->error(Message::illegalError, 'خطا');
           return back();
       }
    }
    public function delete($id)
    {
       
        if(is_numeric($id)){
            $delete = Contact::find($id)->delete();
            toast()->success('با موفقیت انجام شد');
            return redirect()->back();
        }else{
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
       if(is_numeric($id)){
           $delete = Contact::find($id)->delete();
           toast()->success('با موفقیت انجام شد');
           return redirect()->back();
       }else{
           toast()->error(Message::illegalError, 'خطا');
           return back();
       }
    }

    public function status($id)
    {
        if(is_numeric($id)){
            $advertise = Contact::findOrFail($id);
            if ($advertise->count() > 0) {

                if ($advertise->status == 0) {
                    $data = [
                        'status' => 1
                    ];

                } elseif ($advertise->status == 1) {
                    $data = [
                        'status' => 0
                    ];
                }

                $update = $advertise->update($data);

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
        }else{
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }


}
