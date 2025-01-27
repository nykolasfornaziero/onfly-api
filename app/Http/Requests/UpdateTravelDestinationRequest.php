<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelDestinationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'id'=>'exists:travel_destinations,id',
            'status' => 'required|in:aprovado,cancelado',
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'The destination with the provided ID does not exist.',
            'status.in' => 'O status deve ser "aprovado" ou "cancelado".',
        ];
    }
}
