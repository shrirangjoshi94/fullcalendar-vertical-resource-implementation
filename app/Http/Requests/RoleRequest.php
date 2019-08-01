<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'roleName' => 'required|string|unique:roles,display_name'
        ];
    }

    public function messages()
    {
        return [
            'roleName.required' => 'Role name is required',
            'roleName.string' => 'Role name must be string',
            'roleName.unique' => 'Role name must be unique',
        ];
    }
}
