<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerEmailLog;
use App\Models\Customer;

class EmailReplyController extends Controller
{
    /**
     * Handle incoming email webhooks (e.g. from Mailgun, SendGrid, etc.)
     */
    public function handle(Request $request)
    {
        // This is a generic implementation. You'll need to map fields 
        // depending on which provider you use (Mailgun, SendGrid, etc.)

        $from = $request->input('sender') ?: $request->input('from');
        $to = $request->input('recipient') ?: $request->input('to');
        $subject = $request->input('subject');
        $body = $request->input('body-html') ?: $request->input('text');
        $messageId = $request->input('Message-Id');
        $inReplyTo = $request->input('In-Reply-To');

        // 1. Try to find the customer by email
        // Logic: The sender of the reply is the customer we emailed
        $customer = Customer::where('email', 'LIKE', "%$from%")->first();

        // 2. Log the incoming email
        CustomerEmailLog::create([
            'customer_id' => $customer ? $customer->id : null,
            'direction' => 'incoming',
            'from_email' => $from,
            'to_email' => $to,
            'subject' => $subject,
            'message' => $body,
            'message_id' => $messageId,
            'in_reply_to' => $inReplyTo,
            'status' => 'received'
        ]);

        return response()->json(['status' => 'success']);
    }
}
