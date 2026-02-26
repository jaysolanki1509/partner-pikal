@extends('partials.default')
@section('pageHeader-left')
    Booking Dashboard
@stop

@section('pageHeader-right')

@stop

@section('content')
    <style type="text/css">

        .ui-datepicker-inline
        {
            width: 200px !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content" style="height: 750px;">
                        <div class="col-md-2">
                            <div id="datepicker1"></div>
                        </div>
                        <div class="col-md-10">
                            <div id="dateChart">
                                <table id="dateTable" class="table-bordered" border="1" width="100%" style="text-align: center">

                                </table>
                            </div>
                        </div>
                        <div class="col-md-8 hide" id="no_rooms_found">
                            <label style="color: red">No Rooms Found Please Add Rooms.</label>
                            <a href="/rooms">From Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="roomDetailsShow" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- bookview.blade page load in model -->
            </div>

        </div>
    </div>

    {{--Add Room Details modal--}}
    <div id="roomDetails" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Room Details</h4>
                </div>
                <div class="modal-body">

                    <!--- Room details div  --->
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            Room: <label id="lbl_room_number_id" style="font-weight: 700"></label>
                            {!! Form::text('room_id', NULL, ['id'=>'room_id','class' => 'hide form-control']) !!}

                        </div>
                        <div class="col-md-3">
                            Check In: <label id="lbl_check_in_id" style="font-weight: 700"></label>
                            {!! Form::text('check_in', NULL, ['id'=>'check_in','class' => 'hide form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            <label id="days_lbl" class="pull-left">Days:&nbsp;</label>
                            {!! Form::select('days', $one_to_twenty,NULL, ['style'=> "width:75px;height:30px;",'id'=>'days','class' => 'form-control pull-left']) !!}
                        </div>
                        <div class="col-md-3">
                            <label id="rooms" class="pull-left">No of Rooms:&nbsp;</label>
                            {!! Form::select('no_of_rooms', $one_to_twenty,1, ['style'=> "width:75px;height:30px;",'id'=>'no_of_rooms','class' => 'form-control pull-left']) !!}
                        </div>
                    </div>

                    <!--- Name details div  --->
                    <div id="name_div" class="form-group col-md-12">
                        <div class= "col-md-2">
                            {!! Form::label('salutation', 'Salutation*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('salutation', $salutation,NULL, ['id'=>'salutation','class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            {!! Form::label('first_name', 'First name*', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('first_name', NULL, ['id'=>'first_name','class' => 'form-control','placeholder'=>"First Name"]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            {!! Form::label('lst_name', 'Last name*', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('last_name', NULL, ['id'=>'last_name','class' => 'form-control','placeholder'=>"Last Name"]) !!}
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <!--- Contact Number and Adult and child details div  --->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('contact_no', 'Contact No*:', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('contact_no', NULL, ['id'=>'contact_no','class' => 'form-control','placeholder'=>"Contact No"]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('adult', 'Adult*:', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::input('number','adult', 1, ['min'=>'1','max'=>'100','id'=>'adult','class' => 'form-control','placeholder'=>"Adult"]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('child', 'Child*:', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::input('number','child', 0, ['min'=>'0','max'=>'100','id'=>'child','class' => 'form-control','placeholder'=>"Child"]) !!}
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div id="deposit_div" class="form-group col-md-12 hide">
                        {{--<div class="col-md-6">
                            {!! Form::label('payment_mode', 'Payment Mode:', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('payment_mode', [],NULL, ['id'=>'payment_mode','class' => 'form-control']) !!}
                            </div>
                        </div>--}}
                        <div class="col-md-6">
                            {!! Form::label('deposit', 'Deposit*:', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::input('number','deposit', 0.00, ['min'=>'0','id'=>'deposit','class' => 'form-control','placeholder'=>"Deposit"]) !!}
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>

                </div>


                <div class="modal-footer" id="book_btns">
                    <div class="form-group pull-left">
                        <label class="checkbox" style="margin-left: 20px;">
                            {!! Form::checkbox('deposit', 1,false,array('onclick'=>"showDeposit()",'id'=>'deposit_check_box')) !!}
                            <i></i>Deposit Payment
                        </label>
                    </div>
                    <button type="button" onclick="bookRoom('check-in')" class="btn btn-primary">Check In</button>
                    <button type="button" onclick="bookRoom('reservation')" class="btn btn-primary">Reservation</button>
                    <button type="button" onclick="bookRoom('temp-reservation')" class="btn btn-primary">Temp Reservation</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@stop
@section('page-scripts')

    <script>
        
        function showDeposit() {
            var check = $('#deposit_check_box').is(':checked');

            if(check == true){
                $("#deposit_div").removeClass("hide");
            }else{
                $("#deposit_div").addClass("hide");
            }
        }

        //when click on Booked room
        function viewBooking(operation,booking_id) {

            var route = "/booking/"+booking_id+"/edit";
            window.location.replace(route);

        }

        //Room bookings
        function bookRoom(reservation_type) {
            var check_in = $("#check_in").val();
            var room_id = $("#room_id").val();
            var days = $("#days").val();
            var salutation = $("#salutation").val();
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var contact_no = $("#contact_no").val();
            var adult = $("#adult").val();
            var child = $("#child").val();
            var no_of_rooms = $("#no_of_rooms").val();
            var deposit = $("#deposit").val();

            $.ajax({
                url: '/booking-room',
                type: "POST",
                data: {no_of_rooms:no_of_rooms,reservation_type:reservation_type,check_in: check_in,room_id:room_id,days:days,salutation:salutation,first_name:first_name,
                    last_name:last_name,contact_no:contact_no,adult:adult,child:child,deposit:deposit},
                success: function (data) {
                    if ( data == 'success' ) {
                        swal({
                            title : "Booked!",
                            text : "Room Booking Successfully.",
                            type : "success"
                        },function() {
                            dateRangeGenerate("today");
                            $('#roomDetails').modal('hide');
                        });
                    } else {
                        alert('please try again later.');
                    }
                }
            });
        }

        //Generate Date range of 30 days
        function dateRangeGenerate(date) {
            if(date == "today")
                var currDate = new Date();
            else
                var currDate = new Date(date);
            var startDate = new Date(currDate.setDate(currDate.getDate() -8));
            var endDate = new Date(currDate.setDate(currDate.getDate() + 30));
            generateDateArray(startDate,endDate)
        }

        //Create Grid of dates and rooms
        function generateDateArray(startDate1,endDate) {
            $.ajax({
                url: '/generate-date',
                type: "post",
                data: { start_date:startDate1,end_date:endDate},
                success: function (data) {
                    if ( data.message == 'success' ) {
                        if(data.html == ""){
                               $('#no_rooms_found').removeClass('hide');
                        }else {
                            $('#dateTable').html(data.html);
                            $('#no_rooms_found').addClass('hide');
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            //Page Load Grid generate
            dateRangeGenerate("today");

            //Returns month
            function getDateMonth(startDate) {
                var month = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'];
                var result_month = month[startDate.getMonth()];

                return result_month;
            }

            //Open datepicker
            $("#datepicker1").datepicker({
                numberOfMonths:3,
                onSelect: function(dateText, inst) {
                    var dateAsString = dateText; //the first parameter of this function
                    dateRangeGenerate(dateAsString)
                }
            });


        });

        //Room Booking Model open
        function roomBookModel(data) {
            var room_name = $(data).data('room');
            var room_id = $(data).data('room_id');
            var date = $(data).data('date');

            $("#lbl_room_number_id").text(room_name);
            $("#room_id").val(room_id);
            var start_date = new Date(date);
            var month = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'];
            $("#lbl_check_in_id").text(start_date.getDate()+"-"+month[start_date.getMonth()]);
            $("#check_in").val(start_date);
            $("#days").val("1");
            $("#no_of_rooms").val("1");
            $("#salutation").val("1");
            $("#first_name").val("");
            $("#last_name").val("");
            $("#contact_no").val("");
            $("#adult").val("");
            $("#child").val("");
            $("#deposit").val("");

            $('#roomDetails').modal('show');

        }

        //Show Booked Room Model
        function checkDetail(data,room_id) {
            var booking_id = $(data).data('bookingid');
            $.ajax({
                url: '/check-room-details',
                type: "post",
                data: { room_id:room_id,booking_id:booking_id},
                success: function (data) {
                    $('#roomDetailsShow .modal-content').html(data);
                    $('#roomDetailsShow').modal('show');
                }
            });
        }
    </script>
@stop