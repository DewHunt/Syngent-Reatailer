<div class="table-responsive">
    <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="20px">Sl.</th>
                <th>Photo</th>
                <th width="80px">Start Date</th>
                <th width="80px">End Date</th>
                <!--<th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer;">Zone</th>-->
                <th width="50px">Status</th>
                <th width="50px">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($offerList))
                @foreach($offerList as $row)
                <tr>
                    <td>{{ ++$loop->index }}.</td>
                    <td>
                        @if(isset($row->photo) && !empty($row->photo) && $row->photo !=null)
                        <a href="#" class="offerphotoIdModal" data-id="{{ $row->photo }}" data-toggle="modal" data-target="#viewPhotoModal">
                        {{-- <img src="{{ $row->offer_pic }}" alt="" width="250" height="200"/> --}}
                        <img src="{{ asset('public/upload/offer-thumbnail/'.$row->photo)}}" alt="offer"/>
                        </a>
                        @else
                        <img src="{{ asset('public/upload/no-image.png') }}" alt="offer" width="70" height="70"/>
                        @endif
                    </td>
                    <td>{{ $row->sdate }}</td>
                    <td>{{ $row->edate }}</td>
                    <!--<td>
                        @if(isset($row->zone) && !empty($row->zone) && $row->zone !=null)
                        @foreach(json_decode($row->zone, true) as $key => $value)
                        {{ $value }}, 
                        @endforeach
                        @endif
                    </td>-->
                    <td>
                        <input data-id="{{ $row->id }}" class="offer-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
                    </td>
                    <td>
                        <form action="{{ route('promoOffer.destroy',$row->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-id="{{ $row->id }}" id="editOfferInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editOfferModal">Edit</button>

                        <!--<button onclick="return confirm('Are you sure to delete?')" type="submit" class="btn btn-danger btn-sm">
                            Delete
                        </button>-->
                        </form>
                    </td>
                </tr>
                @endforeach
                {{-- <tr><td colspan="6" align="center">{!! $offerList->links() !!}</td></tr> --}}
            @endif
        </tbody>
    </table>
    {{-- <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" /> --}}
</div>

    


                    