<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
	
    protected $table = 'product_category';
    protected $dates = ['deleted_at'];

    public function subcategories() {
    	return $this->hasMany('App\Subcategory', 'product_category_id');
    }

    public function products() {
    	return $this->hasMany('App\Product', 'cat_sub_id')->where('cat_sub_type', 'category');
    }
}
