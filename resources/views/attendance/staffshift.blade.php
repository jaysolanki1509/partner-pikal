@extends('partials.default')
@section('pageHeader-left')
    Staff Shifts Detail
@stop

@section('pageHeader-right')
    <a href="/staff-shifts/create" class="btn btn-primary" title="Add new Shift"><i class="fa fa-plus"></i> Staff Shift</a>
@stop

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="shiftTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Shift</th>
                                <th>Slots</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                                @if(isset($shifts) && sizeof($shifts) > 0 )
                                    @foreach($shifts as $sft)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $sft->id !!}</td>
                                            <td>{!! $sft->name !!}</td>
                                            <?php $slot='';?>
                                            @if( isset($sft->slots))
                                                <?php $slt_arr = json_decode($sft->slots,true); ?>
                                                @foreach( $slt_arr as $slt )
                                                    <?php $slot .= "From: ".$slt['from']." To: ".$slt['to']."<br>";?>
                                                @endforeach
                                            @else
                                                <?php $slot = '-';?>
                                            @endif
                                            <td>{!! $slot !!}</td>
                                            <td>
                                                <a href="/staff-shifts/{!! $sft->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
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

            $('#shiftTable').DataTable({
                responsive: true,
                "order": [[ 0, "desc" ]],
                pageLength: 100
            });
        });
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