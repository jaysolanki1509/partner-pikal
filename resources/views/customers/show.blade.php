<?php
use Illuminate\Support\Facades\Session;
?>
@extends('partials.default')
@section('pageHeader-left')
    @if( isset($customer) && !empty($customer) && $customer->first_name != '' )
        {{ $customer->first_name.' '.$customer->last_name }}
    @else
        {{ 'Unknown' }}
    @endif
@stop

@section('pageHeader-right')

@stop

@section('content')

    <?php $total_paid = 0; ?>
    @if( isset($orders) && sizeof($orders) > 0 )
        @foreach( $orders as $ord )
            <?php $total_paid += $ord->totalprice; ?>
        @endforeach
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="widget-wrap">
                <div class="widget-header block-header">
                    <h3>Customer Detail</h3>
                </div>
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Mobile :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->mobile_number != ''){{ $customer->mobile_number }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Name :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->first_name != ''){{ $customer->first_name }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Email :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->email != ''){{ $customer->email }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Address :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->address != ''){{ $customer->address }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>State :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->state != ''){{ $customer->state }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>City :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->city != ''){{ $customer->city }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Gender :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>{{ $customer->gender }}</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Date of Birth :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->dob != ''){{ $customer->dob }} @else {{ '-' }} @endif</span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Married :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>@if( $customer->married == 1 ){{ 'Yes' }}@else {{ 'No' }} @endif</span>
                            </div>
                        </div>
                        @if( $customer->married == 1 )
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <lable>Date of Anniversary :</lable>
                                </div>
                                <div class="col-md-8">
                                    <span>@if( $customer->doa != ''){{ $customer->doa }} @else {{ '-' }} @endif</span>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <lable>Spouse Name:</lable>
                                </div>
                                <div class="col-md-8">
                                    <span>@if( $customer->spouse_name != ''){{ $customer->spouse_name }} @else {{ '-' }} @endif</span>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <lable>Spouse Number:</lable>
                                </div>
                                <div class="col-md-8">
                                    <span>@if( $customer->spouse_number != ''){{ $customer->spouse_number }} @else {{ '-' }} @endif</span>
                                </div>
                            </div>
                        @endif


                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                                <lable>Total Paid :</lable>
                            </div>
                            <div class="col-md-8">
                                <span>{{ number_format($total_paid,2)}}</span>
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="widget-wrap">
                <div class="widget-header block-header margin-bottom-0 clearfix">
                    <div class="pull-left">
                        <h3>Item wise sales</h3>
                    </div>
                    <div class="pull-right w-action">
                        <ul class="widget-action-bar">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="zmdi zmdi-more"></i></a>
                                <ul class="dropdown-menu">
                                    {{--<li class="widget-reload"><a href="#"><i class="zmdi zmdi-refresh-alt"></i></a></li>--}}
                                    <li class="widget-toggle"><a href="#"><i class="zmdi zmdi-chevron-down"></i></a></li>
                                    <li class="widget-fullscreen"><a href="#"><i class="zmdi zmdi-fullscreen"></i></a></li>
                                    <li class="widget-exit"><a href="#"><i class="zmdi zmdi-power"></i></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="flotchart-container">
                                    <div id="piechart_itemwise" ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <div class="col-md-12">
                            <table class="table foo-data-table" id="orderTable" data-page-size="100" data-limit-navigation="4">
                                <thead>
                                <th data-sort-ignore="true">Invoice No.</th>
                                <th>Order Type</th>
                                <th data-sort-ignore="true" data-hide="phone">Discount</th>
                                <th>Total</th>
                                <th data-hide="phone">Date</th>
                                </thead>

                                <tbody>
                                @if( isset($orders) && sizeof($orders) > 0 )
                                    @foreach( $orders as $ord )
                                        <tr>
                                            <td><a href="javascript:void(0)" onclick="openOrderView({{ $ord->order_id }})">{!! $ord->invoice_no !!}</a></td>
                                            <td>{!! ucwords(str_replace('_',' ',$ord->order_type)) !!}</td>
                                            <td>{!! $ord->discount_value  !!}</td>
                                            <td align="right">{!! $ord->totalprice  !!}</td>
                                            <td>{!! date('Y-m-d H:i:s',strtotime($ord->table_end_date)) !!}</td>
                                        </tr>

                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        <div class="pagination pagination-centered"></div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div style="clear: both"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--print modal--}}
    <div id="printModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice</h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="close_type" />
                    <button type="button" class="btn btn-primary" onclick="print()">Print</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@stop
@section('page-scripts')

    {!! HTML::script('/js/highcharts.js') !!}
    {!! HTML::script('/js/highcharts-3d.js') !!}

    <script type="text/javascript">

       $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#orderTable").footable({
                phone:767,
                tablet:1024
            })

           getItemwiseSale();


        });

       function getItemwiseSale() {

           $("#loading_gif").show();

           var customer_id = '{{ $customer->id }}';

           $.ajax({
               url: '/customer-itemwise-sale',
               type: "POST",
               data: {customer_id: customer_id},
               success: function (data) {
                   //console.log(data);return;
                   $('#piechart_itemwise').highcharts({
                       chart: {
                           //plotBackgroundColor: null,
                           //plotBorderWidth: null,
                           //plotShadow: false,
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
                                   format: '₹ {point.y:.0f}',
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
                           data: getData(data.chart)
                       }]
                   });

               }
           });
       }

       function getData(data){
           var arr = [];
           for(var i=0;i<data.length;i++){
               arr.push({"name" : data[i].name,"y":parseFloat(data[i].y)});
           }
           return arr;

       }

       //open order to view
       function openOrderView( order_id ) {

           $('#printModal').modal('show');
           $('#printModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

           $.ajax({
               url: '/printorder',
               type: "GET",
               data: {order_id : order_id},
               success: function (data) {
                   $('#printModal .modal-body').html(data);
               },
               error:function(error) {
                   successErrorMessage('There is some error ocurred','error');
               }

           });

       }

    </script>
@stop