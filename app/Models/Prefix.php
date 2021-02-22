<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    protected $table="prefix";
    protected $fillable=['key','code','if_serialize','content'];
}
