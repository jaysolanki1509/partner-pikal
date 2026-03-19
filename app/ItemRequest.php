<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemRequest extends Model {


    protected $table = 'item_request';
    use SoftDeletes;

    protected $fillable = array(
        'what_item_id',
        'what_item',
        'owner_to',
        'owner_by',
        'when',
        'qty',
        'existing_qty',
        'satisfied',
        'satisfied_by',
        'satisfied_when',
        'statisfied_qty',
        'location_for',
        'location_from'
    );

    public static function getItemsByOwner_toId($owner_to)
    {
        $items = ItemRequest::where('owner_to',$owner_to)->orderBy('satisfied', 'asc')->get();
        return $items;
    }

    public static function getAllRequestedUsersByOwner_toId($owner_to)
    {
        $items = ItemRequest::distinct()->select('owner_by')->where('owner_to',$owner_to)->where('satisfied','No')->orderBy('satisfied', 'asc')->get();
        return $items;
    }

    public static function getItemsByOwner_byId($owner_by)
    {
        $items_by = ItemRequest::where('owner_by',$owner_by)->get();
        return $items_by;
    }

    public static function getNotSatisfiedItemsOwner_byId($owner_by)
    {
        $items_by = ItemRequest::where('owner_by',$owner_by)->where('satisfied',"No")->get();
        return $items_by;
    }

    public static function getItemsById($id)
    {
        $item = ItemRequest::where('id',$id)->first();
        return $item;
    }

    public static function getNotSetisfiedItemsByOwnerTo($owner_to)
    {
        $not_satisfieditems = ItemRequest::where('owner_to', $owner_to)->where('satisfied',"No")->orderBy('when', 'desc')->get();
        return $not_satisfieditems;
    }

    public static function getTowDatesBetweenItemsByOwnerTo($owner_to, $from_date, $to_date)
    {
        $between_items = ItemRequest::where('owner_to', $owner_to)->where('when','>=', $from_date)->where('when','<=', $to_date)->where('satisfied',"No")->orderBy('when', 'desc')->get();
        return $between_items;
    }

    public static function getTowDatesBetweenSelectedUserItemsByOwnerTo($owner_to, $selected_users ,$from_date, $to_date)
    {
        $selecte_users_items = ItemRequest::where('owner_to', $owner_to)->where('owner_by', $selected_users)->where('when','>=', $from_date)->where('when','<=', $to_date)->where('satisfied',"No")->orderBy('when', 'desc')->get();
        return $selecte_users_items;
    }

    public static function getSelectedUserNotSetisfiedItemsByOwnerTo($owner_to, $selected_users)
    {
        $selected_user_not_satisfieditems = ItemRequest::where('owner_to', $owner_to)->where('owner_by', $selected_users)->where('satisfied',"No")->orderBy('when', 'desc')->get();
        return $selected_user_not_satisfieditems;
    }

    public static function getTodayItemByOwnerTo($owner_to,$today)
    {
        $item = ItemRequest::where('owner_to',$owner_to)->where('when',$today)->where('satisfied',"No")->get();
        return $item;
    }

    public static function getPendingRequestedItemsByOwner_ById($owner_by)
    {
        $pending_requested_items = ItemRequest::where('owner_by',$owner_by)->where('satisfied',"No")->get();
        return $pending_requested_items;
    }


}