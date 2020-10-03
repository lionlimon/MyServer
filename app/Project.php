<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Event;

class Project extends Model
{   
    protected $fillable = ['name', 'user_id'];

    public function components() {
        return $this->hasMany('App\Component');
    }

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function categories() {
        return $this->hasMany('App\Category');
    }
    
    public static function boot() {
        parent::boot(); 
        static::created(function($project){
            Event::dispatch('project.created',$project);
        });
    }


}
