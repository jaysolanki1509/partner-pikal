<?php
?>

@extends('partials.default')
@section('pageHeader-left')
    Customers Detail
@stop

@section('pageHeader-right')

@stop

@section('page-styles')
    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/rowReorder.dataTables.min.css') !!}
    {!! HTML::style('/assets/css/responsive.dataTables.min.2.1.1.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}

@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="table-responsive">
                            <div class="result"></div>
                            <table  class="table dataTable" id="customerTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th title="Name">Name</th>
                                        <th title="Mobile">Mobile</th>
                                        <th title="Email">Email</th>
                                        <th title="Visits">Visits</th>
                                        <th title="LastVisit">Last Visit</th>
                                        <th title="AvgBill">Avg Bill</th>
                                        <th title="Action">Action</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr class="field-input whitebg">
                                        <th style="text-align: center"> </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sendMailModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice Stock Detail</h4>
                </div>
                <div class="modal-body">
                    <label for="no_email" id="no_email" class="hide control-label error"></label><br>

                    <label for="price" class="control-label"> Send To*</label>
                    {!! Form::input('text','send_to',Null, ['placeholder'=>'Mail Send to*', 'class' => 'form-control','required', 'id' => 'send_to']) !!}

                    <label for="price" class="control-label"> Select Template*</label>
                    {!! Form::select('template',$templates,"", ['class' => 'form-control','required', 'id' => 'template']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" id="mail_send" class="btn btn-primary" onclick="sendMail()">Send Mail</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@stop
@section('page-scripts')

    {!! HTML::script('/assets/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('/assets/js/dataTables.responsive.js') !!}
    {!! HTML::script('/assets/js/datatable.list.js') !!}



    <script type="text/javascript">

        var selected = [];
        var emails = [];
        var table = '';

        function sendBtn(flag) {
            $("#send_to").val("");
            $("#template").val("");
            $("#no_email").text("");

            var ids = selected;

            if ( flag == 'show') {
                if ( ids.length == 0 ) {
                    alert('Please select one or multiple Customers.');
                    return;
                }
                $.ajax({
                    url: '/getCustomerEmail',
                    type: "POST",
                    data: {ids: ids},
                    success: function (data) {
                        var customers = (data.email_array).join();
                        $("#send_to").val(customers);

                        if(data.no_email>0){
                            $("#no_email").text("*Note: You have selected "+data.no_email+" Customer with no Email Address.");
                            $("#no_email").removeClass("hide");
                        }else {
                            $("#no_email").addClass("hide");
                        }
                    }
                });

                $('#sendMailModal').modal('show');
            }
        }

        $(document).ready(function() {

            $('#last_visit').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            var tbl_id = 'customerTable';
            var order = 5;
            var url = '/customers';
            var columns = [
                { "mDataProp": "check_col","bSortable": false},
                { "mDataProp": "name"},
                { "mDataProp": "mobile" },
                { "mDataProp": "email" },
                { "mDataProp": "visits" },
                { "mDataProp": "last_visit" },
                { "mDataProp": "avg_bill" },
                { "mDataProp": "action","bSortable":false }
            ];

            loadList( tbl_id, url, order, columns, true);


        });

        //on checkbox click add id in array
        function selectRow(id) {

            var index = $.inArray(id, selected);
            var filter = $('#filter_query').val();

            if ( index === -1 ) {
                selected.push( id );

            } else {
                selected.splice( index, 1 );
                if ( !filter ) {
                    $('#sel_all').attr('checked', false);
                }

            }
        }

        function sendMail() {
            $('#mail_send').attr('disabled',true);
            $('#mail_send').text('Sending...');

            var customer = $("#send_to").val();
            var template = $("#template").val();

            if(customer.trim().length != "" && template.length != "") {
                $('#send_to').css('border-color', '');
                $('#template').css('border-color', '');

                $.ajax({
                    url: '/sendCustomerMail',
                    type: "POST",
                    data: {customer: customer, template:template},
                    success: function (data) {

                        if(data == "success"){
                            successErrorMessage('Mail Send Successfully','success');
                            $('#mail_send').removeAttr('disabled');
                            $('#mail_send').text('Send Mail');
                            $("#sendMailModal .close").click();
                        }else{
                            $('#mail_send').removeAttr('disabled');
                            $('#mail_send').text('Send Mail');
                            successErrorMessage('Error in mail send','error');
                        }

                    }
                });

            }else{
                if(customer.trim().length == "") {
                    $('#send_to').css('border-color', 'red');
                }else{
                    $('#send_to').css('border-color', '');
                }

                if(template.length == ""){
                    $('#template').css('border-color', 'red');
                }else{
                    $('#template').css('border-color', '');
                }
            }
        }

    </script>
@stop