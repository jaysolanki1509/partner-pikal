
@extends('partials.default')
@section('pageHeader-left')
    {{ $order_lable }} Level Details
@stop

@section('pageHeader-right')
    <a href="/table-levels/create" class="btn btn-primary"><i class="fa fa-plus"></i> {{ $order_lable }} Level</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="table_levels">
                            <thead>
                                <th>Name</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if(isset($tablelevels) && sizeof($tablelevels) > 0 )
                                    @foreach($tablelevels as $tab)
                                        <?php
                                        ?>
                                        <tr class="odd gradeX">
                                            <td>{!! $tab->name !!}</td>
                                            <td>
                                                <a href="/table-levels/{!! $tab->id !!}/edit"> <span class="zmdi zmdi-edit" ></span></a>&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $tab->id !!}')" href="#"><span class="zmdi zmdi-close" ></span></a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                       <td colspan="3" >No table level found</td>
                                    </tr>
                                @endif
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
@stop
@section('page-scripts')
    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif
        });
        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/table-levels/"+id+"/destroy"
                window.location.replace(route);
            }
        }

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this table level Details!",
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
                        text : "Your table level has been removed.",
                        type : "success"
                    },function() {
                        var route = "/table-levels/"+id+"/destroy"
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your table level details are safe :)", "error");
                }
            });

        }

    </script>
@stop