<?php use Illuminate\Support\Facades\Auth;?>
@extends('partials.default')
@section('content')
    <div class="col-md-12">
        <?php $user=Auth::user();

            $userid=Auth::user()->id;
            if(!$user->hasRole('Admin')){
        $outlet=\App\Outlet::where('owner_id',$userid)->first();
            }?>

                {{--<div class="importdetails">--}}
                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachoutletdetails" data-whatever="">Import Other Details of Outlet</button>--}}
                    {{--<div class="modal fade" id="attachoutletdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
                        {{--<div class="modal-dialog">--}}
                            {{--<div class="modal-content">--}}
                                {{--<div class="modal-header">--}}
                                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                                    {{--<h4 class="modal-title" id="exampleModalLabel"></h4>--}}
                                {{--</div>--}}
                                {{--<div class="modal-body ">--}}

                                    {{--<div class="form-group">--}}
                                        {{--<div class="input-group input-group-lg mb15 wid-full">--}}
                                            {{--<span class="input-group-addon" style="display:none" ></span>--}}
                                            {{--{!! Form::open(array('url'=>'outlet/importoutletotherdetails','method'=>'POST', 'files'=>true)) !!}--}}
                                            {{--<div class="control-group">--}}
                                                {{--<div class="controls">--}}
                                                    {{--{!! Form::file('outletfile', array('multiple'=>true)) !!}--}}
                                                    {{--<p class="errors">{!!$errors->first('outletfile')!!}</p>--}}
                                                    {{--@if(Session::has('error'))--}}
                                                        {{--<p class="errors">{!! Session::get('error') !!}</p>--}}
                                                    {{--@endif--}}
                                                {{--</div>--}}
                                            {{--</div>--}}

                                        {{--</div>--}}
                                    {{--</div>--}}

                                {{--</div>--}}
                                {{--<div class="modal-footer">--}}
                                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('home.Close') }}</button>--}}

                                    {{--{!! Form::submit(trans('home.Submit'), array('class'=>'btn btn-primary')) !!}--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--<div class="media">--}}
                {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachmenu" data-whatever="">{{ trans('home.Import Outlet Details') }}</button>--}}
                {{--<div class="modal fade" id="attachmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
                    {{--<div class="modal-dialog">--}}
                        {{--<div class="modal-content">--}}
                            {{--<div class="modal-header">--}}
                                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                                {{--<h4 class="modal-title" id="exampleModalLabel"></h4>--}}
                            {{--</div>--}}
                            {{--<div class="modal-body ">--}}

                                {{--<div class="form-group">--}}
                                    {{--<div class="input-group input-group-lg mb15 wid-full">--}}
                                        {{--<span class="input-group-addon" style="display:none" ></span>--}}
                                        {{--{!! Form::open(array('url'=>'outlet/importdetailsexcel','method'=>'POST', 'files'=>true)) !!}--}}
                                        {{--<div class="control-group">--}}
                                            {{--<div class="controls">--}}
                                                {{--{!! Form::file('file1', array('multiple'=>true)) !!}--}}
                                                {{--<p class="errors">{!!$errors->first('images')!!}</p>--}}
                                                {{--@if(Session::has('error'))--}}
                                                    {{--<p class="errors">{!! Session::get('error') !!}</p>--}}
                                                {{--@endif--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                            {{--<div class="modal-footer">--}}
                                {{--<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('home.Close') }}</button>--}}
                                {{--<!--<button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('home.Submit') }}</button> -->--}}
                                 {{--{!! Form::submit(trans('home.Submit'), array('class'=>'btn btn-primary')) !!}--}}
                                {{--{!! Form::close() !!}--}}
                            {{--</div>--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
                {{--<div class="input-group input-group-lg mb15 wid-full" style="float:left;">--}}
                    {{--<span class="input-group-addon" style="display:none" ></span>--}}
                    {{--{!! Form::open(array('url'=>'outlet/exportdetailexcel','method'=>'POST')) !!}--}}
                    {{--<div class="control-group">--}}
                        {{--<div class="controls">--}}

                            {{--@if(Session::has('error'))--}}
                                {{--<p class="errors">{!! Session::get('error') !!}</p>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<button class="btn btn-primary" type="submit" class="exportexcel">{{ trans('home.Export Outlet Details') }}</button>--}}
                {{--</div>--}}


            <div class="media">
                {!! Form::open(array('url'=>'/ajax/dailyreport','method'=>'POST')) !!}
                <div class="col-md-10 form-group">
                    <input type="hidden" name="userid" class="form-control" placeholder="From Date"  id="userid" value="<?php echo Auth::user()->id;?>">
                {!! Form::select('reporttype', array('selecttype'=>'Select Report Type','summaryreport' => 'Summary Report', 'detailedreport' => 'Detailed Report'),'selecttype',array('class' => 'form-control','id'=>'reporttype')) !!}
                </div>
                <span class="input-group-addon" style="display:none" ></span>

                <div class="col-md-4 form-group">

                    <input type="text" name="from_date" class="form-control 12323" placeholder="From Date"  id="from_date" value="">
                </div>
                <div class="col-md-4 form-group">
                    <input type="text" name="to_date" class="form-control 12312" placeholder="To Date"  id="to_date" value="">
                </div>


                <div class="control-group">
                    <div class="controls">

                        @if(Session::has('error'))
                            <p class="errors">{!! Session::get('error') !!}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                <button id=exportsummaryreport class="btn btn-primary" type="submit" class="exportsummaryreport">{{ trans('home.Export Summary Report') }}</button>
                <button id=exportdetailedreport class="btn btn-primary" type="submit" class="exportdetailedreport" style="display:none;">{{ trans('home.Export Detailed Report')}}</button>
                    </div>

            </div>

    </div>


    <script>
            $("#reporttype").on('change',function(){
                    var selectedvalue=$('#reporttype').val();
                       if(selectedvalue=='summaryreport'){
                           $('#exportsummaryreport').css('display','block');
                           $('#exportdetailedreport').css('display','none');
                       }else{
                           $('#exportsummaryreport').css('display','none');
                           $('#exportdetailedreport').css('display','block');
                       }
                    });
            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd"
            });
            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd"
            });
    </script>



    <script type="text/javascript">
        $(document).ready(function(){
            var webSocket;
            var output = document.getElementById("notification_count");
            var appendorderid=document.getElementById("notificationsBody");




                // oprn the connection if one does not exist
                if (webSocket !== undefined
                        && webSocket.readyState !== WebSocket.CLOSED) {
                    return;
                }
                // Create a websocket
                webSocket = new WebSocket("ws://127.0.0.1:8993/notificationtest");

                webSocket.onopen = function(event) {
                    send(<?php echo $outlet;?>);
                };

                webSocket.onmessage = function(event) {
                    var json=$.parseJSON(event.data);
                    appendorderid.innerHTML='';
                    for(key in json["Order_id"]) {
                        if(json["Order_id"].hasOwnProperty(key)) {
                            console.log(json["Order_id"][key].order_id);
                            updateOutput(json["count"]);
                            appendorderid.innerHTML +="</br>You Have New Order Order Id :<a href='/ajax/currentorderdetails?order_id="+json["Order_id"][key].order_id+"&outlet_id=<?php echo $outlet->id; ?>'>"+json["Order_id"][key].order_id+"</a>";
                        }
                    }

                };

                webSocket.onclose = function(event) {
                    updateOutput("Connection Closed");

                };


            function send(message) {
                // Construct a msg object containing the data the server needs to process the message from the chat client.
                // Send the msg object as a JSON-formatted string.
                webSocket.send(JSON.stringify(message));

            }

            function closeSocket() {
                webSocket.close();
            }

            function updateOutput(text) {
                output.innerHTML='';

                output.innerHTML +=  text;

            }
        });


    </script>


    <script>

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-387525-15', 'auto');
        ga('send', 'pageview');

    </script>
@endsection
