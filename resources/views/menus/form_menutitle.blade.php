@extends('partials.default')
@section('pageHeader-left')
    Category Details
@stop

@section('pageHeader-right')
    <a href="/menutitle" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form id="category_form" class="form-horizontal material-form j-forms">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="title" class="col-md-12 control-label">Category</label>
                                    <div class="col-md-12">
                                        {!! Form::input('text','title' ,isset($title)?$title:null, ['class' => 'col-md-8 form-control', 'placeholder'=> 'Enter Menu Category','id'=>'title'] ) !!}
                                        <label id="title_error" class="error hide" for="title">*Title is required</label>
                                        {!! Form::input('hidden','cat_id' ,isset($cat_id)?$cat_id:null, ['class' => 'col-md-8 form-control hide','id'=>'cat_id'] ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="title" class="col-md-12 control-label">Title Order</label>
                                    <div class="col-md-12">
                                        {!! Form::input('number','title_order' ,isset($title_order)?$title_order:null, ['class' => 'col-md-8 form-control', 'placeholder'=> 'Enter Title Order','id'=>'title_order','required'] ) !!}
                                        <label id="title_order_error" class="error hide" for="title">*Title Order is required</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="title" class="col-md-12 control-label">Active</label>
                                    <div class="col-md-12">
                                        @if(isset($active) && !empty($active))
                                            @if($active==0)
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        <input type="radio" name="active" id="act_yes" value="0" checked="checked">
                                                        <i></i>Yes
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        <input type="radio" name="active" id="act_no" value="1">
                                                        <i></i>No
                                                    </label>
                                                </div>
                                            @elseif($active==1)
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        <input type="radio" name="active" id="act_yes" value="0">
                                                        <i></i>Yes
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        <input type="radio" name="active" id="act_no" value="1" checked="checked">
                                                        <i></i>No
                                                    </label>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-md-6">
                                                <label class="radio">
                                                    <input type="radio" name="active" id="act_yes" value="0">
                                                    <i></i>Yes
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="radio">
                                                    <input type="radio" name="active" id="act_no" value="1" checked="checked">
                                                    <i></i>No
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="title" class="col-md-12 control-label">For Sale</label>
                                    <div class="col-md-12">
                                        @if(isset($is_sale) && !empty($is_sale))
                                            @if($is_sale==0)
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        {!! Form::radio('is_sale', '1',null,['id'=>'is_sale_yes']) !!}
                                                        <i></i>Yes
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        {!! Form::radio('is_sale', '0',1,['id'=>'is_sale_no']) !!}
                                                        <i></i>No
                                                    </label>
                                                </div>
                                            @elseif($is_sale==1)
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        {!! Form::radio('is_sale', '1',1,['id'=>'is_sale_yes']) !!}
                                                        <i></i>Yes
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="radio">
                                                        {!! Form::radio('is_sale', '0',null,['id'=>'is_sale_no']) !!}
                                                        <i></i>No
                                                    </label>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-md-6">
                                                <label class="radio">
                                                    {!! Form::radio('is_sale', '1',null,['id'=>'is_sale_yes']) !!}
                                                    <i></i>Yes
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="radio">
                                                    {!! Form::radio('is_sale', '0',1,['id'=>'is_sale_no']) !!}
                                                    <i></i>No
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <label class="checkbox">
                                            @if($action=="add")
                                                {!! Form::checkbox('is_inventory_category', 1,false,["id"=>"is_inventory_category"]) !!}
                                            @else
                                                @if($is_inventory_category == 1)
                                                    {!! Form::checkbox('is_inventory_category', 1,true,["id"=>"is_inventory_category"]) !!}
                                                @else
                                                    {!! Form::checkbox('is_inventory_category', 1,false,["id"=>"is_inventory_category"]) !!}
                                                @endif
                                            @endif
                                            <i></i><span style="font-weight: bold"> Is Inventory Category </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    @if($action=='edit')
                                        <button name='saveExit' id='saveExit' onclick="title_change('update','')" class="btn btn-success primary-btn" type="button" value='true'>Update</button>
                                        {!! HTML::decode(HTML::linkRoute('menu.indextitle','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                                    @else
                                        <button name='saveExit' id='saveExit' onclick="title_change('add','exit')"  class="btn btn-success primary-btn"  type="button" value="true" >Save & Exit</button>
                                        <button name='saveContinue' id='saveContinue' onclick="title_change('add','continue')" class="btn btn-success primary-btn" type="button" value="true">Save & Continue</button>
                                        <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script>

        function title_change(btn_click, flag){

            if($('#title').val() == ''){
                $("#title_error").removeClass('hide');
                return;
            }

            if($('#title_order').val() == ''){
                $("#title_order_error").removeClass('hide');
                return;
            }

            if ( btn_click == "add" ) {

                var is_sale = $('input[name=is_sale]:checked').val();
                var active = $('input[name=active]:checked').val();
                var custom_title = $("#title").val();
                var title_order = $("#title_order").val();
                var is_inventory_category = $('#is_inventory_category').is(':checked');
                $.ajax({
                    url:'/ajax/title_change',
                    Type:'POST',
                    dataType:'json',
                    data: { btn_click : btn_click,
                        custom_title : custom_title,
                        active : active,
                        is_sale : is_sale,
                        title_order : title_order,
                        is_inventory_category : is_inventory_category
                    },
                    success:function(data){
                        if ( data == '1') {

                            if ( flag == 'exit' ) {
                                window.location.replace("/menutitle");
                            } else {
                                window.location.replace("/title");
                            }

                        } else if( data == '0' ) {
                            alert('Category is already available.');
                        }
                    }
                });

            } else if(btn_click == "update"){
                var edited_title = $("#title").val();
                var active = $('input[name=active]:checked').val();
                var item_id = $('#cat_id').val();
                var title_order = $('#title_order').val();
                var is_sale = $('input[name=is_sale]:checked').val();
                var is_inventory_category = $('#is_inventory_category').is(':checked');
                $.ajax({
                    url:'/ajax/title_change',
                    Type:'POST',
                    dataType:'json',
                    data: { btn_click : btn_click,
                        edited_title : edited_title,
                        active : active,
                        is_sale : is_sale,
                        item_id :item_id,
                        title_order :title_order,
                        is_inventory_category : is_inventory_category
                    },
                    success:function(data){
                        if(data == '1')
                            window.location.replace("/menutitle");
                        else if(data == '0')
                            alert('Category is already available.');
                    }
                });
            }

        }

    </script>

@stop