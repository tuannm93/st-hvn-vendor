@extends('layouts.app')
@section('content')
<div class="dailylist-index">
    <div class="container">
        <h5 class="my-3">@lang("daily_list.title")</h5>
        <div>
            <ul id="daily_list" class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link tab active show" id="list1_tab"  data-toggle="tab" href="#list1_content" role="tab"
                                   aria-controls="list1_content" aria-selected="true">@lang("daily_list.this-month")</a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link" id="list2_tab"  data-toggle="tab" href="#list2_content" role="tab"
                                   aria-controls="list2_content" aria-selected="false">@lang("daily_list.all")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list3_tab"  data-toggle="tab" href="#list3_content" role="tab"
                                   aria-controls="list3_content" aria-selected="false">@lang("daily_list.franchise store")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list4_tab" data-toggle="tab" href="#list4_content" role="tab"
                                   aria-controls="list4_content" aria-selected="false">@lang("daily_list.not-affiliated-store")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list5_tab"  data-toggle="tab" href="#list5_content" role="tab"
                                   aria-controls="list5_content" aria-selected="false">@lang("daily_list.progress")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list6_tab" data-toggle="tab" href="#list6_content" role="tab"
                                   aria-controls="list6_content" aria-selected="false">@lang("daily_list.merchant-license")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list7_tab" data-toggle="tab" href="#list7_content" role="tab"
                                   aria-controls="list7_content" aria-selected="false">@lang("daily_list.bidding-fee-claim")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list8_tab" data-toggle="tab" href="#list8_content" role="tab"
                                   aria-controls="list8_content" aria-selected="false">@lang("daily_list.claim")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="list9_tab"  data-toggle="tab" href="#list9_content" role="tab"
                                   aria-controls="list9_content" aria-selected="false">@lang("daily_list.merchant-management")</a>
                            </li>
            </ul>
        </div>
            <div class="tab-content">
                <div id="list1_content" class="tab-pane fade show active" role="tabpanel" aria-labelledby="list1_tab">
                    @component("daily_list/_table", ["files" => $files["list1"]])
                    @endcomponent
                </div>
                <div id="list2_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list2_tab">
                    @component("daily_list/_table", ["files" => $files["list2"]])
                    @endcomponent
                </div>
                <div id="list3_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list3_tab">
                    @component("daily_list/_table", ["files" => $files["list3"]])
                    @endcomponent
                </div>
                <div id="list4_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list4_tab">
                    @component("daily_list/_table", ["files" => $files["list4"]])
                    @endcomponent
                </div>
                <div id="list5_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list5_tab">
                    @component("daily_list/_table", ["files" => $files["list5"]])
                    @endcomponent
                </div>
                <div id="list6_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list6_tab">
                    @component("daily_list/_table", ["files" => $files["list6"]])
                    @endcomponent
                </div>
                <div id="list7_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list7_tab">
                    @component("daily_list/_table", ["files" => $files["list7"]])
                    @endcomponent
                </div>
                <div id="list8_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list8_tab">
                    @component("daily_list/_table", ["files" => $files["list8"]])
                    @endcomponent
                </div>
                <div id="list9_content" class="tab-pane fade" role="tabpanel" aria-labelledby="list9_tab">
                    @component("daily_list/_table", ["files" => $files["list9"]])
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>

@endsection