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

        // 2. Logic: Handle threading based on In-Reply-To and Sender
        $threadId = $messageId; // Default to new thread
        $inReplyToId = null;

        if ($inReplyTo) {
            // Find parent record
            $parent = CustomerEmailLog::where('message_id', $inReplyTo)->first();
            if ($parent) {
                // Check if the reply is from the SAME person we emailed
                // If it's from a different email (e.g., we emailed atif, but zaryab replied),
                // it creates a NEW thread even if it follows In-Reply-To headers technicality.
                // In your requested case: emailed atif -> reply from zaryab = new thread.

                // Compare lowercase emails for safety
                if (strtolower(trim($from)) === strtolower(trim($parent->to_email))) {
                    $threadId = $parent->thread_id;
                    $inReplyToId = $parent->message_id;
                } else {
                    // It's a different person replying (like Zaryab replying to Atif's email)
                    // We treat this as a NEW thread.
                    \Log::info("Different responder detected: Parent To: {$parent->to_email}, Reply From: {$from}. Creating new thread.");
                    $threadId = $messageId;
                }
            }
        }

        // 3. Log the incoming email
        CustomerEmailLog::create([
            'customer_id' => $customer ? $customer->id : null,
            'direction' => 'incoming',
            'from_email' => $from,
            'to_email' => $to,
            'subject' => $subject,
            'message' => $body,
            'message_id' => $messageId,
            'in_reply_to' => $inReplyToId ?: $inReplyTo,
            'thread_id' => $threadId,
            'status' => 'received'
        ]);

        return response()->json(['status' => 'success']);
    }
}
