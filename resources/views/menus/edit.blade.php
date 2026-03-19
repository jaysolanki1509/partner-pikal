@extends('partials.default')
@section('pageHeader-left')
    Category Details
@stop

@section('pageHeader-right')
    <a href="/menu" class="btn btn-primary" title="Back"><i class="fa fa-backward"></i> Back</a>
    <a href="/title" class="btn btn-primary" title="Category"><i class="fa fa-plus"></i> Category</a>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap material-table-widget">
            <div class="widget-container margin-top-0">
                <div class="widget-content">

                    <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="TablesTable">
                        <thead>
                            <th style="display: none">id</th>
                            <th>Category</th>
                            <th data-sort-ignore="true">Action</th>
                        </thead>
                        <tbody>
                            @if(isset($titles) && sizeof($titles) > 0 )
                                @foreach($titles as $id=>$title)
                                    <tr class="odd gradeX">
                                        <td style="display: none">{!! $id !!}</td>
                                        <td>{!! $title !!}</td>
                                        <td>
                                            <a href="/title/{!! $id !!}/edit" title="Edit">
                                                <span class="zmdi zmdi-edit" ></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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
    <script type="text/javascript">
        $(document).ready(function() {
        });

        function customTitle(menu_title){
            if(menu_title != "custom"){
                $.ajax({
                    url:'/check_menu_title',
                    Type:'GET',
                    dataType:'json',
                    data:'title_id='+menu_title,
                    success:function(data){
                        if(data.active=="0")
                            $("#act_yes").prop("checked", true);
                        else
                            $("#act_no").prop("checked", true);
                        //alert(data.is_sale);
                        if(data.is_sale=="1")
                            $("#is_sale_yes").prop("checked", true);
                        else
                            $("#is_sale_no").prop("checked", true);
                    }
                });
            }else{
                $(".edit_field").removeClass("hide");
            }

            var opetion = "";
            $("#btn_add").addClass("hide");
            $("#btn_update").addClass("hide");
            if(menu_title == "custom"){
                $("#custom_title").show();
                $(".edit_field").addClass("hide");
                $(".custom").removeClass("hide");
                $("#btn_add").removeClass("hide");
            }else if(menu_title != ""){
                $("#custom_title").hide();
                $(".edit_field").removeClass("hide");
                $("#btn_update").removeClass("hide");
                opetion = $("#title option:selected").text();
            }else{
                $("#custom_title").hide();
                $(".edit_field").addClass("hide");
            }
            $("#edited_title").val(opetion);
        }

        function title_change(btn_click){

            if(btn_click == "add"){
                var is_sale = $('input[name=is_sale]:checked').val();
                var active = $('input[name=active]:checked').val();
                var custom_title = $("#custom_title").val();
                $.ajax({
                    url:'/ajax/title_change',
                    Type:'POST',
                    dataType:'json',
                    data: { btn_click : btn_click,
                            custom_title : custom_title,
                            active : active,
                            is_sale : is_sale
                            },
                    success:function(data){
                        window.location.replace("/menu/create");
                    }
                });

            }else if(btn_click == "update"){
                var edited_title = $("#edited_title").val();
                var active = $('input[name=active]:checked').val();
                var item_id = $('#title').val();
                var is_sale = $('input[name=is_sale]:checked').val();
                $.ajax({
                    url:'/ajax/title_change',
                    Type:'POST',
                    dataType:'json',
                    data: { btn_click : btn_click,
                            edited_title : edited_title,
                            active : active,
                            is_sale : is_sale,
                            item_id :item_id
                        },
                    success:function(data){
                        window.location.replace("/menu/create");
                    }
                });
            }



        }

    </script>

@stop