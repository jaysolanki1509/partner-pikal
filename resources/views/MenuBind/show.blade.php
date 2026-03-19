<?php use App\CuisineType;?>
<?php use App\Menu; ?>
@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Restaurant_Show.Outlet Detail') }}
@stop
@section('content')

    <script src="../js/CropBox.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/jquery-ui-min.js"></script>

    <link rel="stylesheet" href="../css/jquery-ui.css">



    <style>
        body {
            padding: 30px 0px;
        }

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

<div class="row">
		<div class="col-sm-4 col-md-3">
			<div class="text-center">
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
                                            <input type="hidden" name="restau_id" value="<?php echo $Outlet->id;?>"/>
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
                                            <input type="hidden" name="restau_id" value="<?php echo $Outlet->id;?>"/>
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



				<h4 class="profile-name mb5">{{$Outlet->name or ''}}</h4>
				<div><i class="fa fa-map-marker"></i> {{$Outlet->address or ''}}</div>
                <div class="mb30"></div>
				<div style="clear:both;">


				</div>
			</div><!-- text-center -->
		</div><!-- col-sm-4 col-md-3 -->

		<div class="col-sm-8 col-md-9">
			<ul class="nav nav-tabs nav-line">
				<li class="active" ><a  href="#info"><strong>{{ trans('Restaurant_Show.Information') }}</strong></a></li>
				<li class="" ><a  href="#images"><strong>{{ trans('Restaurant_Show.Images') }}</strong></a></li>
				<li class="" ><a  href="#menu"><strong>{{ trans('Restaurant_Show.Menu') }}</strong></a></li>
			</ul>
			<div class="tab-content nopadding noborder">


				<div id="info" class="tab-pane active">
                        <div class="mb30"></div>
                        <div class="activity-list vehicle-list">


                            <html lang="en">
                             <body>

                            <div class="container-fluid">
                                <ul class="list-group">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Active') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                            @if(isset($Outlet->active) && $Outlet->active!='')
                                                <?php echo $Outlet->active;?>
                                                @else
                                                <?php echo '';?>
                                                @endif
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Country') }}</label>
                                            </b>
                                        </div>

                                        <div class="col-sm-6">
                                            <div>
                                                @if(sizeof($countries)>0 && isset($countries))
                                                    @foreach($countries as $country)
                                                        @if(isset($country->id)){{$country->name}}@endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>





                                    <div class="row">
                                        <div class="col-sm-3">
                                           <b>
                                                <label>{{ trans('Restaurant_Show.State') }}</label>
                                           </b>
                                        </div>

                                        <div class="col-sm-6">
                                            <div>
                                                @if(sizeof($states)>0)
                                                @foreach($states as $state)
                                                    @if(isset($state->id)){{$state->name}}@endif
                                                    @endforeach
                                                    @endif
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.City') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-md-6">
                                            @if(sizeof($cities)>0)
                                            @foreach($cities as $city)
                                            <div>@if(isset($city->id)){{$city->name}}@endif</div>@endforeach
                                                @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                           <b>
                                                <label>{{ trans('Restaurant_Show.Locality') }}</label>
                                           </b>
                                        </div>
                                        <div class="col-sm-6">
                                            @if(isset($Outlet->locality) && $Outlet->locality!=0 && $Outlet->locality!=''){{ $locality->locality}}@endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Pincode') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                            <div>@if(isset($Outlet->pincode)){{$Outlet->pincode}}@endif</div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Famous For') }}</label>
                                            </b>
                                        </div>

                                        <div class="col-sm-6">
                                            <div>@if(isset($Outlet->famous_for)){{$Outlet->famous_for}}@endif</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Outlet Type') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">

                                            @if(sizeof($Outlettype)>0)
                                            @foreach($Outlettype as $rest_type)
                                            <div>
                                                    <?php $rest=\App\OutletType::where('id',$rest_type->outlet_type_id)->first(); echo $rest->type; ?>
                                            </div>
                                                @endforeach
                                                @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Cuisine Type') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">

                                            @if(sizeof($cuisinetypes)>0)
                                            @foreach($cuisinetypes as $cuisinetype)
                                                <div>
                                                        <?php $test=\App\CuisineType::where('id',$cuisinetype->cuisine_type_id)->first(); echo $test->type; ?>
                                                </div>
                                                @endforeach
                                                @endif
                                        </div>
                                    </div>


                                    <?php $servicetype=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');?>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Service Type') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">

                                            <?php $restservicetype=unserialize($Outlet->service_type);?>
                                            @if(isset($restservicetype) && $restservicetype!="")
                                                    @foreach($service_type as $res)
                                                        <div>
                                                            <?php echo $servicetype[$res];?>
                                                         </div>
                                                    @endforeach
                                            @endif
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Tin No') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                            @if(isset($Outlet->tinno) && $Outlet->tinno!='')
                                                <?php echo $Outlet->tinno;?>
                                            @else
                                                <?php echo '';?>
                                            @endif
                                        </div>
                                    </div>




                                    <div class="row">
                                            <div class="col-sm-3">
                                                <b>
                                                    <label>{{ trans('Restaurant_Show.ServiceTax No') }}</label>
                                                </b>
                                            </div>
                                            <div class="col-sm-6">
                                                @if(isset($Outlet->servicetax_no) && $Outlet->servicetax_no != '')
                                                    <?php echo $Outlet->servicetax_no;?>
                                                @else
                                                    <?php echo '';?>
                                                @endif
                                            </div>
                                        </div>



                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Contact Number') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6"><div>{{$Outlet->contact_no or ''}}</div>
                                        </div>
                                    </div>


                                    <div class="row">
                                               <div class="col-sm-3">
                                                   <b>
                                                       <label>{{ trans('Restaurant_Show.Email-Id') }}</label>
                                                   </b>
                                               </div>
                                               <div class="col-sm-6">
                                                   <div>
                                                       <ul class="list-unstyled social-list">
                                                           <li> <a href="#"> {{$Outlet->email_id or ''}}</a></li>
                                                       </ul>
                                                   </div>
                                               </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Web Address') }}</label>
                                            </b>
                                        </div>

                                        <div class="col-sm-6">
                                            <div>
                                                   <ul class="list-unstyled social-list">
                                                            <li> <a href="#">{{$Outlet->url or ''}}</a></li>
                                                        </ul>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Establishment Date') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                            <div>
                                                @if(isset($established_date)){{$established_date}}@endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Timing') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                                <div>
                                                        @foreach($timeslot as $time)
                                                            From  {{$time->from_time}} To {{$time->to_time}}<br/>
                                                        @endforeach
                                                </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Latitude') }}</label>
                                            </b>
                                        </div>

                                        <div class="col-sm-6">
                                            <div>

                                                        @if(isset($Outlet->lat) && $Outlet->lat!=0){{$Outlet->lat}}
                                                        @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-3">
                                            <b>
                                                <label>{{ trans('Restaurant_Show.Longitude') }}</label>
                                            </b>
                                        </div>
                                        <div class="col-sm-6">
                                            <div>

                                                        @if(isset($Outlet->long)&& $Outlet->long!=0){{$Outlet->long}}
                                                        @endif

                                            </div>
                                        </div>
                                    </div>
                                    <br/>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <input type="text" id="address" placeholder="Address" style="width: 100%;"/>
                                        </div>

                                        <button type="button" class="col-sm-2 btn btn-primary" id="findaddress">{{ trans('Restaurant_Show.Locate') }}</button>
                                    </div>
                                    <br/>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div id="map" style="width:400px;height:300px;">

                                    </div>
                                </ul>
                            </div>

                            </body>
                            </html>
                        </div>
                </div>

                <div id="images" class="tab-pane">
					<div class="mb30"></div>
					<div class="activity-list vehicle-list">

                    </div>

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
                                                            <input type="hidden" name="restau_id" value="<?php echo $Outlet->id;?>"/>
                                                            {!! Form::file('images[]', array('multiple'=>true)) !!}
                                                            <p class="errors">{!!$errors->first('images')!!}</p>
                                                            @if(Session::has('error'))
                                                                <p class="errors">{!! Session::get('error') !!}</p>
                                                            @endif
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



				<div id="menu" class="tab-pane">


                        <div class="clearfix"></div>
                    <div class="form pull-right" style="width:100%; margin:20px 0;">

                        <a href="{{ action('MenuController@create',["id=".$Outlet->id]) }}" class="btn btn-primary" style="float: left; margin:0 10px;">{{ trans('Restaurant_Show.Add Menu') }}</a>


                        <div class="media" style="float:left;margin-top:0px; margin-right:10px;">
                            <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal" data-target="#attachmenu" data-whatever="">{{ trans('Restaurant_Show.Import Menu Excel') }}</button>
                            <div class="modal fade" id="attachmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                    {!! Form::open(array('url'=>'menu/importmenuexcel','method'=>'POST', 'files'=>true)) !!}
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <input type="hidden" name="restau_id" value="<?php echo $Outlet->id;?>"/>
                                                            {!! Form::file('file1', array('multiple'=>true)) !!}
                                                            <p class="errors">{!!$errors->first('images')!!}</p>
                                                            @if(Session::has('error'))
                                                                <p class="errors">{!! Session::get('error') !!}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                                            <!--<button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('Restaurant_Show.Submit') }}</button>-->
                                            {!! Form::submit(trans('Restaurant_Show.Submit'), array('class'=>'btn btn-primary')) !!}
                                            {!! Form::close() !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                            <div class="input-group input-group-lg mb15 wid-full" style="float:left;">
                                <span class="input-group-addon" style="display:none" ></span>
                                {!! Form::open(array('url'=>'outlet/exportexcel','method'=>'POST')) !!}
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="hidden" name="restau_id" value="<?php echo $Outlet->id;?>"/>
                                        @if(Session::has('error'))
                                            <p class="errors">{!! Session::get('error') !!}</p>
                                        @endif
                                    </div>
                                </div>
                                <button id="{{$Outlet->id}}" class="btn btn-primary" type="submit" class="exportexcel">{{ trans('Restaurant_Show.Export Menu Excel') }}</button>
                            </div>


                    </div>
                    <div class="clearfix"></div>
                    <div class="activity-list vehicle-list">
                        <div class="media">
                      <div>
                      <div>
                          {{--<a data-toggle="modal" data-target="#{{$cuisinetype->cuisine_type_id}}" data-whatever="">{{$cuisinetype->cuisine_type_id}}</a>--}}
                          </div>
                          </div>



                            <FIELDSET class="fieldsset">
                                <div id="accordion">
                                    @foreach($Outletsectioname as $sectionname)

                                            @if($sectionname->active == 0)
                                                <p><b>{{$sectionname->title}}</b></p>

                                                <div class="center">
                                                    @foreach($menuitems as $items)
                                                        @if($sectionname->id == $items->menu_title_id)
                                                            <h5>
                                                                @if($items->active == 0)
                                                                    <div class="col-sm-8"><p>{{$items['item']}}</p></div>
                                                                @else
                                                                    <div class="col-sm-8"><p>{{$items['item']}}&nbsp;(InActive)</p></div>
                                                                @endif
                                                                    @if(isset($items['price']) && $items['price']!=0 && $items['price']!="")
                                                                        <div class="col-sm-2"><p>{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES') . " " .$items['price']}}</p></div>
                                                                    @else
                                                                        <div class="col-sm-2"><p></p>
                                                                        </div>
                                                                    @endif
                                                            </h5>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                    @endforeach
                                </div>
                            </FIELDSET>

                            {{--<FIELDSET class="fieldsset">--}}
                                {{--<div id="accordion">--}}
                                    {{--@foreach($Outletsectioname as $sectionname)--}}
                                        {{--@if($sectionname['active']== '1')--}}
                                            {{--<p><b>{{$sectionname->title}}</b></p>--}}
                                            {{--<div class="center">--}}
                                                {{--@foreach($menuitems as $items)--}}
                                                    {{--@if($sectionname->id == $items->menu_title_id)--}}
                                                        {{--<h5>--}}
                                                            {{--@if(isset($items['active']) && $items['active']== '1')--}}
                                                                {{--<div class="col-sm-8"><p>{{$items['item']}}</p></div>--}}
                                                                {{--@if(isset($items['price']) && $items['price']!=0 && $items['price']!="")--}}
                                                                    {{--<div class="col-sm-2"><p>{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES') . " " .$items['price']}}</p></div>--}}
                                                                {{--@else--}}
                                                                    {{--<div class="col-sm-2"><p></p>--}}
                                                                    {{--</div>--}}
                                                                {{--@endif--}}

                                                            {{--@elseif($items['active']=='0')--}}
                                                                {{--<p>{{" "}}--}}
                                                                {{--</p>--}}
                                                            {{--@endif--}}
                                                        {{--</h5>--}}
                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                            {{--</div>--}}
                                        {{--@elseif($sectionname['active']=='0')--}}

                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--</FIELDSET>--}}




                        </div>
                    </div></div>
                </div>
        </div>
</div>
    </div><!-- col-sm-9 -->
    </div>
    </div>


    <script>
        $(function() {
            $( "#accordion" ).accordion();
//            var newDiv = "";
//            $('.fieldsset').append(newDiv);
        });
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVgjlIb0ZR6_wmxgr5FCpbVImDaXntxpo&amp;sensor=false&amp;libraries=places"></script>
    <script>

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
                        url: "/outlet/addlocation/{{$Outlet->id}}",
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
        $(document).ready(function() {
            var $lightbox = $('#lightbox');

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

            $('.nav-tabs a').click(function (e) {
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
    </script>
@stop

