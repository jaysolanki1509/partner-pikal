
@extends('partials.default')
@section('pageHeader-left')
    @if($action=='edit')
        Update Staff Shift
    @else
        Add Staff Shift
    @endif
@stop

@section('pageHeader-right')
    <a href="/staff-shifts" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=='edit')
                        {!! Form::model($shift,['route' => array('attendance.updatestaffshift',$shift->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'staffShiftForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                    @else
                        {!! Form::open(['route' => 'attendance.storestaffshift', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'staffShiftForm']) !!}
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('name','Name:', array('class' => 'control-label col-md-12')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('name', null, array('class' => 'form-control','maxlength'=>'15','id' => 'name', 'placeholder'=> 'Name','required')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shift_div">
                        @if( $action == 'add' )
                            <div class="shift_block">
                                <div class="form-group col-md-12">
                                    <div class="row hide">
                                        <div class="col-md-4">
                                            <label for="from" class="control-label">From</label>
                                            <div class="col-md-12 input-group bootstrap-timepicker">
                                                <input id="from0" name="from[]" class="form-control timepicker from-time" type="text" value="" >
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="from" class="control-label">To</label>
                                            <div class="col-md-12 input-group bootstrap-timepicker">
                                                <input id="to0" name="to[]" class="form-control timepicker to-time" type="text" value="" >
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 remove-btn hide">
                                            <label for="from" class="control-label">&nbsp;</label>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger" onclick="removeShift(this)"><i class="fa fa-remove"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        @else
                            @if( isset($shift->slots) && $shift->slots != '')
                                <?php
                                    $slt_arr = json_decode($shift->slots,true);
                                    $i = 0;
                                ?>

                                @foreach( $slt_arr as $slt )
                                    <div class="shift_block">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="from" class="control-label">From</label>
                                                    <div class="col-md-12 input-group bootstrap-timepicker" >
                                                        <input name="from[]" value="{!! $slt['from'] !!}" class="form-control timepicker from-time" type="text">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="from" class="control-label">To</label>
                                                    <div class="col-md-12 input-group bootstrap-timepicker" >
                                                        <input name="to[]" value="{!! $slt['to'] !!}" class="form-control timepicker to-time" type="text" >
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                </div>

                                                <div class="form-group remove-btn col-md-2 @if($i == 0)hide @endif">
                                                    <div class="col-md-2">
                                                        <label for="from" class="control-label"></label>
                                                    </div>
                                                    <div class="col-md-12" style="margin-top: 25px" ;>
                                                        <button type="button" class="btn btn-danger" onclick="removeShift(this)"><i class="fa fa-remove"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $i++;?>
                                @endforeach
                            </div>
                            @endif
                        @endif

                        <div class="form-footer">
                            <div class="col-md-8">
                                @if($action=='edit')
                                    <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                                    {!! HTML::decode(HTML::linkRoute('attendance.staffshift','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                                @else
                                    <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                                    <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                                    <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                                @endif
                                    <button name='addshift' id='addshift' class="btn btn-primary" type="button" onclick="cloneSlot()"><i class="fa fa-plus"></i> Slot</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
                <input type="text" class="hide" id="clone_time" value="1">
            </div>
        </div>
    </div>

    @section('page-scripts')
        <script src="/assets/js/new/lib/jquery.validate.js"></script>
        <script type="text/javascript">

            $(document).ready(function() {

                @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
                @endif
                @if(Session::has('error'))
                    successErrorMessage('{{ Session::get('error') }}','error');
                @endif

                    $("#staffShiftForm").validate({
                    rules: {
                        "name": {
                            required: true
                        }
                    },
                    messages: {
                        "name": {
                            required: "*Name is required"
                        }
                    }

                });
                @if( $action != 'edit' )
                    cloneSlot();
                @else
                    $(".timepicker").timepicker();
                @endif

                //asign_timepicker();
            });

            function cloneSlot() {
                var clone_time = $('#clone_time').val();

                var clone = $('.shift_block:first').clone();
                $(clone).find('.row').removeClass('hide');
                $(clone).find('.remove-btn').removeClass('hide');
                $(clone).find('.from-time').attr('id','from'+clone_time);
                $(clone).find('.from-time').attr('name','from[]');
                $(clone).find('.to-time').attr('id','to'+clone_time);
                $(clone).find('.to-time').attr('name','to[]');
                $(clone).appendTo('.shift_div');

                asign_timepicker();

            }

            function asign_timepicker(){

                var clone_time = $('#clone_time').val();

                $(".timepicker").timepicker();

                $('#clone_time').val(clone_time++);
            }

            function removeShift(e) {
                $(e).closest('.shift_block').remove();
            }
        </script>
    @stop

@stop



