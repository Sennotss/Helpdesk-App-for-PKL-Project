@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Ticket')

@section('content')

<style>
  .custom-select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none;
    padding-right: 1.5em;
  }

  .form-select-sm {
    font-size: 0.9rem;
  }

  .small-badge {
    font-size: 0.75rem;
    padding: 0.5em 0.6em;
  }
</style>

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-12">
          <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0 text-wrap" style="max-width: 60%;">{{$ticket->issue}}</h5>
            <span class="text-muted fs-6">Created at : {{$ticket->created_at}}</span>
          </div>
          <div class="card-body">
            <div class="row my-3">
              <div class="col-sm-3 d-flex align-items-center fs-5 gap-3">
                <span>{{ $ticket->ticket_code }}</span>
                @if ($ticket->priority === 'low')
                  <span class="badge text-bg-success small-badge">Low</span>
                @elseif ($ticket->priority === 'middle')
                  <span class="badge badge-lg text-bg-warning">Middle</span>
                @elseif ($ticket->priority === 'high')
                  <span class="badge badge-lg text-bg-danger">High</span>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex align-items-center text-muted">
                <i class="bx bx-info-circle me-3"></i>
                <span>Status</span>
              </div>
              <div class="col-sm-8">
                @if ($ticket->status === 'open')
                  <span class="badge badge-lg bg-label-primary">Open</span>
                @elseif ($ticket->status === 'onprogress')
                  <span class="badge badge-lg bg-label-warning">On Progress</span>
                @elseif ($ticket->status === 'done')
                  <span class="badge badge-lg bg-label-success">On Progress</span>
                @elseif ($ticket->status === 'revisi')
                  <span class="badge badge-lg bg-label-danger">On Progress</span>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex align-items-center text-muted">
                <i class="bx bx-user me-3"></i>
                <span>Client</span>
              </div>
              <div class="col-sm-8">
                @if ($ticket->client === null)
                  <span>-</span>
                @else
                  <span>{{$ticket->client}}</span>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-user me-3"></i>
                <span>Assigned By</span>
              </div>
              <div class="col-sm-8">
                <select id="assigned_to" class="form-select form-select form-select-sm border-0 p-0 custom-select">
                  <option value="">Select a user</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-user me-3"></i>
                <span>Problem</span>
              </div>
              <div class="col-sm-8">
                <select id="problem_id" class="form-select form-select form-select-sm border-0 p-0 custom-select">
                  <option value="">Select Problem</option>
                  @foreach ($problems as $problem)
                    <option value="{{ $problem->id }}" {{ $ticket->problem_id == $problem->id ? 'selected' : '' }}>{{ $problem->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-user me-3"></i>
                <span>Application</span>
              </div>
              <div class="col-sm-8">
                <select id="application_id" class="form-select form-select form-select-sm border-0 p-0 custom-select">
                  <option value="">Select Application</option>
                  @foreach ($applications as $application)
                    <option value="{{ $application->id }}" {{ $ticket->application_id == $application->id ? 'selected' : '' }}>{{ $application->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted" style="flex-shrink: 0;">
                <i class="bx bx-image me-3"></i>
                <span>Images</span>
              </div>
              <div class="col-sm-8 d-flex flex-column gap-2">
                @forelse ($ticket->images as $image)
                  <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="{{ asset('storage/' . $image->image_path) }}">
                    {{ basename($image->image_path) }}
                  </button>
                @empty
                  <span class="text-muted">No images uploaded.</span>
                @endforelse
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted" style="flex-shrink: 0;">
                <i class="bx bx-link me-3"></i>
                <span>Links</span>
              </div>
              <div class="col-sm-8">
                @forelse ($ticket->links as $link)
                  <button class="btn btn-sm btn-outline-warning mb-2" onclick="window.open('{{ $link->url }}', '_blank')">
                    {{ $link->url }}
                  </button>
                @empty
                  <span class="text-muted">No Links Added.</span>
                @endforelse
              </div>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 d-flex flex-column gap-3">
    <div class="bg-white rounded-3 p-3 shadow-sm d-flex flex-column gap-2">
      <div class="d-flex justify-content-between gap-2">
        <!-- Button untuk Review -->
        <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 w-100 
          @if ($ticket->status === 'open') 
            btn-primary 
          @elseif ($ticket->status === 'onprogress') 
            btn-outline-warning 
          @else 
            btn-secondary 
          @endif">
          <i class="fas fa-search"></i>
          <span>Review</span>
        </button>
    
        <!-- Button untuk Konfirmasi -->
        <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 w-100 
          @if ($ticket->status === 'open') 
            btn-primary 
          @elseif ($ticket->status === 'onprogress') 
            btn-outline-warning 
          @else 
            btn-secondary 
          @endif">
          <i class="fas fa-check"></i>
          <span>Konfirmasi</span>
        </button>
      </div>
    
      <!-- Button untuk Dalam Progres -->
      <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 w-100 
        @if ($ticket->status === 'open') 
          btn-primary 
        @elseif ($ticket->status === 'onprogress') 
          btn-warning 
        @else 
          btn-secondary 
        @endif">
        <i class="fas fa-spinner fa-spin"></i>
        <span>Dalam Progres</span>
      </button>
    </div>
    
    
    <div class="bg-white rounded-3 p-3 shadow-sm" style="min-height:120px;">
      <h2 class="fw-semibold mb-3" style="font-size:14px; color:black;">Description</h2>
      <span>{{$ticket->description}}</span>
    </div>
  </div>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Preview" class="img-fluid rounded shadow" />
      </div>
    </div>
  </div>
</div>
<script>
  document.querySelectorAll('.image-link').forEach(link => {
    link.addEventListener('click', function () {
      const imageUrl = this.getAttribute('data-image');
      document.getElementById('modalImage').setAttribute('src', imageUrl);
    });
  });
</script>
@endsection
