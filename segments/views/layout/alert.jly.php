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

@if (session()->has('addedcart')):
<script>
       setTimeout(function() {
        $(document).find('.floating-cart-widget').click();
       }, 1000)
</script>
@php session()->remove('addedcart') @endphp
@endif