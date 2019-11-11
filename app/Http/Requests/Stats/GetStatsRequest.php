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
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
        ];
    }

    //TODO: return error response if validation failed
}
