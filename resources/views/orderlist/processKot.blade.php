{{--process modal--}}
<?php use App\OutletSetting; ?>
<div id="printKot" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
</div>

{{--print modal--}}
<div id="printKotModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
    <div class="modal-dialog" style="width: 320px;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kot</h4>
            </div>
            <div class="modal-body" style="padding: 15px;">
                <p></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="" id="close_type" />
                <button type="button" class="btn btn-primary" onclick="printkotorder()">Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location='{{ url("/table_index") }}'" >Close</button>
            </div>
        </div>

    </div>
</div>
