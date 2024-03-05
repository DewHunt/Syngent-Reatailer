@extends('admin.master.master')

@section('page-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><h4 class="c-grey-900 mB-20">Stock List</h4></div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table id="example3" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="30px">SL</th>
                                    <th>Brand</th>
                                    <th width="70px" class="text-center">Qunatity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($stockList)
                                    @php
                                        $sl = 1;
                                        $totalQty = 0;
                                    @endphp
                                    @foreach ($stockList as $stock)
                                        @php
                                            $totalQty += $stock->quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>{{ $stock->product_name }}</td>
                                            <td class="text-right">{{ $stock->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th class="text-right">{{ $totalQty }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection