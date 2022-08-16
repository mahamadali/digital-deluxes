@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <h4 class="card-title">Order #{{ $order->id }}</h4>
  </div>

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="card__info">
                <h4 class="card-title" href="#">Order Info</h4>
                
                <div class="row">
                    <div class="col">
                        <div class="order-info-label">User: <span>{{ $order->user->fullName }} #{{ $order->user->id }} </span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="order-info-label">Payment Method: <span>{{ $order->payment_method_type }} </span></div>
                    </div>
                    <div class="col">
                        <div class="order-info-label">Transaction ID: <span>{{ $order->transaction_id }} </span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="order-info-label">Status: <span>{{ $order->status }} </span></div>
                    </div>
                    <div class="col">
                        <div class="order-info-label">Currency: <span>{{ $order->currency }} </span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="order-info-label">Total: <span>${{ $order->order_total() }} </span></div>
                    </div>
                    <div class="col">
                        <div class="order-info-label">Created At: <span>{{ date('M d, Y, H:i', strtotime($order->created_at)) }} </span></div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="card__info">
                <h4 class="card-title" href="#"> Kinguin Info</h4>
                    
                <div class="row">
                    <div class="col">
                        <div class="order-info-label">OrderId: <span>{{ $order->kg_orderid }} </span></div>
                    </div>
                    <div class="col">
                        <div class="order-info-label">Order External Id: <span>{{ $order->reference }} </span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="order-info-label">Dispatch Id: <span>{{ $order->dispatchId }} </span></div>
                    </div>
                    <div class="col">
                        <div class="order-info-label">Status: <span>{{ $order->kg_order_status }} </span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="card__info" style="width: 100%;">
                <h4 class="card-title" href="#"> Order Items</h4>
                    
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Price</td>
                                <td>Qty</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item):
                            <tr>
                                <th>{{ $item->product_id }}</th>
                                <th>{{ $item->product_name }}</th>
                                <th>${{ $item->product_price }}</th>
                                <th>{{ $item->product_qty }}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="card__info" style="width: 100%;">
                <h4 class="card-title" href="#"> Game Serial Keys</h4>
                    
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Serial</td>
                                <td>Offer Id</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->keys as $key):
                            <tr>
                                <th>{{ $key->name }}</th>
                                <th>{{ $key->serial }}</th>
                                <th>{{ $key->offerId }}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endblock