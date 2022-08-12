@if (session()->hasFlash('error')):
    <script>
         toastr.error('{{ session()->flash('error') }}');
    </script>
@endif

@if (session()->hasFlash('success')):
<script>
        toastr.success('{{ session()->flash('success') }}');
</script>
@endif