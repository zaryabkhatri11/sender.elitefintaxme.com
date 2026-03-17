<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_NUMBER');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    /**
     * Format number to E.164 (ensure + and country code).
     */
    protected function formatNumber($number)
    {
        $number = trim($number);
        $number = str_replace([' ', '-', '(', ')'], '', $number);

        if (substr($number, 0, 1) === '+') {
            return $number;
        }

        if (strlen($number) === 10 && is_numeric($number)) {
            return '+1' . $number;
        }

        if (is_numeric($number)) {
            return '+' . $number;
        }

        return $number;
    }

    /**
     * Generate a Twilio Access Token for the browser Voice JS SDK.
     *
     * @param string $identity A unique name for this user/browser session.
     * @return string|null JWT token string, or null if credentials missing.
     */
    public function getAccessToken($identity = 'admin')
    {
        $sid       = env('TWILIO_SID');
        $apiKey    = env('TWILIO_API_KEY');
        $apiSecret = env('TWILIO_API_SECRET');
        $appSid    = env('TWILIO_TWIML_APP_SID');

        if (!$sid || !$apiKey || !$apiSecret || !$appSid) {
            return null;
        }

        // Correct: accountSid, signingKeySid (API Key SID), secret (API Key Secret)
        $accessToken = new AccessToken($sid, $apiKey, $apiSecret, 3600, $identity);

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($appSid);
        $voiceGrant->setIncomingAllow(true);

        $accessToken->addGrant($voiceGrant);

        return $accessToken->toJWT();
    }

    /**
     * Send an SMS message.
     */
    public function sendSms($to, $message, $callbackUrl = null)
    {
        if (!$this->client) {
            return ['success' => false, 'message' => 'Twilio credentials not configured.'];
        }

        $recipients = is_array($to) ? $to : [$to];
        $results = [];

        foreach ($recipients as $recipient) {
            try {
                $recipient = $this->formatNumber($recipient);
                $params = ['from' => $this->from, 'body' => $message];

                if ($callbackUrl) {
                    $params['statusCallback'] = $callbackUrl;
                }

                $msg = $this->client->messages->create($recipient, $params);

                $results[] = ['to' => $recipient, 'success' => true, 'sid' => $msg->sid, 'status' => $msg->status];
            }
            catch (\Exception $e) {
                \Log::error("Twilio SMS failed to $recipient: " . $e->getMessage());
                $results[] = ['to' => $recipient, 'success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Initiate a server-side outbound voice call.
     */
    public function makeCall($to, $twimlUrl, $callbackUrl = null)
    {
        if (!$this->client) {
            return ['success' => false, 'message' => 'Twilio credentials not configured.'];
        }

        try {
            $to = $this->formatNumber($to);
            $params = ['url' => $twimlUrl];

            if ($callbackUrl) {
                $params['statusCallback'] = $callbackUrl;
                $params['statusCallbackEvent'] = ['initiated', 'ringing', 'answered', 'completed'];
            }

            $call = $this->client->calls->create($to, $this->from, $params);

            return ['success' => true, 'sid' => $call->sid, 'status' => $call->status];
        }
        catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
