<?php

namespace App\Http\Controllers\Admin;

use App\Model\Gift;
use App\Model\Product;
use App\Model\Requestgift;
use App\User;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class RequestGiftController extends Controller
{

    protected $user;
    public const countOfRender = 9;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title = Lang::get('cms.header-gift-request-list');
        $allRequestGift = Requestgift::latest()->paginate(self::countOfRender);
        return view('panel.request-gift.index', compact('title', 'allRequestGift'));
    }

    public function requestGift($id)
    {
        if (isset($id) && is_numeric($id)) {

            /* validation gift */
            $findGift = Gift::whereStatus(1)->findOrFail($id);
            $resultScore = $this->validationScoreUser($this->user, $findGift);

            /* validation request again */
            $resultRequestAgain = $this->isRequestUser($this->user, $findGift);
            if ($resultRequestAgain == true) {
                toastr()->error(Message::requestAgain, Lang::get('cms.error'));
                return back();
            }

            if ($resultScore) {

                $saveData = Requestgift::create([
                    'user_id' => $this->user->id,
                    'gift_id' => $findGift->id,
                    'used' => 0
                ]);

                if ($saveData instanceof Requestgift) {
                    toastr()->success(Message::request, Lang::get('cms.success'));
                    return redirect()->route('panel.dashboard.index');
                } else {
                    toastr()->error(Message::illegalError, Lang::get('cms.error'));
                    return redirect()->route('panel.dashboard.index');
                }

            } else {
                toastr()->error(Message::score, Lang::get('cms.error'));
                return back();
            }

        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return redirect()->route('dashboard.index');
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Requestgift::owner()->findOrFail($id);
            $user = $find->user;
            $gift = $find->gift;
            $data = [
                'used' => 0
            ];

            if ($find->used == 0) {
                $result = $this->manageScoreUser($user, $gift, 0);
                if ($result) {
                    $data = [
                        'used' => 1
                    ];
                } else {
                    toastr()->error(Message::score, Lang::get('cms.error'));
                    return back();
                }

            } elseif ($find->used == 1) {

                $result = $this->manageScoreUser($user, $gift, 1);

                if ($result) {
                    $data = [
                        'used' => 0
                    ];
                } else {
                    toastr()->error(Message::score_admin, Lang::get('cms.error'));
                    return back();
                }
            }

            $update = $find->update($data);
            if ($update) {
                toastr()->success(Message::successMessageEdit, 'موفقیت آمیز!');
                return back();
            } else {
                toastr()->error(Message::errorMessageEdit, 'خطا');
                return back();
            }

        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Requestgift::owner()->findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                toastr()->success(Message::successMessageDelete, 'موفقیت آمیز!');
                return back();
            } else {
                toastr()->error(Message::errorMessageDelete, 'خطا');
                return back();
            }
        } else {
            toastr()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    /* =================== private function ========= */
    /* score user */
    private function validationScoreUser($user, $gift)
    {

        if (isset($user) && !empty($user) && isset($gift) && !empty($gift)) {

            $userScore = $user->gift_score;
            $giftScore = $gift->score;
            if ($userScore > 0) {
                if ($userScore >= $giftScore) {

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /* increment / decrement */

    private function manageScoreUser($user, $gift, $increment = 1)
    {
        if (isset($user) && isset($gift) && !empty($user) && !empty($gift)) {

            $scoreGift = $gift->score;
            $scoreUser = $user->gift_score;
            $findUser = User::whereId($user->id)->first();
            if ($increment == 1) {
                if ($findUser) {
                    $incrementScore = $scoreUser + $scoreGift;
                    $updateScoreUser = $findUser->update(['gift_score' => $incrementScore]);
                    if ($updateScoreUser) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

            } elseif ($scoreUser >= $scoreGift && $increment == 0) {

                if ($findUser) {
                    $incrementScore = $scoreUser - $scoreGift;
                    $updateScoreUser = $findUser->update(['gift_score' => $incrementScore]);
                    if ($updateScoreUser) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }
    }

    private function isRequestUser($user, $gift)
    {

        if (isset($user) && isset($gift) && !empty($user) && !empty($gift)) {

            $findGift = Requestgift::where('user_id', $user->id)->where('gift_id', $gift->id)->first();

            if ($findGift) {
                return true;
            } else {
                return false;
            }
        }
    }

}
