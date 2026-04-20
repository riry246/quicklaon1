<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;
use App\Models\SMS;

class SMSController extends Controller
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function index()
    {
        $data['groupedMessages'] = SMS::all()->groupBy('number')->map(function ($group) {
            return $group->sortByDesc('created_at')->first();
        });

        return view('admin.sms.index', $data);

    }

    public function handleIncomingSMS(Request $request)
    {
        $message = $request->input('Body');
        $from = $request->input('From');

        // Store incoming message
        SMS::create([
            'number' => $from,
            'message' => $message,
            'direction' => 'incoming'
        ]);

        // Respond to the message
        $response = new MessagingResponse();
        $response->message("Hello, you said: $message");

        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    public function sendReply(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        try {
            $this->twilio->messages->create($to, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $message
            ]);

            // Store outgoing message
            SMS::create([
                'from' => env('TWILIO_PHONE_NUMBER'),
                'to' => $to,
                'message' => $message,
                'direction' => 'outgoing'
            ]);

            return response()->json(['status' => 'success', 'message' => 'Reply sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function getIndividualMsg(Request $request)
    {
        $id = $request->id;

        $data['groupedMessages'] = SMS::where('number', $id)
            ->get()
            ->groupBy(function ($message) {
                return $message->created_at->format('Y-m-d');
            });

        $data['user'] = $request->id;

        return view('admin.messages.widgets.chatarea', $data);

    }

}
