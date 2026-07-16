<?php

namespace App\Http\Controllers\Admin;

use App\Model\Answer;
use App\Model\Brand;
use App\Model\Question;
use App\Services\questionServices\QuestionService;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = "مدیریت پرسش و پاسخ";
        $question = Question::owner()->with(['user', 'answers'])->latest()->paginate(6);
        return view('panel.question.index', compact('title', 'question'));
    }

    public function create()
    {
        $title = "ایجاد  نظرسنجی     ";
        return view('panel.question.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => "required",
            'body' => "required",
        ]);


        $requestData = [
            'user_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'question' => $request->input('body'),
            'state' => 0,
        ];
        $optionArray = $request->input('option');


        $questionSave = Question::create($requestData);

        if ($questionSave instanceof Question) {
            if (!empty($optionArray)) {
                foreach ($optionArray as $option) {
                    QuestionService::create_answer($questionSave,$option);
                }

            }
            return redirect()->route('panel.question.index')->with(['success' => Message::successMessageCreate]);
        } else {
            return back()->with(['error' => Message::illegalError]);
        }

    }

    public function edit($id)
    {
        $title = "ویرایش نظرسنجی";
        //$question = HasIdInSql::hasId($id, Question::class);
        $question = Question::owner()->findOrFail($id);
        if ($question) {

            return view('panel.question.create', compact('question', 'title'));

        } else {
            return redirect()->route('panel.question.index')->with(['error' => Message::illegalError]);
        }
    }

    public function update(Request $request, $id)
    {
        //$question = HasIdInSql::hasId($id, Question::class);
        $question = Question::owner()->findOrFail($id);

        if ($question && !empty($question)) {

            $this->validate($request, [
                'title' => "required",
                'body' => "required",
            ]);
            $requestData = [
                'user_id' => Auth::user()->id,
                'title' => $request->input('title'),
                'question' => $request->input('body'),
                'state' => 0,
            ];
            $optionArray = $request->input('option');

            $question->update($requestData);

            if ($question && !empty($question)) {

                if ($optionArray && !empty($optionArray)) {
                    QuestionService::delete_answer($question);
                    foreach ($optionArray as $option) {
                        QuestionService::create_answer($question, $option);
                    }
                } else {
                    $answer = Answer::where('question_id', $question->id)->get();
                    if ($answer && !empty($answer)) {
                        QuestionService::delete_answer($question);
                    }
                }

                return redirect()->route('panel.question.index')->with(['success' => Message::successMessageCreate]);
            } else {
                return back()->with(['error' => Message::illegalError]);
            }

        } else {
            return redirect()->route('panel.question.index')->with(['error' => Message::illegalError]);
        }

    }

    public function delete($id)
    {
        $find = Question::findOrFail($id);
        if ($find && !empty($find))
        {
            $deleteData = $find->delete();
            return redirect()->route('panel.question.index')->with(['success' => Message::successMessageDelete]);
        }
    }

    public function state($id)
    {
        $question = Question::findOrFail($id);

        if ($question && !empty($question)) {

            if ($question->state == 0) {
                $data = [
                    'state' => 1
                ];

            } elseif ($question->state == 1) {
                $data = [
                    'state' => 0
                ];

            }

            $update = $question->update($data);

            if ($update) {
                return back()->with(["success" => Message::successMessageEdit]);
            } else {
                return back()->with(['error' => Message::errorMessageEdit]);
            }

        } else {
            return redirect()->route('panel.question.index')->with(['error' => Message::illegalError]);
        }
    }

    public function ajaxRequestBrand(Request $request)
    {
        $id = $request->input('isRequestID');

        if (is_numeric($id)) {

            $brand = Brand::where('state', 1)->get();
            $view = view('panel.question.ajaxView', compact('brand'))->render();
            return response()->json(['html' => $view]);

        } else {
            return redirect()->route('panel.question.index')->with(['error' => Message::illegalError]);
        }
    }

    public function ajaxRequestBrandEdit(Request $request)
    {
        /* type */
        $id = $request->input('isRequestID');
        /* question_id */
        $question_id = $request->input('question_id');

        if (is_numeric($id)) {
            $findBrandId = Question::where('id', $question_id)->first();
            $brand = Brand::where('state', 1)->get();
            $brandEdit = Brand::where('id', $findBrandId->brand_id)->first();
            $view = view('panel.question.ajaxView', compact('brand', 'brandEdit'))->render();
            return response()->json(['html' => $view]);

        } else {
            return redirect()->route('panel.question.index')->with(['error' => Message::illegalError]);
        }
    }

}
