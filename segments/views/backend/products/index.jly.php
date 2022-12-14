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

          <div class="col-md-8 text-right">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="ti-plus"></i> New Product</a>
            <a href="#" class="btn btn-primary delete_products_btn" style="display:none;"><i class="ti-trash"></i>Delete</a>
          </div>

        </div>
        <div class="table-responsive">
          <table id="product-listing" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Product Type</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $product):
              <tr>
                <td><input type="checkbox" name="delete_product" id="delete_product" class="delete_product" value="{{$product->id}}"></td>
                <td><img src="{{ $product->coverImageOriginal }}" alt="" /></td>
                <td>{{ $product->name }}</td>
                <td>€{{ $product->price }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $product->product_type }}</td>
                <td>

                  @if(!empty($product->isInSlider())):
                  <a href="{{ route('admin.products.update_slider_status', ['product' => $product->id , 'status' => 1]) }}" class="btn btn-sm btn-danger">Remove to home slider</a> 
                  @else
                  <a href="{{ route('admin.products.update_slider_status', ['product' => $product->id ,  'status' => 0]) }}" class="btn btn-sm btn-success">Add to home slider</a>
                  @endif 

                  @if($product->product_type == 'M'):
                  <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-success">Edit</a>
                  @endif  
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

  $(document).ready(function() {
    $('.delete_product').on('change',function(){
      if($('.delete_product:checked').length > 0) {
        $('.delete_products_btn').show();
      } else {
        $('.delete_products_btn').hide();
      }
    });

    $('.delete_products_btn').on('click', function() {
      var productIds = [];
      $(".delete_product:checked").each(function ()
      {
        productIds.push(parseInt($(this).val()));
      });
      
      $.ajax({
        url : '{{ route("admin.products.delete") }}',
        type : 'POST',
        data: {
          'prevent_csrf_token': '{{ prevent_csrf_token() }}',
          'productIds': productIds,
        },
        dataType: 'json',
        success: function(response) {
          toastr.success(response.msg);
          location.reload();
        }
      });  

    })
  });
</script>
@endblock