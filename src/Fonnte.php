<?php
namespace TianRosandhy\LaravelFonnte;

use Exception;
use Http;
use Log;

class Fonnte
{
    public $base_url;
    public $token;

    public function __construct()
    {
        $this->base_url = config('fonnte.base_url');
        $this->token = config('fonnte.token');
    }

    public function ping($recipient=null)
    {
        if (empty($recipient)) {
            $recipient = config('fonnte.fallback_recipient');
        }
        return $this->sendMessage($recipient, 'PING');
    }

    public function sendMessage($recipient, $message, $additional_param = [])
    {
        // recipient only accept string by default
        if (is_array($recipient)) {
            $recipient = implode(',', $recipient);
        }

        $endpoint = '/send';
        $param = array_merge($additional_param, [
            'target' => $recipient,
            'message' => $message,
        ]);

        if (config('app.env') == 'local' && config('fonnte.fallback_recipient')) {
            // prevent send unwantend message to another user
            $param['target'] = config('fonnte.fallback_recipient');
        }

        return $this->request($endpoint, $param);
    }

    public function request($endpoint, $param = [])
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])
            ->withOptions([
                'verify' => false,
            ])
            ->asForm()
            ->accept('application/json')
            ->post($this->base_url . $endpoint, $param);

        if (!$response->ok()) {
            // log
            $logparam = $param;
            $logparam['message'] = isset($param['message']) ? (strlen($param['message']) > 20 ? substr($param['message'], 0, 20) . '...' : $param['message']) : null;
            Log::error("ERROR RESPONSE fonnte", [
                'endpoint' => $this->base_url . $endpoint,
                'request' => $logparam,
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            throw new Exception("Error when connect to fonnte endpoint. Check log for more information");
        }

        $logparam = $param;
        $logparam['message'] = isset($param['message']) ? (strlen($param['message']) > 20 ? substr($param['message'], 0, 20) . '...' : $param['message']) : null;
        Log::info("OK RESPONSE fonnte", [
            'endpoint' => $this->base_url . $endpoint,
            'request' => $logparam,
            'response' => $response->body(),
            'status' => $response->status(),
        ]);

        return json_decode($response->body(), true);
    }
}
