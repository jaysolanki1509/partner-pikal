<form method="post" action="/store-printer-bind" class="j-forms">
    <input type="hidden" name="outlet_id" value="{!! $outlet_id !!}">

        @if( isset($arr) && sizeof($arr) > 0 )

            <?php $cat = '';$i=0;?>

            @foreach( $arr as $key=>$val )

                @if( $i==0 || $cat != $val['cat_id'] )

                    @if( $i != 0 )
                            </tbody>
                        </table>
                    @endif

                    <?php $cat = $val['cat_id'];?>
                    <table class="table table-striped table-hover" >
                        <thead>
                            @if ( $cat_id == '' )
                                <tr><th colspan="4" style="text-align: center;background-color: #e7e7e7">{!! $val['cat_name'] !!}</th></tr>
                            @endif
                            <tr>
                                <th>Item Name</th>
                                <th>Printer Name</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                        @endif

                        <tr>
                            <td>
                                {!! $val['item'] !!}
                                <input type="hidden" name="item_id[]" value="{!! $val['id'] !!}">
                                <input type="hidden" name="cat_id[]" value="{!! $val['cat_id'] !!}">
                            </td>
                            <td>
                                <select id="printer_list" class="form-control printer" name="printer_id[]">
                                    <?php /*$default_kot = json_decode(\App\Outlet::find($outlet_id)->printer)->kot_printer;
                                            $printer_name = \App\Printer::find($default_kot)->printer_name;*/ ?>

                                    @if( isset($printers) && sizeof($printers))
                                        <option value="0">Skip Kot</option>
                                        @foreach($printers as $pr)
                                            <option value="{!! $pr->id !!}" @if( isset($val['printer']) && $val['printer'] == $pr->id ){!! 'selected' !!} @endif >{!! $pr->printer_name." (".$pr->printer_type.'-'.$pr->mac_address.")" !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>

                        <?php $i++;?>

                        @if( $i == sizeof($arr))
                            </tbody>

                    </table>
                @endif
            @endforeach

            <hr>
            <div class="form-footer">
                <div class="col-md-12">
                    <button type="submit" class="btn primary-btn btn-success">Submit</button>
                </div>
            </div>
        @endif

</form>