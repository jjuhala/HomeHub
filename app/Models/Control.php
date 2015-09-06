<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class Control extends Model
{

    protected $fillable = ['name', 'show_on_ui', 'type'];

    public function node() {
        return $this->belongsTo('App\Models\Node');
    }

    public function actions() {
        return $this->hasMany('App\Models\Action');
    }

}
