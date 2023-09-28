<?php

namespace App\Http\Requests\DeliveryOrder;

use App\Models\DeliveryOrderForItem;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryOrderStoreRequest extends FormRequest
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
            'do_no' => 'required|string|unique:delivery_orders,do_no',
            'do_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'overdue_amount' => 'required|numeric',
            'total_due' => 'required|numeric',
            'party_limit' => 'required|numeric',
            'delivery_order_for_item_id' => 'required|exists:delivery_order_for_items,id',
            'qty_in_mt' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Get the selected delivery_order_for_item
                    $deliveryOrderForItem = DeliveryOrderForItem::find($this->input('delivery_order_for_item_id'));

                    // Check if qty_in_mt is required based on the delivery_order_for_item.name
                    if (
                        in_array($deliveryOrderForItem->name, ['PP Woven', 'PP Non Woven', 'PP/HDPE Tripal', 'RP Granuels', 'PP/CC/Other Granuels', 'Wastage']) &&
                        is_null($value)
                    ) {
                        $fail("The $attribute field is required for the selected delivery order item.");
                    }
                },
            ],
            'bundel_pcs' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Get the selected delivery_order_for_item
                    $deliveryOrderForItem = DeliveryOrderForItem::find($this->input('delivery_order_for_item_id'));

                    // Check if bundel_pcs is required based on the delivery_order_for_item.name
                    if (
                        in_array($deliveryOrderForItem->name, ['PP Bags (Unlam)', 'PP Bags (Lam)']) &&
                        is_null($value)
                    ) {
                        $fail("The $attribute field is required for the selected delivery order item.");
                    }
                },
            ],
            'base_rate_per_kg' => 'required|numeric',
            'collection' => 'nullable|string',
            'pending_sauda' => 'nullable|string',
        ];
    }
}
