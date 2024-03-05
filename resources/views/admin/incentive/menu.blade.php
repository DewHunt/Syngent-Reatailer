<div class="row">
    @if (false)
        <div class="masonry-item col-md-6 masonry-col mY-10">
            <div class="bgc-white p-20 bd">
                <div style="border-bottom: 1px solid black;">
                    <h4 class="c-grey-900 text-center">Brand Promoter</h4>
                </div>
                <div class="mT-10">
                    <div class="gap-10 peers" style="justify-content: center;">
                        <div class="peer">
                            <a href="{{ route('incentive.addForm',1) }}">   
                                <button type="button" class="btn cur-p btn-primary common-btn-width">Add Incentive</button>
                            </a>
                        </div>
                        <div class="peer">
                            <a href="{{ route('incentive.list',1) }}">
                                <button type="submit" class="btn cur-p btn-secondary common-btn-width">Current Incentive List</button>
                            </a>
                        </div>
    					
                        <div class="peer">
                            <a href="{{ route('incentive.previous-,1list') }}">
                                <button type="submit" class="btn cur-p btn-info common-btn-width">Previous Incentive List</button>
                            </a>
                        </div>
                       
                        <div class="peer">
                            <a href="{{ route('award.addForm',1) }}">   
                                <button type="button" class="btn cur-p btn-success common-btn-width">Add Special Award</button>
                            </a>
                        </div>
                        <div class="peer">
                            <a href="{{ route('award.list',1) }}">
                                <button type="submit" class="btn cur-p btn-secondary common-btn-width">Current Award List</button>
                            </a>
                        </div>
    					
                        <div class="peer">
                            <a href="{{ route('award.previous-,1award-list') }}">
                                <button type="submit" class="btn cur-p btn-info common-btn-width">Previous Award List</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="masonry-item col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 masonry-col mY-10">
        <div class="bgc-white p-20 bd">
            <div style="border-bottom: 1px solid black;">                
                <h4 class="c-grey-900 text-center">Retailer</h4>
            </div>
            <div class="mT-10">
                <div class="w-100 gap-10 peers" style="justify-content: center;">
                    <div class="peer">
                        <a href="{{ route('incentive.addForm',2) }}">   
                            <button type="button" class="btn cur-p btn-warning common-btn-width">Add Incentive</button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ route('incentive.list',2) }}">
                            <button type="submit" class="btn cur-p btn-secondary common-btn-width">Current Incentive List</button>
                        </a>
                    </div>
					
                    {{-- <div class="peer">
                        <a href="{{ route('incentive.previous-list',2) }}">
                            <button type="submit" class="btn cur-p btn-info common-btn-width">Previous Incentive List</button>
                        </a>
                    </div> --}}
                   
                    <div class="peer">
                        <a href="{{ route('award.addForm',2) }}">   
                            <button type="button" class="btn cur-p btn-primary common-btn-width">Add Special Award</button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ route('award.list',2) }}">
                            <button type="submit" class="btn cur-p btn-secondary common-btn-width">Current Award List</button>
                        </a>
                    </div>
					
                    {{-- <div class="peer">
                        <a href="{{ route('award.previous-award-list',2) }}">
                            <button type="submit" class="btn cur-p btn-info common-btn-width">Previous Award List</button>
                        </a>
                    </div> --}}                   
                </div>
            </div>
        </div>
    </div>
</div>

