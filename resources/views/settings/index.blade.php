
@extends('partials.default')
@section('pageHeader-left')
    Application Settings
@stop
@section('pageHeader-right')
    @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'test' || Auth::user()->user_name == 'Niyati')
        <a href="/settings/add" class="btn btn-primary">Add Settings</a>
    @endif
@stop

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" id="updateSetting" class="form" name="updateSetting">
                        <div class="form-group">
                            <div class="form-group col-md-12 @if ($errors->has('name'))has-error @endif" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    {!! Form::label('outlet','Outlet:') !!}
                                </div>
                                <div class="col-md-5">
                                    {!! Form::select('outlet_id',$outlets, null, array('class' => 'form-control','id' => 'outlet_id','onChange' => 'getSittings(this.value)')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="widget-container">
                            <div class="widget-content">
                                <div class="dataTable_wrapper" align="center">
                                    <table class="table table-striped table-bordered table-hover" id="SettingsTable" style="width: 60%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%; text-align: center;">Settings Name</th>
                                                <th style="width: 50%; text-align: center;">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($master as $setting)
                                                <tr class="odd gradeX">
                                                    <td style="width: 50%;">{{ $setting->setting_name }}</td>
                                                    <td>{!! Form::text('settings['.$setting->id.']',null, array('class'=>'form-control','id'=>$setting->id,'placeholder'=> $setting->setting_name, 'required')) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-10" align="right">
                                    <button type="button" onclick="updateSettings()" id="add-btn" class="btn btn-success primary-btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
@stop

@section('page-scripts')
    <script>

        $(document).ready(function() {

            if($('#outlet_id').val() != '') {
                getSittings($('#outlet_id').val());
            }
        });

        function getSittings(outlet_id) {

            $('#SettingsTable td input').val('');

            if(outlet_id==''){
                alert('Please Select Outlet.');
            }else{
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
        }



        function updateSettings() {

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
            var data = $('#updateSetting').serialize();

            $.ajax({
                url:'/update-settings',
                Type:'POST',
                dataType:'json',
                data:data,
                success:function(data){
                    $('#add-btn').text('Add');
                    $('#add-btn').prop('disabled',false);

                    if ( data.status == 'success' ) {
                        location.reload(true);
                    } else if ( data.status == 'error' ) {
                        alert('There is some error ocurred, Please try again later');
                    } else {
                        alert('Please fill all ther field');
                    }
                }
            });
        }

    </script>
@stop