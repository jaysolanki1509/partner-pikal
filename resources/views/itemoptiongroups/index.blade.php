@extends('partials.default')
@section('pageHeader-left')
    Item Option Groups Details
@stop

@section('pageHeader-right')
    <a href="/item-option-group/create" class="btn btn-primary"><i class="fa fa-plus"></i> Item Option Group</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" id="itemOptionGroupTable" data-page-size="100" data-limit-navigation="4">
                            <thead>
                                <th>Name</th>
                                <th>Maximum Select</th>
                                <th>Group Options</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @if ( isset($option_groups) && sizeof($option_groups) > 0)
                                    @foreach( $option_groups as $group )
                                        <tr>
                                            <td>{{ $group['name'] }}</td>
                                            <td>{{ $group['max'] }}</td>
                                            <td>
                                                @if ( isset($group['item_option']) && sizeof($group['item_option']))
                                                    @foreach( $group['item_option'] as $option )

                                                        <span>{{ $option['item_name'] }}</span><br>

                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <a class="row-edit" href="/item-option-group/{!! $group['id'] !!}/edit"><span class="zmdi zmdi-edit"></span></a>
                                                <a class="row-edit" onclick="del({!! $group['id'] !!})" href="#"><span class="zmdi zmdi-close"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>

                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="hide-if-no-paging">
                            <tr>
                                <td colspan="6" class="footable-visible">
                                    <div class="pagination pagination-centered"></div>
                                </td>
                            </tr>
                            </tfoot>
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

            $("#itemOptionGroupTable").footable({
                phone:767,
                tablet:1024
            })
        });
        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Item option group!",
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
                        text : "Your Location Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/item-option-group/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Location Details are safe :)", "error");
                }
            });

        }
    </script>
@stop