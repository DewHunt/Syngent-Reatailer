<div class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="productResultInfo">
        <tbody>
            @if (isset($getInfo) && $getInfo)
                <tr>
                    <th>Brand</th>
                    <td>{{ $getInfo->product_model }}</td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td>{{ $getInfo->product_code }}</td>
                </tr>
                <tr>
                    <th>MRP Price</th>
                    <td>{{ $getInfo->mrp_price }}</td>
                </tr>
                <tr>
                    <th>MSDP Price</th>
                    <td>{{ $getInfo->msdp_price }}</td>
                </tr>
                <tr>
                    <th>MSRP Price</th>
                    <td>{{ $getInfo->msrp_price }}</td>
                </tr>
                <tr>
                    <th>Product Id</th>
                    <td>{{ $getInfo->product_id }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>