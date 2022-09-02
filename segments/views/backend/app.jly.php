<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@plot('title')</title>
    @plot('styles')
    <link rel="stylesheet" href="{{ url('assets/backend/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/vendors/dataTables.net-bs4/dataTables.bootstrap4.css') }}">
    
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

    

    <link rel="shortcut icon" href="" />
</head>

<body>
    <div class="container-scroller">
        @include('backend/layouts/nav')
        <div class="container-fluid page-body-wrapper">
            @include('backend/layouts/sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @include('backend/layouts/alerts')
                    @plot('content')
                </div>
                @include('backend/layouts/footer')
            </div>
        </div>
    </div>
    <script src="{{ url('assets/backend/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ url('assets/backend/js/off-canvas.js') }}"></script>
    <script src="{{ url('assets/backend/js/hoverable-collapse.js') }}"></script>
    <script src="{{ url('assets/backend/js/template.js') }}"></script>
    <script src="{{ url('assets/backend/js/settings.js') }}"></script>
    <script src="{{ url('assets/backend/js/todolist.js') }}"></script>
    <script src="{{ url('assets/backend/js/tabs.js') }}"></script>
    <script src="{{ url('assets/backend/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/backend/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    
    

    <script type="text/javascript">
        var APP_BASE_URL = '{{ url("/") }}';
    </script>
    @plot('scripts')
</body>

</html>