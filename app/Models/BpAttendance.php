<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    
    public function getbpLists(){
		return $this->hasOne('App\Models\BrandPromoter');
	}
	
	public function responses() {
        return $this->hasOne(BrandPromoter::class, 'bp_id');
    }
}
