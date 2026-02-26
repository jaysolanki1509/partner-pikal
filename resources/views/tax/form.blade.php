
@if($action=='add')


    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/tax') }}" files="true" enctype="multipart/form-data">
        {{--{!! Form::open(['route' =>'status.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}
        @else
            {{--<form class="form-horizontal" role="form" method="PUT" action="{{ url('/status/'.$Outlet->id) }}">--}}
            {!! Form::model($tax,array('route' => array('tax.update',$tax->id), 'method' => 'patch', 'class' => 'autoValidate')) !!}
        @endif


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Tax.Tax Type') }}</label>
            </div>


            @if($action=='add')
                <input type="text" class="form-control" name="tax_name" placeholder="Please Enter Valid Tax Type">
            @endif

            @if($action=='edit')

                <input type="text" class="form-control" name="tax_name" value="@if(isset($tax->tax_type)){{$tax->tax_type}}@endif" placeholder="Please Enter Valid Tax Type">
            @endif

        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Tax.Tax Percentage') }}</label>
            </div>


            @if($action=='add')
                <input type="text" class="form-control" name="tax_percentage" placeholder="Please Enter Valid Tax Percentage">
            @endif

            @if($action=='edit')
                <input type="text" class="form-control" name="tax_percentage" value="@if(isset($tax->tax_percent)){{$tax->tax_percent}}@endif" placeholder="Please Enter Valid Tax Percentage">
            @endif

        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5" type="Submit">{{ trans('Tax.Submit') }}</button>
                <button class="btn btn-default" type="Reset">{{ trans('Tax.Reset') }}</button>
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



