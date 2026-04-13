<?php use App\Outlet; ?>
<?php use App\Status ;?>
        @if($action=='add')

            <form class="form-horizontal" role="form" method="POST" id="status_form" novalidate="novalidate" action="{{ url('/status') }}" files="true" enctype="multipart/form-data">
        {{--{!! Form::open(['route' =>'status.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}
        @else
            {{--<form class="form-horizontal" role="form" method="PUT" action="{{ url('/status/'.$Outlet->id) }}">--}}
            {!! Form::model($status,array('route' => array('status.update',$status->id), 'method' => 'patch', 'id' => 'status_form', 'class' => 'autoValidate')) !!}
        @endif


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label"> {{ trans('Status.Outlet Name') }}</label>
            </div>


            <div class="col-md-9 form">
                @if($action=='add')
                    <select class="form-control" name="Outlets">
                        <option selected >{{ trans('Status.Select Outlet Name') }}</option>
                        @foreach($Outlets as $Outlet)
                            <option value="{{$Outlet->id or ''}}">{{$Outlet->name or ''}}</option>
                        @endforeach
                    </select>

                @endif
                    @if($action=='edit')
                        <select class="form-control" name="Outlets">
                            <option selected >{{ trans('Status.Select Outlet Name') }}</option>

                            @foreach($Outlets as $Outlet)
                                @if($status->outlet_id==$Outlet->id)
                                    <option value="{{$Outlet->outlet_id or ''}}" selected>{{$Outlet->name or ''}}</option>
                                @else
                                    <option value="{{$Outlet->outlet_id or ''}}">{{$Outlet->name or ''}}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif


                        {{--<select class="form-control" name="Outlet_name">--}}
                            {{--@foreach($Outlet_name as $name)--}}
                                {{--@if($name->outlet_id == $status->outlet_id)--}}
                                    {{--<option value="{{$name->id or ''}}" selected>{{$name->Outlet_name or ''}}</option>--}}
                                {{--@else--}}
                                    {{--<option value="{{$name->id or ''}}">{{$name->Outlet_name or ''}}</option>--}}
                                {{--@endif--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Status.Order*') }}</label>
            </div>

            <div class="col-md-9 form">
                @if($action=='add')
                    <input type="text" class="form-control" name="order" placeholder="Please Enter Valid Order">
                @endif

                @if($action=='edit')
                    <input type="text" class="form-control" name="order" value="@if(isset($status->order)){{$status->order}}@endif" placeholder="Please Enter Valid Order">
                @endif
            </div>
        </div>



        <div class="col-md-12">
            <div class="col-md-3 form">
                <label for="closing_time" class="control-label">{{ trans('Status.Status*') }}</label>
            </div>

            <div class="col-md-9 form">
                @if($action=='add')
                    <input type="text" class="form-control" name="status" value="" placeholder="Please Enter Valid Status">
                @endif
                @if($action=='edit')
                    <input type="text" class="form-control" name="status" value="@if(isset($status->status)){{$status->status}}@endif" placeholder="Status">
                @endif
            </div>
        </div>



        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5" type="Submit">{{ trans('Status.Submit') }}</button>
                <button class="btn btn-default" type="Reset">{{ trans('Status.Reset') }}</button>
            </div>
        </div>

    </form>



    {{--<script type="text/javascript">--}}
        {{--(function($,W,D)--}}
        {{--{--}}
            {{--var JQUERY4U = {};--}}

            {{--JQUERY4U.UTIL =--}}
            {{--{--}}
                {{--setupFormValidation: function()--}}
                {{--{--}}
                    {{--//form validation rules--}}
                    {{--$("#status-form").validate({--}}
                        {{--rules: {--}}
                            {{--Outlet_name:"required",--}}
                            {{--order: "required|numeric",--}}
                            {{--status: "required",--}}

                            {{--messages: {--}}
                                {{--Outlet_name: "Please enter valid Outlet_name",--}}
                                {{--order: "Please enter valid order",--}}
                                {{--status: "Please enter valid status"--}}
                            {{--},--}}
                            {{--submitHandler: function(form) {--}}
                                {{--form.submit();--}}

                            {{--}--}}
                        {{--}--}}
                    {{--})--}}
                {{--}}--}}


            {{--//when the dom has loaded setup form validation rules--}}
            {{--$(D).ready(function($) {--}}
                {{--JQUERY4U.UTIL.setupFormValidation();--}}
            {{--});--}}

        {{--})(jQuery, window, document);--}}
    {{--</script>--}}
@section('page-scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $("#status_form").validate({
                rules: {
                    order:"required",
                    status:"required"
                },
                messages: {
                    order:"Order is required",
                    status:"Status is required"
                }
            })
            $('#Submit').click(function() {
                $("#status_form").validate();  // This is not working and is not validating the form
            });

        });
    </script>
@stop
