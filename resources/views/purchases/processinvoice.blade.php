
@extends('partials.default')
@section('pageHeader-left')
    Process Invoice
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
                    <form method="post" id="process_invoice_form" name="process_invoice_form">

                        <div class="col-md-8 form-group">
                            {!! Form::select('vendor' ,$vendors, null,array('class'=>'form-control', 'id' => 'vendor','required')) !!}
                        </div>

                        <div class="clearfix form"></div>

                        <div class="col-md-4 form-group">
                            {!! Form::text('from_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                        </div>

                        <div class="col-md-4 form-group">
                            {!! Form::text('to_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>

                        <div class="col-md-1 form-group">
                            <button type="button" onclick="findPaid()" id="paid-btn" class="btn btn-primary" >Unpaid</button>
                        </div>

                        <div class="col-md-2 form-group">
                            <button type="button" onclick="findInvoice()" id="invoice-btn" class="btn btn-primary">Uninvoiced</button>
                        </div>

                    </form>
                    <hr class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="dataTable_wrapper" id="purchase_invoice">
                                        No Recoreds found.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
@stop
@section('page-scripts')
    <script>
        function findInvoice() {

            if ( $('#process_invoice_form').valid() ) {

                var vendor = $('#vendor').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $('#invoice-btn').text('Loading...');
                $('#invoice-btn').prop('disabled',true);
                $.ajax({
                    url:'/processinvoice',
                    type:'POST',
                    data:'vendor='+vendor+'&from_date='+from_date+'&to_date='+to_date+'&flag=invoiced',
                    success:function(data){

                        $('#invoice-btn').text('Uninvoiced');
                        $('#invoice-btn').prop('disabled',false);
                        $('#purchase_invoice').html(data);
                    }
                });

            }
        }

        function findPaid() {

            if ( $('#process_invoice_form').valid() ) {

                var vendor = $('#vendor').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $('#paid-btn').text('Loadiing...');
                $('#paid-btn').prop('disabled',true);
                $.ajax({
                    url:'/processinvoice',
                    type:'POST',
                    data:'vendor='+vendor+'&from_date='+from_date+'&to_date='+to_date+'&flag=paid',
                    success:function(data){

                        $('#paid-btn').text('Unpaid');
                        $('#paid-btn').prop('disabled',false);
                        $('#purchase_invoice').html(data);
                    }
                });

            }
        }



        $(document).ready(function() {

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });

            $("#process_invoice_form").validate({
                rules: {
                    "vendor": {
                        required: true
                    }
                },
                messages: {
                    "vendor": {
                        required: "Select vendor"
                    }
                }

            });

            $('#processInvoiceTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });

        //select all checkboxes
        $("#select_all").change(function(){  //"select all" change
            var status = this.checked; // "select all" checked status
            alert(status);
            $('.checkbox').each(function(){ //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
            });
        });

        //uncheck "select all", if one of the listed checkbox item is unchecked
        $('.checkbox').change(function(){ //".checkbox" change
            if(this.checked == false){ //if this item is unchecked
                $("#select_all")[0].checked = false; //change "select all" checked status to false
            }
        });


    </script>
@stop