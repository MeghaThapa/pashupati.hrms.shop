<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricNonWovenReciveEntry extends Model
{

  protected $fillable = [
      'receive_date', 'receive_no', 'fabric_roll', 'fabric_gsm','fabric_name','fabric_color','length','gross_weight','net_weight','godam_id','planttype_id','plantname_id','shift_id','nonwovenfabric_id'
  ];

  
  public function isActive()
  {
      return $this->status == 1 ? true : false;
  }

  public function nonfabric(){
      return $this->belongsTo(NonWovenFabric::class);
  }


}
