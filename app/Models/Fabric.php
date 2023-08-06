<?php

namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
   use Sluggable;
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'name', 'slug', 'fabricgroup_id', 'status','average_wt','gram_wt','gross_wt','net_wt','meter','roll_no','loom_no','is_laminated','godam_id','bill_no','date_np'
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
               'source' => 'name'
           ]
       ];
   }

   
   public function fabricgroup()
   {
       return $this->belongsTo(FabricGroup::class, 'fabricgroup_id', 'id');
   }

   /**
    * Return relation with Blog Model
    *
    *
    */
   
   // public function subCategories()
   // {
   //     return $this->hasMany('App\Models\SubCategory');
   // }

   /**
    * Return true if the category is active
    *
    * @return boolean
    */
   public function isActive()
   {
       return $this->status == 1 ? true : false;
   }

   /**
    * Return category short note
    *
    * @return string
    */

}
