<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    use MessageTrait;
    public function index()
    {
        $data['groupedMessages'] = Message::all()->groupBy('user_id')->map(function ($group) {
            return $group->sortByDesc('created_at')->first();
        });

        return view('admin.messages.index', $data);
    }

    public function sendMessage(Request $request)
    {
        $message = array(
            'user_id' => $request->user_id,
            'type' => $request->type,
            'subject' => $request->subject,
            'content' => $request->content,
        );

        $this->storeMsg($message);

        return true;

    }
    public function sendMessageAll(Request $request)
    {

        $user = User::all();

        foreach ($user as $u) {

            $message = array(
                'user_id' => $u->id,
                'type' => $request->type,
                'subject' => $request->subject,
                'content' => $request->content,
            );

            $this->storeMsg($message);

        }

        return redirect()->back()->with('success', 'Loan Has been declined successfully!');

    }
    public function getIndividualMsg(Request $request)
    {
        $user_id = $request->id;

        if ($request->id == 0) {
            $user_id = null;
        }

        $data['groupedMessages'] = Message::where('user_id', $user_id)
            ->get()
            ->groupBy(function ($message) {
                return $message->created_at->format('Y-m-d');
            });

        $data['user'] = $request->id;

        return view('admin.messages.widgets.chatarea', $data);

    }

    public function getMessages($loanApplicationID)
    {
        $data['groupedMessages'] = Message::where('loan_application_id', $loanApplicationID)->groupBy('type')->map(function ($group) {
            return $group->sortByDesc('created_at')->first();
        });
        
        dd($data['groupedMessages']);

    }

}
