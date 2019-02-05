<?php

namespace Modules\Icommerceusps\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateIcommerceuspsRequest extends BaseFormRequest
{
    public function rules()
    {
        return [];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
