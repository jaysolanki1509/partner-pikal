
@extends('partials.default')
@section('pageHeader-left')
    Attendance Detail
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <form id="attendance" class="form-horizontal material-form j-forms">
                            @if( isset($outlet_id) && $outlet_id != '' )
                                @if( isset($staffs) && sizeof($staffs) > 0 )
                                    <table class="table table-hover" id="attendanceTable">

                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>In</th>
                                                    <th>Out</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach( $staffs as $st )
                                                    <tr id="row_{!! $st->id !!}">
                                                        <td>{!! $st->name !!}</td>
                                                        <td>

                                                            <label class="radio">
                                                                <input onchange="fillAttendance(this)" @if( $attendee[$st->id] == 'in') checked @endif  data-staff-id="{!! $st->id !!}" class="form-control" type="radio" value="in" name="attend_{!! $st->id !!}">
                                                                <i></i>&nbsp;
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label class="radio">
                                                                <input onchange="fillAttendance(this)" @if( $attendee[$st->id] == 'out') checked @elseif( $attendee[$st->id] == '' ) disabled @endif data-staff-id="{!! $st->id !!}" class="form-control" type="radio" value="out" name="attend_{!! $st->id !!}">
                                                                <i></i>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                    </table>
                                @else
                                    <tr><td>No staff has been added for this outlet.</td></tr>
                                @endif
                            @else
                                <h3 style="text-align:center; color: red;">Please select outlet.</h3>
                            @endif
                        </form>
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

    <script type="text/javascript">

        @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
        @endif
        @if(Session::has('error'))
            successErrorMessage('{{ Session::get('error') }}','error');
        @endif

        $(document).ready(function()
        {
            $("#staffTable").footable({
                phone:767,
                tablet:1024
            });

        });

        function fillAttendance(e) {

            var type = $(e).val();
            var staff_id = $(e).data('staff-id');

            $.ajax({
                url: '/fill-attendance',
                type: "post",
                data: { staff_id:staff_id,type:type},
                success: function (data) {
                    if ( data == 'success' ) {
                        if ( type == 'in' ) {
                            $('#row_'+staff_id).find(":radio[value=out]").removeAttr('disabled');
                        }
                    } else if ( data == 'not found') {
                        alert('No record found for user.');
                    } else {
                        alert('There is some error occurred, Please try again later.');
                    }
                }
            });

        }

        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/location/"+id+"/destroy"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop
