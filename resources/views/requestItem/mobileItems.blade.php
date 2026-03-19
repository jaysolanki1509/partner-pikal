<form class="form-horizontal" role="form" method="POST" id="requestItem" novalidate="novalidate" action="{{ url('/requestItem') }}" files="true" enctype="multipart/form-data">
    <input name="owner_id" type="hidden" value="" id="owner_id" />
    <input name="location_id" type="hidden" value="" id="location_id" />
    <input name="req_date" type="hidden" value="" id="req_date" />
    <div class="col-md-12">
        {{--<div class="col-lg-12">--}}
        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-body">--}}
        {{--<div class="dataTable_wrapper">--}}
        <table class="table table-striped table-hover" id="CategoriesTable">
            <thead>
            <th>Item Name</th>
            <th>Existing Qty</th>
            <th></th>
            <th>Request Qty</th>
            </thead>

            <tbody>
            <?php $i=0; ?>
            @foreach($items as $item)
                <tr class="odd gradeX">
                    @if($i==0)
                        <input type="hidden" value="{!! $item['id'] !!}" name="min_id">
                        <?php $i++ ?>
                    @endif
                    <td>{!! $item['item'] !!}</td>
                    <td>{!! $item['stock'] or 0 !!} {!! $item['unit'] !!}</td>
                    <td><input type="hidden" id="exi_qty-{!! $item['id'] !!}" name="exi_qty-{!! $item['id'] !!}" value="{!! $item['stock'] or 0 !!}"></td>

                    <td><input type="number" value="{!! $item['open_stock'] !!}" id="req_qty-{!! $item['id'] !!}" class="form-control" name="req_qty-{!! $item['id'] !!}" min="0" maxlength="5" size="5" max="100000" placeholder="Request Qty"></td>

                </tr>
                <input type="hidden" value="{!! $item['id'] !!}" name="count">
            @endforeach
            {{-- <input type="hidden" value="{!! $user_id !!}" name="owner_id"> --}}
            <input type="hidden" value="{!! $cate_id !!}" name="cate_id">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><button type="submit" id="Submit" novalidate="novalidate" class="btn btn-primary col-md-6" style="float: left;">Submit</button></td>
            </tr>
            </tbody>
        </table>
    </div>
</form>