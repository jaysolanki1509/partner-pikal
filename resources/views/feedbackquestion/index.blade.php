@extends('partials.default')
@section('pageHeader-left')
    Feedback Questions
@stop

@section('pageHeader-right')
    <a href="/feedback-question/create" class="btn btn-primary"><i class="fa fa-plus"></i> Feedback Question</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" id="fb_quest_table" data-page-size="100" data-limit-navigation="4">
                            <thead>
                            <th>Question</th>
                            <th>Action</th>
                            </thead>

                            <tbody>
                            @if(isset($questions) && sizeof($questions) > 0 )
                                @foreach($questions as $quest)
                                    <tr>
                                        <td>{!! $quest->question !!}</td>
                                        <td>
                                            <a class="row-edit" href="/feedback-question/{!! $quest->id !!}/edit"><span class="zmdi zmdi-edit"></span></a>
                                            <a class="row-edit" onclick="del({!! $quest->id !!})" href="#"><span class="zmdi zmdi-close"></span></a>
                                        </td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"> No record found.</td>
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

            $("#fb_quest_table").footable({
                phone:767,
                tablet:1024
            })
        });
        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Question Details!",
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
                        text : "Your Question Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/feedback-question/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Question Details is safe :)", "error");
                }
            });

        }
    </script>
@stop