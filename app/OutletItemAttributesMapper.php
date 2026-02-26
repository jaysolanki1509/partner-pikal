<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OutletItemAttributesMapper extends Model {

    protected $table ='outlet_item_attributes_mapper';

    protected $fillable = array
    (
        'attribute_id',
        'outlet_id'

    );

    public function user()
    {
        return $this->belongsTo('App\Owner','id','created_by');
    }

    public static function getOutletAttributes($res_id) {

        $attr_arr = array();$new_arr = array();
        $get_attr = OutletItemAttributesMapper::where('outlet_id',$res_id)->get();

        if ( isset($get_attr) && sizeof($get_attr) > 0 ) {
            foreach( $get_attr as $arr ) {
                $item_attr = ItemAttribute::find($arr->attribute_id);
                if ( isset($item_attr) && sizeof($item_attr) > 0 ) {
                    $attr_arr['id'] = $arr->attribute_id;
                    $attr_arr['name'] = $item_attr->name;
                    array_push($new_arr,$attr_arr);
                }
            }
        }
        return $new_arr;
    }
}
