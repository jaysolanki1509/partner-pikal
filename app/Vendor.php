<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Vendor extends Model {

    protected $table ='vendors';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'type',
        'address',
        'created_by',
        'updated_by',
        'contact_person',
        'contact_number'
    );

    public function user()
    {
        return $this->belongsTo('App\Owner','id','created_by');
    }

}
