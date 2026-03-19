<?php

    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
    use App\Outlet;
    use App\OutletSetting;

    $sess_outlet_id = Session::get('outlet_session');
    $currency = OutletSetting::checkAppSetting($sess_outlet_id,'OMR');
?>
@extends('partials.default')

<?php ?>
@section('content')

    <link href="/assets/css/new/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />

    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/assets/css/new/theme.min.css" media="all" rel="stylesheet" type="text/css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.js"></script>

    <style xmlns="http://www.w3.org/1999/html">
        .form-group {
            margin-bottom: 5px;
        }
        .panel-footer{
            padding: 5px 15px; !important;
        }
        .dataTable_wrapper
        {
            width: 100%;
            overflow-y: auto;
            _overflow: auto;
            margin: 0 0 1em;
        }
        .lbl{
            margin-bottom: 0px;
            font-weight: 500;
        }
        .lbl_val{
            margin-bottom: 0px;
        }
        #exp_val{
            cursor: pointer;
            cursor: hand;
        }
        #rev_val{
            cursor: pointer;
            cursor: hand;
        }
        #rev_val:hover {
            text-decoration: underline;
        }
        #exp_val:hover {
            text-decoration: underline;
        }
        .modal-body .caption{
            visibility: hidden;
        }
        .rating-container{
            line-height: 10px;
        }
        .glyphicon-minus-sign{
            visibility: hidden;
        }
        .label-select {
            background-color: #00a3a7;
        }
    </style>

    <div class="row">

        <div class="row_pastdays">
            <?php $i = 7 ?>
            @foreach($dates as $date)
                @if($i==1)
                    <a class="label label-success" id="date_lbl{{$i}}" onclick="getDateData({{$i--}})" style="cursor:pointer;">Today {{ $date }}</a>
                @else
                    <a class="label label-default" id="date_lbl{{$i}}" onclick="getDateData({{$i--}})" style="cursor:pointer;">{{ $date }}</a>
                @endif
            @endforeach
            <input type="hidden" name="date" id="date" style="float: none;" disabled class="form-group col-md-2" value="{{date('Y-m-d')}}">
        </div>

        <div class="col-md-6 form-group">
            <div class="col-md-12">
                <span class="pull-left">Last Updated at <span id="last_updated_at">{{ $last_updated }}</span></span>
            </div>
        </div>

        <div class="col-md-6 form-group">
            <div class="col-md-12" >
                <span class="pull-right" title="View all ratings">
                    <input id="input-id" type="text" class="rating hide" readonly value="{{ $ratings }}" data-size="xs" style="cursor: hand !important;">
                </span>
            </div>
        </div>

        <div style="clear:both"></div>

        <div class="col-md-12">
            <div class="col-lg-3 col-md-6">
                <div class="panel w_bg_teal">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                               
                                @if($currency == 1)
                                <p>OMR</p>
                                @else
                                 <i class="fa fa-inr fa-3x"></i>
                                @endif
                                <div style="font-size: 20px;">Revenue</div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div> <label class="lbl" id="rev">Today</label>  </div>
                                <div style="font-size: 16px;">
                                    <a id="rev_day_anc" href="#" style="color: #FFFFFF" title="Total Sale">
                                        <label class="lbl_val" id="rev_val">
                                            {!! $today_revenue !!}
                                        </label>
                                    </a>
                                    <a id="ong_order_lbl" href="/ongoing-tables" style="color: #FFFFFF;font-size:small  " title="Ongoing Orders">
                                        <label class="lbl_val" id="ongoing_tbl_total">

                                        </label>
                                    </a>
                                </div>
                                @if(date("d") == 1)
                                    <?php $first_date = date('d') ?>
                                    <div>Last Month </div>
                                @else
                                    <?php $first_date = date('d') - 1; ?>
                                    <div>Month </div>
                                @endif

                                <div style="font-size: 16px;">
                                    <b>
                                        <a id="rev_month_anc" href="/orderslist?month={{$first_date}}" style="color: #FFFFFF">
                                            {!! $month_revenue !!}
                                        </a>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">Average</span>
                        <span class="pull-right"><b>{!! $avg_revenue !!}</b></span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel w_bg_deep_purple">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                @if($currency == 1)
                                <p>OMR</p>
                                @else
                                 <i class="fa fa-inr fa-3x"></i>
                                @endif
                                <div style="font-size: 20px;">Expense</div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div><label class="lbl" id="exp">Today </label></div>
                                <div style="font-size: 16px;">
                                    <a id="exp_day_anc" href="#" style="color: #FFFFFF;">
                                        <label class="lbl_val" id="exp_val">{!! $today_expense !!}</label>
                                    </a>
                                </div>
                                @if(date("d")==1)
                                    <div>Last Month </div>
                                @else
                                    <div>Month </div>
                                @endif

                                <div style="font-size: 16px;"><b>
                                        <a id="exp_month_anc" href="expense/pending?month={{$first_date}}" style="color: #FFFFFF">
                                            {!! $month_expense !!}
                                        </a></b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">Average</span>
                        <span class="pull-right"><b>{!! $avg_expense !!}</b></span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel w_bg_cyan">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-3x"></i>
                                <div style="font-size: 20px;">Orders</div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div><label class="lbl" id="ord">Today </label></div>
                                <div style="font-size: 16px;"><label class="lbl_val" id="ord_val">{!! $today_order !!}</label></div>
                                @if(date("d")==1)
                                    <div>Last Month </div>
                                @else
                                    <div>Month </div>
                                @endif
                                <div style="font-size: 16px;"><b>{!! $month_order !!}</b></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">Average</span>
                        <span class="pull-right"><b>{!! $avg_order !!}</b></span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="panel w_bg_amber">
                    <div class="panel-heading" style="padding-bottom: 2px;">
                        <div class="row">
                            <div class="col-xs-6" style="padding-right: 0px;">
                                <i class="fa fa-user fa-3x"></i>
                                <div style="font-size: 20px;">PAX</div>
                                @if($currency == 1)
                                <div style="font-size: 15px; padding-top: 0px;"><b>OMR {!! $avg_pax_rev !!}</b></div>
                                @else
                                <div style="font-size: 15px; padding-top: 0px;"><b>₹ {!! $avg_pax_rev !!}</b></div>
                                @endif
                                
                            </div>
                            <div class="col-xs-6 text-right" style="padding-left: 0px;">
                                <div><label class="lbl" id="pax">Today</label></div>
                                <div style="font-size: 16px;"><label class="lbl_val" id="pax_val">{!! $today_person !!}</label></div>
                                @if(date("d")==1)
                                    <div>Last Month </div>
                                @else
                                    <div>Month </div>
                                @endif
                                <div style="font-size: 16px;"><b>{!! $month_person !!}</b></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">Average</span>
                        <span class="pull-right"><b>{!! $avg_person !!}</b></span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <div class="col-md-6">

        <div class="widget-wrap">
            <div class="widget-header block-header margin-bottom-0 clearfix">
                <div class="pull-left">
                    <h3>Today's Category Wise Sales</h3>
                </div>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flotchart-container">
                                <div id="piechart_today" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="widget-wrap">
            <div class="widget-header block-header margin-bottom-0 clearfix">
                <div class="pull-left">
                    @if(date("d")==1)
                        <h3>Last Month's Category Wise Sales</h3>
                    @else
                        <h3>Current Month's Category Wise Sales</h3>
                    @endif
                </div>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flotchart-container">
                                <div id="piechart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="widget-wrap">
            <div class="widget-header block-header margin-bottom-0 clearfix">
                <div class="pull-left">
                    <h3>Today's Top 10 Item Sales</h3>
                </div>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flotchart-container">
                                <div id="barchart_today" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="widget-wrap">
            <div class="widget-header block-header margin-bottom-0 clearfix">
                <div class="pull-left">
                    @if(date("d")==1)
                        <h3>Last Month's Top 10 Item Sales</h3>
                    @else
                        <h3>Month's Top 10 Item Sales</h3>
                    @endif
                </div>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flotchart-container">
                                <div id="barchart_month" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <?php $user=Auth::user();

            $userid=Auth::user()->id;

            $outlet=\App\Outlet::where('owner_id',$userid)->first();
        ?>
            <div class="widget-wrap">
                <div class="widget-header block-header margin-bottom-0 clearfix">
                    <div class="pull-left">
                        <h3>Current Month's Orders and Revenue</h3>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="flotchart-container">
                                    <div id="linechart" align="center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>


    <div id="viewRating" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Overall Ratings</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <a class="label label-select" onclick="changeRatings('all',this)" style="cursor:pointer;">All Time</a>
                            <a class="label label-default" onclick="changeRatings('lmonth',this)" style="cursor:pointer;">Last Month</a>
                            <a class="label label-default" onclick="changeRatings('cmonth',this)" style="cursor:pointer;">Current Month</a>
                            <a class="label label-default" onclick="changeRatings('today',this)" style="cursor:pointer;">Today</a>
                        </div>
                    </div>

                    <hr style="margin-top:10px; margin-bottom: 10px;" class="col-md-11">

                    <div class="form-group"><?php $count = 1; ?>
                        @if(isset($question_list) && sizeof($question_list)>0)
                            @foreach($question_list as $question)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5 pull-left">
                                            <span>
                                                {{$count++}})&nbsp;&nbsp;{{$question['question']}}
                                            </span>
                                        </div>
                                        <div class="col-md-7 pull-right star_div" id="all">
                                            <span>
                                                <input id="input-id" type="text" class="rating" readonly value="{{$question['all']['avg_ans']}}" data-size="xs" >
                                            </span>
                                        </div>
                                        <div class="col-md-7 pull-right hide star_div" id="cmonth">
                                            <span>
                                                <input id="input-id" type="text" class="rating" readonly value="{{$question['cmonth']['avg_ans']}}" data-size="xs" >
                                            </span>
                                        </div>
                                        <div class="col-md-7 pull-right hide star_div" id="lmonth">
                                            <span>
                                                <input id="input-id" type="text" class="rating" readonly value="{{$question['lmonth']['avg_ans']}}" data-size="xs" >
                                            </span>
                                        </div>
                                        <div class="col-md-7 pull-right hide star_div" id="today">
                                            <span>
                                                <input id="input-id" type="text" class="rating" readonly value="{{$question['today']['avg_ans']}}" data-size="xs" >
                                            </span>
                                        </div>
                                        <hr style="margin-top:10px; margin-bottom: 10px;" class="col-md-11">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-5 pull-left">
                                        <span>
                                            No Questions found.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('page-scripts')
    <!-- important mandatory libraries -->
    {!! HTML::script('/assets/js/new/lib/star-rating.min.js') !!}
    <!-- optionally if you need to use a theme, then include the theme JS file as mentioned below -->
    {!! HTML::script('/assets/js/new/lib/theme.min.js') !!}
    {!! HTML::script('/js/highcharts.js') !!}
    {!! HTML::script('/js/highcharts-3d.js') !!}

