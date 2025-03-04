<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
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
        $postId = $this->route('post'); // Récupère l'ID du post en cas de modification

        return [
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('posts', 'slug')->ignore($postId), // Ignore l'ID du post en cas de modification
            ],

            'status' => ['required', Rule::in(['draft', 'published'])],
            'content' => ['nullable', 'string'],

            'categorie_id' => 'required|array',
            'categorie_id.*' => 'integer|exists:categories,id', // Chaque élément du tableau doit être un entier et exister dans la table categories

            'tags_id' => 'nullable|array',
            'tags_id.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'title.min' => 'Le titre doit avoir au moins 2 caractères.',

            'slug.string' => 'Le slug doit être une chaîne de caractères.',
            'slug.max' => 'Le slug ne doit pas dépasser 255 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',

            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être soit "draft" soit "published".',

            'content.string' => 'Le contenu doit être une chaîne de caractères.',

            'categorie_id.required' => 'Vous devez sélectionner au moins une catégorie.',
            'categorie_id.array' => 'Le champ catégories doit être un tableau.',
            'categorie_id.*.integer' => 'Chaque catégorie doit être un identifiant valide.',
            'categorie_id.*.exists' => 'Vous devez fournir une catégorie existante.',

            'tags_id.array' => 'Le champ tags doit être un tableau.',
            'tags_id.*.integer' => 'Chaque tag doit être un identifiant valide.',
            'tags_id.*.exists' => 'Vous devez fournir un tag existant',
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
