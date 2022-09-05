<div class="page-modals">
    <div class="uk-flex-top" id="modal-report" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">Report</h2>
            <form class="uk-form-stacked" action="{{ route('frontend.support-tickets.submit') }}" id="support-ticket-form" enctype="multipart/form-data">
                {{ prevent_csrf() }}
                <div class="uk-margin">
                    <div class="uk-form-label">Subject</div>
                    <div class="uk-form-controls">
                        <input type="text" placeholder="Title" name="title" class="uk-input" required>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-label">Order Number</div>
                    <div class="uk-form-controls">
                        <input type="text" placeholder="Order Number" name="order_number" class="uk-input" required>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-label">Details</div>
                    <div class="uk-form-controls"><textarea class="uk-textarea" name="details" placeholder="Try to include all details..." required></textarea></div>
                    <div class="uk-form-controls uk-margin-small-top">
                        <!-- <div data-uk-form-custom><input type="file"><button class="uk-button uk-button-default" type="button" tabindex="-1"><i class="ico_attach-circle"></i><span>Attach File</span></button></div> -->
                        <input type="file" name="attachments[]" multiple class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-grid" data-uk-grid>
                        <!-- <div><button class="uk-button uk-button-small uk-button-link">Cancel</button></div> -->
                        <div><button type="submit" class="uk-button uk-button-small uk-button-danger">Submit</button></div>
                        <div id="messages"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="uk-flex-top" id="modal-help" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">Help</h2>
            <div class="search">
                <div class="search__input"><i class="ico_search"></i><input type="search" name="search" placeholder="Search"></div>
                <div class="search__btn"><button type="button"><i class="ico_microphone"></i></button></div>
            </div>
            <div class="uk-margin-small-left uk-margin-small-bottom uk-margin-medium-top">
                <h4>Popular Q&A</h4>
                <ul>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>How to Upload Your Developed Game</span></li>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>How to Go Live Stream</span></li>
                    <li><img src="assets/img/svgico/clipboard-text.svg" alt="icon"><span>Get in touch with the Creator Support Team</span></li>
                </ul>
                <ul>
                    <li><a href="#!">browse all articles</a></li>
                    <li><a href="#!">Send Feedback</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>