<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/27/2015
 * Time: 2:42 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menutitletranslation extends Model {
    protected $table = 'menu_title_transalation';
    public $timestamps = false;
    protected $fillable = ['title'];

}