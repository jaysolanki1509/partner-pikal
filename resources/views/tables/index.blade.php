
@extends('partials.default')
@section('pageHeader-left')
    {{ $order_lable }} Details
@stop

@section('pageHeader-right')
    <a href="/tables/create" class="btn btn-primary"><i class="fa fa-plus"></i> {{ $order_lable }}</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="TablesTable">
                            <thead>
                                <th>{{ $order_lable }} No.</th>
                                <th>{{ $order_lable }} Level</th>
                                <th>No.Of Person</th>
                                <th data-hide="phone">Occupied</th>
                                <th data-hide="phone">Occupied By</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                            @if(isset($tables) && sizeof($tables) > 0 )
                                @foreach($tables as $tab)
                                    <?php
                                            $tbl_level_name = 'Level 0';
                                            if( isset($tab->table_level_id) && $tab->table_level_id != 0 ) {

                                                $table_level = \App\TableLevel::find($tab->table_level_id);

                                                if( isset($table_level) && sizeof($table_level) > 0 ) {
                                                    $tbl_level_name = $table_level->name;
                                                }
                                            }
                                    ?>
                                    <tr class="odd gradeX">
                                        <td>{!! $tab->table_no !!}</td>
                                        <td>{!! $tbl_level_name !!}</td>
                                        <td>{!! $tab->no_of_person !!}</td>
                                        <td>{{ $tab->status==0?'No':'Yes' }}</td>
                                        <td>{{ $tab->occupied_by==''?'N/A':$tab->occupied_by }}</td>
                                        {{--<td>{{ $tab->occupied_by==''?'N/A':\App\Owner::find($tab->occupied_by)->name }}</td>--}}

                                        <td>
                                            <a href="/tables/{!! $tab->id !!}/edit"> <span class="zmdi zmdi-edit" ></span></a>&nbsp;&nbsp;&nbsp;
                                            <a onclick="del('{!! $tab->id !!}')" href="#"><span class="zmdi zmdi-close" ></span></a>
                                            @if($tab->status == 1)
                                                <a class="btn btn-primary" title="Unoccupy" onclick="unoccupy(this,'{!! $tab->id !!}')" href="#"><i class="fa fa-unlock"></i></a>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                   <td colspan="7" >No Table Found</td>
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
                var route = "/tables/"+id+"/destroy"
                window.location.replace(route);
            }
        }
        function unoccupy(ele,id) {
            var temp = confirm("Confirm To Unoccupy?");
            if (temp == true) {
                var route = "/tables/"+id+"/unoccupy"
                window.location.replace(route);
            }
        }

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Table Details!",
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
                        text : "Your Table Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/tables/"+id+"/destroy"
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Table Details are safe :)", "error");
                }
            });

        }

    </script>
@stop