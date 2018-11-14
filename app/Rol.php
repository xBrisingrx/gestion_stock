<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
  protected $table = 'roles';
  protected $fillable = ['name', 'description', 'condition'];


  public function user()
  {
    return $this->hasOne('App\User');
  }
}
