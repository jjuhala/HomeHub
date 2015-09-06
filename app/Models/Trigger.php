<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{

    protected $fillable = ['name', 'control_id', 'limit', 'operand', 'timed', 'only_once', 'frequency'];

    public function action() {
        return $this->belongsTo('App\Models\Action');
    }

}
