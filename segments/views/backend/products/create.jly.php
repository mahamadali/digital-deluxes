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
            <h6>Product</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.products.store') }}" id="create-product-form" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Original Name</label>
                    <input type="text" class="form-control" name="originalName" placeholder="Original Name" >
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Platform</label>
                    <input type="text" class="form-control" name="platform" placeholder="Platform" >
                </div>
            </div>
        </div>
        

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" placeholder="Description"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="text" class="form-control" name="coverImage" placeholder="Cover Image" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Cover Image Original</label>
                    <input type="text" class="form-control" name="coverImageOriginal" placeholder="Cover Image Original" required>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Qty</label>
                    <input type="text" class="form-control" name="qty" placeholder="Qty" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Text Qty</label>
                    <input type="text" class="form-control" name="textQty" placeholder="textQty" required>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" class="form-control" name="price" placeholder="Price" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Total Qty</label>
                    <input type="text" class="form-control" name="totalQty" placeholder="Total Qty" required>
                </div>
            </div>
        </div>  

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Activation Details</label>
                    <textarea class="form-control" name="activationDetails" placeholder="Activation Details"></textarea>
                </div>
            </div>

        </div>  

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tags</label>
                    <input type="text" class="form-control" name="tags" placeholder="Tags">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Merchant Name</label>
                    <input type="text" class="form-control" name="merchantName" placeholder="Merchant Name">
                </div>
            </div>
        </div>  

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Developers</label>
                    <input type="text" class="form-control" name="developers" placeholder="Developers">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Publishers</label>
                    <input type="text" class="form-control" name="publishers" placeholder="Publishers">
                </div>
            </div>
        </div>  


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Genres</label>
                    <input type="text" class="form-control" name="genres" placeholder="Genres" >
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Release Date</label>
                    <input type="date" class="form-control" name="releaseDate" placeholder="Release Date" >
                </div>
            </div>
        </div>  

        <h5 class="mt-3">Product Screenshot</h5>

        <div class="row product_screenshot_container">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Product Screenshot Url</label>
                    <input type="text" class="form-control" name="screenshot_url[]" placeholder="URL" required>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="form-group">
                    <label>Product Screenshot Original Url</label>
                    <input type="text" class="form-control" name="screenshot_url_original[]" placeholder="Original URL" required>
                </div>
            </div>

            <div class="col-md-2">
                <a href="javascript:void(0);" class="btn btn-info btn-sm add_more_screnshot mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-plus"></i></a>
            </div>
        </div>  


        <h5 class="mt-3">Product Video</h5>

        <div class="row product_video_container">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="video_name[]" placeholder="Name" >
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="form-group">
                    <label>Video ID</label>
                    <input type="text" class="form-control" name="video_id[]" placeholder="Video ID" >
                </div>
            </div>

            <div class="col-md-2">
                <a href="javascript:void(0);" class="btn btn-info btn-sm add_more_video mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-plus"></i></a>
            </div>
        </div>  


        <h5 class="mt-3">Product System Requirement</h5>

        <div class="row product_requirement_container">
            <div class="col-md-5">
                <div class="form-group">
                    <label>System</label>
                    <input type="text" class="form-control" name="system[]" placeholder="System" >
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="form-group">
                    <label>Requirement</label>
                    <input type="text" class="form-control" name="requirement[]" placeholder="Requirement" >
                </div>
            </div>

            <div class="col-md-2">
                <a href="javascript:void(0);" class="btn btn-info btn-sm add_more_requirement mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-plus"></i></a>
            </div>
        </div>  
          
        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Create Product</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endblock


@block("scripts")
<script src="https://cdn.tiny.cloud/1/1oygzsxmj2z65b12oe2xsmopyeb339ctejhzi5fgpu8ftp4r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
 
 tinymce.init({
    selector:'textarea',
    height: 500,
    theme: 'silver',
    plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen'
    ],
    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
    image_advtab: true,
    templates: [{
        title: 'Test template 1',
        content: 'Test 1'
    },
    {
        title: 'Test template 2',
        content: 'Test 2'
    }
    ],
    content_css: []
}); 


$(document).on('click', '.add_more_screnshot', function() {
    var clone = $('.product_screenshot_container:first').clone().find('select, input').val('').end();
    $('.product_screenshot_container:last').after(clone);
    $('.product_screenshot_container:last').find('.add_more_screnshot').after('<a href="javascript:void(0);" class="btn btn-danger btn-sm remove_screenshot mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-minus"></i></a>');
    $('.product_screenshot_container:last').find('.add_more_screnshot').remove();
    
});
$(document).on('click', '.remove_screenshot', function() {
    if($('.product_screenshot_container').length > 1) {
        $(this).parent().parent().remove();
    }
});


$(document).on('click', '.add_more_video', function() {
    var clone = $('.product_video_container:first').clone().find('select, input').val('').end();
    $('.product_video_container:last').after(clone);
    $('.product_video_container:last').find('.add_more_video').after('<a href="javascript:void(0);" class="btn btn-danger btn-sm remove_video mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-minus"></i></a>');
    $('.product_video_container:last').find('.add_more_video').remove();
    
});
$(document).on('click', '.remove_video', function() {
    if($('.product_video_container').length > 1) {
        $(this).parent().parent().remove();
    }
});

$(document).on('click', '.add_more_requirement', function() {
    var clone = $('.product_requirement_container:first').clone().find('select, input').val('').end();
    $('.product_requirement_container:last').after(clone);
    $('.product_requirement_container:last').find('.add_more_requirement').after('<a href="javascript:void(0);" class="btn btn-danger btn-sm remove_requirement mt-4" style="font-size:25px;margin-top: 5px;"><i class="ti-minus"></i></a>');
    $('.product_requirement_container:last').find('.add_more_requirement').remove();
    
});
$(document).on('click', '.remove_requirement', function() {
    if($('.product_requirement_container').length > 1) {
        $(this).parent().parent().remove();
    }
});

</script>
@endblock