@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="content-wrapper">
    <div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-lg-4">
                <div class="text-center pb-4">
                    <div >
                        <h3>{{ $user->full_name  }}</h3><hr>
                        <h4 class="text-muted">{{ $user->email  }}</h4>
                        
                    </div>
                </div>
              
            </div>
            
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

@endblock

@block("scripts")
<script>
    $(function($) {
      'use strict';
      $(function() {
        $('#user-listing').DataTable({
          "aLengthMenu": [
            [5, 10, 15, -1],
            [5, 10, 15, "All"]
          ],
          "iDisplayLength": 10,
          "language": {
            search: ""
          }
        });
        $('#user-listing').each(function() {
          var datatable = $(this);
          // SEARCH - Add the placeholder for Search and Turn this into in-line form control
          var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
          search_input.attr('placeholder', 'Search');
          search_input.removeClass('form-control-sm');
          // LENGTH - Inline-Form control
          var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
          length_sel.removeClass('form-control-sm');
        });
      });
    });
</script>
@endblock