<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    protected $fillable = ['name', 'action', 'control_id', 'show_on_ui'];

    public function control() {
        return $this->belongsTo('App\Models\Control');
    }

    public function triggers() {
        return $this->hasMany('App\Models\Trigger');
    }
    
}
