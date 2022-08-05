@extends('app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<form method="post" action="{{ url('supplier/quote/'.$quote->id.'/save') }}" name="save-quote-prices" enctype="multipart/form-data">

<div class="card card-inverse-light-with-black-text flatten-border">
  <div class="card-header">
    <h4>Project: {{ $project->title }} - Send Quote</h4>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col">
        <h6>Project No: {{ $project->project_no }}</h6>
      </div>
      <div class="col">
        <h6>Open Date: {{ $project->open_date_preity }}</h6>
      </div>
      <div class="col">
        <h6>Close Date: {{ $project->close_date_preity }}</h6>
      </div>
      <div class="col">
        <label>Upload Files</label>
        <input type="file" name="supplier_quote_files[]" multiple />
      </div>
    </div>
    <hr>
    @if (count($quote->attachments) > 0):
    <div class="row">
      <div class="col">
        <h4>Legal Documents</h4>
        @foreach ($quote->attachments as $attachment):
          <div class="badge badge-info"><a href="{{ $attachment->attachment_url }}" target="_blank" class="text-white">{{ basename($attachment->attachment_url) }}</a></div>
          @endforeach
      </div>
    </div>
    @endif
  </div>
</div>

<hr>
<div class="row">
  <div class="col-md-2">
    <h3>Items</h4>
  </div>
</div>

  <div class="row mt-2">
    <div class="col">
      <table class="table table-bordered table-responsive">
        <thead>
          <tr class="text-center">
            <th class="m-0 p-2"></th>
            <th class="m-0 p-2">Category</th>
            <th class="m-0 p-2">Item</th>
            <th class="m-0 p-2">Description</th>
            <th class="m-0 p-2">Specification</th>
            <th class="m-0 p-2">Unit</th>
            <th class="m-0 p-2">Qty</th>
            <th>Price</th>
            <th>Amount</th>
            <th>Remark</th>
            <th>Attachment</th>
          </tr>
        </thead>
        @foreach($quoteItems as $quoteItem):
        <tr>
          <td>
            <img src="{{ $quoteItem->item->picture_url }}" />
          </td>
          <td>{{ $quoteItem->category->title }}</td>
          <td>{{ $quoteItem->item->name }}</td>
          <td style="word-break: break-word;">{{{ $quoteItem->description }}}</td>
          <td style="word-break: break-word;">{{{ $quoteItem->specification }}}</td>
          <td>{{ $quoteItem->unit }}</td>
          <td>{{ $quoteItem->qty }}</td>
          <td class="m-0 p-1 text-center">
          <input type="hidden" class="form-control p-1 h-auto" name="item[]" value="{{ $quoteItem->id }}" />
          <input type="hidden" class="form-control p-1 h-auto" name="qty[]" value="{{ $quoteItem->qty }}" />
            <input type="text" class="form-control p-1 h-auto" name="price[]" size="8" />
          </td>
          <td>
            <input type="text" class="form-control p-1 h-auto" name="amount[]" size="8" readonly />
          </td>
          <td class="m-0 p-1 text-center">
            <textarea type="text" class="form-control p-1 h-auto" name="remarks[]"></textarea>
          </td>
          <td class="m-0 p-1 text-center">
            <input type="file" name="attachment[]" />
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="8" class="text-right">Grand Total</td>
          <td class="text-right item-info-summary-line">
            <span class="grand-total">0.00</span>
          </td>
          <td colspan="2" class="text-right">
            <button class="btn btn-success">Submit</button>
          </td>
        </tr>
      </table>
    </div>
  </div>

</form>

@endblock

@block("scripts")
<script type="text/javascript">
  $(document).ready(function() {
    $("form[name='save-quote-prices']").on("submit", function(e) {
      validateQuoteItems();
      if (!hasError()) {
        return true;
      }

      return false;
    });

    // Auto calculate the item lines on change of price
    $(document).on("input", "input[name='price[]']", function() {
      calculateItemLines();
    });

    function validateQuoteItems() {
      $("select, input, textarea").removeClass("error");

      $("input[name='price[]']").filter(function() {
        return !this.value;
      }).addClass("error").trigger('focus');

    }

    function hasError() {
      return $("select, input, textarea").hasClass('error');
    }

    function getParent(itemInfoLine) {
    return $(itemInfoLine).parent().parent();
  }

    function calculateItemLines() {
      var grandTotal = 0;
      $("input[name='qty[]']").each(function(key) {
        let parent = getParent($(this));
        let price = $(parent).find("input[name='price[]']").val();
        let qty = $(this).val();

        if (!isNaN(qty) && !isNaN(price)) {
          let total = parseFloat(qty * price);
          grandTotal += parseFloat(total);
          $(parent).find("input[name='amount[]']").val(total.toFixed(2));
        }

      });

      $("td.item-info-summary-line").find("span.grand-total").html(grandTotal.toFixed(2));

    }

  });
</script>
@endblock