<style>

    #parent {
        border: 1px solid #ddd;
        height: 375px;
    }

</style>
@if( isset($stock) && sizeof($stock) > 0 )
    <div class="col-md-12">
        <div class="col-md-6">
            Item : <b>{!! $stock[0]->item !!}</b>
            <input type="hidden" id="item_id1" value="{{$item_id}}">
        </div>
        <div class="col-md-6">
            Location : <b>{!! $location !!}</b>
            <input type="hidden" id="loc_id" value="{{$loc_id}}">
        </div>
        <hr class="col-md-12">
    </div>

    <div class="table-responsive" id="parent">
    <table id="fixTable" class="table table-striped table-hover" >
        <thead>
        {{--<th>Action</th>--}}
        <th>Qty</th>
        <th>Reason</th>
        <th>Date</th>
        {{--<th>Expiry</th>--}}
        </thead>

        <tbody>
        @foreach($stock as $stk )
            <tr @if($stk->type=='add')style="background-color: #DAF7A6;" @else style="background-color: #FADBD8;" @endif>

                <td>@if($stk->type=='add')
                        <i class="fa fa-plus" style="color: #2ca02c" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-minus" style="color: #9f191f" aria-hidden="true"></i>
                    @endif{!! number_format($stk->stk_quantity,1).' '.$stk->unit !!}</td>
                <td>{!! $stk->reason !!}</td>
                <td>{!! $stk->date or 'NA' !!}</td>
                {{--<td>{!! $stk->expiry_date !!}</td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
        <?php echo $stock->render(); ?>
    </div>

@else
    <span>Stock not available.</span>
@endif

<script>
    $(document).ready(function() {
        var spanText = 0;
        $( ".modal-body .pagination li" ).each(function( index ) {
            var isSpan = $(this).find("span").text();
            if(isSpan == 1) {
                spanText = $(this).find("span").text();
                $(this).find("span").remove();
                $(this).append("<a href='javascript:myFunction(1)'>1</a>");
            }else if(isSpan == '«') {
                spanText = $(this).find("span").text();
                $(this).find("span").remove();
                $(this).append("<a href='javascript:myFunction(1)'>«</a>");
            }
            else{
                var page = $(this).find("a").text();
                if (page == "»")
                {
                    $(this).find("a").prop("href", "javascript:myFunction(" + (parseInt(spanText) + 1) + ")");
                }
                else{
                    $(this).find("a").prop("href", "javascript:myFunction(" + page + ")");

                }
            }
        });

    });

    function myFunction(page){
        var item_id = $('#item_id1').val();
        var loc_id = $('#loc_id').val();
        $.ajax({
            url:'/stock-detail',
            type:'POST',
            data:'item_id='+item_id+'&location_id='+loc_id+"&page="+page,
            success:function(data){
                $('.modal-body tbody').html(data);
            }
        });
        var count = $(".modal-body .pagination li").length;

        $( ".modal-body .pagination li a" ).each(function( index ) {

            var a_tag = $(this).text();
           if(a_tag == page){
               $(this).parent("li").addClass("active");
           }else{
               $(this).parent("li").removeClass("active");
           }

        });
        if(page == 1){
            $(".pagination").find("li:first").addClass("disabled");
            $(".pagination").find("li:first").removeClass("active");
            $(".pagination").find("li:first a").prop("href", "");
        }else{
            $(".pagination").find("li:first").removeClass("disabled");
        }

        if((count-2) == page){
            $(".pagination li:last").addClass("disabled");
            if((parseInt(page) - 1) == 0) {
                $(".pagination").find("li:first a").prop("href", "javascript:myFunction(1)");
            }
            else{
                $(".pagination").find("li:first a").prop("href", "javascript:myFunction(" + (parseInt(page) - 1) + ")");
            }
            $(".pagination").find("li:last a").prop("href", "javascript:myFunction("+page+")");
        }else {
            $(".pagination li:last").removeClass("disabled");
            if((parseInt(page) - 1) == 0) {
                $(".pagination").find("li:first a").prop("href", "javascript:myFunction(1)");
            }
            else{
                $(".pagination").find("li:first a").prop("href", "javascript:myFunction(" + (parseInt(page) - 1) + ")");
            }
            $(".pagination").find("li:last a").prop("href", "javascript:myFunction(" + (parseInt(page) + 1) + ")");
        }
    }

</script>