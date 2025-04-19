@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')
@include('layouts.page-title')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    {{-- <div class="demo-inline-spacing"> --}}
      <h5>Data Tickets</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add Data</button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Code</th>
          <th>Client</th>
          <th>Issue</th>
          <th>Reporter</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id='ticketTable'>
      </tbody>
    </table>
  </div>
  @include('components.loading')
  <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Add Data Users<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="nameBasic" class="form-label">Name</label>
              <input type="text" id="name" class="form-control" placeholder="Enter Name">
              <div class="invalid-feedback" id="error-name"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" class="form-control" placeholder="Enter Email">
              <div class="invalid-feedback" id="error-email"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" class="form-control" placeholder="Enter Password">
              <div class="invalid-feedback" id="error-password"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="exampleFormControlSelect1" class="form-label">Role</label>
              <select class="form-select" id="role" aria-label="Default select example">
                <option selected>Pilih Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
              <div class="invalid-feedback" id="error-role"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveUser'>Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="userEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Edit Data Users<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editUserId">
          {{-- <div class="row">
            <div class="col mb-3">
              <label for="id" class="form-label">ID</label>
              <input type="number" id="editUserId" class="form-control" readonly>
            </div>
          </div> --}}
          <div class="row">
            <div class="col mb-3">
              <label for="nameBasic" class="form-label">Name</label>
              <input type="text" id="editUserName" class="form-control"">
              <div class="invalid-feedback" id="error-edit-name"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="editUserEmail" class="form-control">
              <div class="invalid-feedback" id="error-edit-email"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="exampleFormControlSelect1" class="form-label">Role</label>
              <select class="form-select" id="editUserRole" aria-label="Default select example">
                <option value=''>Pilih Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
              <div class="invalid-feedback" id="error-edit-role"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="exampleFormControlSelect1" class="form-label">Status</label>
              <select class="form-select" id="editUserStatus" aria-label="Default select example">
                <option selected>Pilih Status</option>
                <option value="active">Active</option>
                <option value="non active">Non Active</option>
              </select>
              <div class="invalid-feedback" id="error-edit-status"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveUserEdit'>Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function(){
    const authToken = localStorage.getItem('auth_token');

    function addAuthorizationHeader(xhr) {
        if (authToken) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
        }
    }

    $('#loading').show();
    let apiUrl = "{{config('api.base_url')}}/tickets";
    $.ajax({
    url: apiUrl,
    method: 'GET',
    beforeSend: function(xhr){
      addAuthorizationHeader(xhr)
    },
    success: function (data) {
      console.log(data)
      $('#loading').hide();
      let html = '';
      if (data.length === 0) {
        html = `<tr><td colspan="6" class="text-center">Tidak ada data Ticket</td></tr>`;
      } else {
        data.data.forEach((ticket) => {
          let statusBadge = '';

          if(ticket.status === 'open') {
            statusBadge = '<span class="badge bg-label-primary me-1">Open</span>';
          } else if (ticket.status === 'onprogress') {
            statusBadge = '<span class="badge bg-label-warning me-1">On Progress</span>';
          } else if (ticket.status === 'done') {
            statusBadge = '<span class="badge bg-label-success me-1">done</span>';
          } else if (ticket.status === 'revition') {
            statusBadge = '<span class="badge bg-label-danger me-1">Revisi</span>';
          } else {
            statusBadge = '<span class="badge bg-label-secondary me-1">Unknown</span>';
          }

          html += `
            <tr>
              <td>${ticket.ticket_code}</td>
              <td>${ticket.client}</td>
              <td>${ticket.issue}</td>
              <td>${ticket.user_id}</td>
              <td>${statusBadge}</td>
              <td>
                <button type="button" class="btn btn-info btn-sm px-1" data-code="${ ticket.ticket_code }" id="ticketDetail"><i class="bx bx-chevron-right"></i></button>
              </td>
            </tr>`;
        });
      }

      $('#ticketTable').html(html);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire('Error', 'Gagal memuat data Ticket.', 'error');
    }
  });

  $(document).on('click', '#ticketDetail', function () {
    let ticketCode = $(this).data('code');
    console.log(ticketCode)
    $.ajax({
      url: apiUrl + '/' + ticketCode,
      type: 'GET',
      beforeSend: function(xhr){
        addAuthorizationHeader(xhr)
      },
      success: function (response) {
          console.log(response);
          let code = encodeURIComponent(ticketCode);
          window.location.href = `tickets/detail/${code}`;
        },
      error: function (err) {
          console.error('Gagal mengambil data tiket:', err);
      }
    });
  });

  })
</script>
{{-- @include('layouts.userAccess'); --}}

@endsection
