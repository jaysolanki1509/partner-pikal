<html>
<head>
    @include('partials.styles')
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pikal</title>
    <link rel="icon" type="image/png" href="/bower_components/images/favicon.ico" />
    <link type="text/css" rel="stylesheet" href="/bower_components/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="/bower_components/css/style.css">
    <script type="text/javascript" src="/bower_components/js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="/bower_components/js/bootstrap.js"></script>


</head>

<body>

    <div class="full-width-container">
        <a href="/loglist" style=" margin:10px; float:right;" class="btn btn-primary">Log-List <i class="fa fa-forward"></i></a>
        <a href="/owner" style="margin:10px; float:right;" class="btn btn-primary"><i class="fa fa-dashboard"></i> Home</a>

        <div class="form-group">
            <div class="form-group col-md-12 @if ($errors->has('outlet_id'))has-error @endif">
                <div class="col-md-6">
                    {!! Form::label('outlet_id','Outlet:') !!}
                </div>
                <div class="col-md-12">
                    {!! Form::select('outlet_id',$outlets, null, array( 'required', 'class' => 'col-md-3 form-control','id' => 'outlet_id')) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group col-md-12">
                <div class="col-md-6">
                    <label class="rest">User/Owner</label>
                </div>
                <div class="col-md-12 form">
                    {!! Form::select('owner_id', array(''=>'Select User'), null, ['class' => 'form-control','required', 'id' => 'owner_id']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group col-md-12">
                <div class="col-md-6">
                    <label class="rest">DeviceList</label>
                </div>
                <div class="col-md-12 table" id="device_table">
                </div>
            </div>
        </div>

    </div>

</body>

<script type="text/javascript">

    $('#outlet_id').change(function () {
        var outleid = Number($('#outlet_id').val());
        $('#device_table').html('');
        $.ajax({
            dataType: 'JSON',
            url: '/ajax/outletby',
            type: "POST",
            data: {outlet_id: outleid},
            success: function (data) {
                var newOptions = '';
                newOptions += "<option value=''>Select User</option>";
                var data_id = "" + data.user_id;
                var data_name = data.user_name;
                var owner_id = data_id.split(",");
                for (var i = 0; i < owner_id.length; i++) {
                    owner_id[i] = +owner_id[i];
                        newOptions += '<option value="' + owner_id[i] + '">' + data_name[i] + '</option>';

                }

                jQuery('#owner_id')
                        .find('option')
                        .remove()
                        .end()
                        .append(newOptions);
            },
            error: function (error) {
                var newOptions = '';
                newOptions += '<option value="">Select User</option>';
                jQuery('#owner_id')
                        .find('option')
                        .remove()
                        .end()
                        .append(newOptions);
            }

        });

    });

    $('#owner_id').change(function () {
        var owner_id = Number($('#owner_id').val());

        $.ajax({
            url: '/ajax/getDevices',
            type: "POST",
            data: {owner_id: owner_id},
            success: function (data) {
                $('#device_table').html(data);
            },
            error: function (error) {

            }

        });

    });
    
    function send(flag,device_id) {

            //var level = $('#log_level').text();
            var level = $("#log_level option:selected").text();
            var outlet_id = $("#outlet_id").val();
            var owner_id = $("#owner_id").val();
            var level_id = $("#log_level").val();
            $.ajax({
                dataType: 'JSON',
                url: 'api/v3/send-log-notification',
                type: "POST",
                data: {flag: flag, device_id: device_id, level:level,outlet_id:outlet_id,owner_id:owner_id,level_id:level_id},
                success: function (data) {
                    alert('Notification sent Successfully.')
                }
            });
    }

</script>

</html>
