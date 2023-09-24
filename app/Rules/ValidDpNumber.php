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
    public function __construct()
    {
        //
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
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected dp_number is invalid or not approved.';
    }
}
