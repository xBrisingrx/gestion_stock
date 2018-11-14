<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	// protected $table = 'categories'; Para definir q tabla usar , por defecto asume q tu tabla tiene el mismo nombre pero en prural
	// protected $primaryKey = 'id'; Definimos el nombre del campo primary key , por defecto asume q su nombre es ID
	protected $fillable = ['name', 'description','state']; // Definimos las columnas de la tabla

	function articles()
	{
		return $this->hasMany('App\Article');
	}
}
