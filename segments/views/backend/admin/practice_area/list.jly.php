@extends('backend/app')

@block("title") {{ setting('app.title') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Practice Area</h6>
          </div>
          <div class="col">
            <a class="btn btn-md btn-primary float-right" href="{{ route('admin.practice_area.create') }}">
              Add Practice Area
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if (count($practice_areas) > 0):
              @foreach ($practice_areas as $practice_area):
              <tr>
                <td>{{ $practice_area->title }}</td>
                <td>
                  <a href="{{ url('admin/practice_area/edit/'.$practice_area->id) }}" class="btn btn-sm btn-info">
                    <span><i class="ti-pencil"></i></span>
                  </a>
                  <form method="post" action="{{ url('admin/practice_area/delete/'.$practice_area->id) }}" class="d-inline-block">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="btn btn-sm btn-danger">
                      <span><i class="ti-trash"></i></span>
                      </a>
                  </form>
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center text-muted">No data found</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
@endblock