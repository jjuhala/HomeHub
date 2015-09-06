<?php namespace app\Models;

use app\Models\Node as Node;
use app\Models\Control as Control;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{

    protected $fillable = ['name', 'notes', 'ip'];

    public function controls() {
        return $this->hasMany('App\Models\Control'); // this matches the Eloquent model
    }
    
}
