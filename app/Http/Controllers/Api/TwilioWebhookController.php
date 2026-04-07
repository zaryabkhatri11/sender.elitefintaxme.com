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
        \Log::info("Twilio Incoming SMS Request: ", $request->all());

        $data = [
            'to_num' => $request->input('To'),
            'from_num' => $request->input('From'),
            'body' => $request->input('Body'),
            'message_sid' => $request->input('MessageSid'),
            'direction' => 'inbound',
            'status' => 'received',
            'is_read' => false,
        ];

        // Try to associate with a customer by phone
        $from = $data['from_num'];
        $customer = \App\Models\Customer::where('phone', $from)
            ->orWhere('phone', str_replace('+', '', $from))
            ->orWhere('phone', 'like', '%' . substr($from, -10))
            ->first();
            
        if ($customer) {
            $data['customer_id'] = $customer->id;
            \Log::info("Matched customer: ID=" . $customer->id);
        } else {
            \Log::info("No customer matched for phone: $from");
        }

        try {
            $logEntry = MessagesLog::create($data);
            \Log::info("Inbound SMS stored: ID=" . $logEntry->id);
        } catch (\Exception $e) {
            \Log::error("Failed to store inbound SMS: " . $e->getMessage());
        }

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

    /**
     * TwiML for call bridging.
     */
    public function bridgeVoice(Request $request)
    {
        $customerNum = $request->input('customer_num');
        \Log::info("Twilio Bridge Leg 2: Connecting to $customerNum");
        
        $response = new \Twilio\TwiML\VoiceResponse();

        if ($customerNum) {
            $response->say('Wait while we connect your call.');
            $dial = $response->dial('');
            $dial->number($customerNum);
        } else {
            \Log::error("Twilio Bridge Leg 2 failure: No customer number provided.");
            $response->say('No customer number provided.');
        }

        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}
