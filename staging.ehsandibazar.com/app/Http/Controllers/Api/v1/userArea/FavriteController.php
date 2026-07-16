<?php

namespace App\Http\Controllers\Api\v1\userArea;

use App\Model\favorite;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class FavriteController extends Controller
{
    public function index()
    {
        $favorites=favorite::with(['favoriteable' => function($q){
            $q->with('image');
        }])->owner()->get();
        return response([
            'status' => 200,
            'data' => [
                'favorites' => $favorites,
            ],
            'message' => 'success',
        ]);
    }

    public function delete($id)
    {
        $favrite=favorite::owner()->find($id)->delete();
        return response([
            'status' => 200,
            'message' => Message::successMassageFavoriteDelete,
        ]);
    }
}
