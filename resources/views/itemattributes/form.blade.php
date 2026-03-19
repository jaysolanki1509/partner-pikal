<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($item_attr,['route' => array('item-attributes.update',$item_attr->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'itemAttrForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'item-attributes.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'itemAttrForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name','Name:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class="col-md-12" style=" overflow-x: scroll">
                                {!! Form::label('bind outlet','Bind Attribute with Outlets:',array('class' => 'col-md-12 control-label')) !!}

                                <?php $i=0; $selected = 0; ?>
                                @if( $action == 'add' )
                                    <table width="100%" cellpadding="4">
                                        @foreach($outlets as $key=>$value)
                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="outlet_id[]" value={{$key}} >&nbsp;<i></i>{{$value}}
                                                        </label>
                                                    <?php if(($i%4)==3) { ?> </td></tr> <?php }else{ ?> </td> <?php } $i++; ?>
                                        @endforeach
                                    </table>
                                @else
                                    <table width="100%" >
                                        @foreach($outlets as $key=>$value)
                                            <?php $selected=0; ?>
                                            <?php if(($i%4)!=0) { ?> <td><?php }else{ ?> <tr><td> <?php } ?>
                                                    @if( isset($ot_mapped) && sizeof($ot_mapped) > 0 )
                                                        @foreach($ot_mapped as $isselected)
                                                            @if($isselected->outlet_id == $key)
                                                                <?php $selected=1; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if($selected==1)
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="outlet_id[]" value={{$key}} checked="true">&nbsp;<i></i> {{$value}}
                                                        </label>
                                                    @else
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="outlet_id[]" value={{$key}}>&nbsp;<i></i> {{$value}}
                                                        </label>
                                                    @endif

                                                    <?php if(($i%4)==3) { ?> </td></tr> <?php }else{ ?> </td> <?php } $i++; ?>
                                        @endforeach
                                    </table>

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <div class="col-md-12">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('item-attributes.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
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
    <script type="text/javascript">

        $(document).ready(function() {

            $("#itemAttrForm").validate({
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


        })
    </script>
@stop