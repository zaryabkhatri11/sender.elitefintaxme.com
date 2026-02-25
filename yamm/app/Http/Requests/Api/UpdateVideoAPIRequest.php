<?php

namespace App\Http\Requests\Api;

use App\Models\Video;

class UpdateVideoAPIRequest extends BaseAPIRequest
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
        return Video::$api_update_rules;
    }
}
