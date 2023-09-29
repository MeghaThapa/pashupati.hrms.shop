<?php

namespace App\Rules;

use App\Models\DeliveryOrder;
use Illuminate\Contracts\Validation\Rule;

class ValidDpNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $partyname;

    public function __construct($partyname)
    {
        $this->partyname = $partyname;
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
        return DeliveryOrder::where('do_no', $value)
            ->where('status', 'Approved')
            ->where('supplier_id', $this->partyname)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected DO Number is invalid or not approved for the specified supplier.';
    }
}
