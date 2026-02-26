<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($salutation,['route' => array('salutation.update',$salutation->id), 'method' => 'patch', 'id' => 'salutationForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'salutation.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'salutationForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Salutation Name:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Salutation Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('salutation.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
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
    <script type="text/javascript">

        $(document).ready(function() {

            $("#salutationForm").validate({
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

            })

        })
    </script>
@stop