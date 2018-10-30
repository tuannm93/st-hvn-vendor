@if($isRoleAffiliation)
    @if($isMobile)
        <div id="search-cal-box">
            <div class="d-table-cell w-20 align-middle calendar-directional">
                <div id="cal-go-prev" class="text-center">
                    <span class="fa fa-chevron-left"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div id="cell-cal">
                    </div>
                </div>
            </div>
            <div class="d-table-cell w-20 align-middle calendar-directional">
                <div id="cal-go-next" class="text-center">
                    <span class="fa fa-chevron-right"></span>
                </div>
            </div>
        </div>
        @else
        <div id="search-cal-box">
                <div class="d-table-cell w-20 align-middle calendar-directional">
                    <div id="cal-go-prev" class="text-center">
                        <span class="fa fa-chevron-left"></span>
                    </div>
                </div>
                <div class="d-flex">
                        <div class="abs-cal">
                                <div id="cell-cal1" class="cell-cal">
                                </div>
                        </div>
                        <div class="abs-cal ml-4">
                            <div id="cell-cal2" class="cell-cal">
                            </div>
                        </div>
                        <div class="abs-cal ml-4">
                            <div id="cell-cal3" class="cell-cal">
                            </div>  
                        </div>
                </div>
               
                <div class="d-table-cell w-20 align-middle calendar-directional">
                    <div id="cal-go-next" class="text-center">
                        <span class="fa fa-chevron-right"></span>
                    </div>
                </div>
        </div>
        @endif
@endif
