<div class="table-responsive">
    <table id="dataExport"  class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="30px">Sl.</th>
                <th>Brand</th>
                <th>Retailer Name</th>
                <th>Retailer Phone</th>
                <th width="100px">Sales Qty</th>
                <th width="100px">Sales Amount</th>
                {{-- <th>Sale Details</th> --}}
            </tr>
        </thead>

        <tbody>
            @if(isset($productSalesReport) && !$productSalesReport->isEmpty())
                @php
                    $totalQty = 0;
                    $totalAmount = 0;
                @endphp
                @foreach($productSalesReport as $row)
                    @php
                        $totalQty += $row->saleQty;
                        $totalAmount += $row->saleAmount;
                    @endphp
                    <tr>
                        <td>{{ ++$loop->index }}.</td>
                        <td>{{ $row->product_model }}</td>
                        <td>{{ $row->retailer_name }}</td>
                        <td>{{ $row->retailer_phone_number }}</td>
                        <td class="text-right">{{ $row->saleQty }}</td>
                        <td class="text-right"><span style="float:right">{{ number_format($row->saleAmount,2) }}</span></td>
                        {{-- <td style="text-align: center;">
                            <button type="button" data-id="{{ $row->product_model }}" id="viewProductSalesDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewProductSalesDetailsModal" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </td> --}}                   
                    </tr>
                @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td class="text-right" colspan="4"><b>Total</b></td>
                <td class="text-right"><b>{{ $totalQty }}</b></td>
                <td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
            </tr>
            <tr><td colspan="6" align="center">{!! $productSalesReport->links() !!}</td></tr>
        </tfoot>                
            @endif
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>
