@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<style>
  img {
    max-width: 100%;
    max-height: 100%;
  }
</style>
@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Settings</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.settings.platform-logos.post') }}" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        <div class="form-group">
            <label>Platform</label>
            <select class="form-control" name="platform" required>
            @foreach(platforms() as $platformInfo):
                <option {{ $platformInfo['platform'] }} {{ (!empty($_GET['platform']) && $platformInfo['platform'] == $_GET['platform']) ? 'selected' : '' }}>{{ $platformInfo['platform'] }}</option>
            @endforeach 
            </select>
        </div>
        <div class="form-group">
            <label>Upload Logos</label>
            <input type="file" name="logo[]" class="form-control" multiple required>
        </div>

        <div class="form-group">
            <label>Uploaded Logos</label>
            <div class="row">
              @if(count($logos) > 0):
              @foreach($logos as $logo):
                <div class="col-md-3">
                    <img src="{{ url($logo->path) }}"><br>
                    <a href="{{ route('admin.settings.platform-logos.remove', ['logo' => $logo->id]) }}" class="text-danger">Remove</a>
                </div>
              @endforeach
              @else
                <div class="col-md-12">No Logos</div>
              @endif
            </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endblock


@block("scripts")
<script>
  $(document).ready(function() {
    var selectedPlatform = '{{ isset($_GET["platform"]) && !empty($_GET["platform"]) ? $_GET["platform"] : "" }}';
    if(selectedPlatform == '') {
      var platform = $('select[name="platform"]').val();
      window.location.href = '?platform='+platform;
    }

    $('select[name="platform"]').change(function() {
      var platform = $(this).val();
      window.location.href = '?platform='+platform;
    });
  });
</script>
@endblock