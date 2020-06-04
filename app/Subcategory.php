<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
	use SoftDeletes;

    protected $table = 'product_subcategory';
    protected $dates = ['deteled_at'];

    public function category() {
    	return $this->belongsTo('App\Category', 'product_category_id');
    }

    public function products() {
    	return $this->hasMany('App\Product', 'cat_sub_id', 'id')->where('cat_sub_type', 'subcategory');
    }
}
