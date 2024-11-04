<?php

namespace App\Services;

use GuzzleHttp\Client;
use Throwable;

class SmsService
{
    private LogService $logService;
    private Client $client;
    private string $token = '';
    private mixed $host;
    private mixed $email;
    private mixed $password;

    public function __construct(
        LogService    $logService,
        Client        $client,
    )
    {
        $this->logService = $logService;
        $this->client = $client;
        $this->host = config('services.eskiz.host');
        $this->email = config('services.eskiz.email');
        $this->password = config('services.eskiz.password');
    }

    public function sms($phone, $code)
    {

        $message = 'HomeFix SMS xizmati. Ushbu kodni hech kimga bermang. Tizimga kirish uchun tasdiqlash kodi: ' . $code;

        $data = [
            'mobile_phone' => $phone,
            'message' => $message,
            'from' => 4546
        ];
        return $this->send('post', '/message/sms/send', $data);
    }

    public function auth(): bool
    {
        $data = [
            'email' => $this->email,
            'password' => $this->password
        ];
        $result = $this->send('post', '/auth/login', $data);
        if ($result && isset($result['token'])) {
            $this->token = $result['token'];
            return true;
        }
        return false;
    }

    private function send($method, $url, $params = [])
    {
        $request_id = uniqid();
        $this->logService->request('eskiz', $request_id, $url, json_encode($params, JSON_UNESCAPED_UNICODE));

        if ($method == 'GET') {
            if (!empty($params)) {
                $url = $url . '?' . http_build_query($params);
            }
        }

        try {
            $res = $this->client->$method($this->host . $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'form_params' => $params,
                'http_errors' => false,
                'timeout' => 30,
            ]);
            $code = $res->getStatusCode();
            $body = $res->getBody()->getContents();
            $array_data = json_decode($body, 1);
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            $this->logService->response('eskiz', $request_id, 500, $message);
            return null;
        }

        $this->logService->response('eskiz', $request_id, $code, $body);
        if ($code >= 200 && $code < 300) {
            return $array_data['data'] ?? $array_data;
        } elseif ($code == 401) {
            $token = $this->auth();
            if ($token) {
                return $this->send($method,$url, $params);
            }
        }
        return null;
    }

}
