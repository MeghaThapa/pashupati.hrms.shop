<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exp_cat_id', 'expense_reason', 'slug', 'amount', 'expense_date', 'expense_image', 'note', 'status'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'expense_reason'
            ]
        ];
    }

    /**
     * Return expense short note
     *
     * @return string
     */
    public function shortNote()
    {
        if (strlen($this->note) > 80) {
            return substr($this->note, 0, 80) . '...';
        }
        return $this->note;
    }

    /**
     * Return true if the expense is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    /**
     * Return the expense attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->expense_image)) {
            return asset('img/expenses/' . $this->expense_image);
        }
    }


    /**
     * Return relation with ExpenseCategory Model
     *
     *
     */
    public function expenseCategory()
    {
        return $this->belongsTo('App\Models\ExpenseCategory', 'exp_cat_id');
    }
}
