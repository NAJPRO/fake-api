<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'exists:users,email'],
            'password' => ['required', 'min:5', 'max:255', 'min:5', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Un mot de passe est obligatoire.',
            'password.max' => 'Le mot de passe ne doit pas dépasser 255 caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 5 caractères.',

            'email.required' => 'L\'adresse email est obligatoire.',
            'email.exists' => 'Adresse email incorrect".',

            'email.email' => 'L\'adresse email n\'est pas valide.',

        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true,
            'status_code' => 422,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors(),
            'data' => request()->all(),
        ]));
    }
}
