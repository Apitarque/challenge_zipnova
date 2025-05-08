<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // permitir a todos los usuarios
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',

                // shipping address
                'shipping_address' => 'required|array',
                'shipping_address.street' => 'required|string',
                'shipping_address.city' => 'required|string',
                'shipping_address.province' => 'required|string',
                'shipping_address.postal_code' => 'required|string',
                'shipping_address.country' => 'required|string',

                // billing info
                'billing_info' => 'required|array',
                'billing_info.tax_id' => 'required|string',
                'billing_info.company_name' => 'required|string',
                'billing_info.address' => 'required|array',
                'billing_info.address.street' => 'required|string',
                'billing_info.address.city' => 'required|string',
                'billing_info.address.province' => 'required|string',
                'billing_info.address.postal_code' => 'required|string',
                'billing_info.address.country' => 'required|string',

                // items
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',

            'shipping_address.street.required' => 'La calle de envío es obligatoria.',
            'shipping_address.city.required' => 'La ciudad de envío es obligatoria.',
            'shipping_address.country.required' => 'El país de envío es obligatorio.',

            'billing_info.tax_id.required' => 'El CUIT o CUIL de facturación es obligatorio.',
            'billing_info.company_name.required' => 'El nombre de la empresa es obligatorio.',

            'items.required' => 'Debe agregar al menos un producto a la orden.',
            'items.*.product_id.required' => 'El producto es obligatorio.',
            'items.*.product_id.exists' => 'El producto seleccionado no existe.',
            'items.*.quantity.required' => 'La cantidad del producto es obligatoria.',
            'items.*.quantity.integer' => 'La cantidad debe ser un número entero.',
            'items.*.quantity.min' => 'La cantidad mínima permitida es 1.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422));
    }
}
