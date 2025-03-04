<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CategoriesRequest extends FormRequest
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
        $categorieId = $this->route('categorie'); // Récupère l'ID de la catégorie en cas de modification

        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('categories', 'slug')->ignore($categorieId), // Ignore l'ID de la catégorie en cas de modification
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le titre est obligatoire.',
            'name.string' => 'Le titre doit être une chaîne de caractères.',
            'name.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'name.min' => 'Le titre doit avoir au moins 2 caractères.',

            'slug.string' => 'Le slug doit être une chaîne de caractères.',
            'slug.max' => 'Le slug ne doit pas dépasser 255 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',

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
