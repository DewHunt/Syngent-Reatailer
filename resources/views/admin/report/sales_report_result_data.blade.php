@if(isset($saleList))    
    <div class="table-responsive">
        <table id="dataExport" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">Sl.</th>
                    <th rowspan="2" class="align-middle text-center">Photo</th>
                    <th colspan="2" class="align-middle text-center">Customer</th>
                    <th colspan="2" class="align-middle text-center">Retailer</th>
                    <th colspan="3" class="align-middle text-center">Sale</th>
                    {{-- <th>Order Type</th> --}}
                    {{-- <th>Order Status</th> --}}
                    <th rowspan="2" class="align-middle text-center">Action</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Amount</th>
                </tr>
            </thead>

            @php
                $saleQty = 0;
                $saleAmount = 0;
            @endphp
            <tbody>
                @foreach($saleList as $row)
                    @php
                        $saleQty += $row->total_qty;
                        $saleAmount += $row->total_amount;
                    @endphp
                    <tr>
                        <td>{{ ++$loop->index }}.</td>
                        <td>
                            @php
                                $customerImg = 'public/upload/client/no-image.jpg';
                                if (isset($row->photo) && !empty($row->photo)) {
                                    $customerImg = 'public/upload/client/'.$row->photo;
                                }
                            @endphp
                            <a href="javascript:void(0)" onclick="viewLargePhoto('{{ $customerImg }}')" data-toggle="modal" data-target="#viewPhotoModal">
                                <img src="{{ asset($customerImg) }}" alt="photo" width="50" height="50"/>
                            </a>
                        </td>
                        <td>{{ $row->customer_name == 'null' ? '' : $row->customer_name }}</td>
                        <td>{{ $row->customer_phone == '01null' ? '' : $row->customer_phone }}</td>
                        <td>{{ $row->retailer_name }}</td>
                        <td>{{ $row->retailer_phone_number }}</td>
                        <td class="text-center">{{ $row->sale_date }}</td>
                        <td class="text-right">{{ $row->total_qty }}</td>
                        <td class="text-right">{{ number_format($row->total_amount,2) }}</td>
                        {{-- <td>
                            @if($row->order_type == 1)
                            <span class="badge badge-info badge-sm">{{ 'Online' }}</span>
                            @elseif($row->order_type == 2)
                            <span class="badge badge-success badge-sm">{{ 'Offline' }}</span>
                            @endif
                        </td>
                        <td>
                            @if($row->status == 1)
                            <span class="badge badge-warning badge-sm">{{ 'Pending' }}</span>
                            @elseif($row->status == 0)
                            <span class="badge badge-success badge-sm">{{ 'Success' }}</span>
                            @endif
                        </td> --}}
                        <td style="text-align: center;">
                           <button type="button" data-id="{{ $row->id }}" id="orderDetailsView" class="btn btn-info btn-xs" data-toggle="modal" data-target="#viewOrderDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-right"><b>Total:</b></td>
                    <td>{{ $saleQty }}</td>
                    <td class="text-right">{{ number_format($saleAmount,2) }}</td>
                </tr>
                <tr>
                    <td colspan="10" align="center">{!! $saleList->links() !!}</td>
                </tr>
            </tfoot>
        </table>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    </div>
@endif