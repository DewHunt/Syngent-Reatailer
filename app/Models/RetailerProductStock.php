<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerProductStock extends Model
{
    use HasFactory;

    protected $table = 'retailer_product_stocks';

    protected $fillable = ['retailer_id','product_id','quantity'];

    protected $hidden = ['created_at','updated_at'];
}