<script>

    function changeRatings(display_ele,click_ele) {
        $('.modal-body .label').each(function() {
            $( this ).removeClass( "label-select" );
            $( this ).addClass( "label-default" );
        });
        $(click_ele).removeClass('label-default');
        $(click_ele).addClass('label-select');

        $('#viewRating .star_div').each(function() {
            $( this ).addClass( "hide" );
        });
        $('#viewRating #'+display_ele).each(function() {
            $( this ).removeClass( "hide" );
        });
    }

    $('#rev_month_anc').click(function (event) {
        if($('#main_filter').val() == ''){
            event.preventDefault();
            $('#main_filter').css('border-color', 'red');
            $('#main_filter').focus();
        }
        return;
    });

    $('#rev_day_anc').click(function (event) {
        if($('#main_filter').val() == ''){
            event.preventDefault();
            $('#main_filter').css('border-color', 'red');
            $('#main_filter').focus();
        }
        return;
    });

    $(document).ready(function () {

        var currency = '{!! $currency !!}';
        var currencySymbol = '₹';
        if(currency == 1) {
            currencySymbol = 'OMR';
        }

        //open rating modal on click
        $('.rating-stars').click(function(){
            $('#viewRating').modal('show');
        })

        $("#loading_gif").show();
        var selected_outlet_id = Number($('#outlet_id').val());
        $.ajax({
            url: '/getPieChartData',
            type: "POST",
            data: {outlet_id: selected_outlet_id},
            success: function (data) {
                //console.log(data.month_pie);return;
                $('#piechart').highcharts({
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                //format: '<b>{point.name}</b>: ₹ {point.y:.0f}',
                                format: currencySymbol + ' {point.y:.0f}',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            },
                            showInLegend: true
                        }

                    },
                    series: [{
                        name: 'Sales',
                        colorByPoint: true,
                        data: getData(data.month_pie)
                    }]
                });
            }
        });

        var parentID = document.getElementById('page-wrapper');
        datePieChart(0,moment(new Date()).format('YYYY-MM-DD'));
        topTen(0,moment(new Date()).format('YYYY-MM-DD'));
        topTenMonth(moment(new Date()).format('DD')-1);
        lineChart();

        //SSE for update page data
        if(typeof(EventSource) !== "undefined") {
            var source = new EventSource("/update-page-data/dashboard");
            source.onmessage = function(event) {
                var total_sell = $('#rev_val').text();
                var sell = total_sell.replace(",", "");

                var server_total = event.data.split(":");
                var total = server_total[1];

                if ( parseInt(total) != parseInt(sell)) {
                    if ( $('.row_pastdays').find('a:last').hasClass('label-success')){
                        //source.close();
                        //location.reload();
                        datePieChart(0,moment(new Date()).format('YYYY-MM-DD'));
                        getDateData(1)
                        topTen(0,moment(new Date()).format('YYYY-MM-DD'));
                        lineChart();
                    }
                }

            };
        } else {
            console.log('SSE not supported');
        }


        /*$('#from_date').datepicker({
            dateFormat: "yy-mm-dd",
            maxDate: new Date,
            setdate:new Date

        });

        $('#to_date').datepicker({
            dateFormat: "yy-mm-dd",
            maxDate: new Date,
            setdate:new Date
        });*/

    });

    function  getDateData(day,date) {

        $('.label').removeClass('label-success');
        $('.label').addClass('label-default');
        $('#date_lbl'+day).addClass('label-success');
        if(day < 1) {
            alert("No data available for Tomorrow.")
        }else{
            var d = new Date();
            d.setDate(d.getDate() - (day-1));
            $('#date').val(moment(d).format('DD-MM-YYYY BB'));
            datePieChart(day-1,moment(d).format('YYYY-MM-DD'));
            topTen(day-1,moment(d).format('YYYY-MM-DD'));
        }
    }

    function topTen(day,date) {

        var today = new Date().getDate();
        var selected_date = new Date(date).getDate();
        var selected_outlet_id = Number($('#outlet_id').val());

        $.ajax({
            url: '/getBarChartDateData',
            type: "POST",
            data: {outlet_id: selected_outlet_id, day: day},
            success: function (data) {
                var html = "<ol>";
                for(var i=0;i<data.length;i++) {
                    if(data[i].name != "No orders") {
                        html += "<li>" + data[i].name + " (" + data[i].y + ")</li>";
                    }else{
                        html += "" + data[i].name + " found";
                    }
                }
                html += "</ol>";
                $("#barchart_today").html(html);

            }
        });
    }

    function topTenMonth(day) {

        $.ajax({
            url: '/getBarChartMonthData',
            type: "POST",
            data: { day: day},
            success: function (data) {

                var html = "<ol>";
                for(var i=0;i<data.length;i++) {
                    if(data[i].name != "No orders") {
                        html += "<li>" + data[i].name + " (" + data[i].y + ")</li>";
                    }else{
                        html += "" + data[i].name + " found";
                    }
                }
                html += "</ol>";
                $("#barchart_month").html(html);

                /*$("#barchart_month").highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -65,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Quantity'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: 'Item sold <b>{point.y}</b>'
                    },
                    series: [{
                        name: 'Item',
                        data: getBarData(data),
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            y: 10, // 10 pixels down from the top
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]
                });*/
            }
        });
    }

    function datePieChart(day,date) {
        var currency = '{!! $currency !!}';
        var currencySymbol = '₹';
        if(currency == 1) {
            currencySymbol = 'OMR';
        }
        $('#exp_day_anc').attr('href','expense/pending?day='+day+'');
        $('#rev_day_anc').attr('href','orderslist?day='+day+'');
        $("#loading_gif").show();
        var today = new Date().getDate();
        var selected_date = new Date(date).getDate();

        var date_lbl = today == selected_date?'Today '+moment(new Date(date)).format('(ddd)'):moment(new Date(date)).format('DD MMM (ddd)');
        //alert(date_lbl)
        var selected_outlet_id = Number($('#outlet_id').val());
        $.ajax({
            url: '/getPieChartDateData',
            type: "POST",
            data: {outlet_id: selected_outlet_id,day:day},
            success: function (data) {
                //console.log(data);return;
                $('#piechart_today').highcharts({
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                //format: '<b>{point.name}</b>: ₹ {point.y:.0f}',
                                    format: currencySymbol + '{point.y:.0f}',
                                    // format: '₹ {point.y:.0f}',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            },
                            showInLegend: true
                        }

                    },
                    series: [{
                        name: 'Sales',
                        colorByPoint: true,
                        data: getData(data.date_pie)
                    }]
                });
                $('#rev').text(data.lbl);
                $('#exp').text(data.lbl);
                $('#ord').text(data.lbl);
                $('#pax').text(data.lbl);

                $('#rev_val').text(data.date_revenue);
                $('#exp_val').text(data.date_expense);
                $('#ord_val').text(data.date_order);
                $('#pax_val').text(data.date_person);
                $('#last_updated_at').text(data.last_updated);
                $('#ongoing_tbl_total').text(data.ongoing_tables);

            }
        });
    }

    function getData(data){
        var arr = [];
        for(var i=0;i<data.length;i++){
            if(data[i].name != null) {
                arr.push({"name": data[i].name, "y": parseFloat(data[i].y)});
            }else{
                arr.push({"name": "Open Item", "y": parseFloat(data[i].y)});
            }
        }
        return arr;

    }

    function getBarData(data){
        var arr = [];
        for(var i=0;i<data.length;i++){
            arr.push([ data[i].name, data[i].y]);
        }
        return arr;

    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).delegate("#outlet_id","change",function(e){
        //getSlots();
        lineChart();
    });
    function lineChart() {
        var currency = '{!! $currency !!}';
        var currencySymbol = '₹';
        if(currency == 1) {
            currencySymbol = 'OMR';
        }
        $("#loading_gif").show()
        var selected_outlet_id = Number($('#outlet_id').val());
        $.ajax({
            url: '/ajax/getlinechartdata',
            type: "POST",
            data: {outlet_id : selected_outlet_id},
            success: function (data) {
                var data_array = [];
                var i=0;
                for(key in data) {
                    if(data.hasOwnProperty(key)) {
                        var value = data[key];
                        for(key1 in value) {
                                data_array[i++] = value[key1];
                        }
                    }
                }

                    $('#linechart').highcharts({
                        title: {
                            text: '',
                            x: -20 //center
                        },
                        /*subtitle: {
                            text: 'Source: WorldClimate.com',
                            x: -20
                        },*/
                        xAxis: {
                            categories: data_array[0],
                            crosshair: true,
                            title: {
                                text: 'Date'
                            }
                        },
                        yAxis: [{ // Primary yAxis
                            labels: {
                                format: currencySymbol + ' {value}',
                                style: {
                                    color: '#d9534f'
                                }
                            },
                            title: {
                                text: 'Revenue',
                                style: {
                                    color: '#d9534f'
                                }
                            }

                        }, { // Secondary yAxis
                            gridLineWidth: 0,
                            title: {
                                text: 'Orders',
                                style: {
                                    color: '#337ab7'
                                }
                            },
                            labels: {
                                format: '{value}',
                                style: {
                                    color: '#337ab7'
                                }
                            },
                            opposite: true
                        }],
                        tooltip: {
                            shared: true
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            x: 80,
                            verticalAlign: 'top',
                            y: 55,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                        },
                        series: [{
                            name: 'Orders',
                            type: 'spline',
                            yAxis: 1,
                            data: data_array[1],
                            color: '#337ab7',
                            tooltip: {
                                valueSuffix: ' '
                            }

                        }, {
                            name: 'Revenue',
                            type: 'spline',
                            data: data_array[2],
                            color: '#d9534f',
                            marker: {
                                enabled: false
                            },
                            dashStyle: 'shortdot',
                            tooltip: {
                                valuePrefix: currencySymbol 
                            }

                        }]
                    });

            },
            error:function(error) {
                //alert("error");
            }

        });

        $("#loading_gif").hide();
    }


    $('#showbtn').on('click', function(){
        //alert('here');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        //console.log(from_date+'==='+to_date)
        if (from_date <= to_date){
            return true;
        }else{
            alert('ToDate must be greaterthan FromDate.')
            return false;
        }

    });


    var base_url = "{{ Request::root() }}";

    function setreportdata(data){

        var jsonoutput = $.parseJSON(data);

        var total_order_value=$('#total_order_value');
        var total_volume_value=$('#total_volume_value');
        var total_average_value=$('#total_average_value');
        var top_sell_val=$('#top_sell_val');
        var lowest_order_val=$('#lowest_order_val');
        var highest_order_val=$('#highest_order_val');

        total_order_value.text(jsonoutput.order_count);
        total_volume_value.text(jsonoutput.total);
        total_average_value.text(jsonoutput.average);
        top_sell_val.text(jsonoutput.item);
        lowest_order_val.text(jsonoutput.lowest_order);
        highest_order_val.text(jsonoutput.highest_order);
    }
</script>

@stop