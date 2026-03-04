<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle PayPal Webhooks
     * Reference: https://developer.paypal.com/api/rest/webhooks/
     */
    public function paypal(Request $request)
    {
        Log::info('PayPal Webhook Received', $request->all());

        try {
            $eventType = $request->input('event_type');
            $resource = $request->input('resource');

            if (!$resource || !isset($resource['id'])) {
                return response()->json(['status' => 'ignored', 'message' => 'No resource id found'], 200);
            }

            $invoiceId = $resource['id']; // This corresponds to Invoice uuid

            $invoice = Invoice::where('uuid', $invoiceId)->first();

            if ($invoice) {
                switch ($eventType) {
                    case 'INVOICING.INVOICE.PAID':
                        $invoice->status = 'paid';
                        $invoice->save();
                        break;
                    case 'INVOICING.INVOICE.CANCELLED':
                    case 'INVOICING.INVOICE.REFUNDED':
                        $invoice->status = 'failed'; // or cancelled/refunded based on your business logic
                        $invoice->save();
                        break;
                }
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('PayPal Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Square Webhooks
     * Reference: https://developer.squareup.com/docs/webhooks/overview
     */
    public function square(Request $request)
    {
        Log::info('Square Webhook Received', $request->all());

        try {
            $eventType = $request->input('type');
            $data = $request->input('data.object.invoice');

            if (!$data || !isset($data['id'])) {
                return response()->json(['status' => 'ignored', 'message' => 'No invoice id found'], 200);
            }

            $invoiceId = $data['id']; // This corresponds to Invoice uuid

            $invoice = Invoice::where('uuid', $invoiceId)->first();

            if ($invoice) {
                switch ($eventType) {
                    case 'invoice.payment_made':
                        // Square might just send payment made, we check if invoice is fully paid
                        if (isset($data['status']) && $data['status'] === 'PAID') {
                            $invoice->status = 'paid';
                            $invoice->save();
                        }
                        break;
                    case 'invoice.canceled':
                    case 'invoice.failed':
                        $invoice->status = 'failed';
                        $invoice->save();
                        break;
                }
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Square Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
