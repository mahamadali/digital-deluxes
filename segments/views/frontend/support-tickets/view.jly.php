@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("styles")
<style>
    a:hover {
        color: #000;
    } 

    .order-info-label {
        color: #7b7b7b;
        font-size: 15px;
        font-style: normal;
        font-weight: 500;
        line-height: 0.31;
    }

    .attachment-link {
        background-color: #282727;
        color: #fff;
        padding: 1px 15px;
        border-radius: 10px;
    }

    img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  /* float: left; */
  padding: 30px 15px 0 25px;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.input_msg_write input:focus:not([tabindex^="-"]) {
    outline: 0px dotted #333;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}

.messaging-box {
    flex-direction: column;
    padding: 10px;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 5px 15px rgb(0 0 0 / 8%);
}
</style>
@endblock

@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
        <div class="uk-width">
            <div class="widjet --filters">
                <div class="widjet__head">
                    <h3 class="uk-text-lead">{{ trans('menu.support_tickets') }} #{{ $ticket->id }}</h3>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="game-card__box">
                    <div class="game-card__info" style="width: 100%;">
                        <h4 class="game-card__title" href="#">Ticket Info</h4>

                        
                         
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Title: <span>{{ $ticket->title }} </span></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Order Number: <span>{{ $ticket->order_number }} </span></div>
                            </div>
                        </div>
                        
                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Details: <span>{{ $ticket->details }} </span></div>
                            </div>
                        </div>

                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Attachments: </div>
                                <div class="order-info-label">
                                @foreach($attachments as $attachment):
                                    <a href="{{ url($attachment) }}" class="attachment-link" target="_blank">{{ basename($attachment) }}</a>
                                @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="game-card__bottom">
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Status: <a>{{ $ticket->status }} </a></div>
                            </div>
                            <div class="game-card__rating-and-price">
                                <div class="order-info-label">Created At: <span>{{ date('M d, Y, H:i', strtotime($ticket->created_at)) }} </span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-card --horizontal favourites-game">
                <div class="messaging-box">
                    <h4 class="game-card__title" href="#">Messages</h4>
                    <div class="messaging">
                        <div class="inbox_msg">
                            @if(count($ticket->messages) > 0):
                            <div class="mesgs">
                            <div class="msg_history">
                                @foreach($ticket->messages as $ticketMessage):
                                    @if($ticketMessage->sender_id == auth()->id):
                                        <div class="outgoing_msg">
                                        <div class="sent_msg">
                                            <p>{{ $ticketMessage->message }}</p>
                                            <span class="time_date"> {{ date('h:i A', strtotime($ticketMessage->created_at)) }}    |    {{ date('M d, Y', strtotime($ticketMessage->created_at)) }}</span> </div>
                                        </div>
                                    @else
                                    <div class="incoming_msg">
                                    <div class="incoming_msg_img"> <img src="{{ url('assets/frontend/img/user-1.png') }}" alt="ADMIN"> </div>
                                    <div class="received_msg">
                                        <div class="received_withd_msg">
                                        <p>{{ $ticketMessage->message }}</p>
                                        <span class="time_date"> {{ date('h:i A', strtotime($ticketMessage->created_at)) }}    |    {{ date('M d, Y', strtotime($ticketMessage->created_at)) }}</span></div>
                                    </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @if($ticket->status == 'PENDING'):
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <form method="post" action="{{ route('admin.support-tickets.message', ['ticket' => $ticket->id]) }}">
                                    {{ prevent_csrf() }}
                                    <input type="text" class="write_msg" name="message" placeholder="Type a message" autocomplete="off" />
                                    <button class="msg_send_btn" type="submit"><i class="ico_arrow-circle-right" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            </div>
                            @else
                            <center>Please wait for Administrator</center>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endblock

@block('scripts')
<script>
    $('.msg_history').scrollTop($('.msg_history')[0].scrollHeight);
</script>
@endblock