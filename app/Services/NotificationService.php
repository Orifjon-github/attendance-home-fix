<?php

use App\Services\FirebaseMessagingService;
use Illuminate\Support\Facades\Http;

class NotificationService {
    protected $firebaseMessagingService;

    public function __construct(FirebaseMessagingService $firebaseMessagingService)
    {
        $this->firebaseMessagingService = $firebaseMessagingService;
    }

    public function requestToken()
    {
        try {
            $tokenData = $this->firebaseMessagingService->getAccessToken();
            return response()->json(['token' => $tokenData['access_token']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
