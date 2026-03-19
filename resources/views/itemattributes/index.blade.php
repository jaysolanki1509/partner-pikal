@extends('partials.default')
@section('pageHeader-left')
    Item Attributes
@stop

@section('pageHeader-right')
    <a href="/item-attributes/create" class="btn btn-primary" title="Add new Attribute"><i class="fa fa-plus"></i> Attribute</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" style="width: 100%" data-page-size="100" data-limit-navigation="4" id="AttributTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Name</th>
                                <th>Outlet</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($attributes) && sizeof($attributes) > 0 )
                                    @foreach($attributes as $att)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $att->id !!}</td>
                                            <td>{!! strlen($att->name) > 50 ? substr($att->name,0,50)."..." : $att->name !!}</td>
                                            <td>{!! $att->ot_name !!}</td>
                                            <td>
                                                <a href="/item-attributes/{!! $att->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $att->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
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

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script>
        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $('#AttributTable').DataTable({
            });
        });
        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {

                $.ajax({
                    url: '/item-attributes/destroy',
                    type: "POST",
                    data: {att_id: id},
                    success: function (data) {
                        if ( data == 'success' ) {
                            location.reload();
                        } else {
                            alert('please try again later.');
                        }
                    }
                });
            }
        }

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Attribute Details!",
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
                        text : "Your Attribute Details has been removed.",
                        type : "success"
                    },function() {
                        $.ajax({
                            url: '/item-attributes/destroy',
                            type: "POST",
                            data: {att_id: id},
                            success: function (data) {
                                if (data == 'success') {
                                    location.reload();
                                } else {
                                    alert('please try again later.');
                                }
                            }
                        });
                    });
                } else {
                    swal("Cancelled", "Your Attribute Details are safe :)", "error");
                }
            });

        }

    </script>
@stop