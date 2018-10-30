
    <div class="d-flex justify-content-md-center">
        <button class="btn btn--gradient-green resign btnResign">{{__('affiliation_genre_resign.btn_resign')}}</button>
    </div>
     @if($data['bAllowShowReconfirm']) 
        <div class="d-flex justify-content-md-center">
            <button class="btn mr-2 btn--gradient-green reconfirm btnReconfirm btn-component-resign">
                {{__('affiliation_genre_resign.btn_reconfirm')}}
            </button>
            <button class="btn ml-2 btn--gradient-green fax btnReconfirmFax btn-component-resign">
                {{__('affiliation_genre_resign.btn_reconfirm_fax')}}
            </button>
        </div>
     @endif 
