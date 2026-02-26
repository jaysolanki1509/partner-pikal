<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;

class ItemAttribute extends Model {

    protected $table ='item_attributes';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'

    );

    public function user()
    {
        return $this->belongsTo('App\Owner','id','created_by');
    }


}
