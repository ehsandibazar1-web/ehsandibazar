<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Consultation;
use App\Utility\Message;


class ConsultationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "درخواست های مشاوره";
        $consultations = Consultation::latest()->get();
        return view('panel.consultation.index', compact('consultations' , 'title'));
    }

    public function delete($id)
    {
        if(is_numeric($id)){
            $delete = Consultation::find($id)->delete();
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
            $consultation = Consultation::findOrFail($id);
            if ($consultation->count() > 0) {

                if ($consultation->status == 0) {
                    $data = [
                        'status' => 1
                    ];

                } elseif ($consultation->status == 1) {
                    $data = [
                        'status' => 0
                    ];
                }

                $update = $consultation->update($data);

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
