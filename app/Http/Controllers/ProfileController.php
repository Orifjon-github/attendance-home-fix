<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use Response;

    public function profileInfo(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success(UserResource::make($user));
    }

    public function profileUpdate(Request $request): JsonResponse
    {
        $user = $request->user();
        $update = $request->all();

        if ($request->hasFile('image')) {
            $path = Storage::putFile('public/users', $request->file('image'));
            $path = str_replace('public/', '/storage/', $path);
            $update['image'] = $path;
        }

        $user->update($update);

        return $this->success(UserResource::make($user));
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => Hash::make($request->password)]);

            return $this->success(['message' => 'Password updated successfully']);
        } else {
            return $this->error('Old password is incorrect');
        }
    }
}
