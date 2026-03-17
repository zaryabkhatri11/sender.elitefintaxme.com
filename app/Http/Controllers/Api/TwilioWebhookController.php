<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\MessagesLog;
use App\Models\CallLog;
use Twilio\Security\RequestValidator;

class TwilioWebhookController extends AppBaseController
{
    /**
     * Handle Twilio Status Callbacks.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $messageSid = $request->input('MessageSid');
        $status = $request->input('MessageStatus');
        $errorCode = $request->input('ErrorCode');

        if ($messageSid) {
            $log = MessagesLog::where('message_sid', $messageSid)->first();
            if ($log) {
                $log->status = $status;
                $log->save();
                
                if ($errorCode) {
                    \Log::warning("Twilio Webhook Error for SID $messageSid: $errorCode - Status: $status");
                }
            }
        }

        return response('OK', 200);
    }

    /**
     * Handle Incoming SMS from Twilio.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function incoming(Request $request)
    {
        $data = [
            'to_num' => $request->input('To'),
            'from_num' => $request->input('From'),
            'body' => $request->input('Body'),
            'message_sid' => $request->input('MessageSid'),
            'direction' => 'inbound',
            'status' => 'received',
        ];

        // Try to associate with a customer by phone
        $customer = \App\Models\Customer::where('phone', $data['from_num'])
            ->orWhere('phone', str_replace('+', '', $data['from_num']))
            ->first();
            
        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        MessagesLog::create($data);

        return response('<Response></Response>', 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Handle Twilio Voice TwiML for browser-based calls.
     */
    public function voice(Request $request)
    {
        $to = $request->input('To');

        $response = new \Twilio\TwiML\VoiceResponse();

        if ($to) {
            $dial = $response->dial('');
            $dial->number($to);
        } else {
            $response->say('No destination number provided.');
        }

        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Handle Twilio Call Status Callbacks.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function callStatus(Request $request)
    {
        $callSid = $request->input('CallSid');
        $status = $request->input('CallStatus');
        $duration = $request->input('CallDuration');

        if ($callSid) {
            $log = CallLog::where('call_sid', $callSid)->first();
            if ($log) {
                $log->status = $status;
                if ($duration) {
                    $log->duration = $duration;
                }
                $log->save();
            }
        }

        return response('OK', 200);
    }
}
