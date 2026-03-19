<ul class="menu-list">
    <li class="items" onclick="changePage(this,{{ 1 }})" style=" background: #940d0d; color: white;" >
       <span>
        Page 1
       </span>
    </li>
    <li class="items" onclick="changePage(this,{{ 2 }})" style=" background: #940d0d; color: white;" >
        <span >
         Page 2
        </span>
     </li>
     <li class="items" onclick="changePage(this,{{ 3 }})" style=" background: #940d0d; color: white;">
        <span>
         Page 3
        </span>
     </li>
</ul>
    @foreach ($menu as $key1 => $cat1)
        @if (isset($cat1) && !empty($cat1))
            {{-- <thead id="{{ preg_replace('/[^A-Za-z0-9\-]/', '-', $key1) }}"
        class="sec_tr"> --}}
        
        <?php $options = []; ?>  
            <ul class="menu-list" id="myUL">
                <?php if (sizeof($cat1) > 0 && sizeof($cat1[0]) > 0)  { ?>
                    @foreach ($cat1 as $m)
                     
                        <?php $options = isset($m['item_options']) ? $m['item_options'] : []; ?>
                        <li class="items" id="{{ $m['item_id'] }}" onclick="additem(this,'list',{{ json_encode($options) }},{{ json_encode($attributes) }},'{{ str_replace("'","=",$m['item'])}}','{{ $m['price'] }}',{{ 1 }},'{{ $m['item_id'] }}')">
                            <span class="list-item" style="padding: 1px;" >{{ $m['item'] }}</span>
                        </li>
                    @endforeach
                <?php } ?>
            </ul>
        {{-- </thead> --}}
        @endif
    @endforeach