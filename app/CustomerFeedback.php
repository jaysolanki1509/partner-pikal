<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFeedback extends Model {

    protected $table ='customer_feedbacks';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'order_id',
        'outlet_id',
        'question_id',
        'value',
        'created_by',
        'updated_by',
        'deleted_by'
    );

    public static function getFeedbackByOutletId($outlet_id){

        if($outlet_id != "") {
            $feedback = CustomerFeedback::where('outlet_id', $outlet_id)->sum("value");
            $count = CustomerFeedback::where('outlet_id', $outlet_id)->count();

            if ($count > 0) {
                $avg_feedback = number_format($feedback / $count, 2);
            } else {
                $avg_feedback = 0;
            }
        }else{
            $avg_feedback = 0;
        }

        return $avg_feedback;

    }

    public static function getQuestionListWithAvgAnswer($outlet_id){
        $check = 0;
        if($outlet_id != "") {
            $feedback_ques = FeedbackQuestion::getFbQuestions($outlet_id);
            $quest = array();
            if (isset($feedback_ques) && sizeof($feedback_ques) > 0) {
                foreach ($feedback_ques as $ques) {

                    $quest[$check]['question'] = $ques->question;

                    //Over all ratings
                    $feedback = CustomerFeedback::where('question_id', $ques->id)->sum("value");
                    $count = CustomerFeedback::where('question_id', $ques->id)->count();

                    if($count > 0) {
                        $quest[$check]['all']['avg_ans'] = number_format($feedback / $count, 2);
                    }
                    else{
                        $quest[$check]['all']['avg_ans'] = 0;
                    }

                    //Current month ratings
                    $start_date = date("Y-m-01 00:00:00"); $end_date = date("Y-m-d 23:59:59");
                    $feedback_cm = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                                ->where('question_id', $ques->id)->sum("value");
                    $count_cm = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                            ->where('question_id', $ques->id)->count();

                    if($count_cm > 0) {
                        $quest[$check]['cmonth']['avg_ans'] = number_format($feedback_cm / $count_cm, 2);
                    }else{
                        $quest[$check]['cmonth']['avg_ans'] = 0;
                    }

                    //Last month ratings
                    $start_date = date("Y-n-j 00:00:00", strtotime("first day of previous month"));
                    $end_date = date("Y-n-j 23:59:59", strtotime("last day of previous month"));
                    $feedback_lm = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                                ->where('question_id', $ques->id)->sum("value");
                    $count_lm = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                            ->where('question_id', $ques->id)->count();

                    if($count_lm > 0) {
                        $quest[$check]['lmonth']['avg_ans'] = number_format($feedback_lm / $count_lm, 2);
                    }else{
                        $quest[$check]['lmonth']['avg_ans'] = 0;
                    }

                    //Today ratings
                    $start_date = date("Y-m-d 00:00:00"); $end_date = date("Y-m-d 23:59:59");
                    $feedback_td = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                                ->where('question_id', $ques->id)->sum("value");
                    $count_td = CustomerFeedback::whereBetween('created_at',[$start_date,$end_date])
                                            ->where('question_id', $ques->id)->count();

                    if($count_td > 0) {
                        $quest[$check]['today']['avg_ans'] = number_format($feedback_td / $count_td, 2);
                    }else{
                        $quest[$check]['today']['avg_ans'] = 0;
                    }

                    $check++;
                }
            } else {
                $quest[$check]['question'] = 'No Questions found.';
                $quest[$check]['all']['avg_ans'] = 0;
                $quest[$check]['cmonth']['avg_ans'] = 0;
                $quest[$check]['lmonth']['avg_ans'] = 0;
                $quest[$check]['today']['avg_ans'] = 0;
            }
        }else{
            $quest[$check]['question'] = 'No Questions found.';
            $quest[$check]['all']['avg_ans'] = 0;
            $quest[$check]['cmonth']['avg_ans'] = 0;
            $quest[$check]['lmonth']['avg_ans'] = 0;
            $quest[$check]['today']['avg_ans'] = 0;
        }

        return $quest;

    }

}
