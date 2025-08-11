<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchHotelSuppliersRequest extends FormRequest
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
    public function rules(): array
    {
        return [
                'location' => 'required|string|max:255',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'guests' => 'sometimes|integer|min:1|max:10',
                'min_price' => 'sometimes|numeric|min:0',
                'max_price' => 'sometimes|numeric|min:0|gte:min_price',
                'sort_by' => 'sometimes|string|in:price,rating'
        ];
    }
}
