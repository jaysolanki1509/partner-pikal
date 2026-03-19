<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyReportPdf extends Model {

    protected $table = 'daily_report_pdf_mapper';

    protected $fillable = array(
        'name',
        'outlet_id',
        'path'
    );

    public static function savePdfData($outlet_id,$file_name,$path,$repor_date){

        $check_report = DailyReportPdf::where('report_date',$repor_date)->where('outlet_id',$outlet_id)->count();

        if ( $check_report == 0 ) {
            $data = new DailyReportPdf();
            $data->name = $file_name;
            $data->outlet_id = $outlet_id;
            $data->path = $path;
            $data->report_date = date('Y-m-d') ;
            $data->save();
        }

    }

}
