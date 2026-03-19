<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='edit')
                    {!! Form::model($printer,['route' => array('printers.update',$printer->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'printerForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'printers.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'printerForm']) !!}
                @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('printer_name','Printer Name:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('printer_name', null, array('class' => 'form-control','id' => 'printer_name', 'placeholder'=> 'Printer Name')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('printer_mfg','Printer Mfg:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('printer_mfg',array('hoin'=>'HOIN','epson'=>'EPSON','epos-international' => 'E-PoS International'), null, array('class' => 'select2 form-control','id' => 'printer_mfg')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('mac_address','MAC Address:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12 input-group" style="padding-left: 15px;width:97%;">
                                    <span class="input-group-addon" id="sizing-addon1" >TCP:</span>
                                    {!! Form::text('mac_address', null, array('class' => 'col-md-3 form-control','id' => 'mac_address', 'placeholder'=> 'MAC Address')) !!}
                                </div>
                                <div class="col-md-12 input-group" style="padding-top: 10px; padding-left: 15px;width:97%;">
                                    <span class="input-group-addon" id="sizing-addon1" >IP:</span>
                                    {!! Form::text('printer_ip', null, array('class' => 'col-md-3 form-control','id' => 'printer_ip', 'placeholder'=> 'IP Address')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('printer_type','Printer Type:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('printer_type',array('bluetooth'=>'Bluetooth Printer','network'=>'Network Printer'), null, array('class' => 'select2 form-control','id' => 'printer_type')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">
                            <button type="submit" id="add-btn" class="btn btn-success primary-btn">@if($action=='edit'){!! 'Update' !!} @else {!! 'Add' !!}  @endif</button>
                            <button type="button" onclick="window.location.href ='/printers'" id="add-btn" class="btn btn-danger primary-btn">Cancel</button>
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            jQuery.validator.addMethod('MACChecker', function(value) {
                var mac = "^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$";
                return value.match(mac);
            }, ' Invalid MAC Address');

            $.validator.addMethod('IP4Checker', function(value) {
                var ip = "^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}" +
                        "(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$";
                return value.match(ip);
            }, 'Invalid IP Address');

            $("#printerForm").validate({
                rules: {
                    "printer_name": {
                        required: true
                    },
                    "mac_address": {
                        required: function (element) {
                            if($("#printer_type").val() == 'bluetooth'){
                                return true ;
                            }
                            else
                            {
                                return false;
                            }
                        }
                    },
                    "printer_ip": {
                        required: function (element) {
                            if($("#printer_type").val() == 'network'){
                                return true ;
                            }
                            else
                            {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    "printer_name": {
                        required: "Enter printer name"
                    },
                    "mac_address": {
                        MACChecker: "Enter valid Mac Address"
                    },
                    "printer_ip": {
                        IP4Checker: "Enter valid Ip Address"
                    }
                }

            });

        });

    </script>
@stop