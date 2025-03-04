<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'max:255', 'string'],
            'email' => ['required', 'email', 'unique:users,email', 'string'],
            'password' => ['required', 'min:5', 'max:255', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'utilisateur est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'name.min' => 'Le nom doit avoir au moins 2 caractères.',

            'password.required' => 'Un mot de passe est obligatoire.',
            'password.max' => 'Le mot de passe ne doit pas dépasser 255 caractères.',
            'password.min' => 'Le mot de passe doit avoir au moins 2 caractères.',

            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cet adresse email existe déjà".',
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
        ]));
    }
}
