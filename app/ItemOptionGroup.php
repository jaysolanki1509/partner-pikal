<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemOptionGroup extends Model {

    protected $table ='item_option_groups';

    use SoftDeletes;
    protected $softDelete = true;

    public function itemGroupOptions()
    {
        return $this->hasMany('App\ItemGroupOption','item_option_group_id','id');
    }

    public static function getOutletOptiongroups($outlet_id ) {

        $group_arr = array();

        $item_option_group = ItemOptionGroup::with('itemGroupOptions')->where('outlet_id',$outlet_id)->get();
        if ( isset($item_option_group) && sizeof($item_option_group) > 0 ) {
            $i=0;
            foreach ( $item_option_group as $group ) {

                $group_arr[$i]['id'] = $group->id;
                $group_arr[$i]['name'] = $group->name;
                $group_arr[$i]['max'] = $group->max;
                $group_arr[$i]['select_type'] = $group->select_type;
                if ( isset($group->itemGroupOptions) && sizeof($group->itemGroupOptions) > 0 ) {
                    $j=0;
                    foreach ( $group->itemGroupOptions as $option ) {

                        $item = Menu::find($option->option_item_id);
                        if(isset($item->item)) {
                            $group_arr[$i]['item_option'][$j]['item_id'] = $option->option_item_id;
                            $group_arr[$i]['item_option'][$j]['item_name'] = $item->item;
                            $group_arr[$i]['item_option'][$j]['item_price'] = $option->option_item_price;
                            $group_arr[$i]['item_option'][$j]['item_default'] = $option->default_option;
                        }
                        $j++;
                    }
                }

                $i++;
            }
        }

        return $group_arr;
    }

    public static function getItemGroupOption($item_id) {

        //get menu item option group
        $opt_group_arr = array();
        $item_option_group = ItemOptionGroupMapper::where('item_id',$item_id)->get();
        if ( isset($item_option_group) && sizeof($item_option_group) > 0 ) {
            $i=0;
            foreach ( $item_option_group as $grp ) {

                $group = ItemOptionGroup::with('itemGroupOptions')->where('id',$grp->item_option_group_id)->get();

                if ( isset($group) && sizeof($group) > 0 ) {

                    foreach ( $group as $grp_opt ) {

                        $opt_group_arr[$i]['id'] = $grp_opt->id;
                        $opt_group_arr[$i]['name'] = $grp_opt->name;
                        $opt_group_arr[$i]['max'] = $grp_opt->max;
                        $opt_group_arr[$i]['select_type'] = $grp_opt->select_type;

                        if ( isset($grp_opt->itemGroupOptions) && sizeof($grp_opt->itemGroupOptions) > 0 ) {
                            $j=0;
                            foreach ( $grp_opt->itemGroupOptions as $opt ) {

                                $item = Menu::find($opt->option_item_id);

                                $opt_group_arr[$i]['options'][$j]['option_id'] = $opt->option_item_id;
                                $opt_group_arr[$i]['options'][$j]['option_name'] = $item->item;
                                $opt_group_arr[$i]['options'][$j]['option_price'] = $opt->option_item_price;
                                $opt_group_arr[$i]['options'][$j]['default_option'] = $opt->default_option;
                                $j++;
                            }
                        }

                    }

                    $i++;
                }

            }
        }

        return $opt_group_arr;

    }

}
