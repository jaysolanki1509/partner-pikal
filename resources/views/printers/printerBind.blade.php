
@extends('partials.default')
@section('pageHeader-left')
    Bind Printer
@stop

@section('pageHeader-right')
    <a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row" id="order_filter">
        <div class="col-md-12">
            <div class="widget-wrap">

                <div class="widget-header block-header clearfix">
                    <form class='j-forms'>

                        <div class="col-md-6">
                            {!! Form::select('category_id', $categories,null,array('class' => 'form-control select2','id'=>'category_id')) !!}
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" id="show_btn" onclick="getList()">Show</button>
                        </div>

                    </form>
                </div>

                <div class="widget-container">
                    <div class="widget-content">
                        <div id="data-div">
                            Select Menu Category
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
            $('#outlet_id').select2({
                placeholder: 'Select an Outlet'
            });
            $('#category_id').select2({
                placeholder: 'Select Menu Category'
            });

            getList();


        });

        function getList(){

            var category_id = $('#category_id').val();

            if ( category_id == '') {
                $('#category_id').select2('open');
                return;
            }

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/printer-bind',
                type: "POST",
                data: {cat_id : category_id,flag:'fetch'},
                success: function (data) {

                    $('#data-div').html(data);
                    $('.printer').select2();
                    processBtn('show_btn','remove','Show');

                }
            });

        }

    </script>

@stop