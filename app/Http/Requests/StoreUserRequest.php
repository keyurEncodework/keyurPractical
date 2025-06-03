<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
   
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|string|unique:users,mobile_number',
            'birth_date' => 'required|date|before:today',
            
            'addresses' => 'required|array|min:1',
            'addresses.*.address' => 'required|string',
            'addresses.*.pin_code' => 'required|string',
            'addresses.*.city' => 'required|string|max:255',
            'addresses.*.state' => 'required|string|max:255',
            'addresses.*.type' => ['required', Rule::in(['Home', 'Office'])],
        ];
    }

    public function messages()
    {
        return [
            'addresses.required' => 'At least one address is required',
            'addresses.*.address.required' => 'Address field is required for all addresses',
            'addresses.*.pin_code.required' => 'Pin code is required for all addresses',
            'addresses.*.city.required' => 'City is required for all addresses',
            'addresses.*.state.required' => 'State is required for all addresses',
            'addresses.*.type.required' => 'Address type is required for all addresses',
            'addresses.*.type.in' => 'Address type must be either Home or Office',
        ];
    }
}

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
   
    public function rules()
    {
        $userId = $this->route('user');

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($userId)],
            'email' => ['required','string', Rule::unique('users')->ignore($userId)],
            'birth_date' => 'required|date|before:today',
            
            'addresses' => 'required|array|min:1',
            'addresses.*.address' => 'required|string',
            'addresses.*.pin_code' => 'required|string',
            'addresses.*.city' => 'required|string|max:255',
            'addresses.*.state' => 'required|string|max:255',
            'addresses.*.type' => ['required', Rule::in(['Home', 'Office'])],
        ];
    }

    public function messages()
    {
        return [
            'addresses.required' => 'At least one address is required',
            'addresses.*.address.required' => 'Address field is required for all addresses',
            'addresses.*.pin_code.required' => 'Pin code is required for all addresses',
            'addresses.*.city.required' => 'City is required for all addresses',
            'addresses.*.state.required' => 'State is required for all addresses',
            'addresses.*.type.required' => 'Address type is required for all addresses',
            'addresses.*.type.in' => 'Address type must be either Home or Office',
        ];
    }
}