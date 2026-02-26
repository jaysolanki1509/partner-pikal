<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($arrival_departure_mode,['route' => array('arrival-departure-mode.update',$arrival_departure_mode->id), 'method' => 'patch', 'id' => 'arrivalDepartureModeForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'arrival-departure-mode.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'arrivalDepartureModeForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Arrival Departure Mode Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Arrival Departure Mode Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('description','Mode Description*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('description', null, array('rows'=>4,'class' => 'col-md-3 form-control','id' => 'description', 'placeholder'=> 'Arrival Departure Mode Description','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('arrival-departure-mode.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
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

            $("#arrivalDepartureModeForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "description": {
                        required: true
                    }

                },
                messages: {
                    "name": {
                        required: "*Name is required"
                    },
                    "description": {
                        required: "*Description is required"
                    }
                }

            })

        })
    </script>
@stop