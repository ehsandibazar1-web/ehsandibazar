<?php

namespace App\Http\Controllers\Admin;

use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Province;
class ProvinceController extends Controller
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
        $province=Province::orderby('id','desc')->paginate(10);
        return view('panel.province.index',compact('province',$province));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=new Province($request->all());
        if($data->save())
        {
            toast()->success('با موفقیت انجام شد');
            return  redirect()->back();
        }
        else
        {
            toast()->error('مشکلی رخ داده است', 'ناموفق!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       if(is_numeric($id)){
           $update=Province::find($id);
           if($update->update($request->all()))
           {
               toast()->success('با موفقیت انجام شد');
               return redirect()->back();
           }
           else
           {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteEnd = Province::find( $id )->delete();
        toast()->success('با موفقیت انجام شد');
        return redirect()->back();
    }
}
