@extends('partials.default')
@section('pageHeader-left')
    Booking Details
@stop

@section('pageHeader-right')
    <a href="/booking" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    <style>td {
            padding: 10px;
        }</style>

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    {!! Form::model($booking[0],array('id'=>'booking_form','route' => array('booking.update',$booking[0]->booking_id), 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms', 'files'=> true)) !!}
                        <div class="form-group">
                            <div class="row">
                                {!! Form::hidden('guest_id', null, array('class' => 'form-control','id' => 'guest_id')) !!}
                                <div class="col-md-2">
                                    {!! Form::label('salutation','Salutation*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('salutation_id', $salutations,1, array('class' => 'form-control','id' => 'salutation')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('first_name','First Name*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('first_name', null, array('class' => 'form-control','id' => 'first_name', 'placeholder'=> 'First Name')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('last_name','Last Name*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('last_name', null, array('class' => 'form-control','id' => 'last_name', 'placeholder'=> 'Last Name')) !!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    {!! Form::label('gender','Gender*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('gender', ['male'=>'Male','female'=>'Female'],1, array('class' => 'form-control','id' => 'gender')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($booking[0]->reservation_type == "check-in")
                            <div class="check_in_div form-group">
                        @else
                            <div class="check_in_div form-group hide">
                        @endif

                            <div class="row">

                                <div class="col-md-3">
                                    {!! Form::label('id_proof','ID Proof:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('id_proof', ['driving_license'=>'Driving License','passport'=>'Passport'],1, array('class' => 'form-control','id' => 'id_proof')) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    {!! Form::label('id_number','ID Number:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('id_number', null, array('class' => 'form-control','id' => 'id_number', 'placeholder'=> 'Proof Id Number')) !!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    {!! Form::label('dob','Date Of Birth:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('dob',null, array('class' => 'form-control','id' => 'dob')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('email','Email:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('email', null, array('class' => 'form-control','id' => 'email')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    {!! Form::label('purpose','Purpose:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::textarea('purpose', null, array('rows'=>"3",'class' => 'form-control','id' => 'purpose')) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    {!! Form::label('Country','Country:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('country', $country,null, array('class' => 'form-control','id' => 'country')) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    {!! Form::label('state','State:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('state', $state,null, array('class' => 'form-control','id' => 'state')) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    {!! Form::label('city','City:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('city', $city,null, array('class' => 'form-control','id' => 'city')) !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    {!! Form::label('check_in','Check in Date*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('check_in_date', null, array('onchange'=>'setDuration()','readonly','class' => 'form-control','id' => 'check_in_date')) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('check_out','Check out Date*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('check_out_date', null, array('onchange'=>'setDuration()','readonly','class' => 'form-control','id' => 'check_out_date')) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('duration','Duration*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('duration', null, array('readonly','class' => 'form-control','id' => 'duration')) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('adult','Adult*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('adult', $one_to_twenty,null, array('class' => 'form-control','id' => 'adult')) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('child','Child*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('child', $one_to_twenty,null, array('class' => 'form-control','id' => 'child')) !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <table width="100%">
                                <tr>
                                    <th>
                                        {!! Form::label('room','Room:', array('id'=>'room','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('room_type','Room Type:', array('id'=>'room_type','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('guest_name','Guest Name:', array('id'=>'guest_name','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('contact','Contact:', array('id'=>'contact','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('ad-ch','Adult-Child:', array('id'=>'ad-ch','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('in_out','Chk-in Chk-out:', array('id'=>'in_out','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('status','Status:', array('id'=>'status','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('price','Price:', array('id'=>'price','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                    <th>
                                        {!! Form::label('action','Action:', array('id'=>'action','class' => 'col-md-12 control-label')) !!}
                                    </th>
                                </tr>
                                <?php $i=0; $sub_total = 0.00; $discount = 0.00; $deposit = 0.00; $taxes = 0.00; $additional_charges = 0.00; $final_total = 0.00; ?>
                                @foreach($booking as $book)

                                    <tr id="{{++$i}}" align="center">
                                        <td width="7%">
                                            {!! Form::select('room[]',$rooms,$book->room_id, array('onchange'=>'changeRoomTypes(this,'.$i.')','id'=>'room','class' => 'col-md-12 form-control')) !!}
                                        </td>
                                        <td width="15%">
                                            {!! Form::select('room_type[]',$room_types,$book->room_type_id, array('onchange'=>'changeRoom(this,'.$i.')','id'=>'room_type','class' => 'col-md-12 form-control')) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('guest_name[]',$book->first_name.' '.$book->last_name, array('id'=>'guest_name','class' => 'col-md-12 form-control')) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('contact[]',$book->mobile, array('id'=>'contact','class' => 'col-md-12 form-control ')) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('room_adult[]',$one_to_twenty, $book->adlt, array('id'=>'adult','class' => 'col-md-6')) !!}
                                            {!! Form::select('room_child[]',$one_to_twenty, $book->chld, array('id'=>'child','class' => 'col-md-6')) !!}
                                        </td>
                                        <td width="20%">
                                            {!! Form::text('check_in[]', $book->check_in, array('readonly','class' => 'col-md-6 dpopen')) !!}
                                            {!! Form::text('check_out[]', $book->check_out, array('readonly','class' => 'col-md-6 dpopen')) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('status[]',['reservation'=>'Reservation','temp-reservation'=>'Temp Reservation','check-in'=>'Check In'] ,$book->reservation_typ, array('onchange'=>'changeStatus(this)','id'=>'status','class' => 'status col-md-12 form-control')) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('price[]',$book->price, array('style'=>'text-align: right','class' => 'price col-md-12 form-control')) !!}
                                            {!! Form::hidden('booking_rooms_id[]',$book->booking_rooms_id) !!}
                                        </td>
                                        <td>
                                            <a href="#" onclick="del('{!! $book->booking_rooms_id !!}')"> <span class="zmdi zmdi-delete"></span> </a>
                                        </td>
                                    </tr>
                                    <?php
                                        if($sub_total == 0)
                                            $deposit += $book->deposit;
                                        $sub_total += $book->price;
                                    ?>
                                @endforeach

                                <tr align="right">
                                    <td colspan="7" style="font-weight: 700">Sub Total:</td>
                                    <td><span id="sub_total">{{$sub_total}}</span></td>
                                    <td></td>
                                </tr>
                                <tr align="right">
                                    <td colspan="7" style="font-weight: 700">Additional Charges:</td>
                                    <td><span id="additional_charge">{{$additional_charges}}</span></td>
                                    <td></td>
                                </tr>
                                <tr align="right" id="tax_td">
                                    <td colspan="6" style="font-weight: 700">Select Tax:</td>
                                    <td colspan="2">{!! Form::select('taxes',$all_tax, Null, array('onChange'=>'calcTax()','id'=>'tax_select','class' => 'col-md-12 form-control')) !!}</td>
                                </tr>
                                <tr align="right" id="old_tax">
                                    <td colspan="7" style="font-weight: 700">Taxes:</td>
                                    <td><span id="taxes">{{$taxes}}</span></td>
                                    <td></td>
                                </tr>
                                @if($discount != 0)
                                    <tr align="right">
                                        <td colspan="7" style="font-weight: 700">Discount:</td>
                                        <td><span id="discount">{{number_format($discount,2)}}</span></td>
                                        <td></td>
                                    </tr>
                                @endif
                                @if($deposit != 0)
                                    <tr align="right">
                                        <td colspan="7" style="font-weight: 700">Deposit:</td>
                                        <td><span id="deposit">{{number_format($deposit,2)}}</span></td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr align="right" class="hide">
                                        <td colspan="7" style="font-weight: 700">Deposit:</td>
                                        <td><span id="deposit">{{number_format($deposit,2)}}</span></td>
                                        <td></td>
                                    </tr>
                                @endif
                                <tr align="right">
                                    <td colspan="7" style="font-weight: 700">Final Total:</td>
                                    <td><span id="final_total" style="font-weight: 700">{{$final_total}}</span></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    <input type="hidden" id="booking_id" value="{{$booking[0]->booking_id}}">

                    <div class="form-footer">
                        <button class="btn btn-success primary-btn"  id="submit_btn" type="submit">Update Booking</button>
                        <button class="btn btn-success primary-btn"  id="folio_btn">Show Folio</button>
                        <button type="button" class="btn btn-danger primary-btn" onclick='deleteBooking({{$booking[0]->booking_id}})'>Cancel Booking</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script>

        function  changeRoomTypes(ele,raw) {
            var room_id = $(ele).val();
        }
        function checkTotal(){
            var total = 0;
            $('.price').each(function () {
                total += parseFloat($(this).val());
            });
            $("#sub_total").text(total);
        }

        function deleteBooking(booking_id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Booking Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/booking/'+booking_id,
                        type: "Delete",
                        success: function (data) {
                            if(data == "success"){
                                swal({
                                    title : "Deleted!",
                                    text : "Your Booking Details has been removed.",
                                    type : "success"
                                });
                                var route = "/booking/";
                                window.location.replace(route);
                            }else{
                                swal("Cancelled", "Your Bookings Details not delete because of error", "error");
                            }

                        }
                    });

                } else {
                    swal("Cancelled", "Your Bookings Details are safe :)", "error");
                }
            });
        }

        function changeStatus(ele) {
            var room_status = $(ele).val();
            if(room_status == 'check-in'){
                $('.check_in_div').removeClass('hide');
            }
        }

        $(document).ready(function() {
            setDuration();
            checkTotal();
            calcTax();

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $('#check_in_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#check_out_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });
            $('#dob').DatePicker({
                format: "1990-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });
            $('.dpopen').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $("#booking_form").validate({
                rules: {
                    "first_name": {
                        required: true
                    }
                },
                messages: {
                    "first_name": {
                        required: "First Name is Required."
                    }
                }
            });
        });

        function setDuration(){
            var check_in_date = new Date($('#check_in_date').val());
            var check_out_date = new Date($('#check_out_date').val());
            var timeDiff = Math.abs(check_in_date.getTime() - check_out_date.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $("#duration").val(diffDays);
        }

        function parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[1], mdy[0]-1, mdy[2]);
        }

        function calcTax() {
            checkTotal();
            var sub_total = $('#sub_total').text();
            var selected_tax = $('#tax_select').val();
            var booking_id = $('#booking_id').val();

            if(selected_tax != "") {
                $.ajax({
                    url: '/calc-room-tax',
                    type: "Post",
                    data: {sub_total: sub_total, tax_slab: selected_tax, booking_id:booking_id},
                    success: function (data) {
                        $(".new_tax").remove();
                        $("#old_tax").remove();
                        $(data.html).insertAfter("#tax_td");
                        $("#final_total").html(data.final_total);
                        $("#taxes").html(data.tax_total);

                    }
                });
            }
        }

    </script>
@stop