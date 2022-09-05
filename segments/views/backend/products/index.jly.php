@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div class="row">
        <div class="col-md-2">
          <h4 class="card-title">Products</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
          <input type="search" name="search" class="form-control" placeholder="Type and press enter to search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
          </form>
        </div>
      </div>
      <div class="table-responsive">
        <table id="product-listing" class="table">
          <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $product):
                    <tr>
                        <td><img src="{{ $product->coverImageOriginal }}" alt="" /></td>
                        <td>{{ $product->name }}</td>
                        <td>€{{ $product->price }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>
                            <a href="{{ route('admin.products.view', ['product' => $product->id]) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
          </tbody>
        </table>
        {{ $products__pagination->links() }}
      </div>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
<script>
  $(document).ready(function() {
    // var table = $('#product-listing').DataTable({
    //   order: [0, 'desc'],
    //   lengthChange: false,
    //   buttons: [{
    //     extend: 'csv',
    //     exportOptions: {
    //       columns: [0, 1, 2, 3, 4]
    //     }
    //   }]
    // });

    // table.buttons().container()
    //   .appendTo('#product-listing_wrapper .col-md-6:eq(0)');
  });
</script>
@endblock