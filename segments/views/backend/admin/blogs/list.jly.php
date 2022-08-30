@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<style>
    .blog-section img {
        max-height: 100%;
        max-width: 100%;
    }
</style>
@endblock

@block("content")
<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary"><i class="ti-plus"></i> New Blog</a>
    </div>
</div>
<div class="card-columns">
    @foreach($blogs as $blog):
    <div class="card blog-section">
        <img class="card-img-top" src="{{ url($blog->post_img) }}" alt="Card image cap">
        <div class="card-body">
        <h4 class="card-title mt-3">{{ $blog->title }}</h4>
        <p class="card-text text-right">
            <a href="{{ route('admin.blogs.edit', ['blog' => $blog->id]) }}"><i class="ti-pencil"></i></a>
            <a href="{{ route('admin.blogs.delete', ['blog' => $blog->id]) }}" class="text-danger"><i class="ti-trash"></i></a>
        </p>
        </div>
    </div>
    @endforeach
</div>

@endblock


@block("scripts")
@endblock