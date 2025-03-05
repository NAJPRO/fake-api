<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
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
            'user_id' => ['nullable', 'exists:users,id'],
            'post_id' => ['required', 'exists:posts,id'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'content' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'identifiant de l\'utilisateur est requis.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',

            'post_id.required' => 'L\'identifiant du post est requis.',
            'post_id.exists' => 'Le post spécifié n\'existe pas.',

            'parent_id.exists' => 'Le commentaire parent spécifié n\'existe pas.',

            'content.required' => 'Le contenu du commentaire est requis.',
            'content.string' => 'Le contenu du commentaire doit être une chaîne de caractères.',
            'content.min' => 'Le commentaire doit contenir au moins :min caractères.',
            'content.max' => 'Le commentaire ne peut pas dépasser :max caractères.',
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
