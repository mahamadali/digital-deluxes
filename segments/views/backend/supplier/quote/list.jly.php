@extends('app')

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
            <h6>Quotes</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Quote Project No</th>
              <th>Title</th>
              <th>Open Date</th>
              <th>Close Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
            $quotes = [];
            $quoteCount = 0;
              if (!empty($quotationIds)) {
                $quotes = Models\Quote::whereIn('id', $quotationIds)->orderBy('id', 'DESC')->get();
              }
            @endphp
            @if (count($quotes) > 0):
              @foreach ($quotes as $quote):

              @if ($quote->hasSupplierPrices(auth()->id)):
                @php continue; @endphp;
              @endif

              @php
                $quoteCount++;
              @endphp

              <tr>
                <td>{{ $quote->project()->project_no }}</td>
                <td>{{ $quote->project()->title }}</td>
                <td>{{ $quote->project()->open_date_preity }}</td>
                <td>{{ $quote->project()->close_date_preity }}</td>
                <td>
                    @php
                      $curr_date = strtotime(date('Y-m-d'));
                      $close_date = strtotime($quote->project()->close_date_preity);
                    @endphp

                    @if ($curr_date > $close_date):
                      <div class="badge badge-danger">Project Expired</div>
                    @else
                      <a href="{{ url('supplier/quote/'.$quote->id.'/create') }}" class="btn btn-sm btn-primary" title="Create Quote">
                        <span><i class="ti-plus"></i> Give Quote</span>
                      </a>
                    @endif
                    
                </td>
              </tr>
              @endforeach
            @endif
            @if($quoteCount == 0):
              <tr>
                <td colspan="5" class="text-center text-muted">No data found</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
@endblock