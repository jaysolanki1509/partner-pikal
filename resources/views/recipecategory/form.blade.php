
@if($action=='add')
    <script type="text/javascript">
        function capitalize(textboxid, str) {
            // string with alteast one character
            if (str && str.length >= 1)
            {
                var Char = str.charAt(0);
                var remainingStr = str.slice(1);
                str = zChar.toUpperCase() + remainingStr;
            }
            document.getElementById(textboxid).value = str;
        }
    </script>

    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/cancellationreason') }}" files="true" enctype="multipart/form-data">
        {{--{!! Form::open(['route' =>'status.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}
        @else
            {{--<form class="form-horizontal" role="form" method="PUT" action="{{ url('/status/'.$Outlet->id) }}">--}}
            {!! Form::model($cancel,array('route' => array('cancellationreason.update',$cancel->id), 'method' => 'patch', 'class' => 'autoValidate')) !!}
        @endif
        <div class="col-md-12">
            @if($action=='add')
        @if($totalOutletcount>1)
            <div class="col-md-3 form">
                <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
            </div>

            <div class="col-md-3 form">
                <select id="rest_id" class="form-control" name="Outlet_name">
                    <option value="select" selected>{{ trans('OrderIndex.Select') }}</option>
                    @foreach($Outlet as $rest)
                        <option value="{{$rest->id or ''}}">{{$rest->name or ''}}</option>
                    @endforeach
                </select>
            </div>
        @else
            @foreach($Outlet as $rest)
                <div class="col-md-3 form">
                    <input type="hidden" value="{{$rest->id or ''}}" id="Outlet_name" name="Outlet_name"/>
                    <label>{{$rest->name or ''}}</label>
                </div>
            @endforeach
        @endif
        @else
                @if($totalOutletcount>1)
                    <div class="col-md-3 form">
                        <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
                    </div>

                    <div class="col-md-3 form">
                        <select id="rest_id" class="form-control" name="Outlet_name">
                            <option value="select" selected>{{ trans('OrderIndex.Select') }}</option>
                            @foreach($Outlet as $rest)
                                @if($rest->id == $cancel->outlet_id)
                                    <option value="{{$rest->id or ''}}" selected>{{$rest->name or ''}}</option>
                                @else
                                    <option value="{{$rest->id or ''}}">{{$rest->name or ''}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @else
                    @foreach($Outlet as $rest)
                        <div class="col-md-3 form">
                            <input type="hidden" value="{{$rest->id or ''}}" id="Outlet_name" name="Outlet_name"/>
                            <label>{{$rest->name or ''}}</label>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Cancellation.Reason Of Cancellation') }}</label>
            </div>


                @if($action=='add')
                <div class="col-md-3 form">
                    <textarea name="reason" rows="4" cols="50"></textarea>
                </div>
                @endif

                @if($action=='edit')
                <div class="col-md-3 form">
                    <textarea name="reason" rows="4" cols="50">@if(isset($cancel->reason_of_cancellation)){{$cancel->reason_of_cancellation}}@endif</textarea>
                </div>
                @endif

        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5" type="Submit">{{ trans('Cancellation.Submit') }}</button>
                <button class="btn btn-default" type="Reset">{{ trans('Cancellation.Reset') }}</button>
            </div>
        </div>

    </form>



    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#Submit").validate({
                rules: {
                    "reason": {
                        required: true
                    }
                },
                messages: {
                    "coupon_code": {
                        required: "Reason Must be Added"
                    }
                }
            })
        })
        $('#Submit').click(function() {
            $("#Outlet_form").valid();  // This is not working and is not validating the form

        })
    </script>



