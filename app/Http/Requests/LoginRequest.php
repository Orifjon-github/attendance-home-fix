<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $username
 * @property mixed $password
 * @property mixed $fcm_token
 */
class LoginRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required'
        ];

    }

    protected function failedValidation(Validator $validator)
    {
        foreach ($validator->errors()->messages() as $message){
            $response = $this->error($message[0], 422);
            throw new ValidationException($validator, $response);
        }
    }
}
