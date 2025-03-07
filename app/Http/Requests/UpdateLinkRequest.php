<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'original_url'   => 'url',
            'short_code' => 'string',
        ];
    }


    public function validationData()
    {
        $this->merge(['short_code' => $this->route('short_code')]);

        return parent::validationData();
    }
}
