<div class="table-responsive">
    <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="20px">Sl.</th>
                <th>Brand</th>
                <th width="250px">Category Name</th>
                <th width="70px" class="text-center">MRP</th>
                <th width="70px" class="text-center">MSDP</th>
                <th width="70px" class="text-center">MSRP</th>
                <!--<th data-column_name="status" style="cursor: pointer;">Status</th>-->
                <th width="100px" class="text-center noExport">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($product_list))
                @foreach($product_list as $row)
                <tr>
                    <td>{{ ++$loop->index }}.</td>
                    <td>{{ $row->product_model }}</td>
                    <td>{{ $row->category_name }}</td>
                    <td class="text-right">{{ number_format($row->mrp_price,2) }}</td>
                    <td class="text-right">{{ number_format($row->msdp_price,2) }}</td>
                    <td class="text-right">{{ number_format($row->msrp_price,2) }}</td>
                    {{-- <td>
                        <input data-id="{{ $row->product_master_id }}" class="product-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
                    </td> --}}
                    <td class="text-center">
                        <button type="button" data-id="{{ $row->product_master_id }}" id="editProductInfo" class="btn btn-primary" data-toggle="modal" data-target="#editProductModal"><i class="fa fa-edit"></i></button> 

                        <button type="button" data-id="{{ $row->product_master_id }}" id="viewProductDetails" class="btn btn-info" data-toggle="modal" data-target="#viewProductDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>

                        {{-- <button type="button" data-id="{{ $row->product_master_id }}" id="productStock" class="btn btn-success" data-toggle="modal" data-target="#productStockModal">Stock Maintenance</button> --}}
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>