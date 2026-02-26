<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table class="table table-striped table-bordered table-hover">

                        <tr style="background-color: #428bca; ">
                            <th style="text-align: center; font-size: 14px !important;" class="col-md-3">Owner</th>
                            <th style="text-align: center; font-size: 14px !important;" class="col-md-3">Set Level</th>
                            <th style="text-align: center; font-size: 14px !important;" class="col-md-3">Fetch Log</th>
                            <th style="text-align: center; font-size: 14px !important;" class="col-md-3">Delete Log</th>
                        </tr>
                        <?php
                        if(sizeof($devices)>0){?>
                        <tr>
                            <td style="font-size: 14px !important;"> {{ $devices->user_name }} </td>
                            <td style="font-size: 14px !important;">
                                <select id="log_level" class='form-control' onchange="send('level','{{$devices->device_id}}')">
                                    @foreach($levels as $id=>$level)
                                        @if($id == $selected_level)
                                            <option value="{{$id}}" selected>{{ $level }}</option>
                                        @else
                                            <option value="{{$id}}">{{ $level }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            {{--<td> {!! Form::select('log_level', $level,null,array('onchange'=>'send("level",device_id)','class' => 'form-control','id'=>'log_level')) !!} </td>--}}
                            <td align="center"> <button type="button" class="btn btn-primary" id="show_btn" onclick="send('fatch','{{$devices->device_id}}')" style="margin-left: 5px;">Send</button> </td>
                            <td align="center"> <button type="button" class="btn btn-primary" id="show_btn" onclick="send('delete','{{$devices->device_id}}')" style="margin-left: 5px;">Send</button> </td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td colspan="4" align="center">No Expense Found.</td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>