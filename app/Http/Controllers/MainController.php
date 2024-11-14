<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Resources\AttendanceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    use Response;
    public function attendance(Request $request): JsonResponse
    {
        $user = $request->user();
        $type = $request->input('type');
        if ($type == 'come' && $user->in_office) return $this->error('Status and type mismatch');
        if ($type == 'went' && !$user->in_office) return $this->error('Status and type mismatch');
        $user->in_office = $type == 'come' ? '1' : '0';
        $user->save();
        $user->attendances()->create($request->all());
        return $this->success(['message' => 'Attendance added successfully.']);
    }

    public function history(Request $request): JsonResponse
    {
        $user = $request->user();
        $attendance = $user->attendances()->get();

        return $this->success(AttendanceResource::collection($attendance));
    }
}
