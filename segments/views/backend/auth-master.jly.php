<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@plot('title') - {{ setting('app.description') }}</title>
        @plot('styles')
        <!-- plugins:css -->
      <link rel="stylesheet" href="{{ url('assets/backend/vendors/feather/feather.css') }}">
      <link rel="stylesheet" href="{{ url('assets/backend/vendors/ti-icons/css/themify-icons.css') }}">
      <link rel="stylesheet" href="{{ url('assets/backend/vendors/css/vendor.bundle.base.css') }}">
      <!-- endinject -->
      <!-- Plugin css for this page -->
      <!-- End plugin css for this page -->
      <!-- inject:css -->
      <link rel="stylesheet" href="{{ url('assets/backend/css/vertical-layout-light/style.css') }}">
      <!-- endinject -->
      <link rel="icon" type="image/x-icon" href="{{ url('assets/img/favicon.ico') }}">
        <link rel="shortcut icon" href="{{ url('assets/img/favicon.ico') }}" type="image/x-icon">
    </head>
    <body>
        @plot('content')
        <script src="{{ url('assets/backend/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ url('assets/backend/js/off-canvas.js') }}"></script>
        <script src="{{ url('assets/backend/js/hoverable-collapse.js') }}"></script>
        <script src="{{ url('assets/backend/js/template.js') }}./"></script>
        <script src="{{ url('assets/backend/js/settings.js') }}"></script>
        <script src="{{ url('assets/backend/js/todolist.js') }}"></script>
        @plot('scripts')
    </body>
</html>