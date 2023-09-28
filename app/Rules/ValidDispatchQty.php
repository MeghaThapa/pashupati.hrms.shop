<?php

namespace App\Rules;

use App\Models\SaudaItem;
use Illuminate\Contracts\Validation\Rule;

class ValidDispatchQty implements Rule
{
    private $saudaItemId;

    public function __construct($saudaItemId)
    {
        $this->saudaItemId = $saudaItemId;
    }

    public function passes($attribute, $value)
    {
        $saudaItem = SaudaItem::find($this->saudaItemId);

        if (!$saudaItem) {
            return false;
        }

        return $value <= $saudaItem->qty;
    }

    public function message()
    {
        return 'The dispatch quantity must be less than or equal to the available quantity.';
    }
}
