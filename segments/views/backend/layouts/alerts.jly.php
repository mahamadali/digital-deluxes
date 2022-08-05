@if (session()->hasFlash('error')):
  <div class="alert alert-danger">
    <span>{{ session()->flash('error') }}</span>
  </div>
@endif

@if (session()->hasFlash('success')):
  <div class="alert alert-success">
    <span>{{ session()->flash('success') }}</span>
  </div>
@endif

@if (session()->hasFlash('warning')):
  <div class="alert alert-warning">
    <span>{{ session()->flash('warning') }}</span>
  </div>
@endif

@if (session()->hasFlash('info')):
  <div class="alert alert-info">
    <span>{{ session()->flash('info') }}</span>
  </div>
@endif