<?php
use Illuminate\Support\Facades\Session;

    $item_cat = Session::get('item_category');
    if ( !isset($item_cat) || $item_cat == '') {
        $item_cat = null;
    }
$acc_id = Auth::user()->account_id;
$account = \App\Account::find($acc_id);
?>
<style>
    .wrapper {
        position: relative;
        border-radius: 100px;
        display: inline-block;
    }
    .close:before {
        content: '✕';
        font-size: 15px;
    }
    .close {
        background:rgba(255,255,255,0.8);
        position: absolute;
        top: -10px;
        right: 0px;
        cursor: pointer;
    }
</style>
    <div class="mb30"></div>
    <?php  $m=0; ?>
<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap">

            @if($action=="add")
                {!! Form::open(['id'=>'menu_form','route' =>['menu.store'], 'method' => 'post', 'files'=> true, 'class'=>'form-horizontal material-form j-forms']) !!}
            @elseif($action=="edit")
                {!! Form::model($item,array('id'=>'menu_form','route' => array('menu.update',$Itemid), 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms', 'files'=> true)) !!}
            @endif

            @if($action=="add")
                {!! Form::text('item_id',0, array('class'=>'form-control hide','id' => 'item_id')) !!}
            @elseif($action=="edit")
                {!! Form::text('item_id',$Itemid, array('class'=>'form-control hide','id' => 'item_id')) !!}
            @endif


            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <label for="title" class="col-md-12 control-label">Category*</label>
                        <div class="col-md-12">
                            @if($action=="add")
                                {!! Form::select('title'.$m,$title ,$item_cat, array('class'=>'form-control cetegory-box','onchange'=>'customTitle(this.value)','id' => 'title'.$m,'required')) !!}
                                <input type="text" style="display: none;" class="form-control" name="custom_title" id="custom_title<?php echo $m?>" placeholder="Enter Menu Category">

                            @elseif($action=="edit")
                                {!! Form::select('title'.$m,$title ,$item->menu_title_id, array('class'=>'form-control cetegory-box','onchange'=>'customTitle(this.value)','id' => 'title'.$m,'required')) !!}
                                <input type="text" style="display: none;" class="col-md-8 form-control" name="custom_title" id="custom_title<?php echo $m?>" placeholder="Enter Menu Category">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="title" class="col-md-12 control-label">&nbsp;</label>
                        <div class="col-md-12">
                            <a  type="button" href="/title" class="btn primary-btn btn-success"> Add New </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <hr>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <label for="item" class="col-md-12 control-label"> {{ trans('Menu_Create.Item') }}*</label>
                        <div class="col-md-9">
                            @if($action=="add")
                                {!! Form::text('item'.$m,null, array('class'=>'form-control','id' => 'item'.$m, 'maxlength'=> 50,'placeholder'=> 'Enter Item','required')) !!}
                            @else
                                {!! Form::text('item'.$m,$item->item, array('class'=>'form-control',  'maxlength'=> 50,'id' => 'item'.$m, 'placeholder'=> 'Enter Item','required')) !!}
                            @endif
                        </div>
                        <div class="col-md-3">
                            @if($action=="add")
                                {!! Form::text('alias'.$m,null, array('class'=>'form-control','id' => 'alias'.$m, 'placeholder'=> 'Alias')) !!}
                            @else
                                {!! Form::text('alias'.$m,$item->alias, array('class'=>'form-control','id' => 'alias'.$m, 'placeholder'=> 'Alias')) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">

                    <div class="col-md-4">
                        <label class="col-md-12 control-label">Item Code*</label>
                        <div class="col-md-12">
                            {!! Form::text('item_code' ,null, ['class' => 'form-control', 'placeholder'=> 'Item Code','id'=>'item_code'] ) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12">
                            <label class="checkbox">
                                @if($action=="add")
                                    {!! Form::checkbox('is_inventory_item', 1,false,["id"=>"is_inventory_item","onchange" => "changeInventory()"]) !!}
                                @else
                                    @if($item->is_inventory_item == 1)
                                        {!! Form::checkbox('is_inventory_item', 1,true,["id"=>"is_inventory_item","onchange" => "changeInventory()"]) !!}
                                    @else
                                        {!! Form::checkbox('is_inventory_item', 1,false,["id"=>"is_inventory_item","onchange" => "changeInventory()"]) !!}
                                    @endif
                                @endif
                                <i></i><span style="font-weight: bold"> Is Inventory Item </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="col-md-12 control-label">Barcode</label>
                        <div class="col-md-12">
                            {!! Form::text("barcode", NULL,array('class'=>'form-control',"placeholder"=>"barcode","id"=>"barcode")) !!}
                        </div>
                    </div>
                </div>
            </div>

            @if( isset($account) && $account->enable_inventory == 1 )

                <div class="form-group inventory1">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="unit" class="col-md-12 control-label">Unit</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::select('unit_id0',$units,null, ['onchange'=>"changeUnit(this)", 'class' => 'form-control','required', 'id' => 'unit_id0']) !!}
                                @else
                                    {!! Form::select('unit_id0',$units,$item->unit_id, ['onchange'=>"changeUnit(this)", 'class' => 'form-control','required', 'id' => 'unit_id0']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-12 control-label">Brand</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::text('brand' ,null, ['class' => 'form-control', 'placeholder'=> 'Brand Name','id'=>'brand'] ) !!}
                                @else
                                    {!! Form::text('brand' ,$item->brand, ['class' => 'form-control', 'placeholder'=> 'Brand Name','id'=>'brand'] ) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group inventory2">
                    <div class="row">
                        <div class="col-md-9">
                            <label for="unit" class="col-md-12 control-label">Conversion definition (Unit/Value=ConversionUnit)</label>
                            @if( isset($secondary_units) && $secondary_units != '' )
                                <?php $j=1;?>
                                @foreach( $secondary_units as $key=>$sec )
                                    <div class="sec_units">
                                        <div class="sec_unit_div">
                                            <div class="col-md-4">
                                                <select name="secondary_unit[]" class="form-control">
                                                    @if( isset($units) && sizeof($units) > 0 )
                                                        @foreach($units as $key1=>$un )
                                                            <option @if( $key1 == $item->unit_id ) style="display: none;" @endif value="{!! $key1 !!}" @if( $key == $key1 ){!! 'selected' !!}@endif>{!! $un !!}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="control-label">=</label>
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::text('secondary_value[]' ,$sec, ['class' => 'form-control', 'placeholder'=> 'Value'] ) !!}
                                            </div>
                                            <div class="col-md-2">
                                                @if ( $j == sizeof($secondary_units))
                                                    <button type="button" class="btn primary-btn btn-success"  title="Add Unit" onclick="cloneUnit(this)"><i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger secondary-btn @if($j==1) hide @endif"  title="Remove Unit" onclick="removeUnit(this)"><i class="fa fa-times"></i></button>
                                                @else
                                                    <button type="button" class="btn primary-btn btn-success hide"  title="Add Unit" onclick="cloneUnit(this)"><i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger secondary-btn"  title="Remove Unit" onclick="removeUnit(this)"><i class="fa fa-times"></i></button>
                                                @endif
                                            </div>
                                            <?php $j++;?>
                                        </div>
                                    </div>

                                @endforeach

                            @else

                                <div class="sec_units">
                                    <div class="sec_unit_div">
                                        <div class="col-md-5 ">
                                            {!! Form::select('secondary_unit[]',$units,null, ['class' => 'form-control']) !!}
                                        </div>

                                        <div class="col-md-1">
                                            <label class="control-label">=</label>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('secondary_value[]' ,null, ['class' => 'form-control', 'placeholder'=> 'Value'] ) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary add-item"  title="Add Item" onclick="cloneUnit(this)"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-danger remove-item hide" title="Add Item" onclick="removeUnit(this)"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>
                </div>

                <div class="form-group inventory3">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-md-12 control-label">Order Unit</label>
                            <div class="col-md-12">
                                <select name="order_unit" onchange="checkOrderUnit(this)" class="form-control">
                                    <option value="">Select Order Unit</option>
                                    @if( isset($units) && sizeof($units) > 0 )
                                        @foreach($units as $key1=>$un )
                                            <option value="{!! $key1 !!}" @if( isset($item->order_unit) && $item->order_unit == $key1 ){!! 'selected' !!}@endif>{!! $un !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-12 control-label"> HSN#/SAC# </label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::input('text','hsn_sac_code',null, array('class'=>'col-md-6 form-control','id' => 'hsn_sac_code', 'placeholder'=> 'Item Order')) !!}
                                @else
                                    {!! Form::input('text','hsn_sac_code',$item->hsn_sac_code, array('class'=>'col-md-6 form-control','id' => 'hsn_sac_code', 'placeholder'=> 'Item Order')) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="price" class="col-md-12 control-label"> Sale Price*</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::input('number','price'.$m,null, array('onchange'=>'getPrice()','class'=>'form-control','min'=>0,'id' => 'price'.$m, 'placeholder'=> 'Enter Price', 'required')) !!}
                                @else
                                    {!! Form::input('number','price'.$m,$item->price, array('onchange'=>'getPrice()','class'=>'form-control','id' => 'price'.$m, 'placeholder'=> 'Enter Price', 'required')) !!}
                                @endif
                                <label id="price_err" class="col-md-12" style="color: red; display: none;">Price must be numeric.</label>
                            </div>
                        </div>

                        <div class="col-md-4 inventory4">
                            <label class="col-md-12 control-label">Purchase Price</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::input('text','buy_price'.$m ,null, array('onchange'=>'getPprice()','class' => 'form-control', 'placeholder'=> 'Buy Price','id'=>'buy_price' )) !!}
                                @else
                                    {!! Form::input('text','buy_price'.$m ,$item->buy_price, array('onchange'=>'getPprice()','class' => 'form-control', 'placeholder'=> 'Buy Price','id'=>'buy_price') ) !!}
                                @endif
                                <label id="purchase_err" class="col-md-12" style="color: red; display: none;">Purchase Price must be numeric.</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        @if( $itemwise_tax )
                            <div class="col-md-3">
                                <label class="col-md-12 control-label"> Tax Slot </label>
                                <div class="col-md-12">
                                    @if($action=="add")
                                        {!! Form::select('tax_slab',$tax_slab,null, ['class' => 'form-control', 'id' => 'tax_slab']) !!}
                                    @else
                                        {!! Form::select('tax_slab',$tax_slab,$item->tax_slab, ['class' => 'form-control', 'id' => 'tax_slab']) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if( $itemwise_dis )
                            <div class="col-md-3">
                                <label class="col-md-12 control-label"> Discount Type </label>
                                <div class="col-md-12">
                                    {!! Form::select('discount_type',array(''=>'Select Type','fixed'=>'Fixed','percentage'=>'Percentage'),null, ['class' => 'form-control', 'id' => 'discount_type']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md-12 control-label"> Dis. Value </label>
                                <div class="col-md-12">
                                    {!! Form::input('text','discount_value' ,null, array('class' => 'form-control', 'placeholder'=> 'Dis. Value','id'=>'discount_value' )) !!}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @else

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="unit" class="col-md-12 control-label">Unit</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::select('unit_id0',$units,null, ['onchange'=>"changeUnit(this)", 'class' => 'form-control','required', 'id' => 'unit_id0']) !!}
                                @else
                                    {!! Form::select('unit_id0',$units,$item->unit_id, ['onchange'=>"changeUnit(this)", 'class' => 'form-control','required', 'id' => 'unit_id0']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="col-md-12 control-label"> Sale Price*</label>
                            <div class="col-md-12">
                                @if($action=="add")
                                    {!! Form::input('number','price'.$m,null, array('onchange'=>'getPrice()','class'=>'form-control','min'=>0,'id' => 'price'.$m, 'placeholder'=> 'Enter Price')) !!}
                                @else
                                    {!! Form::input('number','price'.$m,$item->price, array('onchange'=>'getPrice()','class'=>'form-control','id' => 'price'.$m, 'placeholder'=> 'Enter Price')) !!}
                                @endif
                                <label id="price_err" class="col-md-12" style="color: red; display: none;">Price must be numeric.</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        @if( $itemwise_tax )
                            <div class="col-md-3">
                                <label class="col-md-12 control-label"> Tax Slot </label>
                                <div class="col-md-12">
                                    @if($action=="add")
                                        {!! Form::select('tax_slab',$tax_slab,null, ['class' => 'form-control', 'id' => 'tax_slab']) !!}
                                    @else
                                        {!! Form::select('tax_slab',$tax_slab,$item->tax_slab, ['class' => 'form-control', 'id' => 'tax_slab']) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if( $itemwise_dis )
                            <div class="col-md-3">
                                <label class="col-md-12 control-label"> Discount Type </label>
                                <div class="col-md-12">
                                    {!! Form::select('discount_type',array('fixed'=>'Fixed','percentage'=>'Percentage'),null, ['class' => 'form-control', 'id' => 'discount_type']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md-12 control-label"> Dis. Value </label>
                                <div class="col-md-12">
                                    {!! Form::input('text','discount_value' ,null, array('class' => 'form-control', 'placeholder'=> 'Dis. Value','id'=>'discount_value' )) !!}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @endif

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <label class="col-md-12 control-label"> {{ trans('Menu_Create.Details') }}</label>
                        <div class="col-md-12">
                            @if($action=="add")
                                {!! Form::text('details'.$m,null, array('class'=>'form-control','id' => 'details'.$m, 'placeholder'=> 'Enter Details')) !!}
                            @else
                                {!! Form::text('details'.$m,$item->details, array('class'=>'form-control','id' => 'details'.$m, 'placeholder'=> 'Enter Details')) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-md-12 control-label">Item Sequence*</label>
                        <div class="col-md-12">
                            @if($action=="add")
                                {!! Form::input('number','item_order',null, array('class'=>'col-md-6 form-control','onchange'=>'getSeq()','id' => 'item_order', 'placeholder'=> 'Item Order')) !!}
                            @else
                                {!! Form::input('number','item_order',$item->item_order, array('class'=>'col-md-6 form-control','onchange'=>'getSeq()','id' => 'item_order', 'placeholder'=> 'Item Order')) !!}
                            @endif
                                <label id="seq_err" class="col-md-12" style="color: red; display: none;">Item Sequence must be numeric.</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="col-md-12 control-label"> {{ trans('Menu_Create.Food') }}</label>
                        <div class="col-md-12">
                            @if($action=="add")
                                <div class="col-md-6">
                                    <label class="radio">
                                        <input type="radio" name="food{{$m}}" id="veg" value="veg" checked="true">
                                        <i></i>Veg
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio">
                                        <input type="radio" name="food{{$m}}" id="nonveg" value="nonveg">
                                        <i></i>NonVeg
                                    </label>
                                </div>
                            @else
                                @if($item->food=="nonveg")
                                    <div class="col-md-6">
                                        <label class="radio">
                                            <input type="radio" name="food{{$m}}" id="veg" value="veg">
                                            <i></i>Veg
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio">
                                            <input type="radio" name="food{{$m}}" id="nonveg" value="nonveg" checked="true">
                                            <i></i>NonVeg
                                        </label>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="radio">
                                            <input type="radio" name="food{{$m}}" id="veg" value="veg" checked="true">
                                            <i></i>Veg
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio">
                                            <input type="radio" name="food{{$m}}" id="nonveg" value="nonveg">
                                            <i></i>NonVeg
                                        </label>
                                    </div>

                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-md-12 control-label">Item Image</label>
                        <div class="col-md-4">
                            <div class="wrapper">
                                @if($action=="add")
                                    <image src="/images/menu_default.png" width="48px" height="48px"/>
                                @else
                                    @if(isset($item->image))
                                        <image src="{!! $item->image !!}" width="48px" height="48px"/>
                                        <span class="close" onclick="deleteImage({{$item->id}})"></span>
                                    @else
                                        <image src="/images/menu_default.png" width="48px" height="48px"/>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            {!! Form::file('image', array('id'=>"image")) !!}
                        </div>
                    </div>
                    <?php $ses_outlet = Session::get('outlet_session'); ?>
                    @if(\App\OutletSetting::checkAppSetting($ses_outlet,'isSelfServiceMode'))
                        <div class="col-md-4">
                            <label class="col-md-12 control-label">Item Color</label>
                            <div class="col-md-4">
                                {!!  Form::input('color','color',null, array('class' => 'form-control col-md-12','placeholder' => 'Enter Color'))  !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-8">
                        <label id="image_err" style="color: red; display: none;">Image size must be less than 2MB</label>
                        <label id="no_image_err" style="color: red; display: none;">Uploaded File is not an Image</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">

                        <a class="col-md-12" href="/item-option-group/create"><i class="fa fa-plus"></i> Item Options Groups</a>

                        <div class="col-md-12" id="item_options_div">
                            <div class="form-group option-select-div">
                                <div class="col-md-12">
                                    <select name="option_groups[]" placeholder="Select option group" class="form-control" id="option_groups" multiple >
                                        @if( isset($option_groups) && sizeof($option_groups) > 0 )
                                            @for( $i=0; $i < sizeof($option_groups); $i++ )
                                                <option @if(isset($selected_option_groups))@if(in_array($option_groups[$i]->id,$selected_option_groups)) selected @endif @endif value="{{ $option_groups[$i]->id }}">{{ $option_groups[$i]->name }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <hr class="row">

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <div class="col-md-12" >
                            <label class="control-label">Item Visibility</label>
                        </div>
                        <div class="col-md-12" >
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td style="text-align: center"><b>Outlet</b></td>
                                    <td style="text-align: center"><b>IsSale</b></td>
                                    <td style="text-align: center"><b>IsActive</b></td>
                                    <td style="text-align: center"><b>Bind</b></td>
                                </tr>
                                @if($action=="add")
                                    @foreach($myoutlets as $key=>$myoutlet)
                                        <tr>
                                            <td> {!! $myoutlet !!} </td>
                                            <td style="text-align: center">
                                                <label class="checkbox">
                                                    {!! Form::checkbox('sale['.$key.']', 1,true) !!}
                                                    <i></i>
                                                </label>
                                            </td>
                                            <td style="text-align: center">
                                                <label class="checkbox">
                                                    {!! Form::checkbox('act['.$key.']', 0,true) !!}
                                                    <i></i>
                                                </label>
                                            </td>
                                            <td style="text-align: center">
                                                <label class="checkbox">
                                                    {!! Form::checkbox('bind['.$key.']', 1,true) !!}
                                                    <i></i>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else

                                    @foreach($myoutlets as $key=>$myoutlet)
                                        <tr>
                                            <td style="text-align: center"> {!! $myoutlet !!} </td>
                                            <?php $bind=0; $active=0; $sale=0?>
                                            @foreach($selected_outlet as $outlets)
                                                @if($key==$outlets)
                                                    <?php $bind=1; ?>
                                                @endif
                                            @endforeach
                                            @foreach($actives as $outlets)
                                                @if($key==$outlets)
                                                    <?php $active=1; ?>
                                                @endif
                                            @endforeach
                                            @foreach($sales as $outlets)
                                                @if($key==$outlets)
                                                    <?php $sale=1; ?>
                                                @endif
                                            @endforeach
                                            <td style="text-align: center">
                                                @if($sale==1)
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('sale[]', $key,true) !!}
                                                        <i></i>
                                                    </label>
                                                @else
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('sale[]', $key,false) !!}
                                                        <i></i>
                                                    </label>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                @if($active==0)
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('act[]', $key,true) !!}
                                                        <i></i>
                                                    </label>
                                                @else
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('act[]', $key,false) !!}
                                                        <i></i>
                                                    </label>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                @if($bind==1)
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('bind[]', $key,true) !!}
                                                        <i></i>
                                                    </label>
                                                @else
                                                    <label class="checkbox">
                                                        {!! Form::checkbox('bind[]', $key,false) !!}
                                                        <i></i>
                                                    </label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                @endif

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('menu.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                            <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                        @endif
                </div>
            </div>

            {!! Form::close() !!}
            <?php //$m++; ?>
            <input type="hidden" name="countf" id="countf" value="{!! $m !!}">
            <input type="hidden" name="coutmenuoption" id="coutmenuoption" value="{!! $m !!}">

        </div>
    </div>
</div>




@section('page-scripts')
<script src="/assets/js/new/lib/jquery.validate.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $("#image_err").css('display', 'none');
        $("#no_image_err").css('display', 'none');
        $("#price_err").css('display', 'none');
        $("#seq_err").css('display', 'none');
        changeInventory();
    });

    function changeInventory() {
        var check = $('#is_inventory_item').is(':checked');

        if(check == true){
            $(".inventory1").removeClass("hide");
            $(".inventory2").removeClass("hide");
            $(".inventory3").removeClass("hide");
            $(".inventory4").removeClass("hide");
        }else{
            $(".inventory1").addClass("hide");
            $(".inventory2").addClass("hide");
            $(".inventory3").addClass("hide");
            $(".inventory4").addClass("hide");

        }
    }



    function deleteImage(itemId) {

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Item image!",
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
                    text : "Your Item image has been removed.",
                    type : "success"
                },function() {

                    var route = "/menu/"+itemId+"/imageDestroy";
                    window.location.replace(route);
                });
            } else {
                swal("Cancelled", "Your Item image is safe :)", "error");
            }
        });

    }

    function customTitle(menu_title){
        if(menu_title=="custom"){
            $("#custom_title0").show();
        }else{
            $("#custom_title0").hide();
        }
    }


    function removeUnit(el) {

        //remove current row
        $(el).closest('.sec_unit_div').remove();

        if ($('.sec_unit_div').length == 1 ) {
            $('.sec_unit_div .remove-item').addClass('hide');
        }
        $('.sec_unit_div:last').find('.add-item').removeClass('hide');

    }

    function cloneUnit(el) {

        var check = true;
        $( ".sec_units input" ).each(function( index ) {

            //provide default color
            $(this).css('border-color','#ccc');

            var text = $(this).val();
            if ( text == '') {
                $(this).css('border-color','red');
                check = false;
            }
        });

        if ( check ) {
            $(el).parent().find('.remove-item').removeClass('hide');
            var ele = $(el).closest('.sec_units').clone().insertAfter('.sec_units:last');

            //make fields empty
            ele.find('input').val('');
            $(el).addClass('hide');
        }


    }

    //check orderunit available in base unit and other unit
    function checkOrderUnit(el) {

        var check = false;
        //selected value
        var selected_val = $(el).val();
        
        if ( selected_val == '') {
            return;
        }

        var base_unit = $('#unit_id0').val();
        if ( selected_val == base_unit ) {
            check = true;
        }
        $(".sec_unit_div select").each(function() {
            if( selected_val == $(this).val()) {
                check = true;
            }
        });

        if ( !check ) {
            alert('Please select unit from base unit or other unit');
            $(el).val(base_unit);
        }


    }

    $(".btn-primary").click(function (evt) {
        if($("input:radio[name='is_sell']:checked").val() == 1){
            if($("#price0").val() == '') {
                alert("Price is required.");
                $("#price0").focus()
                evt.preventDefault();
            }
        }
    });

    function changeUnit(el) {
        var unit = $(el).val();

        $(".sec_unit_div select option").each(function() {
            $(this).show();
        });

        $(".sec_unit_div select option[value='"+unit+"']").hide();
    }


    $(document).ready(function() {

        $("#menu_form").submit(function(e){
            if(!$.isNumeric( $("#price0").val())){
                $("#price_err").css('display', 'block');
                e.preventDefault();
            }else {
                $("#price_err").css('display', 'none');
            }


            if(!$.isNumeric( $("#item_order").val())){
                $("#seq_err").css('display', 'block');
                e.preventDefault();
            }else {
                $("#seq_err").css('display', 'none');
            }

            var size = $('#image')[0].files[0].size;
            if(size > 2097152) {
                $("#image_err").css('display', 'block');
                e.preventDefault();
            }
            var file = $('#image')[0].files[0];
            var fileType = file["type"];
            var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
            if ($.inArray(fileType, ValidImageTypes) < 0) {
                $("#no_image_err").css('display', 'block');
                e.preventDefault();
            }
        });

        $('.cetegory-box').select2({
            placeholder: 'Select Category'
        });

        $('#option_groups').select2();

        $("#image").change(function () {
            var size = $('#image')[0].files[0].size;
            if(size > 2097152){
                $("#image_err").css('display', 'block');
            }else{
                $("#image_err").css('display', 'none');
            }
            var file = $('#image')[0].files[0];
            var fileType = file["type"];
            var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
            if ($.inArray(fileType, ValidImageTypes) < 0) {
                $("#no_image_err").css('display', 'block');
            }else{
                $("#no_image_err").css('display', 'none');
            }
        });
        //alert($("#title0").val());
        $(".is_sell_items").show();
        @if($action != "add")
            @if($item->is_sell=='0')
                $(".is_sell_items").show();
            @endif
        @endif

        changeUnit();
        customTitle($("#title0").val());
        function customTitle(menu_title){
            if(menu_title=="custom"){
                $("#custom_title0").show();
            }else{
                $("#custom_title0").hide();
            }
        }

        $('#expiry_date').datepicker({
            format: "yyyy/mm/dd"
        });

    });

    function getPrice() {
        var price = $('#price0').val();
        if(!$.isNumeric(price)){
            $("#price_err").css('display', 'block');
            e.preventDefault();
        }else {
            $("#price_err").css('display', 'none');
        }
    }

    function getPprice() {
        var pprice = $('#buy_price').val();
        if(!$.isNumeric(pprice)){
            $("#purchase_err").css('display', 'block');
            e.preventDefault();
        }else {
            $("#purchase_err").css('display', 'none');
        }
    }

    function getSeq() {
        var pprice = $('#item_order').val();
        if(!$.isNumeric(pprice)){
            $("#seq_err").css('display', 'block');
            e.preventDefault();
        }else {
            $("#seq_err").css('display', 'none');
        }
    }

    $("#menu_form").validate({
        ignore: [],
        rules: {
            "title0": {
                required: true
            },
            "item0":{
                required: true
            },
            "item_code":{
                required: true
            }
        },
        messages: {

            "title0": {
                required: "Category is required"
            },
            "item0": {
                required: "Item is required"
            },
            "item_code": {
                required: "Item code is required"
            }
        }
    });
        var someText1='';
        var n = document.getElementById("countf").value;
        var m = document.getElementById("coutmenuoption").value;

        $('#addItem'+(n-1)).click(function ()
        {
            var o=n++;
            var unit=$("#unit_id0").html();
            //alert($("#unit_id").html());
            someText1 = '<div  class="container" id="control'+o+'">'+
                '<div class="row no-gutter"> <div class="col-md-7">'+
                '<button type="button" class="btn btn-danger romove-item-btn" style="margin-top: 10px;margin-bottom:5px;float: right;" onclick="control'+o+'.remove()"><i class="fa fa-times"></i></button><br>'+
                '</div></div>'+

                '<div class="row">'+
                '<div class="col-md-2 form">'+
                '<label  for="item">{{ trans('Menu_Create.Item') }}</label></div><div class="col-md-5">'+
            '<div class="col-md-9" style="padding-left: 0px"><input type="text" name="item'+o+'" required="required" id="item'+o+'" placeholder="Enter Item" class="form-control"></div>'+
            '<div class="col-md-3" style="padding-left: 0px"><input type="text" name="alias'+o+'" id="alias'+o+'" placeholder="Alias" class="form-control"></div>'+

            '</div></div><div class="clearfix form"></div>' +

            '<div class="row"><div class="col-md-2 form">'+
            '<label for="details">{{ trans('Menu_Create.Details') }}</label>'+
            '</div>'+
            '<div class="col-md-5 ">'+
            '<input type="text" id="details'+o+'" name="details'+o+'" placeholder="Enter Details" class="col-md-6 form-control" >'+
            '</div></div> <div class="clearfix form"></div>'+

            '<div class="row"><div class="col-md-2 form">'+
            '<label  for="item">Unit</label></div><div class="col-md-2">' +
            '<select id="unit_id'+o+'" name="unit_id'+o+'" required class="col-md-10 form-control">'+
            unit+
            '</select>'+
            '</div> <div class="col-md-1 form">'+
        '<label for="price" style="margin: 0;padding: 8px 0 0;">{{ trans('Menu_Create.Price') }}</label></div>'+
            '<div class="col-md-2 form">'+
            '<input type="number" name="price'+o+'" required="required" id="item'+o+'" placeholder="Enter Price" class="form-control">'+
            '</div></div> <div class="clearfix form"></div>'+



            '<div class="row"><div class="col-md-2 form">'+
        '<label for="option">{{ trans('Menu_Create.Food') }}</label>'+
            '</div>'+
            '<div class="col-md-10 ">'+
        '{{'Veg'}}' +'<input type="radio" name="food'+o+'" value={{'veg'}} checked="true"> &nbsp;' +
        '{{'Nonveg'}}' +'<input type="radio" name="food'+o+'" value={{'nonveg'}}> &nbsp;' +
            '</div></div>'+

            '<div class="row"><div class="col-md-2 form">'+
        '<label for="option">{{ trans('Menu_Create.Active') }}</label>'+
            '</div>'+
            '<div class="col-md-10 ">'+
        '{{'Yes'}}' +'<input type="radio" name="active'+o+'" value={{'0'}} checked="true"> &nbsp;' +
        '{{'No'}}' +'<input type="radio" name="active'+o+'" value={{'1'}}> &nbsp;' +
            '</div></div>'+

            '<input type=hidden name="countf" value='+o+'>'+'<input type=hidden name="countmenuoption" value='+(m-1)+'>'
            var newDiv = $('<div class="row">').append(someText1);
            $(this).before(newDiv);

            $('#addoption'+(m-1)).click(function ()
            {
                var x = m++;
                alert("in : "+x);
                someText = '<div  class="container" id="control' + x + '">' +
                    '<div class="row">' +
                    '<button type="button" class="btn btn-primary" style="float: right; " onclick="control'+x+'.remove()"><i class="fa fa-times"></i></button><br>' +
                '</div>' +

                '<div class="row"><div class="col-md-2 form">' +
            '<label for="option">{{ trans('Menu_Create.Option') }}</label>' +
                '</div>' +
                '<div class="col-md-5 ">' +
                '<input type="text" id="option'+[o] +[x] + '" name="opt'+[o] +[x] + '" placeholder="Enter Options" class="col-md-9" >' +
                '</div></div>' +

                '<div class="row">' + '' +
                '<div class="col-md-2 form">' +
            '<label  for="price">{{ trans('Menu_Create.Price') }}</label></div>' +
                '<div class="col-md-5 ">' +
                '<input type="text" name="opt_price'+[o] +[x] + '"  id="item'+[o] +[x] +'" placeholder="Enter Price" class="col-md-9">' +
                '</div></div>' +

                '<input type=text name="coutmenuoption" value='+ x + '>'
                var newDiv = $('<div class="row">').append(someText);
                $(this).before(newDiv);
            });
        });

        var someText = '';

        $('#addoption'+(m-1)).click(function ()
        {
            var o = m++;
            someText = '<div  class="container" id="control' + o + '">' +
                '<div class="row"><div class="col-md-7">' +
                '<button type="button" class="btn btn-danger romove-item-btn" style="margin-top: 10px;margin-bottom:5px;float: right;" onclick="control' + o + '.remove()"><i class="fa fa-times"></i></button><br>' +
            '</div></div>' +

            '<div class="row"><div class="col-md-2 form">' +
        '<label for="option">{{ trans('Menu_Create.Option') }}</label>' +
            '</div>' +
            '<div class="col-md-5 form">' +
            '<input type="text" id="option' +[0]+[o] + '" name="opt' +[0]+[o] + '" placeholder="Enter Options" class="form-control" required="required">' +
            '</div></div>' +


            '<div class="row">' + '' +
            '<div class="col-md-2 form">' +
        '<label  for="price">{{ trans('Menu_Create.Price') }}</label></div>' +
            '<div class="col-md-5 form">' +
            '<input type="text" name="opt_price' +[0]+[o] + '"  id="item' +[0]+[o] + '" placeholder="Enter Price" class="form-control" required="required">' +
            '</div></div>' +

            '<input type=hidden name="coutmenuoption" value='+ o + '>'
            var newDiv = $('<div class="row">').append(someText);
            $(this).before(newDiv);
        });


</script>

@stop
