<?php
    use App\CuisineType;
    use App\Menu;


?>
@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Restaurant_Show.Outlet Detail') }}
@stop

@section('pageHeader-right')
    <a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    <link rel="stylesheet" href="../css/jquery-ui.css">

    <style>

        #lightbox .modal-content {
            display: inline-block;
            text-align: center;
        }


        #lightbox .close {
            opacity: 1;
            color: rgb(255, 255, 255);
            background-color: rgb(25, 25, 25);
            padding: 5px 8px;
            border-radius: 30px;
            border: 2px solid rgb(255, 255, 255);
            position: absolute;
            top: -15px;
            right: -55px;

            z-index:1032;
        }
        .container
        {
            position: absolute;
            top: 10%; left: 10%; right: 0; bottom: 0;
        }
        .action
        {
            width: 400px;
            height: 30px;
            margin: 10px 0;
        }
        .cropped>img
        {
            margin-right: 10px;
        }
        #map {
            width: 100%;
            height: 400px;
        }
        .option_fieldset{
            border: 1px solid #ccc;
            margin-bottom: 25px;
            margin-left: inherit;
            margin-right: inherit;
            margin-top: inherit;
            min-width: inherit;
            padding-bottom: 25px;
            padding-left: inherit;
            padding-right: inherit;
            padding-top: inherit;
        }


    </style>
    <style>
        ul.scrollmenu {
            background-color: #EEEEEE;
            overflow: auto;
            white-space: nowrap;
        }

        ul.scrollmenu li {
            display: inline-block;
            text-align: center;
        }
        li.active a{
            background-color: #8CC63E;
            color: #fff !important;
        }
        ul.scrollmenu a {
            color: #868686;
        }

    </style>
    <meta name="csrf-token" content="<?= csrf_token() ?>">

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('failure') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap ft-left" style="width: 100%;">
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="col-md-3">
                            @if(sizeof($image)>0)

                                <img class="img-offline img-responsive img-profile" src= "/{{$image}}"  alt="" >

                                <button style="margin:10px;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addprofileimage" data-whatever="">{{ trans('Restaurant_Show.Update Profile Image') }}</button>
                                <div class="modal fade" id="addprofileimage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="exampleModalLabel"></h4>
                                            </div>
                                            <div class="modal-body ">

                                                <div class="form-group" align="center">
                                                    <div class="input-group input-group-lg mb15 wid-full">
                                                        <span class="input-group-addon" style="display:none" ></span>
                                                        {!! Form::open(array('url'=>'apply/upload','method'=>'POST', 'files'=>true)) !!}
                                                        <input type="hidden" id="restau_id" name="restau_id" value="<?php echo $outlet['id'];?>"/>
                                                        <input type="hidden" name="raw_data" id="raw_data"/>
                                                        <div class="imageBox">
                                                            <div class="thumbBox"></div>

                                                        </div>
                                                        <div class="action">
                                                            <input type="file" id="file" name="image" style="float:left; width: 250px">
                                                            <input type="button" id="btnCrop" value="Crop" style="float: right">
                                                            <input type="button" id="btnZoomIn" value="+" style="float: right">
                                                            <input type="button" id="btnZoomOut" value="-" style="float: right">
                                                        </div>
                                                        <div class="cropped">

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                                                {!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}
                                                {!! Form::close() !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @else
                                <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#addprofileimage" data-whatever="">{{ trans('Restaurant_Show.Add Profile Image') }}</button>
                                <div class="modal fade" id="addprofileimage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="exampleModalLabel"></h4>
                                            </div>
                                            <div class="modal-body ">

                                                <div class="form-group" align="center">
                                                    <div class="input-group input-group-lg mb15 wid-full">
                                                        <span class="input-group-addon" style="display:none" ></span>
                                                        {!! Form::open(array('url'=>'apply/upload','method'=>'POST', 'files'=>true)) !!}
                                                        <input type="hidden" name="restau_id" value="<?php echo $outlet['id'];?>"/>
                                                        <input type="hidden" name="raw_data" id="raw_data"/>
                                                        <div class="imageBox">
                                                            <div class="thumbBox"></div>

                                                        </div>
                                                        <div class="action">
                                                            <input type="file" id="file" name="image" style="float:left; width: 250px">
                                                            <input type="button" id="btnCrop" value="Crop" style="float: right">
                                                            <input type="button" id="btnZoomIn" value="+" style="float: right">
                                                            <input type="button" id="btnZoomOut" value="-" style="float: right">
                                                        </div>
                                                        <div class="cropped">

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                                                {!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}
                                                {!! Form::close() !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            <h4 class="profile-name mb5">{{$outlet['name'] or ''}}</h4>
                            <div><i class="fa fa-map-marker"></i> {{  $outlet['address'] or ''}}</div>
                            <div class="mb30"></div>

                        </div>

                        <div class="col-md-9 col-sm-9" id="outlet_div">
                            <div class="scrollmenu">
                                <ul class="nav material-tabs scrollmenu" role="tablist">

                                    <li class="active" ><a data-toggle="tab" href="#info"><strong>{{ trans('Restaurant_Show.Information') }}</strong></a></li>
                                    {{--<li class="" ><a href="#images"><strong>{{ trans('Restaurant_Show.Images') }}</strong></a></li>--}}
                                    <li class="" ><a href="#printers"><strong> Printers </strong></a></li>
                                    <li class="" ><a href="#appsettings"><strong> App Settings </strong></a></li>
                                    <li class="" ><a href="#paymentoption"><strong> Payment Option </strong></a></li>
                                    @if($outlet['zoho_config'])
                                        <li class="" ><a href="#paymentoptionids"><strong> Zoho Config </strong></a></li>
                                    @endif
                                    <li class="" ><a href="#taxes"><strong> Taxes </strong></a></li>
                                    <li class="" ><a href="#tax_detail"><strong> Tax Details </strong></a></li>
                                    <li class="" ><a href="#delivery_charge"><strong> Delivery Charge </strong></a></li>
                                    <li class="" ><a href="#timeslots"><strong> Time Slots </strong></a></li>
                                    <li class="" ><a href="#appview"><strong> App View </strong></a></li>

                                </ul>
                            </div>



                            <div class="tab-content">

                                <div id="info" class="tab-pane active">
                                    <div class="container-fluid">
                                        <ul class="list-group">

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Active') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(isset($outlet['active']) && $outlet['active'] !='')
                                                            <?php echo $outlet['active'];?>
                                                        @else
                                                            <?php echo '';?>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Country') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(sizeof($countries)>0 && isset($countries))
                                                            @foreach($countries as $country)
                                                                @if(isset($country->id)){{$country->name}}@endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.State') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(sizeof($states)>0)
                                                            @foreach($states as $state)
                                                                @if(isset($state->id))
                                                                    {{$state->name}}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.City') }}</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if(sizeof($cities)>0)
                                                            @foreach($cities as $city)
                                                                @if(isset($city->id))
                                                                    {{$city->name}}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Locality') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(isset($outlet['locality']) && $outlet['locality'] !=0 && $outlet['locality'] !='')
                                                            {{ $locality['locality'] }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Pincode') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div>@if(isset($outlet['pincode'])){{$outlet['pincode']}}@endif</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Famous For') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div>@if(isset($outlet['famous_for'])){{$outlet['famous_for']}}@endif</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Outlet Type') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(sizeof($Outlettype)>0)
                                                            @foreach($Outlettype as $rest_type)
                                                                <?php $rest=\App\OutletType::where('id',$rest_type->outlet_type_id)->first(); echo $rest->type; ?>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Cuisine Type') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(sizeof($cuisinetypes)>0)
                                                            @foreach($cuisinetypes as $cuisinetype)
                                                                <?php $test=\App\CuisineType::where('id',$cuisinetype->cuisine_type_id)->first(); echo $test->type; ?>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <?php $servicetype=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Service Type') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?php $restservicetype=unserialize($outlet['service_type']);?>
                                                        @if(isset($restservicetype) && $restservicetype!="")
                                                            @foreach($service_type as $res)
                                                                <?php echo $servicetype[$res];?>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Contact Number') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        {{$outlet['contact_no'] or ''}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">Delivery Numbers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        {{$outlet['delivery_numbers'] or ''}}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                       <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Email-Id') }}</label>
                                                   </div>
                                                   <div class="col-sm-6">
                                                        {{$outlet['email_id'] or ''}}
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Web Address') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        {{$outlet['url'] or ''}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Establishment Date') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(isset($established_date))
                                                            {{$established_date}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{--<div class="form-group">--}}
                                                {{--<div class="row">--}}
                                                    {{--<div class="col-md-6">--}}
                                                        {{--<label class="col-md-12 control-label">{{ trans('Restaurant_Show.Timing') }}</label>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-6">--}}
                                                        {{--@foreach($timeslot as $time)--}}
                                                            {{--From  {{$time->from_time}} To {{$time->to_time}}<br/>--}}
                                                        {{--@endforeach--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Latitude') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(isset($outlet['lat']) && $outlet['lat'] !=0)
                                                            {{$outlet['lat']}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-md-12 control-label">{{ trans('Restaurant_Show.Longitude') }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        @if(isset($outlet['long'])&& $outlet['long']!=0){{$outlet['long']}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <form class="form-horizontal material-form j-forms">

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="address" placeholder="Address"/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-success" id="findaddress">
                                                            {{ trans('Restaurant_Show.Locate') }}
                                                        </button>
                                                    </div>
                                                </div>
                                                <br/>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <div id="map" style="width:400px;height:300px;">

                                                </div>

                                            </form>

                                        </ul>
                                    </div>
                                </div>

                                <div id="printers" class="tab-pane">

                                    <form method="post" id="updateSetting" class="form" name="updateSetting">
                                        <input type="hidden" id="outlet_id" name="outlet_id" value="<?php echo $outlet['id'];?>"/>
                                        <div class="widget-content">
                                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="SettingsTable">
                                                <thead></thead>
                                                <tbody>

                                                    <tr>
                                                        <td>Kot Printer</td>
                                                        <?php if (isset($outlet['printer'])) $kot = isset(json_decode($outlet['printer'])->kot_printer)?json_decode($outlet['printer'])->kot_printer:"";  ?>
                                                        <td>
                                                            {!! Form::select('kot_printer',$printers,isset($kot)?$kot:null,array('class' => 'ddprinter form-control','id'=>'kot_printer','style'=>"width: 100%;")) !!}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Duplicate Kot Printer</td>
                                                        <?php if (isset($outlet['printer'])) $duplicate_kot = isset(json_decode($outlet['printer'])->duplicate_kot_printer)?json_decode($outlet['printer'])->duplicate_kot_printer:"";  ?>
                                                        <td>
                                                            {!! Form::select('duplicate_kot_printer',$printers,isset($duplicate_kot)?$duplicate_kot:null,array('class' => 'ddprinter form-control','id'=>'duplicate_kot_printer','style'=>"width: 100%;")) !!}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Bill Printer</td>
                                                        <?php if (isset($outlet['printer'])) $bill = isset(json_decode($outlet['printer'])->bill_printer)?json_decode($outlet['printer'])->bill_printer:""; ?>
                                                        <td> {!! Form::select('bill_printer',$printers,isset($bill)?$bill:null,array('class' => 'ddprinter form-control','id'=>'bill_printer','style'=>"width: 100%;")) !!} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Stock Response Printer</td>
                                                        <?php

                                                                $res_printer = '';
                                                                if (isset($outlet['printer'])){
                                                                    $res_prnt = json_decode($outlet['printer']);
                                                                    if ( isset($res_prnt) && sizeof($res_prnt) > 0) {
                                                                        if ( isset($res_prnt->response_printer)) {
                                                                            $res_printer = $res_prnt->response_printer;
                                                                        } else {
                                                                            $res_printer = '';
                                                                        }
                                                                    }
                                                                }

                                                        ?>
                                                        <td> {!! Form::select('response_printer',$printers,isset($res_printer)?$res_printer:null,array('class' => 'ddprinter form-control','id'=>'response_printer','style'=>"width: 100%;")) !!} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>No. Of Duplicate Kot</td>
                                                        <td>
                                                            {!! Form::select('duplicate_kot_count',array('0'=>'Select','1'=>'1','2'=>'2'),$dp_kot_count,array('class' => 'ddprinter form-control','id'=>'duplicate_kot_count','style'=>"width: 100%;")) !!}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12" align="right">
                                            <button type="button" onclick="updateSettings()" id="add-btn" class="btn btn-success primary-btn">Update</button>
                                        </div>

                                    </form>

                                </div>

                                <div id="appsettings" class="tab-pane">

                                    <form method="post" id="updateMasterSetting" class="form-horizontal material-form j-forms" name="updateMasterSetting">

                                        <div class="widget-content">
                                            <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="SettingsTable1">
                                                <thead>

                                                </thead>
                                                <tbody>
                                                    <?php $setting_cnt = 1;?>
                                                    @foreach( $settings as $setting )

                                                        @if( $setting_cnt % 2 != 0 || $setting_cnt == 1 )
                                                            <tr class="odd gradeX">
                                                        @endif
                                                                @if( $setting['setting_value'] == 'false' )
                                                                    <?php $check = 'false'; ?>
                                                                @else
                                                                    <?php $check = 'true'; ?>
                                                                @endif
                                                                <td @if($setting['setting_name'] == "deleteYesterdayOrders") class="hide" @else class="col-xs-12 col-md-6 col-lg-6 pull-left" @endif>

                                                                    @if($setting['setting_name'] != "allowFloatQty")
                                                                            <label class="checkbox">
                                                                                <input onchange="checkValidSettings(this)" data-name="{{ $setting['setting_org_name'] }}" type="checkbox" name="settings[{{$setting['id']}}]" value='true' @if($setting['setting_value']=='true') checked='true' @endif>&nbsp;&nbsp;
                                                                                <i></i>{{ $setting['setting_name'] }}
                                                                            </label>
                                                                    @else
                                                                        {{-- Temp solution for gwaliya floating qty allow --}}
                                                                        <label class="checkbox hide col-md-6">
                                                                            <input onchange="checkValidSettings(this)" data-name="{{ $setting['setting_org_name'] }}" type="checkbox" name="settings[{{$setting['id']}}]" value='true' @if($setting['setting_value']=='true') checked='true' @endif>&nbsp;&nbsp;
                                                                            <i></i>{{ $setting['setting_name'] }}
                                                                        </label>
                                                                    @endif
                                                                </td>


                                                        @if( $setting_cnt % 2 == 0 )
                                                            </tr>
                                                        @endif

                                                        <?php $setting_cnt++;?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12" align="right">
                                            <button type="button" onclick="updateMasterSettings()" id="add-btn" class="btn btn-success" >Update</button>
                                        </div>

                                    </form>
                                </div>

                                <div id="timeslots" class="tab-pane">

                                    <?php  $m=0; ?>
                                    <div class="row">
                                        <form  class="form-horizontal material-form j-forms" role="form" id="timeSlotsCreate" name="timeSlotsCreate">
                                            <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                            <div class="col-md-12">

                                                <?php $i=0; ?>
                                                <div id="appendtimeslots">
                                                </div>

                                                <div class="col-md-9 form">
                                                    <div class="col-md-3 input-append bootstrap-timepicker ">

                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="closing_time" class="control-label"></label>
                                                    </div>
                                                    <div class="col-md-3 input-append bootstrap-timepicker ">

                                                    </div>

                                                </div>
                                                <?php $i++; ?>

                                                {!!Form::hidden('countf',$i,array('id'=>'countf'))!!} <!-- pre added timeslot count -->
                                                <div class="col-md-12 form">
                                                    <div id="myID" class="col-md-3 form">
                                                        <input type="button" id="btn_myid" value="Add More Session" class="btn btn-primary mr5">
                                                        {{--</div>
                                                        <div class="col-md-9 form">--}}
                                                    </div>
                                                    <button class="btn btn-primary mr5" onclick="updateTimeSlots()"  id="Submit" style="margin-left: 10px;" novalidate="novalidate" type="button" >{{ trans('Restaurant_Form.Submit') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                                <div id="appview" class="tab-pane">

                                    <?php  $m=0; ?>
                                    <div class="row">
                                        <form  class="form-horizontal material-form j-forms" role="form" id="applayout" name="applayout">
                                            <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                            <div class="col-md-12">

                                                <div class="col-md-6 form">
                                                    <label class="radio">
                                                        <input type="radio" name="app_layout" id="app_layout" value="tabbed_layout" @if(isset($outlet['app_layout'])  && $outlet['app_layout'] == "tabbed_layout") checked="true" @endif>
                                                        <i></i> Tabbed Layout
                                                    </label>

                                                </div>
                                                <div class="col-md-6 form">
                                                    <label class="radio">
                                                        <input type="radio" name="app_layout" id="app_layout" value="category_layout" @if(isset($outlet['app_layout'])  && $outlet['app_layout'] == "category_layout") checked="true" @endif>
                                                        <i></i> Category Layout
                                                    </label>
                                                </div>

                                                <div class="col-md-12 form">
                                                    <button class="btn btn-primary mr5" onclick="updateAppView()"  id="Submit" style="margin-left: 10px;" novalidate="novalidate" type="button" >{{ trans('Restaurant_Form.Submit') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                                <div id="paymentoption" class="tab-pane">
                                    <div class="mb30"></div>
                                    <div class="activity-list vehicle-list">

                                        <div class="row">
                                            <form class="form-horizontal material-form j-forms"  id="paymentOptionform" name="paymentOptionform">
                                                <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                                <div class="col-md-12">

                                                    <?php $options =  json_decode($outlet['payment_options'],TRUE); ?>

                                                    @if(isset($options) && sizeof($options)>0)

                                                        @foreach($payment_options as $po_id=>$option)

                                                            @if(!in_array($po_id,$payment_options_without_source))

                                                                @if( isset($options) && array_key_exists($po_id,$options))
                                                                    <div class="option_fieldset" name="clubfields{{$po_id}}">
                                                                @else
                                                                    <div class="option_fieldset" name="clubfields{{$po_id}}" disabled>
                                                                @endif
                                                            @else
                                                                <div name="clubfields{{$po_id}}" disabled>
                                                            @endif

                                                            <legend>
                                                                <label>
                                                                    @if( isset($options) && array_key_exists($po_id,$options))
                                                                        <label class="checkbox">
                                                                            <input type="checkbox" id="chk_payment_option" name="{{$po_id}}" checked="checked" value="{{$po_id}}" >
                                                                            <i></i><b>{{$option}}</b>
                                                                        </label>
                                                                    @else
                                                                        <label class="checkbox">
                                                                            <input type="checkbox" id="chk_payment_option" name="{{$po_id}}" value="{{$po_id}}" >
                                                                            <i></i><b>{{$option}}</b>
                                                                        </label>
                                                                    @endif
                                                                </label>
                                                            </legend>

                                                            @if(!in_array($po_id,$payment_options_without_source))
                                                                <table width="100%">
                                                                    <?php $i=0; ?>
                                                                    @foreach($sources as $s_id=>$source)
                                                                        @if(strtolower($option) == 'cash')
                                                                            @if(strtolower($source) != 'paytm' && strtolower($source) != 'upi' &&
                                                                            strtolower($source) != 'card' && strtolower($source) != 'bhim' )

                                                                                @if(isset($options[$po_id]) && sizeof($options[$po_id])>0)
                                                                                    @if( is_array($options[$po_id]) )
                                                                                        @if(in_array($s_id,$options[$po_id]))
                                                                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                            <label class="checkbox">
                                                                                                <input type="checkbox" checked="checked" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                                <i></i>{{$source}}
                                                                                            </label>
                                                                                            <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                        @else
                                                                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                            <label class="checkbox">
                                                                                                <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                                <i></i>{{$source}}
                                                                                            </label>
                                                                                            <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                        @endif
                                                                                    @else
                                                                                        @if( $s_id == $options[$po_id] )
                                                                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                            <label class="checkbox">
                                                                                                <input type="checkbox" checked="checked" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                                <i></i>{{$source}}
                                                                                            </label>
                                                                                            <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                        @else
                                                                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                            <label class="checkbox">
                                                                                                <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                                <i></i>{{$source}}
                                                                                            </label>
                                                                                            <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                        @endif
                                                                                    @endif
                                                                                @else
                                                                                    <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                    <label class="checkbox">
                                                                                        <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                        <i></i>{{$source}}
                                                                                    </label>
                                                                                    <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if(isset($options[$po_id]) && sizeof($options[$po_id])>0)
                                                                                @if( is_array($options[$po_id]) )
                                                                                    @if(in_array($s_id,$options[$po_id]))
                                                                                        <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                        <label class="checkbox">
                                                                                            <input type="checkbox" checked="checked" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        </label>
                                                                                        <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                    @else
                                                                                        <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                        <label class="checkbox">
                                                                                            <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        </label>
                                                                                        <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                    @endif
                                                                                @else
                                                                                    @if( $s_id == $options[$po_id])
                                                                                        <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                        <label class="checkbox">
                                                                                            <input type="checkbox" checked="checked" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        </label>
                                                                                        <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                    @else
                                                                                        <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                        <label class="checkbox">
                                                                                            <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        </label>
                                                                                        <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                    @endif
                                                                                @endif

                                                                            @else
                                                                                <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                <label class="checkbox">
                                                                                    @if(in_array(strtolower($source),["payu","paytm","upi"]))
                                                                                        <input type="checkbox" checked="checked" disabled="disabled" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                        <i></i>{{$source}}
                                                                                    @else
                                                                                        <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                        <i></i>{{$source}}
                                                                                    @endif
                                                                                </label>
                                                                                <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </table>
                                                            @endif
                                                            </div>
                                                        @endforeach

                                                    @else

                                                        @foreach($payment_options as $po_id=>$option)
                                                            @if(!in_array($po_id,$payment_options_without_source))
                                                                @if(strtolower($option) == 'cash')
                                                                    <div class="option_fieldset" name="clubfields{{$po_id}}">
                                                                @else
                                                                    <div class="option_fieldset" name="clubfields{{$po_id}}" disabled>
                                                                @endif
                                                            @else
                                                                <div style="margin-left: 15px;" name="clubfields{{$po_id}}" disabled>
                                                            @endif
                                                                <legend>
                                                                    <label class="checkbox">
                                                                        @if(strtolower($option) == 'cash')
                                                                            <input type="checkbox" name="{{$po_id}}" checked="checked" value="{{$po_id}}" >
                                                                            <i></i>{{$option}}
                                                                        @else
                                                                            <input type="checkbox" name="{{$po_id}}" value="{{$po_id}}" >
                                                                            <i></i>{{$option}}
                                                                        @endif
                                                                    </label>
                                                                </legend>
                                                                @if(!in_array($po_id,$payment_options_without_source))
                                                                    <table width="100%">
                                                                        <?php $i=0; ?>
                                                                        @foreach($sources as $s_id=>$source)
                                                                            @if(strtolower($option) == 'cash')
                                                                                @if(strtolower($source) != 'paytm' && strtolower($source) != 'upi' &&
                                                                                        strtolower($source) != 'card' && strtolower($source) != 'bhim' )
                                                                                        <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                                    <label class="checkbox">
                                                                                                        <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                                        <i></i>{{$source}}
                                                                                                    </label>
                                                                                                <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                                @endif
                                                                            @elseif(strtolower($option) == 'online')
                                                                                <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                    <label class="checkbox">
                                                                                        @if(in_array(strtolower($source),["payu","paytm","upi"]))
                                                                                            <input type="checkbox" checked="checked" disabled="disabled" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        @else
                                                                                            <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                            <i></i>{{$source}}
                                                                                        @endif
                                                                                    </label>
                                                                                <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                            @else
                                                                                <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                                                    <label class="checkbox">
                                                                                        <input type="checkbox" name="{{$po_id}}[]" value={{$s_id}} >
                                                                                        <i></i>{{$source}}
                                                                                    </label>
                                                                                <?php if(($i%4)==3) { ?> </tr></td> <?php }else{ ?> </td> <?php } $i++; ?>
                                                                            @endif

                                                                        @endforeach
                                                                    </table>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                    @endif
                                                    <div class="form-group">
                                                        <button class="btn btn-success primary-btn pull-right" onclick="updatePaymentMode()"  id="payment_mode_btn" style="margin-left: 10px;" type="button" >Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="paymentoptionids" class="tab-pane">
                                    <div class="mb30"></div>
                                    <div class="activity-list vehicle-list">

                                        <div class="row">
                                            <form class="form-horizontal material-form j-forms"  id="paymentOptionidform" name="paymentOptionidform">
                                                <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                                <div class="col-md-12">

                                                        <table width="100%" style="margin-bottom: 20px;">
                                                            <tr>
                                                                <td>Username </td>
                                                                <td><input class="form-control tx-value" value="{{$zoho_username}}" placeholder="" type="text" name="zoho_username"> </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Password </td>
                                                                <td><input class="form-control tx-value" value="{{$zoho_password}}" placeholder="" type="text" name="zoho_password"> </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Organization ID </td>
                                                                <td><input class="form-control tx-value" value="{{$zoho_org_id}}" placeholder="" type="text" name="zoho_organization_id"> </td>
                                                            </tr>
                                                        </table>

                                                    <hr>

                                                    @if(isset($outlet['payment_options']) && sizeof($outlet['payment_options'])>0)
                                                        <table width="100%" style="margin-bottom: 20px;">
                                                            <?php $options =  json_decode($outlet['payment_options'],TRUE); ?>
                                                            @if(isset($options) && sizeof($options)>0)
                                                                @foreach($payment_options as $po_id=>$option)
                                                                    @if(array_key_exists($po_id,$options))

                                                                        <tr>
                                                                            <td width="25%" height="10px">{{$option}}</td>
                                                                            <td width="75%">
                                                                                <input required class="form-control tx-value" value="{{isset($zoho_ids['zoho_payment_ids'][$po_id][0])?$zoho_ids['zoho_payment_ids'][$po_id][0]:''}}" placeholder="" type="text" name="zoho_payment_ids[{{$po_id}}][0]">
                                                                            </td>
                                                                        </tr>
                                                                        @if(isset($sources) && sizeof($sources)>0)
                                                                            @foreach($sources as $s_id=>$source)
                                                                                @if(isset($options[$po_id]))

                                                                                    @if(is_array($options[$po_id]))
                                                                                        @if(in_array($s_id,$options[$po_id]))
                                                                                            <tr>
                                                                                                <td width="25%" height="10px"><span>{{$option}}-{{$sources[$s_id]}}</span></td>
                                                                                                <td width="75%" >
                                                                                                    <input type="text" value="{{isset($zoho_ids['zoho_payment_ids'][$po_id][$s_id])?$zoho_ids['zoho_payment_ids'][$po_id][$s_id]:''}}" required class="form-control tx-value" placeholder="" name="zoho_payment_ids[{{$po_id}}][{{$s_id}}]">
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endif

                                                                                @endif
                                                                            @endforeach
                                                                        @endif

                                                                    @endif
                                                                @endforeach

                                                                <td width="25%" height="10px">Unpaid</td>
                                                                <td width="75%">
                                                                    <input required class="form-control tx-value" value="{{isset($zoho_ids['zoho_payment_ids'][0][0])?$zoho_ids['zoho_payment_ids'][0][0]:''}}" placeholder="" type="text" name="zoho_payment_ids[0][0]">
                                                                </td>

                                                            @endif
                                                    </table>
                                                    @else
                                                        No Payment Options are added.
                                                    @endif
                                                    <div class="form-group">
                                                        <button class="btn btn-success primary-btn pull-right" onclick="updatePaymentId()"  id="payment_id_btn" style="margin-left: 10px;" type="button" >Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="images" class="tab-pane">
                                    <div class="media">
                                        <button type="button" style="float:right;margin:10px;" class="btn btn-primary" data-toggle="modal" data-target="#attachdocuments" data-whatever="">{{ trans('Restaurant_Show.Add Images') }}</button>
                                        <div class="modal fade" id="attachdocuments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="exampleModalLabel"></h4>
                                                    </div>
                                                    <div class="modal-body ">

                                                        <div class="form-group">
                                                            <div class="input-group input-group-lg mb15 wid-full">
                                                                <span class="input-group-addon" style="display:none" ></span>
                                                                {!! Form::open(array('url'=>'apply/multiple_upload','method'=>'POST', 'files'=>true)) !!}
                                                                <div class="control-group">
                                                                    <div class="controls">
                                                                        <input type="hidden" name="restau_id" value="<?php echo $outlet['id'];?>"/>
                                                                        {!! Form::file('images[]', array('multiple'=>true)) !!}

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                                                       <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('Restaurant_Show.Submit') }}</button> -->
                                                        {!! Form::submit(trans('Restaurant_Show.Submit'), array('class'=>'btn btn-primary')) !!}
                                                        {!! Form::close() !!}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($Outletimages as $rest)
                                        <?php $firstImage = true;?>

                                        <div class="col-md-3" style="padding-bottom: 10px;">
                                            {!! Form::open(array('url'=>'apply/destroy','method'=>'POST')) !!}
                                            <a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="height:210px;">

                                                    <input type="hidden" name="Outletimage_id" value="<?php echo $rest->id?>"/>
                                                <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $rest->outlet_id?>"/>
                                                <input type="hidden" name="image_name" value="<?php echo $rest->image_name?>"/>

                                            <img src="/uploads/{{ $rest->image_name }}" style="object-fit:cover;height:100%;" >
                                            </a>
                                            {!! Form::submit(trans('Restaurant_Show.delete'), array('class'=>'btn btn-danger')) !!}
                                            {!! Form::close() !!}
                                        </div>

                                            <?php $firstImage = false; ?>
                                    @endforeach

                                    <div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="taxes" class="tab-pane j-forms" >

                                    <?php

                                        $taxx = '';
                                        if ( isset($outlet['taxes']) && $outlet['taxes'] != '' ) {
                                            $taxx = json_decode($outlet['taxes'],true);
                                        }

                                        $dine_in = $take_away = $home_delivery = 'select';

                                        if ( isset($outlet['default_taxes']) &&  $outlet['default_taxes'] != '' ) {

                                            $d_taxes = json_decode($outlet['default_taxes'],true);

                                            $dine_in = $d_taxes[0]['dine_in'];
                                            $take_away = $d_taxes[0]['take_away'];
                                            $home_delivery = $d_taxes[0]['home_delivery'];
                                        }

                                    ?>

                                    @if( isset($taxx) && $taxx != '')

                                        <div class="form-group col-md-12">
                                            <div class="col-md-2">
                                                <label class="control-label">Dine In</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control tax-type" id="dine_in">
                                                    <option value="select">Select Tax</option>
                                                    @foreach( $taxx as $tx_key=>$tx_val )
                                                        <option @if( $tx_key == $dine_in ) selected="selected" @endif value="{{ $tx_key }}">{{ $tx_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="col-md-2">
                                                <label class="control-label">Take Away</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control tax-type" id="take_away">
                                                    <option value="select">Select Tax</option>
                                                    @foreach( $taxx as $tx_key=>$tx_val )
                                                        <option @if( $tx_key == $take_away ) selected="selected" @endif value="{{ $tx_key }}">{{ $tx_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="col-md-2">
                                                <label class="control-label">Home Delivery</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control tax-type" id="home_delivery">
                                                    <option value="select">Select Tax</option>
                                                    @foreach( $taxx as $tx_key=>$tx_val )
                                                        <option @if( $tx_key == $home_delivery ) selected="selected" @endif value="{{ $tx_key }}">{{ $tx_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="form-footer pull-right">
                                            <button class="btn btn-primary" onclick="saveTax()"  id="save_tax_btn" type="button" > Submit </button>
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <h3 class="" style="text-align:center">Please add tax</h3>
                                        </div>
                                        <div class="col-md-12" style="text-align:center">
                                            <span><a href="/taxes" >Click Here</a></span>
                                        </div>
                                    @endif
                                </div>

                                <div id="tax_detail" class="tab-pane j-forms">
                                    <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                    <div id="tax_detail_content">
                                        <div id="tx_detail_label" class="form-group">
                                            <!-- Delivery lable -->
                                            <div class="col-md-4">
                                                <label class="control-label"><b>Field Lable</b></label>
                                            </div>
                                            <div class="col-md-1">
                                                <span></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label"><b>Field Value</b></label>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                        </div>
                                        <hr class="col-md-12" style="margin-top: 10px;">
                                        <div style="clear:both"></div>
                                        <div id="tax_details_field" class="form-group">
                                            <!-- Delivery charge field -->
                                            @if( isset($tax_details) && $tax_details != '' && sizeof($tax_details) > 0 )
                                                @foreach( $tax_details[0] as $tx_field=>$tx_val )
                                                    <div class="tx-field-div">
                                                        <div class="col-md-4 form-group">
                                                           <input type="text" placeorder="Field lable" class="form-control tx-field" value="{{ $tx_field }}">
                                                        </div>
                                                        <div class="col-md-1 form-group">
                                                            <span> = </span>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <input type="text" placeorder="Field value" class="form-control tx-value" value="{{ $tx_val }}">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <button class="btn btn-danger rm-tax" onclick="removeTaxDetail(this)"><i class="fa fa-times"></i></button>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            @else
                                                <span id="tx_detail_msg">No tax detail added.</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div style="clear:both"></div>
                                    <hr>
                                    <span class="err-msg pull-left tax-detail-err" style="color: red"></span>

                                    <div class="form-footer pull-right">
                                        <button class="btn btn-success" onclick="addTaxDetailSlab()"  id="add_tx_btn" type="button" ><i class="fa fa-plus"></i> Tax Detail </button>
                                        <button class="btn btn-success" onclick="saveTaxDetail()" id="save_tx_btn" type="button"> Submit </button>
                                    </div>

                                </div>

                                <div id="delivery_charge" class="tab-pane j-forms" >
                                    <input type="hidden" name="outlet_id" id="outlet_id" value="<?php echo $outlet['id']?>"/>
                                    <div id="delivery_charge_content">
                                        <div id="charge_label" class="form-group">
                                            <!-- Delivery lable -->
                                            <div class="col-md-4">
                                                <label class="control-label"><b>Order Amount</b></label>
                                            </div>
                                            <div class="col-md-1">
                                                <span></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label"><b>Delivery Charge</b></label>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                        </div>
                                        <hr class="col-md-12" style="margin-top: 10px;">
                                        <div style="clear:both"></div>
                                        <div id="charge_field" class="form-group">
                                            <!-- Delivery charge field -->
                                            <?php $order_val = "temp"; ?>
                                            @if( isset($delivery_charge) && $delivery_charge != '' && sizeof($delivery_charge) > 0 )
                                                @foreach( $delivery_charge[0] as $ord_val=>$chrg )
                                                    <div class="field-div">
                                                        <div class="col-md-4 form-group">
                                                            @if($ord_val == "Fixed")
                                                                <?php $order_val = $ord_val; ?>
                                                                <input type="text" disabled placeorder="Order Amount" class="form-control order_amt" value="{{ $ord_val }}">
                                                            @else
                                                                <input type="text" placeorder="Order Amount" class="form-control order_amt" value="{{ $ord_val }}">
                                                            @endif
                                                            </div>
                                                        <div class="col-md-1 form-group">
                                                            <span> = </span>
                                                            </div>
                                                        <div class="col-md-4 form-group">
                                                            <input type="text" placeorder="Delivery Charge" class="form-control delivery_charge" value="{{ $chrg }}">
                                                        </div>
                                                        @if($ord_val != "Fixed")
                                                            <div class="col-md-3 form-group">
                                                                <button class="btn btn-danger rm-tax" onclick="removeCharge(this)"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        @endif
                                                        </div>
                                                @endforeach
                                            @else
                                                <span id="empty_msg">No Charge slab added.</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div style="clear:both"></div>
                                    <hr>
                                    <span class="err-msg pull-left charge-err" style="color: red"></span>

                                    <div class="form-footer pull-left">
                                        <label class="checkbox">
                                            @if($order_val == "Fixed")
                                                <input type="checkbox" id="fixed_rate" name="vehicle" checked="checked" value="fixed_rate" onchange="addFixChargeSlab()"/>
                                            @else
                                                <input type="checkbox" id="fixed_rate" name="vehicle" value="fixed_rate" onchange="addFixChargeSlab()"/>
                                            @endif
                                            <i></i>Fixed Rate
                                        </label>
                                    </div>

                                    <div class="form-footer pull-right">
                                        @if($order_val == "Fixed")
                                            <button class="btn btn-success" disabled="disabled" onclick="addChargeSlab()"  id="add_charge_btn" type="button" ><i class="fa fa-plus"></i> Charge Slab </button>
                                        @else
                                            <button class="btn btn-success" onclick="addChargeSlab()"  id="add_charge_btn" type="button" ><i class="fa fa-plus"></i> Charge Slab </button>
                                        @endif
                                        <button class="btn btn-success" onclick="savecharge()" id="charge_btn" type="button"> Submit </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')

    <script src="../js/CropBox.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script>
        $(function() {
            $( "#accordion" ).accordion();
            $('.tax-type').select2();
        });
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIjzsD2aEd6GJc2eBb6i0cRXkOZ5KHD5g&amp;sensor=false&amp;libraries=places"></script>
    <script>

        //upate valid settings
        function checkValidSettings(el) {

            var disc_after_tax = $("#appsettings").find("[data-name='discountAfterTax']").prop('checked');
            var itemwise_tax = $("#appsettings").find("[data-name='itemWiseTax']").prop('checked');
            var itemwise_disc = $("#appsettings").find("[data-name='itemWiseDiscount']").prop('checked');

            if ( el.checked ) {

                if ( $(el).data('name') == 'itemWiseDiscount' ) {

                    if ( disc_after_tax == true && itemwise_tax == false ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', false);
                    }

                } else if ( $(el).data('name') == 'discountAfterTax' ) {
                    if ( itemwise_disc == true && itemwise_tax == false ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', false);
                    }
                }

            } else {

                if ( $(el).data('name') == 'itemWiseTax' ) {
                    if ( disc_after_tax == true && itemwise_disc == true ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', true);
                    }
                }

            }

        }

        //remove charge slab
        function removeTaxDetail(el) {

            $(el).parent().parent().remove();

            if ( $('#tax_details_field').children().length == 0 ) {
                $('#tax_details_field').html('<span id="tx_detail_msg">No tax details added.</span>');
            }

        }

        function addTaxDetailSlab() {

            if ( $('#tax_details_field .tx-field-div').length > 0 ) {

                var tx_field = $('#tax_details_field .tx-field-div:last').find('.tx-field').val().trim();
                var tx_value = $('#tax_details_field .tx-field-div:last').find('.tx-value').val().trim();

                var error = false;
                if ( tx_field == '' ) {
                    $('#tax_details_field .tx-field-div:last').find('.tx-field').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#tax_details_field .tx-field-div:last').find('.tx-field').css('border-color','');
                }

                if ( tx_value == '' ) {
                    $('#tax_details_field .tx-field-div:last').find('.tx-value').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#tax_details_field .tx-field-div:last').find('.tx-value').css('border-color','');
                }

                if ( error == true ) {
                    return;
                }
            }

            var content =   '<div class="tx-field-div">'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Field lable" class="form-control tx-field" value="">'+
                    '</div>'+
                    '<div class="col-md-1 form-group">'+
                    '<span> = </span>'+
                    '</div>'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Field value" class="form-control tx-value" value="">'+
                    '</div>'+
                    '<div class="col-md-3 form-group">'+
                    '<button class="btn btn-danger rm-tax" onclick="removeTaxDetail(this)"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                    '</div>';

            $('#tax_details_field #tx_detail_msg').remove();
            $('#tax_details_field').append(content);

        }

        function saveTaxDetail() {

            var tx_detail = {}; var cnt = 0;
            var outlet_id = $("#outlet_id").val();
            $('#tax_details_field .tx-field-div').each(function () {
                var tx_field = $(this).find(".tx-field").val().trim();
                var tx_value = $(this).find(".tx-value").val().trim();

                if ( tx_field != '' || tx_value != '' ) {
                    tx_detail[tx_field] = [];
                    tx_detail[tx_field].push({tx_value:tx_value});
                    cnt++;
                }
            });

            processBtn('save_tx_btn','add','Submit...');

            $.ajax({
                type:'post',
                url:'/store-tax-details',
                data:{tx_detail:tx_detail,outlet_id:outlet_id},
                dataType:'json',
                success:function(data){

                    processBtn('save_tx_btn','remove','Submit');
                    if ( data.status == 'success') {

                        successErrorMessage('Tax details has been udpate.','success');

                    } else {
                        successErrorMessage(data.msg,'error');
                    }

                }
            });

        }

        function addFixChargeSlab() {
            $("#fixed_rate").each(function() {
                if ($(this).prop('checked') == true) { //do something } });
                    var content =   '<div class="field-div">'+
                            '<div class="col-md-4 form-group">'+
                            '<input type="text" placeorder="Order Amount" class="form-control order_amt" disabled value="Fixed">'+
                            '</div>'+
                            '<div class="col-md-1 form-group">'+
                            '<span> = </span>'+
                            '</div>'+
                            '<div class="col-md-4 form-group">'+
                            '<input type="text" placeorder="Delivery Charge" class="form-control delivery_charge" value="">'+
                            '</div>'+
                            '<div class="col-md-3 form-group">'+
                            '</div>'+
                            '</div>';

                    $('.field-div').remove();
                    $('#charge_field #empty_msg').remove();
                    $('#charge_field').append(content);
                    $('#add_charge_btn').prop('disabled', true);
                }else{
                    $('.field-div').remove();
                    $('#add_charge_btn').prop('disabled', false);
                    $('#charge_field').html('<span id="empty_msg">No Charge slab added.</span>');
                    //alert($(this).prop('checked'));
                }
            });
        }

        function addChargeSlab() {

            if ( $('#charge_field .field-div').length > 0 ) {

                var ord_amt = $('#charge_field .field-div:last').find('.order_amt').val().trim();
                var charge = $('#charge_field .field-div:last').find('.delivery_charge').val().trim();

                var error = false;
                if ( ord_amt == '' ) {
                    $('#charge_field .field-div:last').find('.order_amt').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#charge_field .field-div:last').find('.order_amt').css('border-color','');
                }

                if ( charge == '' ) {
                    $('#charge_field .field-div:last').find('.delivery_charge').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#charge_field .field-div:last').find('.delivery_charge').css('border-color','');
                }

                if ( error == true ) {
                    return;
                }
            }

            var content =   '<div class="field-div">'+
                                '<div class="col-md-4 form-group">'+
                                    '<input type="text" placeorder="Order Amount" class="form-control order_amt" value="">'+
                                '</div>'+
                                '<div class="col-md-1 form-group">'+
                                    '<span> = </span>'+
                                '</div>'+
                                '<div class="col-md-4 form-group">'+
                                    '<input type="text" placeorder="Delivery Charge" class="form-control delivery_charge" value="">'+
                                '</div>'+
                                '<div class="col-md-3 form-group">'+
                                    '<button class="btn btn-danger rm-tax" onclick="removeCharge(this)"><i class="fa fa-times"></i></button>'+
                                '</div>'+
                            '</div>';

            $('#charge_field #empty_msg').remove();
            $('#charge_field').append(content);

        }

        //remove charge slab
        function removeCharge(el) {

            $(el).parent().parent().remove();

            if ( $('#charge_field').children().length == 0 ) {
                $('#charge_field').html('<span id="empty_msg">No Charge slab added.</span>');
            }

        }

        //store Delivery charge slab
        function savecharge() {

            var charges = {}; var cnt = 0;
            var outlet_id = $("#outlet_id").val();
            $('#charge_field .field-div').each(function () {
                var ord_val = $(this).find(".order_amt").val().trim();
                var del_charge = $(this).find(".delivery_charge").val().trim();

                if ( ord_val != '' || del_charge != '' ) {
                    charges[ord_val] = [];
                    charges[ord_val].push({del_charge:del_charge});
                    cnt++;
                }
            });
            var override = $("#override_charge").prop('checked');

            processBtn('charge_btn','add','Submit...');

            $.ajax({
                type:'post',
                url:'/store-delivery-charge',
                data:{charges:charges, override_charge:override, outlet_id:outlet_id},
                dataType:'json',
                success:function(data){

                    processBtn('charge_btn','remove','Submit');
                    if ( data.status == 'success') {

                        successErrorMessage('Delivery charge has been udpate.','success');

                    } else {
                        successErrorMessage(data.msg,'error');
                    }

                }
            });


        }

        function saveTax() {

            var dinein = $('#dine_in').val();
            var take_away = $('#take_away').val();
            var home_delivery = $('#home_delivery').val();
            var outlet_id = $('#outlet_id').val();

            if ( dinein != 'select' || take_away != 'select' || home_delivery != 'select') {

                processBtn('save_tax_btn','add','Submit...');

                $.ajax({
                    type:'post',
                    url:'/update-order_type-taxes',
                    data:{dine_in:dinein,take_away:take_away,home_delivery:home_delivery, outlet_id:outlet_id},
                    success:function(data){

                        processBtn('save_tax_btn','remove','Submit');

                        if ( data == 'success') {
                            successErrorMessage('Taxes has been updated successfully','success');
                        } else {
                            successErrorMessage('There is some error occurred, Please try again later','error');
                        }

                    }
                });

            } else {
                processBtn('save_tax_btn','add','Submit...');

                $.ajax({
                    type:'post',
                    url:'/update-order_type-taxes',
                    data:{dine_in:dinein,take_away:take_away,home_delivery:home_delivery, outlet_id:outlet_id},
                    success:function(data){

                        processBtn('save_tax_btn','remove','Submit');

                        if ( data == 'success') {
                            successErrorMessage('Taxes has been updated successfully','success');
                        } else {
                            successErrorMessage('There is some error occurred, Please try again later','error');
                        }

                    }
                });
            }


        }

        var latlng = new google.maps.LatLng(23.0300, 72.5800);
        var mapOptions = {
            zoom: 16,
            componentRestrictions:{country: 'in'} ,// set the map zoom level
            center: latlng, // set the center region
            disableDefaultUI: true,
            zoomControl: true, // whether to show the zoom control beside
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var input = document.getElementById('address');
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        autocomplete = new google.maps.places.Autocomplete(input, mapOptions);
        var marker = new google.maps.Marker({
            map: map, // refer to the map you've just initialise
            position: latlng, // set the marker position (is based on latitude & longitude)
            draggable: false // allow user to drag the marker
        });


        var geocoder = new google.maps.Geocoder();

        function geocoding(keyword) {
            geocoder.geocode({address: keyword}, function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);

                    // ADD THIS
                    $.ajax({
                        method: "POST",
                        url: "/outlet/addlocation/{{$outlet['id']}}",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { outlet_id:$("#outlet_id").val(),latitude: results[0].geometry.location.lat(), longitude: results[0].geometry.location.lng() }
                    })
                            .done(function( msg ) {

                            });

                } else {
                    alert('Location not found.');
                }
            });
        }
        function Addimage(){
            document.getElementById('addimages').style.display="block";
        }
        function exportexcel(getrestaurantid){
            $.ajax({
                method: "POST",
                url: "/outlet/exportexcel",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: { outlet_id:getrestaurantid}
            })
                    .done(function( msg ) {

                        window.open('http://parag.foodklub.com','_blank' );
                    });
        }

        //Update payment mode
        function updatePaymentId() {
            var check = 1;
            $('#paymentOptionidform input').each(function() {
                if ($(this).val() == '') {
                    swal({
                        title: "Error",
                        text: "All Input fields must be filled!",
                        type: "warning",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                    check = 0;
                }
            });
            if(check) {
                $("input").removeAttr("disabled");
                var data = $('#paymentOptionidform').serialize();

                $('#payment_id_btn').attr('disabled', true);
                $('#payment_id_btn').text('Updating...');

                $.ajax({
                    type: 'post',
                    url: '/update-payment-id',
                    data: data,
                    success: function (data) {
                        $('#payment_mode_btn').attr('disabled', false);
                        $('#payment_mode_btn').text('Submit');
                        swal({
                            title: "Success",
                            text: "Payment ids have been updated successfully!",
                            type: "success",
                            confirmButtonColor: "#43C6DB",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        });
                    },
                    error: function () {
                        swal({
                            title: "Error",
                            text: "Payment options doesnot saved!",
                            type: "warning",
                            confirmButtonColor: "#43C6DB",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        });
                    }
                });
                $('#payment_id_btn').text('Update');
                $('#payment_id_btn').attr('disabled', false);
            }
        }

        //Update payment mode
        function updatePaymentMode() {
            $("input").removeAttr("disabled");
            var data = $('#paymentOptionform').serialize();

            $('#payment_mode_btn').attr('disabled',true);
            $('#payment_mode_btn').text('Updating...');

            $.ajax({
                type:'post',
                url:'/update-payment-mode',
                data:data,
                success:function(data){
                    $('#payment_mode_btn').attr('disabled',false);
                    $('#payment_mode_btn').text('Submit');
                    swal({
                        title: "Success",
                        text: "Payment options has been updated successfully!",
                        type: "success",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                },
                error:function () {
                    swal({
                        title: "Error",
                        text: "Payment options has doesnot saved!",
                        type: "warning",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                }
            });
            $('#payment_mode_btn').text('Update');
            $('#payment_mode_btn').attr('disabled',false);

        }

        $(document).ready(function() {
            $('.ddprinter').select2({
                placeholder: 'Select Printer'
            });
            var $lightbox = $('#lightbox');
            getSittings();
            checkSlots();

            $('.material-tabs a').click(function (e) {
                var abc = $(this).attr('href');
                $(abc).tab('show');
                var scrollmem = $('body').scrollTop();
                $('html,body').scrollTop(scrollmem);
            });

            $('[data-target="#lightbox"]').on('click', function(event) {
                var $img = $(this).find('img'),
                        src = $img.attr('src'),
                        alt = $img.attr('alt'),
                        css = {
                            'maxWidth': $(window).width() - 100,
                            'maxHeight': $(window).height() - 100
                        };

                $lightbox.find('.close').addClass('hidden');
                $lightbox.find('img').attr('src', src);
                $lightbox.find('img').attr('alt', alt);
                $lightbox.find('img').css(css);
            });

            $lightbox.on('shown.bs.modal', function (e) {
                var $img = $lightbox.find('img');

                $lightbox.find('.modal-dialog').css({'width': $img.width()});
                $lightbox.find('.close').removeClass('hidden');
            });
        });

        $(function(){
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.material-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });
        $(window).load(function() {
            var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: 'avatar.png'
            }
            var cropper = $('.imageBox').cropbox(options);
            $('#file').on('change', function(){
                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = $('.imageBox').cropbox(options);
                }
                reader.readAsDataURL(this.files[0]);
                this.files = [];
            })
            $('#btnCrop').on('click', function(){
                var img = cropper.getDataURL()
                $('.cropped').append('<img src="'+img+'">');
                $('#raw_data').val(img);
                $('#file').append(img);
            })
            $('#btnZoomIn').on('click', function(){
                cropper.zoomIn();
            })
            $('#btnZoomOut').on('click', function(){
                cropper.zoomOut();
            })
        });

        jQuery("#findaddress").on('click',function(){

            geocoding(document.getElementById('address').value);


        });

        function updateSettings() {

            var data = $("#updateSetting").serialize();

            $.ajax({
                url:'/update-printers',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage('Settings has been udpate.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        function updateTimeSlots() {

            var data = $("#timeSlotsCreate").serialize();

            $.ajax({
                url:'/storetimeslot',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage('TimeSlots has been udpated.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        function updateAppView() {

            var data = $("#applayout").serialize();

            $.ajax({
                url:'/storelayout',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage(data.msg,'success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage(data.msg,'error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        function getSittings() {

            //$('#SettingsTable td input').val('');
            var outlet_id = $('#outlet_id').val();

            $.ajax({
                url:'/getSettings',
                Type:'POST',
                dataType:'json',
                data:'outlet_id='+outlet_id,
                success:function(data){
                    for(var i=0;i<data.length;i++){
                        //alert(data[i].id);
                        $('#'+data[i].id).val(data[i].setting_value);
                    }
                }
            });

        }

        function updateMasterSettings() {

            var bad = 0;
            $('.form :text').each(function ()
            {
                if ($.trim(this.value) == "") bad++;
            });
            if (bad > 0) {
                alert('All fields must be filled.');
                return;
            }

            var outlet_id = $('#outlet_id').val();

            $('#add-btn').text('Processing...');
            $('#add-btn').prop('disabled',true);
            var data = $('#updateMasterSetting').serialize();
            //console.log(data);return;
            $.ajax({
                url:'/update-settings',
                Type:'POST',
                data:data,
                success:function(data){
                    $('#add-btn').text('Add');
                    $('#add-btn').prop('disabled',false);

                    if ( data.status == 'success' ) {
                        successErrorMessage('Settings has been udpate.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });
        }

        function checkSlots() {
            var o_id = $('#outlet_id').val();
            var someText='';
            $.ajax({
                url: '/checktimeslots',
                Type: 'POST',
                dataType: 'json',
                data: 'outlet_id=' + o_id ,
                success: function (data) {
                    $("#appendtimeslots").html('');
                    $('#countf').val(data.length);
                    for(var i=0;i<data.length;i++){
                        var x=z++;  //when add new features increase id
                        someText = '<div class="">' +
                                '<div class="" id="control'+x+'">'+
                                '<div class="col-md-12 form" style="padding-left:0px;">' +
                                '<div class="col-md-2 timepicker-label">'+
                                '<label for="closing_time" class="control-label">Session</label>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control add-on" type="text" value="'+data[i].slot_name+'" placeholder="Name">'+
                                '</div>'+
                                '<div class="col-md-1">'+
                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">' +
                                '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control" type="text" value="'+data[i].from_time+'" placeholder="From">'+
                                '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                '</div>'+
                                '<div class="col-md-1 timepicker-label">'+
                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">'+
                                '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control" type="text"  value="'+data[i].to_time+'" placeholder="To">'+
                                '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                '</div>'+
                                '<a href="/timeslots/'+data[i].id+'/destroy"><input type="button" class="btn btn-primary mr5" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                                '</div>'+

                                '<input type=hidden name="count" value='+x+'>' +
                                '</div>' +
                                '</div>';
                        var newDiv = $("<div class='col-md-12'>").append(someText);
                        $("#appendtimeslots").append(newDiv);

                        $('#opening_time'+x).timepicker({
                            showMeridian:false
                        });
                        $('#closing_time'+x).timepicker({
                            showMeridian:false
                        });

                        $(".timepicker").timepicker({
                            showMeridian:false
                        });
                    }
                }
            });
        }

        //timeslot script
        var i=1;
        var someText='';
        var z = document.getElementById("countf").value; //get pre added time slots
        $("#btn_myid").click(
            function () {
                var x=z++;  //when add new features increase id
                someText = '' +
                    '<div class="">' +
                        '<div class="" id="control'+x+'">'+
                            '<div class="col-md-12 form" style="padding-left:0px;">'+
                                '<div class="col-md-2 timepicker-label">'+
                                    '<label for="closing_time" class="control-label">Session</label>'+
                                '</div>'+
                                '<div class="col-md-2 input-append">'+
                                    '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control" type="text" value="@if(isset($outlet['slot_name'])){{$outlet->slot_name}}@endif" placeholder="Name">'+
                                '</div>'+
                                '<div class="col-md-1 timepicker-label">'+
                                    '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">'+
                                    '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control" type="text" value="@if(isset($outlet['opening_time'])){{$outlet['opening_time']}}@endif" placeholder="From">'+
                                    '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                '</div>'+
                                '<div class="col-md-1 timepicker-label">'+
                                    '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">'+
                                    '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control" type="text"  value="@if(isset($outlet['closing_time'])){{$outlet['closing_time']}}@endif" placeholder="To">'+
                                    '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                '</div>'+
                                '<input type="button" class="btn btn-danger" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                            '</div>'+
                            '<input type=hidden name="count" value='+x+'>' +
                        '</div>' +
                    '</div>';
                var newDiv = $("<div class='col-md-12'>").append(someText);
                $("#appendtimeslots").append(newDiv);

                $('#opening_time'+x).timepicker({
                    showMeridian:false
                });
                $('#closing_time'+x).timepicker({
                    showMeridian:false
                });
            }
        )


    </script>
@stop
