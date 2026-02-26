
@extends('partials.default')
@section('pageHeader-left')
    Application Settings Master
@stop
@section('pageHeader-right')
    {{--<a href="/settings" class="btn btn-primary">Back</a>--}}
@stop

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form id="add_setting" name="add_setting">

                        <div class="form-group">
                            <div class="form-group col-md-12">
                                <div class="col-md-2">
                                    {!! Form::label('app_setting','App Setting:') !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::text('setting_name', null, array('class' => 'col-md-3 form-control','id' => 'setting_name', 'placeholder'=> 'Setting Name')) !!}
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('app_setting','Default Value:') !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::select('setting_default', array('' => 'Select default value', 'true' => 'True', 'false' => 'False'),null,array('class' => 'form-control','id'=>'setting_default')) !!}
                                </div>

                                <div class="col-md-2" >
                                    <button type="button" onclick="addSetting();" id="add-btn" class="btn btn-success primary-btn" title="Add new Setting"><i class="fa fa-plus"></i>&nbsp;Setting</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="col-lg-12">
                    <div class="widget-wrap material-table-widget">
                        <div class="widget-container margin-top-0">
                            <div class="widget-content">
                                <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4"  id="SettingsTable" >
                                    <thead>
                                        <th>Settings Name</th>
                                        <th>Default Value</th>
                                        <th data-sort-ignore="true">Action</th>
                                    </thead>

                                    <tbody>
                                        @if ( isset($settings) && sizeof($settings) > 0 )
                                            @foreach($settings as $setting)

                                                <tr class="odd gradeX">
                                                    <td>{!! $setting->setting_name or '' !!}</td>
                                                    <td>{!! $setting->setting_default or '' !!}</td>
                                                    <td>
                                                        <a href="#" onclick= "del({!! $setting->id !!})">
                                                            <span class="zmdi zmdi-close" title="Delete"></span>
                                                        </a>
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
     <script type="text/javascript">
        $(document).ready(function() {

            $('#setting_default').select2({
                placeholder: 'Select Default Value'
            });
            $('#setting_name').val('');
            $('#setting_default').val('');
        });

        $('.btn_destroy').click(function (event) {
            var temp = confirm("Do you want to remove the entry?\n It will remove this Setting from all the outlets.");
            if (temp != true) {
                event.preventDefault();
            }
        });

        $('.confirm').click(function (e) {
            alert("hi");
            location.reload(true);

        });

        function addSetting() {

            if ( $('#add_setting').valid() ) {

                var setting_name = $('#setting_name').val();
                var setting_default = $('#setting_default').val();
                if(setting_name == ''){
                    $('#setting_name').css('border-color', 'red');
                    return;
                }else{
                    $('#setting_name').css('border-color', '');
                }
                if(setting_default == ''){
                    $('#setting_default').css('border-color', 'red');
                    return;
                }else{
                    $('#setting_default').css('border-color', '');
                }

                $('#add-btn').text('Processing...');
                $('#add-btn').prop('disabled',true);

                $.ajax({
                    url:'/settings/add',
                    Type:'POST',
                    dataType:'json',
                    data:'setting_name='+setting_name+'&setting_default='+setting_default,
                    success:function(data){
                        $('#add-btn').text('Add');
                        $('#add-btn').prop('disabled',false);

                        if ( data.status == 'success' ) {
                            swal({
                                title: "Success",
                                text: "Setting added Successfully!",
                                type: "success",
                                confirmButtonColor: "#4caf50"
                            },function() {
                                relode('add','');
                            });
                            //
                        } else if ( data.status == 'error' ) {
                            alert('There is some error ocurred, Please try again later');
                        } else {
                            alert('Please fill all ther field');
                        }
                    }
                });

            }
        }


        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Settings Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal({
                            title : "Deleted!",
                            text : "Your Settings Details has been removed.",
                            type : "success"
                        },
                        function() {
                            var route = "/settings/"+id+"/destroy";
                            relode('del',route);
                    });
                } else {
                    swal("Cancelled", "Your Settings Details are safe :)", "error");
                }
            });
        }

        function relode(str,route) {
            if(str == 'del'){
                window.location.replace(route);
            }else{
                location.reload(true);
            }
        }
    </script>
@stop