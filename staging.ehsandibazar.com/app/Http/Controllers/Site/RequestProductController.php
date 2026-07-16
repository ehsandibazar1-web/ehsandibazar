<?php

namespace App\Http\Controllers\Site;

use App\Model\Product;
use App\Model\Requestproduct;
use App\Utility\Level;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class RequestProductController extends Controller
{
    protected $user;
    protected const countOfRender = 9;
    protected $allProduct;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->allProduct = Product::whereStatus(1)->get();
    }

    public function index()
    {
        $requestProduct = Requestproduct::owner()->latest()->paginate(self::countOfRender);
        return view('site.request-product.index', compact('requestProduct'));
    }

    public function create()
    {

        if (!in_array($this->user->level, Level::levelAdmins())) {

            if ($this->user->credit <= 0) {
                return redirect()->route('panel.payment.index');
            }
        }

        $allProduct = $this->allProduct;
        return view('site.request-product.create', compact('allProduct'));
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $title = Lang::get('cms.edit-request-product');
            $allProduct = $this->allProduct;
            $findIdProducts = Requestproduct::owner()->findOrFail($id);

            /* image */
            $images = "";
            if (isset($findIdProducts->image) && !empty($findIdProducts->image)) {
                $images = explode(",", $findIdProducts->image);
            }
            /* video */
            $videos = "";
            if (isset($findIdProducts->video) && !empty($findIdProducts->video)) {
                $videos = explode(",", $findIdProducts->video);
            }

            return view('site.request-product.create', compact('videos', 'images', 'allProduct', 'title', 'findIdProducts'));
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function store(Request $request)
    {
        $product_id = $request->input('product_id');
        $description = $request->input('description');
        $details = $request->input('details');
        $images = $request->input('filepath');
        $videos = $request->input('video');
        $catalog = $request->input('catalog');

        /* validation */
        $this->validate($request, [
            'description' => 'required',
            'product_id' => 'required'
        ]);

        /* validation if product_id == 0 */
        if (isset($product_id) && $product_id == 0) {
            Product::whereStatus(1)->findOrFail($product_id);
            if (!isset($details) || empty($details) || $details == null) {
                return back()->with(['error' => 'فیلد جزییات الزامی می باشد.']);
            }
        }

        /* validation array Image */
        if ($message = $this->is_validFileArray($images, config('whiteList.validImage'),
            Message::notFoundImage,
            Message::inValidImage,
            Message::imageNotCorrectly)) {
            return back()->with(['error' => $message]);
        }

        /* validation array Video */
        if ($message = $this->is_validFileArray($videos, config('whiteList.validVideo'),
            Message::notFoundVideo,
            Message::inValidVideo,
            Message::videoNotCorrectly
        )) {
            return back()->with(['error' => $message]);
        }

        /* validation catalog */
        if (isset($catalog) && !empty($catalog)) {
            if (!$this->is_formatValid($catalog, config('whiteList.validPdf'))) {
                return back()->with(['error' => "لطفا کاتالوگ محصول را با فرمت مناسب وارد نمایید."]);
            }
        }
        /* get array image and put , and convert to string */
        $arrayImage = "";
        if (isset($images) && count($images) > 0) {
            $arrayImage = $this->arrayImage($images);
        }
        /* get array video and put , and convert to string */
        $arrayVideo = "";
        if (isset($videos) && count($videos) > 0) {
            $arrayVideo = $this->arrayVideo($videos);
        }

        $requestData = [
            'image' => $arrayImage,
            'video' => $arrayVideo,
            'catalog' => $catalog,
            'user_id' => $this->user->id,
            'description' => $description,
            'details' => isset($details) ? $details : "",
            'status' => 0,
            'product_id' => isset($product_id) && !empty($product_id) ? $product_id : 0
        ];

        $saveData = Requestproduct::owner()->create($requestData);

        if ($saveData instanceof Requestproduct) {
            $this->user->decrement('credit');
            toastr()->success(Message::successMessageCreate, Lang::get('cms.success'));
            return redirect()->route('site.request.product');
        } else {
            toastr()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('site.request.product');
        }

    }

    public function update(Request $request, $id)
    {
        if (is_numeric($id)) {
            $findProducts = Requestproduct::owner()->findOrFail($id);
            $product_id = $request->input('product_id');
            $description = $request->input('description');
            $details = $request->input('details');
            $images = $request->input('filepath');
            $videos = $request->input('video');
            $catalog = $request->input('catalog');

            /* validation */
            $this->validate($request, [
                'description' => 'required',
                'product_id' => 'required'
            ]);

            /* validation if product_id == 0 */
            if (isset($product_id) && $product_id == 0) {
                if (!isset($details) || empty($details) || $details == null) {
                    return back()->with(['error' => 'فیلد جزییات الزامی می باشد.']);
                }
            }

            /* validation array Image */
            if ($message = $this->is_validFileArray($images, config('whiteList.validImage'),
                Message::notFoundImage,
                Message::inValidImage,
                Message::imageNotCorrectly)) {
                return back()->with(['error' => $message]);
            }

            /* validation array Video */
            if ($message = $this->is_validFileArray($videos, config('whiteList.validVideo'),
                Message::notFoundVideo,
                Message::inValidVideo,
                Message::videoNotCorrectly
            )) {
                return back()->with(['error' => $message]);
            }

            /* validation catalog */
            if (isset($catalog) && !empty($catalog)) {
                if (!$this->is_formatValid($catalog, config('whiteList.validPdf'))) {
                    return back()->with(['error' => "لطفا کاتالوگ محصول را با فرمت مناسب وارد نمایید."]);
                }
            }
            /* get array image and put , and convert to string */
            $arrayImage = "";
            if (isset($images) && count($images) > 0) {
                $arrayImage = $this->arrayImage($images);
            }
            /* get array video and put , and convert to string */
            $arrayVideo = "";
            if (isset($videos) && count($videos) > 0) {
                $arrayVideo = $this->arrayVideo($videos);
            }

            $requestData = [
                'image' => $arrayImage,
                'video' => $arrayVideo,
                'catalog' => $catalog,
                'user_id' => $this->user->id,
                'description' => $description,
                'details' => isset($details) ? $details : "",
                'status' => 0,
                'product_id' => isset($product_id) && !empty($product_id) ? $product_id : 0
            ];

            $updateData = $findProducts->update($requestData);

            if ($updateData) {
                toastr()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('site.request.product');
            } else {
                toastr()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('site.request.product');
            }

        } else {
            return back()->with(['error' => Message::illegalError]);
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $find = Requestproduct::owner()->findOrFail($id);
            $deleteData = $find->delete();
            if ($deleteData) {
                toastr()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return back();
            } else {
                toastr()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return back();
            }
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function status($id)
    {
        if (is_numeric($id)) {
            $find = Requestproduct::owner()->findOrFail($id);
            if ($find->status == 0) {
                $data = [
                    'status' => 1
                ];

            } elseif ($find->status == 1) {
                $data = [
                    'status' => 0
                ];
            }
            $update = $find->update($data);
            if ($update) {
                toastr()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return back();
            } else {
                toastr()->error(Message::errorMessageEdit, Lang::get('cms.error'));
                return back();
            }
        } else {
            toastr()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function ajaxRequestProduct(Request $request)
    {
        $product_id = $request->input('product_id');
        if (isset($product_id) && is_numeric($product_id)) {
            $findProductRequest = Product::with(['image'])->whereStatus(1)->findOrFail($product_id);
        } else {
            return "خطا در ارسال داده";
        }
        $view = view('site.request-product.ajax.found-product', compact('findProductRequest'))->render();
        return response()->json(['html' => $view]);
    }


    /* ==============================  function extra ========================== */
    /* validation first */
    private function is_validFileArray($file, $whiteList, $notFountMessage, $whiteListMessage, $notFileFormat)
    {
        if (isset($file) && !empty($file)) {
            foreach ($file as $item) {
                if ($item != null) {
                    $explodeFile = explode(".", $item);
                    if ($explodeFile && count($explodeFile) == 2) {
                        $fileReceived = strtolower($explodeFile[1]);
                        if (in_array($fileReceived, $whiteList)) {
                            if (!file_exists(trim(base_path() . $item))) {
                                return $notFountMessage;
                            }
                        } else {
                            return $whiteListMessage;
                        }
                    } else {
                        return $notFileFormat;
                    }
                }
            }
        }
    }

    /* isFormat valid */
    private function is_formatValid($file, $whiteList)
    {
        if (isset($file) && !empty($file)) {
            if ($file != null) {
                $explodeFile = explode(".", $file);
                if ($explodeFile && count($explodeFile) == 2) {
                    $fileReceived = strtolower($explodeFile[1]);
                    if (in_array($fileReceived, $whiteList)) {

                        if (!file_exists(trim(base_path() . $file))) {

                            return false;
                        }

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /* array image and convert to string */
    private function arrayImage($images)
    {
        if (isset($images) && count($images) > 0) {
            $arrayImage = "";
            foreach ($images as $itemImages) {
                $arrayImage .= $itemImages . ",";
            }
            $arrayImage = rtrim($arrayImage, ",");
        }
        return $arrayImage;
    }

    /* array video and convert to string */
    private function arrayVideo($videos)
    {
        if (isset($videos) && count($videos) > 0) {
            $arrayVideo = "";
            foreach ($videos as $itemVideo) {
                $arrayVideo .= $itemVideo . ",";
            }
            $arrayVideo = rtrim($arrayVideo, ",");
        }
        return $arrayVideo;
    }

}
