<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    protected $fillable =['categoy_id', 'name', 'code', 'sale_price', 'stock', 'description', 'active'];

    function category()
    {
    	return $this->belongsTo('App\Category');
    }
}
