
@extends('partials.default')
@section('pageHeader-left')
    Sources Details
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form id="add_source" name="add_source">
                        <div class="form-group col-md-10">
                            <div class="col-md-2" style="margin-top: 5px">
                                {!! Form::label('name','Source Name:') !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Source Name')) !!}
                            </div>
                            <div class="col-md-2">
                                <button type="button" onclick="addSource();" id="add-btn" title="Add new Source" class="btn btn-success primary-btn">
                                    <i class="fa fa-plus"></i> Source </button>
                            </div>
                        </div>
                    </form>

                    <div class="widget-wrap material-table-widget">
                        <div class="widget-container margin-top-0">
                            <div class="widget-content">

                                <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="SourceTable">
                                    <thead>
                                        <th>Source Name</th>
                                        <th data-sort-ignore="true">Action</th>
                                    </thead>

                                    <tbody>
                                        @if ( isset($sources) && sizeof($sources) > 0 )
                                            @foreach($sources as $sr)
                                                <tr class="odd gradeX">
                                                    <td>{!! $sr->name or '' !!}</td>
                                                    <td>
                                                        <a href="#" onclick="del({!! $sr->id !!})" title="Delete">
                                                            <span class="zmdi zmdi-close"></span>
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
        </div>
    </div>
</div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

        @if(Session::has('success'))
            successErrorMessage('{{ Session::get('success') }}','success');
        @endif
        @if(Session::has('error'))
            successErrorMessage('{{ Session::get('error') }}','error');
        @endif

    $('#name').val('');

        $("#add_source").validate({
            rules: {
                "name": {
                    required: true
                }
            },
            messages: {
                "name": {
                    required: "Source name is required."
                }
            }

        });
    });

    function addSource() {

        if ( $('#add_source').valid() ) {

            var source_name = $('#name').val();

            $('#add-btn').text('Processing...');
            $('#add-btn').prop('disabled',true);

            $.ajax({
                url:'/add-source',
                Type:'POST',
                dataType:'json',
                data:'source_name='+source_name,
                success:function(data){
                    $('#add-btn').html('<i class="fa fa-plus"></i> Source</button>');
                    $('#add-btn').prop('disabled',false);

                    if ( data.status == 'success' ) {
                        swal({
                            title: "Success",
                            text: "Source added Successfully!",
                            type: "success",
                            confirmButtonColor: "#4caf50"
                        },function() {
                            location.reload(true);
                        });
                    } else if ( data.status == 'error' ) {
                        alert('There is some error ocurred, Please try again later');
                    } else {
                        alert('Please fill all ther field');
                    }
                }
            });
        }
    }

    function del(id) {

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Source Details!",
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
                    text : "Your Source Details has been removed.",
                    type : "success"
                },function() {
                    var route = "/source/"+id+"/destroy";
                    window.location.replace(route);
                });
            } else {
                swal("Cancelled", "Your Source Details are safe :)", "error");
            }
        });

    }

</script>
@stop