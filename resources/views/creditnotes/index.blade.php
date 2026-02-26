@extends('partials.default')
@section('pageHeader-left')
    Credit Notes List
@stop
@section('pageHeader-right')
    <a href="/credit-note/create" class="btn btn-primary" title="Add New Credit Note"><i class="fa fa-plus"></i> Credit Note</a>
    <style>

        .box {
            border:1px solid #BBB;
            background:#eee;
            position:relative;
        }
        .ribbon {
            position: absolute;
            right: -5px; top: -5px;
            z-index: 1;
            overflow: hidden;
            width: 75px; height: 75px;
            text-align: right;
        }
        .ribbon_red {
            position: absolute;
            right: -5px; top: -5px;
            z-index: 1;
            overflow: hidden;
            width: 75px; height: 75px;
            text-align: right;
        }
        .ribbon_blue {
            position: absolute;
            right: -5px; top: -5px;
            z-index: 1;
            overflow: hidden;
            width: 75px; height: 75px;
            text-align: right;
        }
        .ribbon span {
            font-size: 10px;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold; line-height: 20px;
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg); /* Needed for Safari */
            width: 100px; display: block;
            background: #79A70A;
            background: linear-gradient(#9BC90D 0%, #79A70A 100%);
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 19px; right: -21px;
        }
        .ribbon span::before {
            content: '';
            position: absolute;
            left: 0px; top: 100%;
            z-index: -1;
            border-left: 3px solid #79A70A;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #79A70A;
        }
        .ribbon span::after {
            content: '';
            position: absolute;
            right: 0%; top: 100%;
            z-index: -1;
            border-right: 3px solid #79A70A;
            border-left: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #79A70A;
        }

        .ribbon_red span {
            font-size: 10px;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold; line-height: 20px;
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg); /* Needed for Safari */
            width: 100px; display: block;
            background: #8F0808;
            background: linear-gradient(#F70505 0%, #8F0808 100%);
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 19px; right: -21px;
        }
        .ribbon_red span::before {
            content: '';
            position: absolute;
            left: 0px; top: 100%;
            z-index: -1;
            border-left: 3px solid #8F0808;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #8F0808;
        }
        .ribbon_red span::after {
            content: '';
            position: absolute;
            right: 0%; top: 100%;
            z-index: -1;
            border-right: 3px solid #8F0808;
            border-left: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #8F0808;
        }

        .ribbon_blue span {
            font-size: 10px;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold; line-height: 20px;
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg); /* Needed for Safari */
            width: 100px; display: block;
            background: #1e5799;
            background: linear-gradient(#2989d8 0%, #1e5799 100%);
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 19px; right: -21px;
        }
        .ribbon_blue span::before {
            content: '';
            position: absolute;
            left: 0px; top: 100%;
            z-index: -1;
            border-left: 3px solid #1e5799;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #1e5799;
        }
        .ribbon_blue span::after {
            content: '';
            position: absolute;
            right: 0%; top: 100%;
            z-index: -1;
            border-right: 3px solid #1e5799;
            border-left: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #1e5799;
        }
        b {
          font-weight: bold;
        }
        th {
          font-weight: bold;
        }
    </style>
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
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="CNTable">
                            <thead>
                            <tr>
                                <th> Date </th>
                                <th> Credit Note# </th>
                                <th> Referance# </th>
                                <th> CustomerName </th>
                                <th> Invoice# </th>
                                <th> Status </th>
                                <th> Amount </th>
                                <th> Balance </th>
                                <th data-sort-ignore="true"> Action </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $note)

                                <tr class="odd gradeX" onclick="getCN({{$note['id'] or ''}})">
                                    <td>{{$note['cn_date'] or ''}}</td>
                                    <td>{{$note['cn_no'] or ''}}</td>
                                    <td>{{$note['reference_no'] or ''}}</td>
                                    <td>{{$note['customer_name'] or ''}}</td>
                                    <td>{{$note['invoice_no'] or ''}}</td>
                                    <td>{{$note['status'] or ''}}</td>
                                    <td>{{$note['amount'] or ''}}</td>
                                    <td>{{$note['balance'] or ''}}</td>
                                    <td><a href="#" onclick="getCN({{$note['id'] or '' }})" title="Edit">
                                            <i class="zmdi zmdi-file-text" aria-hidden="true"></i>
                                        </a>&nbsp;|&nbsp;<a href="/tax/{{$note['id'] or '' }}/edit" title="Edit">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>&nbsp;|&nbsp;<a href="/tax/{{$note['id'] or ''}}/destroy" title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a></td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
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
    <div id="creditNoteShow" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- creditnotes.printView page load in model -->
            </div>

        </div>
    </div>

@stop
@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#CNTable').footable();
        });

        function getCN(cn_id) {

            $.ajax({
                url: '/getCraditNote',
                type: "post",
                data: { cn_id:cn_id},
                success: function (data) {
                    $('#creditNoteShow .modal-content').html(data);
                    $('#creditNoteShow').modal('show');
                }
            });
        }

        function printData()
        {
            var divToPrint=document.getElementById("creditnote");
            newWin= window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }

    </script>
@stop
