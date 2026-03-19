
@extends('partials.default')

@section('pageHeader-left')
    Account Settings
@stop


@section('content')


    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form class='form-horizontal material-form j-forms' id="accountSettingsForm">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::select('account_id', isset($accounts)?$accounts:array(),null,array('class' => 'select2 form-control','id'=>'account_id','onchange'=>'showSettings()')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget-container" id="data_div">
                                        <div class="widget-content">
                                            <div class="setting-body"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>


@stop

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#account_id').select2({
                placeholder: 'Select an Account'
            });
        });

        //open print popup
        function showSettings() {

            var account_id = $('#account_id').val();

            if (account_id == '') {
                $('.panel-body').html('<div class="col-md-12" id="loading_div" style="text-align: center;font-weight: bold">Please select account</div>');
                return;
            }

            $.ajax({
                url:'/show-account-setting',
                type: "POST",
                data: {account_id : account_id},
                success: function (data) {

                    $('#data_div #loading_div').remove();
                    $('.setting-body').html(data);

                },
                error:function(error) {
                    alert('There is some error ocurred.');
                }

            });

        }

        function storeSetting() {

            var params = $('#accountSettingsForm').serialize();

            processBtn('submit_btn','add','Processing...');

            $.ajax({
                url:'/store-account-setting',
                type: "POST",
                data: params,
                success: function (data) {

                    $('#data_div .py_loading').remove();
                    if( data == 'success') {

                        swal({
                            title: "Saved?",
                            text: "Account settings updated successfully!",
                            type: "success",
                            confirmButtonColor: "#4caf50"
                        },function (e) {
                            location.reload();
                            processBtn('submit_btn','remove','Submit');
                        });

                    }


                },
                error:function(error) {
                    alert('There is some error ocurred.');
                }

            });


        }

    </script>


@stop