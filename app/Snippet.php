<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = ['user_id', 'name', 'text', 'description', 'type', 'component_id'];
}
