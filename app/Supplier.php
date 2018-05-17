<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
	protected $table = 'suppliers';
    protected $fillable = ['code','name','address','city','phone','deadline','deleted'];
}
