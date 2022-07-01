<?php

namespace App\Http\Requests\tasks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class TaskUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return Config::get('tasks.update_rules');
    }
}
