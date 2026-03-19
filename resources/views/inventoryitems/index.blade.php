@extends('partials.default')
@section('pageHeader-left')
    Item Details
@stop

@section('pageHeader-right')
    <a href="/inventoryitems/create" class="btn btn-primary">Add Item</a>
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
                        <table class="table table-striped table-bordered table-hover" id="inventoryitemtable">
                            <thead>
                            <th style="display: none">id</th>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Action</th>
                            </thead>

                            <tbody>
                            @foreach( $items as $itm )
                                <tr class="odd gradeX">
                                    <td style="display: none">{!! $itm->id !!}</td>
                                    <td>{!! $itm->category !!}</td>
                                    <td>{!! $itm->item !!}</td>
                                    <td>{!! $itm->price !!}</td>
                                    <td>
                                        <a class="btn btn-primary" href="/inventoryitems/{!! $itm->id !!}/edit"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-primary" href="/inventoryitems/{!! $itm->id !!}/destroy"><i class="fa fa-times"></i></a>
                                    </td>
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
@stop
@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#inventoryitemtable').DataTable({
                responsive: true,
                "order": [[ 1, "asc" ]],
                iDisplayLength: 100
            });
        });
    </script>
@stop