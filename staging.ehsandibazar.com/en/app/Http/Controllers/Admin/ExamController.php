<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Exam;
use App\Utility\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ExamController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "درخواست آزمون";
        $exams = Exam::latest()->paginate(10);
        return view('panel.exam.index', compact('exams' , 'title'));
    }





    public function update(Request $request, $id)
    {
        if(is_numeric($id)){
            $update = Exam::find($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(is_numeric($id)){
            $delete = Exam::find($id)->delete();
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
            $advertise = Exam::findOrFail($id);
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
