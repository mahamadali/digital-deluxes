@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")

@endblock

@block("content")


@foreach($pages as $page):
<div class="row mt-6">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h6>{{ $page->title }}</h6>
        </div>
      </div>
      <form method="post" action="{{ route('admin.cms.store', ['page' => $page->id]) }}" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" placeholder="Content">{{ $page->description }}</textarea>
                </div>
            </div>
        </div>
          
        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach

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
</script>
@endblock