<?php

namespace App\Http\Requests\Api\v1\Service\DynamicContentController;

use Illuminate\Foundation\Http\FormRequest;

class LoadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|max:20480'
        ];
    }
}
