<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($room,['route' => array('rooms.update',$room->id), 'method' => 'patch', 'id' => 'roomForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'rooms.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'roomForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Room Name/Number*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Room Name or Number','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::label('room_type_id','Room Type*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('room_type_id', $room_type, null, array('class' => 'col-md-3 form-control','id' => 'room_type_id','required')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('room_status_id','Room Status*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('room_status_id', $room_status, null, array('class' => 'col-md-3 form-control','id' => 'room_status_id','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('description','Room Description:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('description', null, array('rows'=>4,'class' => 'col-md-3 form-control','id' => 'description', 'placeholder'=> 'Room Description')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('rooms.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn" type="Submit" value="true">Save & Continue</button>
                            <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                        @endif
                    </div>
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $("#roomForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "room_type_id": {
                        required: true
                    },
                    "room_status_id": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "*Room Name or Number is required"
                    },
                    "room_type_id": {
                        required: "*Room Type is required"
                    },
                    "room_status_id": {
                        required: "*Room Status is required"
                    }
                }

            })

        })
    </script>
@stop