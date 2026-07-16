<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\City;

class CityController extends Controller
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
        $city = City::orderby('id', 'desc')->paginate(10);
        return view('panel.province.city', compact('city', $city));
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

        $this->validate($request, [
            'province_id' => 'required',
            'name' => 'required'
        ]);

        $data = new City($request->all());
        if ($data->save()) {
            toast()->success('با موفقیت انجام شد');
            return redirect()->back();
        } else {
            toast()->error('مشکلی رخ داده است', 'ناموفق!');
            return redirect()->back();
        }
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

        $this->validate($request, [
            'province_id' => 'required',
            'name' => 'required'
        ]);


        $update = City::find($id);
        if ($update->update($request->all())) {
            toast()->success('با موفقیت انجام شد');
            return redirect()->back();
        } else {
            toast()->error('مشکلی رخ داده است', 'ناموفق!');
            return redirect()->back();
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
        $find = City::findOrFail($id);
        $deleteData = $find->delete();
        if ($deleteData) {
            toast()->success(Message::successMessageCreate, 'موفقیت آمیز!');
            return back();
        }
        return redirect()->back();
    }
}
