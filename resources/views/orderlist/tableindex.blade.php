
@extends('partials.default_udp')
{{-- @section('pageHeader-left')
  Please Select {{ $order_lable }} 
@stop --}}

{{-- @section('pageHeader-right') --}}
    {{-- <a href="/tables/create" class="btn btn-primary"><i class="fa fa-plus"></i> {{ $order_lable }}</a> --}}
    {{-- <a href="/tables/create" class="btn btn-primary"><i class="fa fa-plus"></i> {{ $orderlist->order_id }}</a> --}}
{{-- @stop --}}

@section('content')
    <style>

        ul.box li {
            font-size: 30px;
            text-align: center;
            display: inline-block;
            width: 15%;
            background-color: #EEEEEE;
            /* padding: 5%; */
            border: 1px solid;
            margin: 5px;
            line-height: 35px;
            /* padding: 10px 0; */
            height: 125px;
            float: left;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            position: relative;
        }
        li.new button:hover,
         li.new.selected {
            color: #fff;
            /* background: #00a3a7 !important; */
        }
        li.new:hover{
            background: #00a3a7 !important;
            cursor: pointer;
        }
        li.active {
            /* background-color: #00a3a7 !important; */
               /* color: #000 !important; */
        }
        button.click {
            background: orange !important;
            color: black !important;
            font-size: 25px;
        }
        /* li.newclick {
            background: orange !important;
            cursor: pointer;
        } */
        /* li.newclick.active {
            background: orange !important;
        } */
        
        li.newclick.check {
            background: orange !important;
        }
        li.newclick.status {
            background: rgb(190, 32, 14) !important;
        }
        li.newclick:hover{
            /* background: #00a3a7 !important; */
            cursor: pointer;
        }
        button.closetable:hover{
            background: #00a3a7 !important;
            cursor: pointer;
            color: #fff;
            border: 1px solid;
        }
        button.closetable{
            border: 1px solid;
            background: white;
            line-height: normal;
            /* height: auto; */
            font-size: 14px;
            padding: 3px 15px;
            /* float: right; */
            margin-right: 2px;
            margin-top: 5%;
            border-color: white;
        }
        button.preview:hover{
            background: #00a3a7 !important;
            cursor: pointer;
            color: #fff;
            border: 1px solid;
        }
        button.preview{
            border: 1px solid;
            background: white;
            line-height: normal;
            /* height: auto; */
            font-size: 14px;
            padding: 3px 15px;
            /* float: right; */
            margin-right: 2px;
            margin-top: 5%;
            border-color: white;
        }
        button.opentable{
            width: 100%;
            /* height: 100%; */
            font-size: 90%;
            /* justify-content: center; */
            /* align-items: center; */
            /* float: left; */
            border: 0px;
            color: black;
            /* color: #8CC63E; */
            text-decoration: none;
            background-color: transparent;
            position: relative;
            line-height: 22px;
        }
        span.noofperson {
            display: inline-block;
            vertical-align: top;
            font-size: 18px;
            color: black;
            padding: 1px;
            /* top: 6%; */
            padding: 1px;
            position: absolute;
            top: 0;
            /* line-height: 25px; */
            right: 2px;
        }
        button.addorder {
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
            float: left;
            display: flex;
            border: 0px;
            color: #8CC63E;
            text-decoration: none;
            background-color: transparent;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="TablesTable">
                            <tbody>
                            @if(isset($tables) && !empty($tables) )
                            <ul class="box" id="buttons">
                                @foreach($tables as $tab)
                                            <?php $i=1;?>
                                                        @if (!empty($tab->totalprice))
                                                            <li class="newclick @if($i == 1 )active @endif @if($tab->status == 1 )status @endif @if(!empty($tab->totalprice) )check @endif" onclick="changeSelect(this)" >
                                                                <span style="display: inline-block; vertical-align: top; /* margin-bottom: 96px; */ color: black; padding: 1px; /* top: 0; */ position: absolute; padding: 1px;">{!! $tab->table_no !!}</span>
                                                                
                                                                <button class="opentable" class="click" @if($tab->status == 0 ) onclick="addorder(this,'{!! $tab->id !!}',{{ json_encode($tab) }})" @endif ><br> <span style="font-size: 65%;"> @if(!empty($tab->totalprice)) {!! number_format($tab->totalprice,3) !!} @endif</span><br><span style="font-size: 53%;">{{ $tab->startdate }}</span>
                                                                   
                                                                </button>
                                                                @if($tab->status == 1)
                                                                    <button class="closetable" onclick="closetable(this,'{!! $tab->id !!}',{{ json_encode($tab) }})" >Close</button>
                                                                    <button class="preview" onclick="preview(this,'{!! $tab->id !!}',{{ json_encode($tab) }})" >Preview</button>
                                                                @endif
                                                                <span class="noofperson">{{ $tab->person_no }} pax</span>
                                                            </li>
                                                        @else
                                                            <li class="new @if($i == 1 )active @endif" onclick="changeSelect(this)">
                                                                <button class="addorder" onclick="addorder(this,'{!! $tab->id !!}',{{ json_encode($tab) }})" href="#">{!! $tab->table_no !!}
                                                                </button>
                                                            </li>
                                                        @endif
                                            <?php $i++;?>
                                @endforeach
                            </ul>
                            @else
                                <tr>
                                   <td colspan="7" >No Table Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="append">

                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    {{-- <div id="openItemModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
        <div class="row mt30">
            <div class="col-sm-1">
                {!! Form::checkbox('datetime', 1, 1, ['class' => 'field','id'=>'date']) !!}
            </div>
            <label class="col-sm-2 control-label">Cash</label>


            <div class="col-sm-1">
                {!! Form::checkbox('invoice_no', 1, 1, ['class' => 'field','id'=>'invoice_no']) !!}
            </div>
            <label class="col-sm-2 control-label">Invoice No.</label>


            <div class="col-sm-1">
                {!! Form::checkbox('order_no', 1, 1, ['class' => 'field','id'=>'order_no']) !!}
            </div>
            <label class="col-sm-2 control-label">Order No.</label>
        </div>
    </div>
</div> --}}


<div id="openItemModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Table Close</h4>
            </div>

            <div class="modal-body ">
                <div class="col-sm-1">
                    <input type="checkbox" name="locationthemes" id="cash" value="cash" >
                </div>
                <label class="col-sm-2 control-label">Cash</label>
                <div class="col-sm-1">
                    <input type="checkbox" name="locationthemes" id="card" value="card">
                </div>
                <label class="col-sm-2 control-label">Card</label>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button type="button" id="update_btn" class="btn btn-primary" onclick="checkorder('add')"> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('orderlist.processOrderUdupi')
@include('orderlist.processKot')
@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/orderProcess.js"></script>  
    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif
        });
        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/tables/"+id+"/destroy"
                window.location.replace(route);
            }
        }
        function unoccupy(ele,id) {
            var temp = confirm("Confirm To Unoccupy?");
            if (temp == true) {
                var route = "/tables/"+id+"/unoccupy"
                window.location.replace(route);
            }
        }

        function addorder(ele,id,tab) {
             tno = tab.table_no;
             no_of_person = tab.no_of_person;
             console.log(tab.totalprice);
             console.log(id);
             orderid = tab.order_id;
             console.log(orderid);
             if(tab.totalprice == ''){ 
                console.log("here");
                var route = "/addorder/"+tno+""+"/"+no_of_person+""
                window.location.replace(route);
             }else{
                var route = "/editorders/"+orderid+"";
                window.location.replace(route);
                // console.log("here");
             }
           
           
        }

        function changeSelect(ele) {
        console.log(ele);
            $(".active").removeClass("active");
            $(ele).addClass("active");
        }

        var orderid = '';
        function checkorder()
        {
            // alert($('input[name="locationthemes"]:checked'));
                $('input[name="locationthemes"]:checked').each(function() {
                        $.ajax({
                        url: '/closeTable',
                        type: "post",
                        data: {
                            orderid: orderid,
                            value: this.value,
                        },
                        success: function(data) {
                            console.log(data);
                            window.location.href = "/table_index";
                        }
                    })
                });
               
        } 
        function closetable(ele,id,tab){
             orderid = tab.order_id;
             console.log(orderid);
             $('#openItemModal').modal('show');
        }

        function preview(ele,id,tab){
            orderid = tab.order_id;
            console.log(orderid);
            openOrderView(orderid, 'refresh');
        }
    </script>
@stop