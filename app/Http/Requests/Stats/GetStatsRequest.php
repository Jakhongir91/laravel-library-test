<?php

namespace App\Http\Requests\Stats;

use Illuminate\Foundation\Http\FormRequest;

class GetStatsRequest extends FormRequest
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
            // TODO: add validation rules
        ];
    }

    //TODO: return error response if validation failed
}
