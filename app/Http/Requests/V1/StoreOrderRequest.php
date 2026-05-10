<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use Policies in a real app
    }

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'uuid', 'exists:organizations,id'],
            'customer_id' => ['required', 'uuid', 'exists:customers,id'],
            'address_id' => ['nullable', 'uuid', 'exists:addresses,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'uuid', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
