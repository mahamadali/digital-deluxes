@extends('app')

@block("title") {{ setting('app.title', 'Digital Deluxes') }} @endblock
<style>
  .entry-content p {
    color: black;
  }
  .entry-media {
    max-height: 250px;
    min-height: 250px;
  }

  .entry-media a {
    width: 100%;
  }

  .entry-media img {
    min-height: 250px;
    max-height: 250px;
    width: 100%;
  }
</style>
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
    <div class="uk-width-1@l uk-first-column">
    <div class="uk-grid uk-grid-medium uk-child-width-1-3@l uk-child-width-2-2@m uk-child-width-1-1@s uk-flex-top uk-flex-wrap-top" uk-grid="masonry: true">
        @foreach($blogs as $blog):
            <section class="b-post b-post-full clearfix" style="transform: translateY(0px);">
                <div class="entry-media">
                    <a href="{{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}"><img class="img-fluid" src="{{ url($blog->post_img) }}" alt="Foto" style="filter: brightness(50%);"></a>
                    <div class="entry-meta">
                        
                        <span class="entry-meta__item">{{ date('M d, Y', strtotime($blog->created_at)) }}</span>
                        <span class="entry-meta__item" title="Total Views" style="float:right;display:flex;"><i class="ico_eye"></i> {{ $blog->blogviews()->count() }}</span>

                    </div>

                </div>
                <div class="entry-main">
                    <div class="entry-content">
                        <h1 class="entry-title" style="margin: 0;"><a href="{{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}">{{ $blog->title }}</a></h1>
                        @if(strlen(strip_tags($blog->description)) > 150):
                        <p>{{ substr(strip_tags($blog->description), 0, 150) }} <a class="more_post" href="{{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}"><i class="ico_more"></i></a> </p>
                        @else
                        <p>{{ $blog->description }}<a class="more_post" href="{{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}"></a> </p>
                        @endif
                        
                    </div>

                </div>
            </section>
        @endforeach
    </div>
    </div>
    </div>
</main>
@endblock

@block('scripts')

@endblock