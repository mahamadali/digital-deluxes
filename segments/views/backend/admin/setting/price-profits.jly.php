@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")

@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Price Profits</h6>
          </div>
        </div>
      </div>
      <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-4">
                Min Price
            </div>

            <div class="col-md-4">
                Max Price
            </div>

            <div class="col-md-4">
                Profit Percentage
            </div>
        </div>
      <form method="post" action="{{ route('admin.settings.profit.update') }}">
        {{ prevent_csrf() }}
        @foreach($profits as $profit):
          
          <div class="row mb-3">
            <div class="col-md-4">
              <input type="text" class="form-control" name="min_price[{{ $profit->id }}]" value="{{ $profit->min_price }}" required>
            </div>

            <div class="col-md-4">
                @if($profit->max_price > 0):
                <input type="text" class="form-control" name="max_price[{{ $profit->id }}]" value="{{ $profit->max_price }}" required>
                @else
                <input type="hidden" class="form-control" name="max_price[{{ $profit->id }}]" value="{{ $profit->max_price }}" required>
                @endif
            </div>

            <div class="col-md-4">
              <input type="text" class="form-control" name="profit_perc[{{ $profit->id }}]" value="{{ $profit->profit_perc }}" >
            </div>
          </div>
          
        @endforeach 
        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endblock


@block("scripts")
@endblock