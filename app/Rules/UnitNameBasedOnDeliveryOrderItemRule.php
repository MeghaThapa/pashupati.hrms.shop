<?php

namespace App\Rules;

use App\Models\DeliveryOrderForItem;
use Illuminate\Contracts\Validation\Rule;

class UnitNameBasedOnDeliveryOrderItemRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $allowedKgsItems = ['PP Woven', 'PP Non Woven', 'PP/HDPE Tripal', 'RP Granuels', 'PP/CC/Other Granuels', 'Wastage'];
    protected $allowedPcsItems = ['PP Bags (Unlam)', 'PP Bags (Lam)'];

    protected $deliveryOrderForItemId;

    public function __construct($deliveryOrderForItemId)
    {
        $this->deliveryOrderForItemId = $deliveryOrderForItemId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $item = DeliveryOrderForItem::find($this->deliveryOrderForItemId);

        if (!$item) {
            return false; // Item not found, consider adding appropriate error handling
        }

        if (in_array($item->name, $this->allowedKgsItems) && $value === 'Kgs') {
            return true;
        }

        if (in_array($item->name, $this->allowedPcsItems) && $value === 'Pcs') {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be "Kgs" for specific items or "Pcs" for other items.';
    }
}
