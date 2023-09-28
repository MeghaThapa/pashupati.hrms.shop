<?php

namespace App\Http\Requests\SaudaItem;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UnitNameBasedOnDeliveryOrderItemRule;

class SaudaItemStoreRequest extends FormRequest
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
        return [
            'sauda_no' => 'required|string|unique:sauda_items,sauda_no',
            'sauda_date' => 'required|date',
            'sauda_for' => 'required|in:Export,Local',
            'supplier_id' => 'required|exists:suppliers,id',
            'acc_name' => 'required|string',
            'delivery_order_for_item_id' => 'required|exists:delivery_order_for_items,id',
            'fabric_name' => ['nullable', 'array'],
            'fabric_name.*' => ['string', 'nullable'],
            'qty' => 'required|numeric|min:0',
            'unit_name' => [
                'required',
                'in:Kgs,Pcs',
                new UnitNameBasedOnDeliveryOrderItemRule($this->input('delivery_order_for_item_id')),
            ],
            'rate' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ];
    }
}
