<?php

namespace App\Http\Controllers\Admin;

use App\Events\eventSendEmailAnswerTicket;
use App\Model\Ticket;
use App\Model\TicketAnswer;
use App\Utility\Level;
use App\Utility\Message;
use App\Utility\TicketType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SEO;

class TicketController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        SEO::setTitle('تیکت');
        $tickets = Ticket::with(['answer'])->owner()->latest()->get();
        return view('panel.ticket.index', compact('tickets'));
    }

    public function TicketView($id)
    {
        $ticket = Ticket::with(['answer'])->owner()->findOrFail($id);
        SEO::setTitle($ticket->subject);
        return view('panel.ticket.ticket-view', compact('ticket'));
    }

    public function SendTicket(Request $request)
    {
        $this->validate($request, [
            'body' => "required|min:5",
            'subject' => "required",
        ]);
        $saveTicket = [
            'user_id' => auth()->user()->id,
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 0,
            'tracking_code' => time()
        ];
        $create = Ticket::create($saveTicket);
        if ($create instanceof Ticket) {
            toast()->success(Message::SuccessMessageTicket, 'موفقیت آمیز!');
            return redirect()->route('panel.ticket.index');
        } else {
            toast()->success(Message::ErrorMessageTicket, 'ناموفق!');
            return redirect()->route('panel.ticket.index');
        }

    }

    public function SendTicketAnswer(Request $request)
    {
        $this->validate($request, [
            'answer' => "required|min:5",
            'ticket_id' => "required|integer",
            'attach' => "nullable|mimes:png,jpg,jpeg,gif,pdf|max:100240",
        ]);

        $ticket = Ticket::with('user')->owner()->findOrFail($request->ticket_id);
        $saveAnswer = [
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'answer' => $request->answer,
            'status' => TicketType::WAITTING
        ];
        $create = TicketAnswer::create($saveAnswer);
        $attach = $request->file('attach');
        if ($create instanceof TicketAnswer) {
            /* upload image */
            if ($attach && !empty($attach)) {
                $path = "/ticket-attach/" . Auth::id() . "/" . $create->id;
                $imageStore = $attach->store($path);
                $fileStore = $create->files()->create([
                    'url' => $imageStore,
                    'user_id' => Auth::user()->id,
                    'fileable_id' => $create->id,
                    'fileable_type ' => get_class($create)
                ]);
            }

            $ticket->update(['status' => TicketType::ANEWERED]);

            if ($ticket->send_email == 1){
                event(new eventSendEmailAnswerTicket($ticket));
            }

            toast()->success('پاسخ شما با موفقیت ارسال گردید', 'موفقیت آمیز!');
            return redirect()->route('panel.ticket.view', $ticket->id);
        } else {
            toast()->success('پاسخ شما ارسال نگردید', 'ناموفق!');
            return redirect()->route('panel.ticket.view', $ticket->id);
        }

    }

    public function delete($id)
    {
        $ticket = Ticket::owner()->find($id);
        $delete = $ticket->delete();
        if ($delete) {
            toast()->success('تیکت مورد نظر حذف گردید', 'موفقیت آمیز!');
            return redirect()->route('panel.ticket.index');
        } else {
            toast()->error('تیکت مورد نظر حذف نگردید!', 'نا موفق!');
            return redirect()->route('panel.ticket.index');
        }
    }

    public function status(Request $request)
    {
        $status = $request->input('status');
        $ticketId = $request->input('ticket');
        if (is_numeric($ticketId)) {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->count() > 0) {

                $data = [
                    'status' => $status
                ];

                $update = $ticket->update($data);

                if ($update) {
                    return response([
                        'status' => 200,
                        'msg' => 'وضعیت تیکت تغییر کرد',
                    ]);
                } else {
                    toast()->error(Message::errorMessageEdit, 'خطا');
                    return back();
                }

            } else {
                toast()->error(Message::systemError, 'خطا');
                return back();
            }
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    public function StatusAnswer($id)
    {
        if (is_numeric($id)) {
            $answer = TicketAnswer::findOrFail($id);
            if ($answer->count() > 0) {

                if ($answer->status == TicketType::WAITTING) {
                    $data = [
                        'status' => TicketType::OBSERVE
                    ];

                } elseif ($answer->status == TicketType::OBSERVE) {
                    $data = [
                        'status' => TicketType::ANEWERED
                    ];
                } elseif ($answer->status == TicketType::ANEWERED) {
                    $data = [
                        'status' => TicketType::WAITTING
                    ];
                }

                $update = $answer->update($data);

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
        } else {
            toast()->error(Message::illegalError, 'خطا');
            return back();
        }
    }

    /******* Start Of Extra Funtion *******/
    public static function GetCountAnswerWatting($id)
    {
        $ticket = TicketAnswer::with(['user'])->where('ticket_id', $id)->where('status', TicketType::WAITTING)->get();
        $i = 0;
        foreach ($ticket as $item) {
            if ($item->user->level != Level::SUPER_ADMIN) {
                $i++;
            }
        }
        return $i;
    }
    /******* End Of Extra Funtion *******/
}
