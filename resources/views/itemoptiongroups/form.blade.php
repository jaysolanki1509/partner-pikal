
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($itemoptiongroup,['route' => array('itemoptiongroups.update',$itemoptiongroup->id), 'method' => 'patch', 'id' => 'itemOptionGroupForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'itemoptiongroups.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'itemOptionGroupForm']) !!}
                @endif

                {!! Form::hidden('item_option_group_id', null, array('class' => 'col-md-3 form-control','id' => 'item_option_group_id')) !!}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::label('max','No. of Item Select:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('max',$max_arr ,null, array('class'=>'form-control','id' => 'max')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">

                            <a class="col-md-12" href="javascript:void(0)"><i class="fa fa-plus"></i> Item Options</a>

                            <div class="col-md-12" id="item_options_div">
                                <div class="form-group option-select-div">
                                    <div class="col-md-8">
                                        <select class="form-control" id="customize_item_id">
                                            <option value="0">Select Item</option>
                                            @if( isset($menu_items) && sizeof($menu_items) > 0 )
                                                @for( $i=0; $i < sizeof($menu_items); $i++ )
                                                    <option data-price="{{ $menu_items[$i]['price'] }}" value="{{ $menu_items[$i]['id'] }}">{{ $menu_items[$i]['name'] }}</option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 add-option-item-btn-div">
                                        <button type="button" class="btn primary-btn btn-success pull-left"  title="Add Item" onclick="addOptionItem()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                @if( isset($item_group_options) && sizeof($item_group_options) > 0 )
                                    @foreach( $item_group_options as $opt )
                                        <div class="form-group item-option">
                                            <div class="col-md-1">
                                                <input title="Make default selected" onchange="checkMaxSelect(this)" type="checkbox" @if( $opt['default'] == 1 ) checked @endif value="{{ $opt['id'] }}" name="option_item_default[]" class="form-control">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" value="{{ $opt['name'] }}"name="option_item_name[]" class="form-control" placeholder="Item name" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" value="{{ $opt['price'] }}" name="option_item_price[]" class="form-control" placeholder="Item price">
                                                <input type="text" value="{{ $opt['id'] }}" name="option_item_id[]" class="form-control option-item-id hide">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger" title="Remove Item" onclick="removeOptionItem(this,{{ $opt['id'] }})"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <hr class="col-md-8">
                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('location.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn" type="Submit" value="true">Save & Continue</button>
                            <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                        @endif
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $('#customize_item_id').select2();

            @if(Session::has('success'))
                    successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#itemOptionGroupForm").validate({
                rules: {
                    "name": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "*Name is required"
                    }
                }

            })

            //check any option added or not
            $("#itemOptionGroupForm").submit(function(e) {

                if ( $('.item-option').length == 0 ) {
                    successErrorMessage('Please add group options first.','error');
                    e.preventDefault();
                }
            });


        })

        function checkMaxSelect(ele) {

            var selected = 0;
            var max_select = $('#max').val();

            $('.item-option input:checkbox').each(function () {
                if ( this.checked ) {
                    selected++;
                }
            });

            if ( selected > max_select) {
                $(ele).removeAttr('checked');
                successErrorMessage('Maximum '+max_select+' option can be select.','error');
                return;
            }

        }

        function addOptionItem() {

            var item_id = $('#customize_item_id').val();
            var item_name = $('#customize_item_id').select2('data')[0];
            var item_price = $('#customize_item_id').find(":selected").data("price");
            var parent_id = $('#item_id').val();


            if ( item_id == 0 ) {
                successErrorMessage('Please select item','error');
                return;
            }

            //check repeat option item
            var check_option_item = 0;
            $( ".option-item-id" ).each(function( index ) {
                if ( $(this).val() == item_id ) {
                    check_option_item = 1;
                }
            });

            if ( check_option_item == 1 ) {
                successErrorMessage('This item has been added already','error');
                return
            }

            //alert(item_name.text)
            var html =  '<div class="form-group item-option">'+
                    '<div class="col-md-1">'+
                    '<input title="Make default selected" onchange="checkMaxSelect(this)" type="checkbox" value="'+item_id+'" name="option_item_default[]" class="form-control">'+
                    '</div>'+
                    '<div class="col-md-5">'+
                    '<input type="text" value="'+item_name.text+'"name="option_item_name[]" class="form-control" placeholder="Item name" readonly>'+
                    '</div>'+
                    '<div class="col-md-4">'+
                    '<input type="text" value="'+item_price+'" name="option_item_price[]" class="form-control" placeholder="Item price">'+
                    '<input type="text" value="'+item_id+'" name="option_item_id[]" class="form-control option-item-id hide">'+
                    '</div>'+
                    '<div class="col-md-2">'+
                    '<button type="button" class="btn btn-danger" title="Remove Item" onclick="removeOptionItem(this,'+item_id+')"><i class="fa fa-times"></i></button>'+
                    '</div>' +
                    '</div>';

            $(html).insertAfter(".option-select-div")
        }

        function removeOptionItem( ele, option_id ) {

            var item_option_group_id = $('#item_option_group_id').val();
            var option_id = option_id;

            if ( item_option_group_id == null ) {
                $(ele).parent().parent().remove()
                return;
            }

            $.ajax({
                url:'/remove-item-group-option',
                type:'POST',
                data:'item_option_group_id='+item_option_group_id+'&item_option_id='+option_id,
                success:function(data){
                    $(ele).parent().parent().remove()
                    successErrorMessage('Item option has been removed successfully','success');

                }
            })
        }
    </script>
@stop