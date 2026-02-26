<?php use App\Outlet; use App\CuisineType;?>
@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Status.Status Detail') }}
@stop
@section('content')

    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <script src="../js/CropBox.js"></script>
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

    <div class="row">
        <div class="col-sm-4 col-md-3">

        </div><!-- col-sm-4 col-md-3 -->

        <div class="well">
            <ul class="nav nav-tabs nav-line">
                <li class="active" ><a  href="#info"><strong>{{ trans('Status.Information') }}</strong></a></li>
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
                                    <div class="col-sm-2">
                                        <h4>
                                            <label>{{ trans('Status.Outlet Name:') }}</label>
                                        </h4>

                                    </div>
                                    <div class="col-sm-8">
                                        <div>
                                            <h4>
                                                <?php if(isset($Outlet->name)){$restname =Outlet::where('id',$status->outlet_id)->first();echo $restname['name'];}?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-sm-2">
                                        <h4><label>{{ trans('Status.Order:') }}</label></h4>
                                    </div>
                                    <div class="col-sm-8">
                                        <div>
                                            <h4>
                                                {{$status->order or ''}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2">
                                        <h4><label>{{ trans('Status.Status:') }}</label></h4>
                                    </div>
                                    <div class="col-sm-8">
                                        <div><h4>@if(isset($status->status)){{$status->status}}@endif</h4></div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                        </body>
                        </html>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- col-sm-9 -->



@stop

