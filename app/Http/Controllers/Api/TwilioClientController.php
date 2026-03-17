<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Services\TwilioService;

class TwilioClientController extends AppBaseController
{
    /**
     * Generate an Access Token for the Twilio Voice JS SDK.
     */
    public function token(Request $request)
    {
        $identity = $request->input('identity', 'admin_' . auth()->id());
        $twilio   = new TwilioService();
        $token    = $twilio->getAccessToken($identity);

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Twilio not configured. Add TWILIO_TWIML_APP_SID to .env'], 500);
        }

        return response()->json(['success' => true, 'token' => $token, 'identity' => $identity]);
    }
}
