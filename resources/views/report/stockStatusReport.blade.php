@extends('partials.default')

@section('pageHeader-left')
    Stock Status Report
@stop

@section('content')

    <div class="col-md-12 form-group" id="order_filter">

        @if( $status == 'success')
            <form method="post">

                <div class="col-md-3 form-group">
                    <select name="location_id" id="location_id" class="form-control">
                        <option value="">Select Location</option>
                        @if( isset($locations) && sizeof($locations) > 0 )
                            @foreach( $locations as $key=>$loc )
                                <option value="{!! $key !!}">{!! $loc !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">All Category</option>
                        @if( isset($categories) && sizeof($categories) > 0 )
                            @foreach( $categories as $cat )
                                <option value="{!! $cat->id !!}">{!! $cat->title !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::text('item_id', null, array('class'=>'','id' => 'item_id', 'placeholder'=> 'All Item')) !!}
                </div>
                <div style="clear: both"></div>
                <div class="col-md-5 form-group">
                    {!! Form::text('from_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                </div>
                <div class="col-md-5 form-group">
                    {!! Form::text('to_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="show_btn" onclick="getList()" style="margin-left: 5px;">Show</button>
                </div>
            </form>
        @else
            <div class="form-control">Please check your connection.</div>
        @endif
    </div>
    <div style="clear: both"></div>
    <div class="col-md-12" id="loading_div"></div>

    <div class="col-lg-12" id="data-list">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <b>Select filters for the report</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });
        })

        //add text of vendor on select for edit and error handling time
        $('#item_id').on("select2-selecting", function(e) {
            var v_text = e.choice.text;
            $('#item_text').val(v_text);
        });

        /* vendoor autocomplete */
        $('#item_id').select2(
        {
            placeholder: 'All Items',
            //Does the user have to enter any data before sending the ajax request
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                //How long the user has to pause their typing before sending the next request
                quietMillis: 150,
                //The url of the json service
                url: '/autocomplete',
                dataType: 'json',
                //Our search term and what page we are on
                data: function (term, page) {

                    return {
                        searchTerm: term,
                        parent_id:$('#cat_id').val(),
                        flag:'item'
                    };
                },
                results: function (data, page) {
                    //Used to determine whether or not there are more results available,
                    //and if requests for more data should be sent in the infinite scrolling
                    return { results: data};
                }
            }
        });

        //get

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var location_id = $('#location_id').val();
            var cat_id = $('#cat_id').val();
            var item_id = $('#item_id').val();

            $('#show_btn').attr('disabled',true);
            $('#show_btn').text('Loading...');
            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/stock-status-report',
                type: "post",
                data: { from_date:from,to_date:to,location_id:location_id,cat_id:cat_id,item_id:item_id},
                success: function (data) {
                    $('#loading_div').empty();
                    $('#show_btn').attr('disabled',false);
                    $('#show_btn').text('Show');
                    $('#data-list').html(data);

                }
            });

        }


    </script>
@stop