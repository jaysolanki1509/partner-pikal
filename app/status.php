<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation;
class Status extends Model
{
    protected $table = 'status';

    protected $fillable = array(
        'Outlet_name',
        'order',
        'status'
    );

    public static function getallstatusofOutlet($outlet_id)
    {
        $status = Status::where('outlet_id', $outlet_id)->orderby('order', 'ASC')->get();
        return $status;
    }

    public static function getstatusbyname($currentstatus)
    {
        $getcurrentstatus = Status::where('status', $currentstatus)->get();
        return $getcurrentstatus;
    }

    public static function getstatusbyitssequenceandoutletid($sequence, $outletid)
    {
        $getnextstatus = Status::where('order', '>', $sequence)->where('outlet_id', $outletid)->orderby('order', 'ASC')->first();
        return $getnextstatus;
    }

    public static function getstatusbyid($userid)
    {
        $status = Status::where('owner_id', $userid)->get();
        return $status;
    }
}