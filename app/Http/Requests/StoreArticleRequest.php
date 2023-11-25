<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreArticleRequest extends FormRequest
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
            'title'=>['required' , 'string' , 'max:255'],
            'content'=>['required' , 'string'],
            'category_id'=>['required' , 'integer'],
            'meta_title'=>['sometimes' , 'string' , 'max:255'],
            'meta_description'=>['sometimes' , 'string'],
            'show_at_popular'=>['boolean'],
            'archive'=>['boolean'],
            'tags'=>['sometimes', 'string'],
            'thumb_id'=>['nullable' , 'numeric']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status'=>false,
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
