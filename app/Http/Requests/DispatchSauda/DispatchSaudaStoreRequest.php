<?php

namespace App\Http\Requests\DispatchSauda;

use App\Rules\ValidDispatchQty;
use Illuminate\Foundation\Http\FormRequest;

class DispatchSaudaStoreRequest extends FormRequest
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
            'sauda_item_id' => 'required|exists:sauda_items,id',
            'dispatch_date' => 'required|date',
            'dispatch_qty' => [
                'required',
                'numeric',
                'min:0',
                new ValidDispatchQty($this->input('sauda_item_id')), // Custom validation rule
            ],
            'party_acc' =>'required|string',
            'remarks' => 'nullable|string',
        ];
    }
}
