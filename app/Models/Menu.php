<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
	protected $table = "menus";
	protected $guarded = [];
	public $timestamps = false;

    protected $fillable = [
    	'parent_menu', 'menu_name', 'menu_link', 'menu_icon', 'order_by', 'status', 'is_full_off'
    ];

	protected $hidden = [
		'created_at','updated_at'
	];
}
