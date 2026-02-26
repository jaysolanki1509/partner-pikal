<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Room Details</h4>
</div>
<div class="modal-body">
    <div class="form-group col-md-12">
        <div class="col-md-3">
            Room: <label id="lbl_room_number_id" style="font-weight: 700">{{$booking[0]->room_name}}</label>

        </div>
        <div class="col-md-3">
            Check In: <label id="lbl_check_in_id" style="font-weight: 700">{{$booking[0]->check_in_date}}</label>
        </div>
        <div class="col-md-3">
            <label id="days_lbl" class="pull-left">Days:&nbsp;</label>
            <label name="days" id="days" style="font-weight: 700">{{$booking[0]->duration}}</label>
        </div>
        <div class="col-md-3">
            <label id="rooms" class="pull-left">No of Rooms:&nbsp;</label>
            <label name="no_of_rooms" id="no_of_rooms" style="font-weight: 700">{{$booking[0]->no_of_rooms}}</label>
        </div>
    </div>

    <!--- Name details div  --->
    <div class="form-group col-md-12">
        <div class= "col-md-6">
            {!! Form::label('name', 'Name:', array('class' => 'control-label')) !!}
            <label name="name" id="name" style="font-weight: 700">{{$booking[0]->salutation_name}}&nbsp;{{$booking[0]->first_name}}&nbsp;{{$booking[0]->last_name}}</label>
        </div>
        <div class= "col-md-6">
            {!! Form::label('contact', 'Contact:', array('class' => 'control-label')) !!}
            <label name="contact" id="contact" style="font-weight: 700">{{$booking[0]->mobile}}</label>
        </div>

    </div>

    <div style="clear: both;"></div>

    <!--- Contact Number and Adult and child details div  --->
    <div class="form-group col-md-12">
        <div class="col-md-2">
            {!! Form::label('adult', 'Adult:', array('class' => 'control-label')) !!}
            <label id="lbl_adult" style="font-weight: 700">{{$booking[0]->adult}}</label>
        </div>
        <div class="col-md-2">
            {!! Form::label('child', 'Child:', array('class' => 'control-label')) !!}
            <label id="lbl_child" style="font-weight: 700">{{$booking[0]->child}}</label>
        </div>
        <div class="col-md-5">
            {!! Form::label('reservation_type', 'Reservation Type:', array('class' => 'control-label')) !!}
            <label id="lbl_reservation_type" style="font-weight: 700">{{ucwords($booking[0]->reservation_type)}}</label>
        </div>
        <div class="col-md-3">
            {!! Form::label('reservation_type', 'Deposit:', array('class' => 'control-label')) !!}
            <label id="deposit" style="font-weight: 700">{{ number_format($booking[0]->deposit,2) }}&nbsp;₹</label>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="modal-footer" id="edit_book_btns">
    <button type="button" onclick="viewBooking('edit',{{$booking[0]->booking_id}})" class="btn btn-primary">Edit Booking</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>