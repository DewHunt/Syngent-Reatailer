<div class="table-wrapper-scroll-y my-custom-scrollbar">
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Model</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $groupId = (Session::get('catId')) ? Session::get('catId'):1; @endphp
            @if(isset($productModelLists) && !empty($productModelLists))
                @foreach($productModelLists as $key=>$row)
                <tr>
                    @php
                    $getStockInfo = checkModelStockByBP($groupId,$row->product_master_id,$row->product_model);
                    
                    $green = 0;$yellow = 0; $red = 0;
                    if(!empty($getStockInfo)) {
                        $pmId   = $getStockInfo->product_master_id;
                        $green  = $getStockInfo->green;
                        $yellow = $getStockInfo->yellow;
                        $red    = $getStockInfo->red;
                    }
                    @endphp
                    <th scope="row">{{ ++$loop->index }}.</th>
                    <td>
                        <input class="mobileCheckBox" type="checkbox" id="selct_model" name="select_model[]" value="{{ $row->product_master_id }}" @if(!empty($getStockInfo) && $row->product_master_id == $pmId) checked="checked" @endif/> {{ $row->product_model }}
                    </td>
                    <td class="Td-input">
                        <input type="hidden" name="product_master_id[{{ $row->product_master_id }}]" value="{{ $row->product_master_id }}">
                        <input type="hidden" name="product_id[{{ $row->product_master_id }}]" value="{{ $row->product_id }}">
                        <input type="hidden" name="model_name[{{ $row->product_master_id }}]" value="{{ $row->product_model }}">
                        
                        <label>Green:</label> 
                        <input class="color-input-padd" type="number" name="green[{{ $row->product_master_id }}]" minlength="0" style="padding:0px 5px" value="{{ $green }}">
                        <label>Yellow:</label> 
                        <input class="color-input-padd" type="number" name="yellow[{{ $row->product_master_id }}]" minlength="0" style="padding:0px 5px" value="{{ $yellow }}">
                        <label>Red:</label> 
                        <input class="color-input-padd" type="number" name="red[{{ $row->product_master_id }}]"  minlength="0" style="padding:0px 5px" value="{{ $red }}">
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>