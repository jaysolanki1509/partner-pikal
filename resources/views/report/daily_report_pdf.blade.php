@extends('partials.default')
@section('pageHeader-left')
    Detailed Daily Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <span class="pull-left dash-label col-md-2"> Select Outlets: </span>
                        <div class="col-md-9 form-group">
                            {!! Form::select('outlet_id',$outlets,null,array('id' => 'outlet_id','style'=>'width:100%;','class'=>'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-2">From</div>
                        <div class="col-md-4 form-group">
                            {!! Form::text('from_date', isset($fromdate)?$fromdate:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                        </div>

                        <div class="col-md-1">To</div>
                        <div class="col-md-4 form-group">
                            {!! Form::text('to_date', isset($todate)?$todate:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                        <div class="col-md-1 " style="padding-left: 0px;">
                            <div class=" form-group">
                                <input type="button" name="show" class="btn btn-primary" id="showbtn" value="Show" >
                            </div>

                        </div>
                    </div>



                    <div style="clear:both"></div>

                    <div class="col-md-12 detail_report_table">

                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script>
        $(document).ready(function() {

            $(document).delegate("#showbtn","click",function(e){
                fetch_outlet_report()
            });

            function fetch_outlet_report(){
                var outlet_id = $('#outlet_id').val();
                if(outlet_id!=0){
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if(from_date>to_date){
                        alert("From date must be greater then To date.")
                    }else{
                        $.ajax({
                            url: '/ajax/get_detail_report_pdf',
                            type: "POST",
                            data: {outlet_id : outlet_id,from_date : from_date,to_date : to_date},
                            success: function (data) {
                                if(data!=false){
                                    $('.detail_report_table').html(data);
                                }
                                else
                                    alert('No details found of your outlet.');
                            }
                        });
                    }
                }else{
                    alert("Please Select an Outlet");
                }
            }


            $('#pdf_table').DataTable({
                responsive: true,
                pageLength: 100
            });

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });

        });



    </script>

@stop