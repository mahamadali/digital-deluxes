@extends('app')

@block("title") {{ setting('app.title', 'Digital Deluxes') }} @endblock
@block('meta-tags')
<!-- Twitter Card data -->
<meta name="twitter:card" value="{{ $blog->title }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $blog->title }}" />
<meta property="og:type" content="{{ setting('app.title') }}" />
<meta property="og:url" content="{{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}" />
<meta property="og:image" content="{{ url($blog->post_img) }}" />
<meta property="og:description" content="{{ $blog->title }}" />
@endblock
@block("styles")
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
<style>
  /*
    Auther: Abdelrhman Said
*/

@import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

*:focus,
*:active {
  outline: none !important;
  -webkit-tap-highlight-color: transparent;
}

.social-wrapper{
    background: #ddd;
    padding: 50px;
    text-align: center;
}

.social-wrapper .icon{
  position: relative;
  background-color: #ffffff;
  border-radius: 50%;
  margin: 10px;
  width: 50px;
  height: 50px;
  line-height: 50px;
  font-size: 22px;
  display: inline-block;
  align-items: center;
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  color: #333;
  text-decoration: none;
}

.social-wrapper .tooltip {
  position: absolute;
  top: 0;
  line-height: 1.5;
  font-size: 14px;
  background-color: #ffffff;
  color: #ffffff;
  padding: 5px 8px;
  border-radius: 5px;
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.social-wrapper .tooltip::before {
  position: absolute;
  content: "";
  height: 8px;
  width: 8px;
  background-color: #ffffff;
  bottom: -3px;
  left: 50%;
  transform: translate(-50%) rotate(45deg);
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.social-wrapper .icon:hover .tooltip {
  top: -45px;
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}

.social-wrapper .icon:hover span,
.social-wrapper .icon:hover .tooltip {
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
}

.social-wrapper .facebook:hover,
.social-wrapper .facebook:hover .tooltip,
.social-wrapper .facebook:hover .tooltip::before {
  background-color: #3b5999;
  color: #ffffff;
}

.social-wrapper .twitter:hover,
.social-wrapper .twitter:hover .tooltip,
.social-wrapper .twitter:hover .tooltip::before {
  background-color: #46c1f6;
  color: #ffffff;
}

.social-wrapper .instagram:hover,
.social-wrapper .instagram:hover .tooltip,
.social-wrapper .instagram:hover .tooltip::before {
  background-color: #e1306c;
  color: #ffffff;
}

.social-wrapper .email:hover,
.social-wrapper .email:hover .tooltip,
.social-wrapper .email:hover .tooltip::before {
  background-color: #333333;
  color: #ffffff;
}

.social-wrapper .youtube:hover,
.social-wrapper .youtube:hover .tooltip,
.social-wrapper .youtube:hover .tooltip::before {
  background-color: #de463b;
  color: #ffffff;
}
</style>
@endblock

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

        <div class="social-wrapper">
            <a href="https://www.facebook.com/share.php?t=Please check out {{ $blog->title }} blog&u={{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}" target="_blank" class="icon facebook">
                <div class="tooltip">Facebook</div>
                <span><i class="fab fa-facebook-f"></i></span>
            </a>
            <a href="http://twitter.com/share?text=Please check out {{ $blog->title }} blog&url={{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}" target="_blank" class="icon twitter">
                <div class="tooltip">Twitter</div>
                <span><i class="fab fa-twitter"></i></span>
            </a>
            <!-- <a href="https://www.instagram.com/?url={{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}" class="icon instagram">
                <div class="tooltip">Instagram</div>
                <span><i class="fab fa-instagram"></i></span>
            </a> -->
            <a href="mailto:?subject=Please check out {{ $blog->title }} blog&body={{ route('blogs.view', ['blog' => $blog->id, 'slug' => $blog->slug]) }}" target="_blank" class="icon email">
                <div class="tooltip">Mail</div>
                <span><i class="fa fa-envelope"></i></span>
            </a>
            <!-- <a href="#" class="icon youtube">
                <div class="tooltip">Youtube</div>
                <span><i class="fab fa-youtube"></i></span>
            </a> -->
        </div>


        </div>

        
    </div>
</main>
@endblock

@block('scripts')

@endblock