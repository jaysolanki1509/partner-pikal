<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($guest_source,['route' => array('guest-source.update',$guest_source->id), 'method' => 'patch', 'id' => 'guestSourceForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'guest-source.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'guestSourceForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Guest Source Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Guest Source Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('description','Source Description*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('description', null, array('rows'=>4,'class' => 'col-md-3 form-control','id' => 'description', 'placeholder'=> 'Guest Source Description','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('guest-source.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
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

            $("#guestSourceForm").validate({
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