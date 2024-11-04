<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    use Response;
    public function attendance(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->attendance()->create($request->all());
        return $this->success(['message' => 'Attendance added successfully.']);
    }
}
