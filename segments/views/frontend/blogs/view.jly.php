@extends('app')

@block("title") {{ setting('app.title', 'Digital Deluxes') }} @endblock
<style>
  
</style>
@block("content")
<main class="page-main">
    <div class="uk-grid" data-uk-grid>
    <div class="uk-width-1@l uk-first-column">

        <div class="uk-grid  uk-child-width-2-2@l uk-child-width-2-2@m uk-child-width-1-1@s">

            <section class="b-post b-post-full b-post-single clearfix">
                <div class="entry-media">
                    <img class="img-fluid" src="{{ url($blog->post_img) }}" alt="Foto" style="filter: brightness(50%);">
                    <div class="entry-meta">
                        <span class="entry-meta__item">{{ date('M d, Y', strtotime($blog->created_at)) }}</span>
                    </div>

                </div>
                <div class="entry-main">
                    <div class="entry-content">


                        <h1 class="entry-title"><a href="#">{{ $blog->title }}</a></h1>
                        <div class="">{{ $blog->description }}</div>

                    </div>

                </div>
            </section>



        </div>


        </div>
    </div>
</main>
@endblock

@block('scripts')

@endblock