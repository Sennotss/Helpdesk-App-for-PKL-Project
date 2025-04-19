@extends('layouts/contentNavbarLayout')

@section('title', 'Applications')

@section('content')
@include('layouts.page-title')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    {{-- <div class="demo-inline-spacing"> --}}
      <h5>Data Applications</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applicationModal">Add Data</button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Description</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id='applicationTable'>
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Add Data Application<h5>
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
              <label for="description" class="form-label">Description</label>
              <textarea type="text" id="description" class="form-control"></textarea>
              <div class="invalid-feedback" id="error-description"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveApplication'>Save changes</button>
        </div>
      </div>
    </div>
  </div>
  @include('components.loading')
  <div class="modal fade" id="applicationEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Edit Data Application<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editAppId">
          {{-- <div class="row">
            <div class="col mb-3">
              <label for="id" class="form-label">ID</label>
              <input type="number" id="editUserId" class="form-control" readonly>
            </div>
          </div> --}}
          <div class="row">
            <div class="col mb-3">
              <label for="nameBasic" class="form-label">Name</label>
              <input type="text" id="editAppName" class="form-control" placeholder="Enter Name">
              <div class="invalid-feedback" id="error-edit-name"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea type="text" id="editAppDescription" class="form-control"></textarea>
              <div class="invalid-feedback" id="error-edit-description"></div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="exampleFormControlSelect1" class="form-label">Status</label>
              <select class="form-select" id="editAppStatus" aria-label="Default select example">
                <option selected>Pilih Status</option>
                <option value="active">Active</option>
                <option value="non active">Non Active</option>
                <option value="maintenance">Maintenance</option>
              </select>
              <div class="invalid-feedback" id="error-edit-status"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveApplicationEdit'>Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function(){
    const authToken = localStorage.getItem('auth_token');

    function addAuthorizationHeader(xhr) {
        if (authToken) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
        }
    }
    $('#loading').show();
    let apiUrl = "{{config('api.base_url')}}/applications";
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
        html = `<tr><td colspan="6" class="text-center">Tidak ada data Aplikasi</td></tr>`;
      } else {
        data.forEach((application, index) => {
          let statusBadge = '';

          if(application.status === 'active') {
            statusBadge = '<span class="badge bg-label-primary me-1">Active</span>';
          } else if (application.status === 'nonactive') {
            statusBadge = '<span class="badge bg-label-danger me-1">Non Active</span>';
          } else if (application.status === 'maintenance') {
            statusBadge = '<span class="badge bg-label-warning me-1">Maintenance</span>';
          } else {
            statusBadge = '<span class="badge bg-label-secondary me-1">Unknown</span>';
          }

          html += `
            <tr>
              <td>${index + 1}</td>
              <td>${application.name}</td>
              <td>${application.description}</td>
              <td>${statusBadge}</td>
              <td>
                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#applicationEditModal" class='applicationEdit' data-id="${ application.id }" id="applicationEdit"><i class="bx bx-edit-alt"></i></button>
              </td>
            </tr>`;
        });
      }

      $('#applicationTable').html(html);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire('Error', 'Gagal memuat data aplikasi.', 'error');
    }
  });

    $("#saveApplication").click(function (e) {
      e.preventDefault();

      let name = $("#name").val();
      let description = $("#description").val();

      $.ajax({
        url: apiUrl,
        type: "POST",
        contentType: "application/json",
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        data: JSON.stringify({
          name: name,
          description: description,
        }),
        success: function (response) {
          $("#applicationModal").modal("hide");
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

          $('#applicationModal').modal('show');
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

    $(document).on('click', '#applicationEdit', function () {
      let appId = $(this).data('id');
      $.ajax({
        url: apiUrl + '/' + appId,
        method: 'GET',
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        success: function (response) {
          const application = response.data;

          $('#editAppId').val(application.id);
          $('#editAppName').val(application.name);
          $('#editAppDescription').val(application.description);
          $('#editAppStatus').val(application.status);

          $('#applicationEditModal').modal('show');
        },
        error: function () {
          Swal.fire('Error', 'Gagal mengambil data aplikasi.', 'error');
        }
      });
    });

    $("#saveApplicationEdit").click(function (e) {
      e.preventDefault();

      const appId = $("#editAppId").val();
      const name = $("#editAppName").val();
      const description = $("#editAppDescription").val();
      const status = $("#editAppStatus").val();

      $.ajax({
        url: apiUrl + '/' + appId,
        type: "PUT",
        contentType: "application/json",
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        data: JSON.stringify({
          name: name,
          description: description,
          status: status
        }),
        success: function (response) {
          $("#applicationEditModal").modal("hide");
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message,
            confirmButtonText: 'OK'
          }).then(() =>
          $('#loading').show(),
          location.reload());
        },
        error: function (xhr) {
          if (xhr.status === 422) {
            const res = xhr.responseJSON;
            const errors = res.errors;

            for (const field in errors) {
              $(`#editApp${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
              $(`#error-edit-${field}`).text(errors[field][0]);
            }

            $('#applicationEditModal').modal('show');
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

    // $(document).on('click', '#userDelete', function () {
    //   let userId = $(this).data('id');
    //   Swal.fire({
    //       title: 'Apakah Anda yakin?',
    //       text: 'Data pengguna ini akan dihapus!',
    //       icon: 'warning',
    //       showCancelButton: true,
    //       confirmButtonText: 'Hapus',
    //       cancelButtonText: 'Batal'
    //   }).then((result) => {
    //       if (result.isConfirmed) {
    //           $.ajax({
    //               url: apiUrl + '/' + userId,
    //               type: 'DELETE',
    //               success: function () {
    //                 Swal.fire({
    //                     title: 'Dihapus!',
    //                     text: 'Data pengguna telah dihapus.',
    //                     icon: 'success',
    //                     allowOutsideClick: false,
    //                     confirmButtonText: 'OK'
    //                 }).then(() => {
    //                     $('#loading').show(),
    //                     location.reload();
    //                 });

    //               },
    //               error: function () {
    //                   Swal.fire('Error', 'Gagal menghapus data pengguna.', 'error');
    //               }
    //           });
    //       }
    //   });
    // });

  })
</script>
@include('layouts.userAccess');
@endsection
