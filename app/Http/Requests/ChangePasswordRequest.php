<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $old_password
 * @property mixed $password
 */
class ChangePasswordRequest extends FormRequest
{
    use Response;
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        foreach ($validator->errors()->messages() as $message) {
            $response = $this->error($message[0], 422);
            throw new ValidationException($validator, $response);
        }
    }
}
