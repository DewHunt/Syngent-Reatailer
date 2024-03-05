<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandPromoter extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    
    public function getAttendanceLists(){
		return $this->belongsTo(BpAttendance::class,'bp_id','id');
	}
	
	public function getLatestAttendances(){
		$currentdate = date('Y-m-d');
		return $this->hasOne(BpAttendance::class,'bp_id','id')->latestOfMany();
	}
	
	public function getOldestAttendances(){
		return $this->hasOne(BpAttendance::class,'bp_id','id')->oldestOfMany();
	}
	
	public function getAttendanceById(){
		return $this->hasMany(BpAttendance::class,'bp_id','id');
	}
}
