<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
  protected $table = 'persons';
  protected $fillable = ['name', 'document_type', 'document_num', 'direction', 'phone', 'email'];

  public function provider()
  {
    return $this->hasOne('App\Provider');
  }

  public function user()
  {
    return $this->hasOne('App\User');
  }
}
