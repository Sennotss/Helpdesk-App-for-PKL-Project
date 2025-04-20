@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')
@include('layouts.page-title')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    {{-- <div class="demo-inline-spacing"> --}}
      <h5>Data Tickets</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">Add Data</button>
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
  <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Add Tickets<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="client" class="form-label">Client</label>
              <input type="text" id="client" class="form-control" placeholder="Enter Client">
              <div class="invalid-feedback" id="error-client"></div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="issue" class="form-label">Issue</label>
              <input type="text" id="issue" class="form-control" placeholder="Enter Issue">
              <div class="invalid-feedback" id="error-issue"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="email" class="form-label">Description</label>
              <textarea type="text" id="description" class="form-control"></textarea>
              <div class="invalid-feedback" id="error-email"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Upload Gambar</label>
              <div id="image-wrapper">
                <input type="file" name="images[]" class="form-control mb-2" accept="image/*">
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addImage()">+ Tambah Gambar</button>
              <div class="invalid-feedback" id="error-images"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="links" class="form-label">Links Terkait</label>
              <div id="link-wrapper">
                <div class="input-group mb-2">
                  <input type="url" name="links[]" class="form-control" placeholder="Enter link">
                  <button type="button" class="btn btn-outline-primary" onclick="addLinkInput()">+</button>
                </div>
              </div>
              <div class="invalid-feedback" id="error-links"></div>
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
          } else if (ticket.status === 'resolved') {
            statusBadge = '<span class="badge bg-label-success me-1">Resolved</span>';
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
    $(document).on('click', '#saveUser', function (e) {
      e.preventDefault();

      let formData = new FormData();

      formData.append("client", $("#client").val());
      formData.append("issue", $("#issue").val());
      formData.append("description", $("#description").val());

      $("input[name='images[]']").each(function () {
        const files = $(this)[0].files;
        if (files.length > 0) {
          formData.append("images[]", files[0]);
        }
      });

      $("input[name='links[]']").each(function () {
        const link = $(this).val();
        if (link) {
          formData.append("links[]", link);
        }
      });

      $.ajax({
        url: apiUrl,
        type: "POST",
        processData: false,
        contentType: false,
        data: formData,
        beforeSend: function (xhr) {
          addAuthorizationHeader(xhr)
        },
        success: function (response) {
          $("#userModal").modal("hide");
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message,
          }).then(() => location.reload());
        },
        error: function (xhr) {
          if (xhr.status === 422) {
            const res = xhr.responseJSON;
            const errors = res.errors;

            for (const field in errors) {
              $(`#${field}`).addClass('is-invalid');
              $(`#error-${field}`).text(errors[field][0]);
            }

            $('#userModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: xhr.responseText,
            });
          }
        }
      });
    });
  })

  function addImage() {
    const wrapper = document.getElementById('image-wrapper');
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.className = 'form-control mb-2';
    input.accept = 'image/*';
    wrapper.appendChild(input);
  }

  function addLinkInput() {
    const wrapper = document.getElementById('link-wrapper');
    const newInput = document.createElement('div');
    newInput.classList.add('input-group', 'mb-2');
    newInput.innerHTML = `
      <input type="url" name="links[]" class="form-control" placeholder="Enter link">
      <button type="button" class="btn btn-outline-danger" onclick="this.parentNode.remove()">-</button>
    `;
    wrapper.appendChild(newInput);
  }
</script>
{{-- @include('layouts.userAccess'); --}}

@endsection
