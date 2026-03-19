<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>

<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($location,['route' => array('location.update',$location->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'locationForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'location.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'locationForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('outlet_id','Outlet*:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('outlet_id',$outlets, null, array('class' => 'form-control select2','id' => 'outlet_id')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <label class="checkbox">
                                    @if(isset($location))
                                        @if(isset($location->default_location) && $location->default_location == 1)
                                            <input type="checkbox" name="default_location" value=1 checked="true"><i></i>Default Location
                                        @else
                                            <input type="checkbox" name="default_location" value=1><i></i>Default Location
                                        @endif
                                    @else
                                        <input type="checkbox" name="default_location" value=1><i></i>Default Location

                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('location.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
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
        $('#outlet_id').select2({
            placeholder: 'Select Outlet'
        });

    @if(Session::has('success'))
            successErrorMessage('{{ Session::get('success') }}','success');
    @endif
    @if(Session::has('error'))
        successErrorMessage('{{ Session::get('error') }}','error');
    @endif

    $("#locationForm").validate({
            rules: {
                "name": {
                    required: true
                },"outlet_id": {
                    required: true
                }
            },
            messages: {
                "name": {
                    required: "*Name is required"
                },"outlet_id": {
                    required: "*Outlet Selection is required"
                }
            }

        })


    })
</script>
@stop