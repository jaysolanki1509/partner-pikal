@extends('partials.default')
@section('pageHeader-left')
    Stock Level
@stop

@section('pageHeader-right')
    <a href="/location" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="col-md-5 form-group">
                        {!! Form::select('location_id',$locations,null,array('class' => 'form-control','id'=>'location_id')) !!}
                    </div>
                    <div class="col-md-5 form-group">
                        {!! Form::select('category_id', $categories,null,array('class' => 'form-control','id'=>'category_id')) !!}
                    </div>

                    <div class="col-md-2">
                        <div class=" form-group">
                            <button type="button" class="btn btn-success primary-btn pull-left" id="show_btn" onclick="getList()">Show</button>
                        </div>
                    </div>

                </div>

                <div class="widget-container">
                    <div class="widget-content">
                        <div id="data-div" class="cashsales_report_table">
                            Select filters for result.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-styles')
    <style>
        .dataTable_wrapper {
            overflow-x:scroll;
        }

    </style>
@stop

@section('page-scripts')

    <script>

        $(document).ready(function() {
            $('#location_id').select2({
                placeholder: 'Select Location'
            });
            $('#category_id').select2({
                placeholder: 'Select Category'
            });

        });

        function getList(){

            var location_id = $('#location_id').val();
            var category_id = $('#category_id').val();

            if ( location_id == '') {
                alert('Please select location');
                $('#location_id').focus();
                return;
            }

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/location/stock-level',
                type: "POST",
                data: {loc_id : location_id,cat_id : category_id},
                success: function (data) {

                    processBtn('show_btn','remove','Show');
                    $('#data-div').html(data);
                }
            });
        }

    </script>

@stop