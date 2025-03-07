<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DestroyLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'short_code' => 'required|string',
        ];
    }


    public function validationData()
    {
        $this->merge(['short_code' => $this->route('short_code')]);

        return parent::validationData();
    }

}
