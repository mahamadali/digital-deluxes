<div class="page-modals">
    <div class="uk-flex-top" id="modal-report" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">{{ trans('support.report') }}</h2>
            <form class="uk-form-stacked" action="{{ route('frontend.support-tickets.submit') }}" id="support-ticket-form" enctype="multipart/form-data">
                {{ prevent_csrf() }}
                <div class="uk-margin">
                    <div class="uk-form-label">{{ trans('support.subject') }}</div>
                    <div class="uk-form-controls">
                        <input type="text" placeholder="{{ trans('support.title') }}" name="title" class="uk-input" required>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-label">{{ trans('support.order_number') }}</div>
                    <div class="uk-form-controls">
                        <input type="text" placeholder="{{ trans('support.order_number') }}" name="order_number" class="uk-input" required>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-label">{{ trans('support.details') }}</div>
                    <div class="uk-form-controls"><textarea class="uk-textarea" name="details" placeholder="{{ trans('support.try_to_include_all') }}" required></textarea></div>
                    <div class="uk-form-controls uk-margin-small-top">
                        <!-- <div data-uk-form-custom><input type="file"><button class="uk-button uk-button-default" type="button" tabindex="-1"><i class="ico_attach-circle"></i><span>Attach File</span></button></div> -->
                        <input type="file" name="attachments[]" multiple class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-grid" data-uk-grid>
                        <!-- <div><button class="uk-button uk-button-small uk-button-link">Cancel</button></div> -->
                        <div><button type="submit" class="uk-button uk-button-small uk-button-danger">{{ trans('support.submit') }}</button></div>
                        <div id="messages"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="uk-flex-top" id="modal-help" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">{{ trans('support.help') }}</h2>
            <div class="search">
                <div class="search__input"><i class="ico_search"></i><input type="search" name="search" placeholder="Search"></div>
                <div class="search__btn"><button type="button"><i class="ico_microphone"></i></button></div>
            </div>
            <div class="uk-margin-small-left uk-margin-small-bottom uk-margin-medium-top">
                <h4>{{ trans('support.popular_qa') }}</h4>
                <ul>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>{{ trans('support.how_to_upload_game') }}</span></li>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>{{ trans('support.how_to_go_live') }}</span></li>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>{{ trans('support.get_in_touch_support_team') }}</span></li>
                </ul>
                <ul>
                    <li><a href="#!">{{ trans('support.browse_all_articles') }}</a></li>
                    <li><a href="#!">{{ trans('support.send_feedback') }}</a></li>
                </ul>
            </div>
        </div>
    </div>


    <div class="uk-flex-top" id="modal-buy-now-restrication" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title"></h2>
            
                
                <div class="uk-margin mt-3  text-center" style="text-align: center;">
                    <h5>THIS PRODUCT VERSION CANNOT BE ACTIVATED IN YOUR COUNTRY</h5>
                </div>

                <div class="uk-margin mt-1 text-center" style="text-align: center;">
                    <button type="button" class="uk-button uk-button-small uk-button-danger buy_now_current_choice">CONTINUE WITH CURRENT CHOICE</button>
                </div>

                <div class="uk-margin text-center" style="text-align: center;">
                    <button class="uk-button uk-button-small uk-button-link close_buynow_restrication_popup" type="button"  data-uk-close>Cancel</button>
                </div>
            
        </div>
    </div>
</div>