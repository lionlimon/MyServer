<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['name', 'project_id', 'user_id', 'category_id'];

    public function snippets() {
        return $this->hasMany('App\Snippet');
    }
    
    public function categories() {
        return $this->hasMany('App\Category');
    }
}
