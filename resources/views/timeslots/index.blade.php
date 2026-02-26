@extends('partials.default')
@section('pageHeader-left')
    Time Session
@stop
@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>

    @endif
    <?php  $m=0; ?>
    <div class="row well">
        <form class="form-horizontal" role="form" method="POST" id="feedbackCreate" name="feedbackCreate" action="{{ url('/storetimeslot') }}" files="true" enctype="multipart/form-data">

            <div class="col-md-12">

                <div class="col-md-1 form">
                    <label class="control-label lbl_outlet" style="padding-left:15px">Outlet</label>
                </div>

                <div class="col-md-7 form">
                    {!! Form::select('outlet_id', $select_outlets, null, ['class' => 'form-control ', 'id' => 'outlet_id', 'onchange' => 'checkSlots(this.value)','style'=>'margin-left:10px;'] ) !!}
                </div>

                <?php $i=0; ?>
                <div id="appendtimeslots" style = "padding-top:60px">
                </div>

                <div class="col-md-9 form">
                    <div class="col-md-3 input-append bootstrap-timepicker ">

                    </div>
                    <div class="col-md-2">
                        <label for="closing_time" class="control-label"></label>
                    </div>
                    <div class="col-md-3 input-append bootstrap-timepicker ">

                    </div>

                </div>
                <?php $i++; ?>

                {!!Form::hidden('countf',$i,array('id'=>'countf'))!!} <!-- pre added timeslot count -->
                <div class="col-md-12 form">
                    <div id="myID" class="col-md-2 form">
                        <input type="button"  value="Add More Session" class="btn btn-primary mr5">
                    {{--</div>
                    <div class="col-md-9 form">--}}
                    </div>
                    <button class="btn btn-primary mr5"  id="Submit" style="margin-left: 10px;" novalidate="novalidate" type="Submit" >{{ trans('Restaurant_Form.Submit') }}</button>
                </div>
            </div>
        </form>
    </div>

@stop

@section('page-scripts')
    <script>

        function checkSlots(o_id) {
            var someText='';
            $.ajax({
                url: '/checktimeslots',
                Type: 'POST',
                dataType: 'json',
                data: 'outlet_id=' + o_id ,
                success: function (data) {
                    $("#appendtimeslots").html('');
                    $('#countf').val(data.length);
                    for(var i=0;i<data.length;i++){
                        var x=z++;  //when add new features increase id
                        someText = '<div class="">' +
                                    '<div class="" id="control'+x+'">'+
                                        '<div class="col-md-12 form" style="padding-left:0px;">' +
                                            '<div class="col-md-1 timepicker-label">'+
                                                '<label for="closing_time" class="control-label">Session</label>'+
                                            '</div>'+
                                            '<div class="col-md-2 input-append bootstrap-timepicker ">'+
                                                '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control add-on" type="text" value="'+data[i].slot_name+'" style="width: 100%; display: inline;" placeholder="Name">'+
                                            '</div>'+
                                            '<div class="col-md-1 timepicker-label">'+
                                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                            '</div>'+
                                            '<div class="col-md-2 input-append bootstrap-timepicker ">' +
                                                '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control add-on" type="text" value="'+data[i].from_time+'" style="width: 75%; margin-right:4px; display: inline;">'+
                                                '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                            '</div>'+
                                            '<div class="col-md-1 timepicker-label">'+
                                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                            '</div>'+
                                            '<div class="col-md-2 input-append bootstrap-timepicker">'+
                                                '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control add-on" type="text"  value="'+data[i].to_time+'" style="width: 75%; margin-right:4px; display: inline;">'+
                                                '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                            '</div>'+
                                            '<input type="button" class="btn btn-primary mr5" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                                        '</div>'+

                                        '<input type=hidden name="count" value='+x+'>' +
                                    '</div>' +
                                '</div>';
                        var newDiv = $("<div class='col-md-12'>").append(someText);
                        $("#appendtimeslots").append(newDiv);

                        $('#opening_time'+x).timepicker({
                            format: 'HH:mm',
                            minuteStep: 1,
                            pick12HourFormat: false,
                            showMeridian: false
                        });

                        $('#closing_time'+x).timepicker({
                            format: 'HH:mm',
                            minuteStep: 1,
                            pick12HourFormat: false,
                            showMeridian: false
                        });

                        $(".timepicker").timepicker({
                            format: 'HH:mm',
                            minuteStep: 1,
                            pick12HourFormat: false,
                            showMeridian: false
                        });
                    }
                }
            });
        }

        $(document).ready(function() {

            checkSlots($('#outlet_id').val());

        });

        var i=1;
        var someText='';
        var z = document.getElementById("countf").value; //get pre added time slots
        $("#myID").click(
                function () {
                    var x=z++;  //when add new features increase id
                    someText = '' +
                        '<div class="">' +
                            '<div class="" id="control'+x+'">'+
                                '<div class="col-md-12 form" style="padding-left:0px;">'+
                                    '<div class="col-md-1 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">Session Name</label>'+
                                    '</div>'+
                                    '<div class="col-md-2 input-append bootstrap-timepicker ">'+
                                        '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control add-on" type="text" value="@if(isset($Outlet->slot_name)){{$Outlet->slot_name}}@endif" style="width: 100%; display: inline;" placeholder="Name">'+
                                    '</div>'+
                                    '<div class="col-md-1 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                    '</div>'+
                                    '<div class="col-md-2 input-append bootstrap-timepicker ">'+
                                        '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control add-on" type="text" value="@if(isset($Outlet->opening_time)){{$Outlet->opening_time}}@endif" style="width: 75%; margin-right:4px; display: inline;">'+
                                        '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                    '</div>'+
                                    '<div class="col-md-1 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                    '</div>'+
                                    '<div class="col-md-2 input-append bootstrap-timepicker">'+
                                        '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control add-on" type="text"  value="@if(isset($Outlet->closing_time)){{$Outlet->closing_time}}@endif" style="width: 75%; margin-right:4px; display: inline;">'+
                                        '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                    '</div>'+
                                    '<input type="button" class="btn btn-primary mr5" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                                '</div>'+
                                '<input type=hidden name="count" value='+x+'>' +
                            '</div>' +
                        '</div>';
                    var newDiv = $("<div class='col-md-12'>").append(someText);
                    $("#appendtimeslots").append(newDiv);

                    $('#opening_time'+x).timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });

                    $('#closing_time'+x).timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });

                    $(".timepicker").timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });
                }
        )
    </script>
@stop