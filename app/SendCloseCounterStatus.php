<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SendCloseCounterStatus extends Model {

    protected $table ='send_close_counter_status';


    protected $fillable = array
    (
        'outlet_id',
        'start_date',
        'end_date',
        'amount',
        'total_by_user',
        'total_from_db',
        'remarks',
        'sms_count'
    );


}
