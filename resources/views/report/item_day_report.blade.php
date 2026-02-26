@if(isset($itemwise) && $itemwise !='')
    <button id="export_excel" onclick="getList('excel')" class="btn btn-primary pull-right" style="margin-bottom: 10px"> Export excel</button>
@endif
<table border="1" id="item_table" class="table table-striped table-bordered table-hover">
    @if(isset($itemwise) && $itemwise !='')
        <thead>
            <tr>
                <th>Item</th>
                <?php
                    $begin = new DateTime( $from_date );
                    $end = new DateTime( $to_date );
                    $end = $end->modify( '+1 day' );

                    $interval = new DateInterval('P1D');
                    $daterange = new DatePeriod($begin, $interval ,$end);

                ?>
                @foreach($daterange as $date)
                    <th>{{$date->format("M-d-D")}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <?php   $total = array();  //Total sold unique item.
                for($i=0;$i<sizeof($selected_items);$i++){
                    $temp = 0;                                 //Check whether item is sold or not
                    $total_item = 0;                           //Total items sold in selected duration
                    /*$item = \App\Menu::where('item',$selected_items[$i])->first();*/ ?>
                    @foreach($daterange as $date)
                        @if(isset($itemwise[$selected_items[$i]][$date->format("Y-m-d")]))
                            <?php $temp = 1; ?>
                        @endif
                    @endforeach
                    @if($temp == 1)
                        <tr>
                            <td class="col-md-1">{{ $selected_items[$i] }}</td>

                            @foreach($daterange as $date)

                                @if(!isset($total[$date->format("Y-m-d")]) || !$total[$date->format("Y-m-d")]>0)
                                    <?php $total[$date->format("Y-m-d")] = 0; ?>
                                @endif

                                @if(isset($itemwise[$selected_items[$i]][$date->format("Y-m-d")]))
                                    <td>{!! $itemwise[$selected_items[$i]][$date->format("Y-m-d")] !!}</td>
                                    <?php $total_item += $itemwise[$selected_items[$i]][$date->format("Y-m-d")]; ?>
                                    <?php $total[$date->format("Y-m-d")] += $itemwise[$selected_items[$i]][$date->format("Y-m-d")] ; ?>
                                @else
                                    <td>0</td>
                                @endif

                            @endforeach

                        </tr>
                    @endif
                <?php }  ?>
            <tr>
                <td>Total : {{sizeof($selected_items)}} items</td>
                @foreach($daterange as $date)
                    <td>{!! $total[$date->format("Y-m-d")] !!}</td>
                @endforeach

            </tr>
        </tbody>
    @else
        <thead>
            <tr>
                <td><h3>No item sold between selected dates.</h3></td>
            </tr>
        </thead>
    @endif
</table>
