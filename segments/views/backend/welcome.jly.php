@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock

@block("styles")
        <link rel="stylesheet" href="{{ url('assets/css/welcome.css') }}" />
@endblock

@block("content")
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{ setting('app.title') }}
                </div>
                <div class="footer">
                    <span>- Built & being managed by {{ $author->name }}</span>
                </div>
            </div>
        </div>
@endblock

@block("scripts")
    <script src="{{ url('assets/js/app.js') }}" type="text/javascript"></script>
@endblock