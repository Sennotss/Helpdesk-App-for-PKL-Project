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
            <div class="d-flex" style="flex-grow: 1;">
              <a class="btn btn-close" href="{{ route('tickets') }}"></a>
              <h5 class="mb-0 ms-2 text-wrap" style="max-width: calc(80% - 50px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$ticket->issue}}</h5>
            </div>
            <span class="text-muted fs-6">Created at : {{$ticket->created_at}}</span>
          </div>
          <div class="card-body">
            @php
              $isAdmin = session('auth_user')['role'] === 'admin';
            @endphp
            <div class="row my-3">
              <div class="col-sm-3 d-flex align-items-center fs-5 gap-3">
                <span>{{ $ticket->ticket_code }}</span>
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
                @elseif ($ticket->status === 'resolved')
                  <span class="badge badge-lg bg-label-success">Resolved</span>
                @elseif ($ticket->status === 'revition')
                  <span class="badge badge-lg bg-label-danger">Revisi</span>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex align-items-center text-muted">
                <i class="bx bxs-factory me-3"></i>
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
                @if ($isAdmin)
                  <select id="assigned_to" class="form-select form-select-sm border-0 p-0 custom-select">
                    <option value="">Select a user</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                  </select>
                @else
                  @php
                    $assignedUser = collect($users)->firstWhere('id', $ticket->assigned_to);
                  @endphp
                  @if ($assignedUser)
                    <p class="mb-0">{{ $assignedUser->name }}</p>
                  @else
                    <p class="mb-0">-</p>
                  @endif
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-cog me-3"></i>
                <span>Problem</span>
              </div>
              <div class="col-sm-8">
                @if ($isAdmin)
                  <select id="problem_id" class="form-select form-select-sm border-0 p-0 custom-select">
                    <option value="">Select Problem</option>
                    @foreach ($problems as $problem)
                      <option value="{{ $problem->id }}" {{ $ticket->problem_id == $problem->id ? 'selected' : '' }}>{{ $problem->name }}</option>
                    @endforeach
                  </select>
                @else
                  <p class="mb-0">{{ $ticket->problem->name ?? '-' }}</p>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-laptop me-3"></i>
                <span>Application</span>
              </div>
              <div class="col-sm-8">
                @if ($isAdmin)
                  <select id="application_id" class="form-select form-select-sm border-0 p-0 custom-select">
                    <option value="">Select Application</option>
                    @foreach ($applications as $application)
                      <option value="{{ $application->id }}" {{ $ticket->application_id == $application->id ? 'selected' : '' }}>{{ $application->name }}</option>
                    @endforeach
                  </select>
                @else
                  <p class="mb-0">{{ $ticket->application->name ?? '-' }}</p>
                @endif
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-3 d-flex text-muted"  style="flex-shrink: 0;">
                <i class="bx bx-objects-vertical-bottom me-3"></i>
                <span>Priority</span>
              </div>
              <div class="col-sm-8">
                @if ($isAdmin)
                  <select id="priority" name="priority" class="form-select form-select-sm border-0 p-0 custom-select">
                    <option value="">Pilih Prioritas</option>
                    <option value="low" {{ (old('priority', $ticket->priority ?? '') == 'low') ? 'selected' : '' }}>Low</option>
                    <option value="middle" {{ (old('priority', $ticket->priority ?? '') == 'middle') ? 'selected' : '' }}>Middle</option>
                    <option value="high" {{ (old('priority', $ticket->priority ?? '') == 'high') ? 'selected' : '' }}>High</option>
                  </select>
                @else
                  <p class="mb-0 text-capitalize">{{ $ticket->priority ?? '-' }}</p>
                @endif
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
    <div class="chat-container mt-3 p-4 bg-light rounded shadow">
      <h3><i class='bx bx-chat'  style='font-size: 1em;'></i> Discussion Chat</h3>
      @php
        $authUser = session('auth_user');
      @endphp

      @foreach ($discussions as $discussion)
          @if ($authUser && $discussion->user_id == $authUser['id'])
              {{-- Pesan dari user yang sedang login (kanan) --}}
              <div class="chat-message-wrapper flex-grow-1 text-end">
                  <div class="chat-message-text bg-primary text-white p-2 rounded d-inline-block">
                      <p class="mb-0">{{ $discussion->message }}</p>
                  </div>
                  <small class="text-muted d-block">{{ $discussion->created_at->diffForHumans() }}</small>
              </div>
          @else
              {{-- Pesan dari orang lain (kiri) --}}
              <div class="chat-message-wrapper flex-grow-1 text-start">
                  <div class="chat-message-text bg-primary text-white p-2 rounded d-inline-block">
                      <p class="mb-0">{{ $discussion->message }}</p>
                  </div>
                  <small class="text-muted d-block">{{ $discussion->user->name }} - {{ $discussion->created_at->diffForHumans() }}</small>
              </div>
          @endif
      @endforeach


      <form id="discussion-form" class="d-flex mt-3">
          @csrf
          <input type="hidden" name="ticket_code" value="{{ $ticket->ticket_code }}">
          <input type="text" name="message" class="form-control me-2" placeholder="Tulis pesan..." required>
          <button type="submit" class="btn btn-primary">Kirim</button>
      </form>
  </div>

  </div>
  <div class="col-lg-4 d-flex flex-column gap-3">
    <div class="bg-white rounded-3 p-3 shadow-sm d-flex flex-column gap-2">
      <div class="d-flex justify-content-between gap-2">
        <!-- Button untuk Review -->
        <?php
          $user = session('auth_user');
        ?>
        @if ($user['role'] === 'admin')
        <button type="button" id="btnReview"
          class="btn d-flex align-items-center justify-content-center gap-2 w-100
            {{ $ticket->status === 'open' ? 'btn-primary' : ($ticket->status === 'onprogress' ? 'btn-primary' : 'btn-primary') }}"
          {{ ($ticket->status === 'open' || $ticket->status === 'resolved') ? 'disabled' : '' }}>
          <i class="fas fa-search"></i>
          <span>Review</span>
        </button>
        <button type="button" id="btnProgress"
          class="btn d-flex align-items-center justify-content-center gap-2 w-100
            {{ $ticket->status === 'open' ? 'btn-primary' : ($ticket->status === 'onprogress' ? 'btn-primary' : 'btn-primary') }}"
          {{($ticket->status === 'onprogress' || $ticket->status === 'resolved') ? 'disabled' : '' }}>
          <i class="fas fa-spinner fa-spin"></i>
          <span>On Progress</span>
        </button>
        @elseif ($user['role'] === 'user')
          <!-- Tombol Revisi untuk User -->
          <button type="button" id="btnRevisi"
            class="btn btn-warning d-flex align-items-center justify-content-center gap-2 w-100"
            {{ $ticket->status !== 'resolved' ? 'disabled' : '' }}>
            <i class="fas fa-edit"></i>
            <span>Ajukan Revisi</span>
          </button>
        @endif
      </div>
        <!-- Tombol Dalam Progres -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  document.querySelectorAll('.image-link').forEach(link => {
    link.addEventListener('click', function () {
      const imageUrl = this.getAttribute('data-image');
      document.getElementById('modalImage').setAttribute('src', imageUrl);
    });
  });

  const authToken = localStorage.getItem('auth_token');

  function addAuthorizationHeader(xhr) {
      if (authToken) {
          xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
      }
  }

  $('#btnProgress').on('click', function () {
    const assignedTo = $('#assigned_to').val();
    const applicationId = $('#application_id').val();
    const problemId = $('#problem_id').val();
    const priority = $('#priority').val();
    let apiUrl = "{{config('api.base_url')}}/tickets";
    const ticketCode = encodeURIComponent("{{$ticket->ticket_code}}");

    $.ajax({
      url: apiUrl + "/" + ticketCode,
      method: 'PUT',
      beforeSend: function(xhr){
        addAuthorizationHeader(xhr)
      },
      data: {
        status: 'onprogress',
        assigned_to: assignedTo,
        application_id: applicationId,
        problem_id: problemId,
        priority: priority
      },
      success: function (response) {
        console.log('Update berhasil:', response);
        location.reload();
      },
      error: function (xhr) {
        console.error('Gagal update:', xhr.responseText);
        alert('Gagal mengupdate tiket. Cek console untuk detail.');
      }
    });
  });

  $('#btnReview').on('click', function () {
  const assignedTo = $('#assigned_to').val();
  const applicationId = $('#application_id').val();
  const problemId = $('#problem_id').val();
  const priority = $('#priority').val();
  const apiUrl = "{{config('api.base_url')}}/tickets";
  const ticketCode = encodeURIComponent("{{$ticket->ticket_code}}");

  $.ajax({
    url: `${apiUrl}/${ticketCode}`,
    method: 'PUT',
    beforeSend: function (xhr) {
      addAuthorizationHeader(xhr);
    },
    data: {
      status: 'resolved',
      assigned_to: assignedTo,
      application_id: applicationId,
      problem_id: problemId,
      priority: priority
    },
    success: function (response) {
      console.log('Tiket direview:', response);
      location.reload();
    },
    error: function (xhr) {
      console.error('Gagal review:', xhr.responseText);
      alert('Gagal mereview tiket.');
    }
  });
  });

  $('#btnRevisi').on('click', function () {
    const assignedTo = $('#assigned_to').val();
    const applicationId = $('#application_id').val();
    const problemId = $('#problem_id').val();
    const priority = $('#priority').val();
    const apiUrl = "{{config('api.base_url')}}/tickets";
    const ticketCode = encodeURIComponent("{{$ticket->ticket_code}}");

    $.ajax({
      url: `${apiUrl}/${ticketCode}`,
      method: 'PUT',
      beforeSend: function (xhr) {
        addAuthorizationHeader(xhr);
      },
      data: {
        status: 'revition',
        assigned_to: assignedTo,
        application_id: applicationId,
        problem_id: problemId,
        priority: priority
      },
      success: function (response) {
        console.log('Revisi diajukan:', response);
        location.reload();
      },
      error: function (xhr) {
        console.error('Gagal revisi:', xhr.responseText);
        alert('Gagal mengirim revisi.');
      }
    });
  });

  $('#discussion-form').on('submit', function (e) {
    e.preventDefault();

    const formData = $(this).serialize();
    const ticketCode = encodeURIComponent("{{$ticket->ticket_code}}");
    const apiUrl = `{{ config('api.base_url') }}/tickets/${ticketCode}/discussions`;
    console.log(apiUrl);

    $.ajax({
      url: apiUrl,
      method: 'POST',
      beforeSend: function(xhr) {
        addAuthorizationHeader(xhr)
      },
      data: formData,
      success: function(response) {
        console.log('Diskusi ditambahkan:', response);
        location.reload();
      },
      error: function(xhr) {
        console.error('Gagal menambahkan diskusi:', xhr.responseText);
        alert('Gagal mengirim pesan.');
      }
    });
  });
</script>
@endsection
