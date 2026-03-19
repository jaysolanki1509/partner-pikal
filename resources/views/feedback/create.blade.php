<?php
use App\Outlet;
use App\Owner;
use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>

@extends('partials.default')
@section('pageHeader-left')
    Feedback Design
@stop
@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    <?php  $m=0; ?>

                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="feedbackCreate" name="feedbackCreate" action="{{ url('/createform') }}" files="true" enctype="multipart/form-data">
                        @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">

                                        <label class="control-label">Outlet</label>
                                        <div class="col-md-12 form">
                                            {!! Form::select('outlet_id', $select_outlets, null, ['class' => 'form-control', 'id' => 'outlet_id','required', 'onchange'=>'getFeedback()']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                            <label id="warning_text" class="hide error">All fields are required.</label>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Create Fields</label><br/>
                                <div class="col-md-4">
                                    <input type="text" class="form-control field" maxlength="12" id="field_name<?php echo $m?>" required name="field_name<?php echo $m?>" placeholder="Enter Field(max:12 char)">
                                </div>

                                <div class="col-md-2">
                                    <select name="field_type<?php echo $m?>"  class="form-control" onchange="showField(this,<?php echo $m?>)">
                                        <option value="line">Line</option>
                                        <option value="options">Custom Text</option>
                                    </select>
                                </div>

                                <div class="col-md-5 form">
                                    <input type="number" class="form-control field" value="1" id="line_number<?php echo $m?>" value="1" name="line_number<?php echo $m?>" placeholder="Quantity" min="1" max="5">
                                    <input type="text" class="form-control field" required="required" maxlength="33" id="options_type<?php echo $m?>" style="display: none"  name="options_type<?php echo $m?>" placeholder="Enter Customised Text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input type=hidden name="count" value="<?php echo $m?>">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="addMore<?php echo $m;?>" title="Add new field"><i class="fa fa-plus"></i> Field</button>
                            </div>
                        </div>
                        <div class="form-footer">
                            <div class="col-md-12">
                                <input type="button" id="btnSubmit" value="Create Form" class="btn primary-btn btn-success">
                            </div>
                            <?php $m++; ?>
                            {!!Form::hidden('countf',$m,array('id'=>'countf'))!!}
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')
<script>

    function getFeedback(){
        var outlet_id = $('#outlet_id').val();
        $('#design_table').remove();
        $.ajax({
            url: '/getFeedback',
            type: "POST",
            data: {outlet_id:outlet_id},
            success: function (data) {
                $(data).insertAfter( "#countf" );
            }
        });
    }

    $(document).ready(function() {

        @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
        @endif
        @if(Session::has('error'))
            successErrorMessage('{{ Session::get('error') }}','error');
        @endif

            var session_outlet = $("#main_filter").val();
        if(session_outlet != ''){
            $.ajax({
                url: '/getFeedback',
                type: "POST",
                data: {outlet_id:session_outlet},
                success: function (data) {
                    $(data).insertAfter( "#countf" );

                }
            });
        }
    });

    $("#btnSubmit").click(function(e){
        validate_fields();
        e.preventDefault();

    });


    function showField(field_name,id){

        if(field_name.value.match('line')){
            $('#line_number'+id).show();
            $('#options_type'+id).hide();
        }
        else{
            $('#line_number'+id).hide();
            $('#options_type'+id).show();
        }
    }



        var someText='';
        var n = document.getElementById("countf").value;

        $('#addMore'+(n-1)).click(function()
        {
            var j = n-1;
            if($('#field_name'+j).val()==''){
                swal({
                    title: "Warning!",
                    text: "Please Fill up previous all fields!",
                    confirmButtonColor: "#DD6B55"
                });
                return;
            }
            var o = n++;

            if($('field_name'))
            someText =
                '<div id="control'+o+'">' +

                '<div class="form-group"><div class="row"><div class="col-md-12"><div class="col-md-4">' +
                    '<input type="text" class="form-control nming field" maxlength="12" id="field_name'+o+'" name="field_name'+o+'" placeholder="Enter Field(max:12 char)" required="required">' +
                '</div>' +

                '<div class="col-md-2">' +
                    '<select name="field_type'+o+'" id="field_type'+o+'" class="form-control" onchange="showField(this,'+o+')">' +
                        '<option value="line">Line</option>' +
                        '<option value="options">Custom Text</option>' +
                    '</select>' +
                '</div>' +

                '<div class="col-md-5">' +
                    '<input type="number" class="form-control qting" value=1 id="line_number'+o+'" name="line_number'+o+'" placeholder="Quantity" min="1" max="5">' +
                    '<input type="text" required="required" class="form-control field" id="options_type'+o+'" maxlength="33" style="display: none"  name="options_type'+o+'" placeholder="Enter Customised Text">'+
                '</div>' +

                    '<div class="col-md-1">' +
                        '<button type="button" class="btn btn-danger" onclick="control'+o+'.remove()"><i class="fa fa-times"></i></button><br>'+
                    '</div>'+
                '</div>' +
                '<input type=hidden name="count" value='+o+'>'+
            '</div>';

            var newDiv = $('<div class="row">').append(someText);
            $(this).before(newDiv);
        });



    function deleteRow(tableID) {
        try {
            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }
        }catch(e) {
            alert(e);
        }
    }

    function validate_fields(){
        var check=0;

        $(".field").each(function() {

           if($(this).is(":visible") && $(this).val()==""){
                check=1;
               $("#warning_text").removeClass('hide');
               swal({
                   title: "Warning!",
                   text: "Please Fill up previous all fields!",
                   confirmButtonColor: "#DD6B55"
               });
               return;
           }
            else{
               $(this).css('border-color','#ccc');
           }
        });

        if(check==0)
        {
            $("#warning_text").addClass('hide');
            document.getElementById('feedbackCreate').submit();
        }
    }

</script>
@stop