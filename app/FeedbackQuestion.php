<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackQuestion extends Model {

    protected $table ='feedback_questions';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'question',
        'outlet_id',
        'created_by',
        'updated_by',
        'deleted_by'
    );

    #TODO: get outlet feedback questions
    public static function getFbQuestions( $outlet_id ) {

        $quest = FeedbackQuestion::select('id','question')->where('outlet_id',$outlet_id)->get();

        return $quest;
    }

}
