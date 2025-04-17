@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')
@include('layouts.page-title')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    {{-- <div class="demo-inline-spacing"> --}}
      <h5>Data Users</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add Data</button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id='userTable'>
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
                <option value="client">Client</option>
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
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="editUserEmail" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="exampleFormControlSelect1" class="form-label">Role</label>
              <select class="form-select" id="editUserRole" aria-label="Default select example">
                <option value=''>Pilih Role</option>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
              </select>
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
    $('#loading').show();
    let apiUrl = "{{config('api.base_url')}}/users";
    $.ajax({
    url: apiUrl,
    method: 'GET',
    success: function (data) {
      console.log(data)
      $('#loading').hide();
      let html = '';
      if (data.length === 0) {
        html = `<tr><td colspan="6" class="text-center">Tidak ada data pengguna</td></tr>`;
      } else {
        data.forEach((user, index) => {
          html += `
            <tr>
              <td>${index + 1}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.role}</td>
              <td>
                ${user.status === 'active'
                  ? '<span class="badge bg-label-primary me-1">Active</span>'
                  : '<span class="badge bg-label-danger me-1">Non Active</span>'
                }
              </td>
              <td>
                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#userEditModal" class='userEdit' data-id="${ user.id }" id="userEdit"><i class="bx bx-edit-alt"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-id="${ user.id }" id="userDelete"><i class="bx bx-trash-alt"></i></button>
              </td>
            </tr>`;
        });
      }

      $('#userTable').html(html);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire('Error', 'Gagal memuat data pengguna.', 'error');
    }
  });

    $("#saveUser").click(function (e) {
      e.preventDefault();

      let name = $("#name").val();
      let email = $("#email").val();
      let password = $("#password").val();
      let role = $("#role").val();

      $.ajax({
        url: apiUrl,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
          name: name,
          email: email,
          password: password,
          role: role
        }),
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

    $(document).on('click', '#userEdit', function () {
      let userId = $(this).data('id');
      $.ajax({
        url: apiUrl + '/' + userId,
        method: 'GET',
        success: function (response) {
          const user = response.data;

          $('#editUserId').val(user.id);
          $('#editUserName').val(user.name);
          $('#editUserEmail').val(user.email);
          $('#editUserRole').val(user.role);
          $('#editUserStatus').val(user.status);

          $('#userEditModal').modal('show');
        },
        error: function () {
          Swal.fire('Error', 'Gagal mengambil data user.', 'error');
        }
      });
    });

    $("#saveUserEdit").click(function (e) {
      e.preventDefault();

      const userId = $("#editUserId").val();
      const name = $("#editUserName").val();
      const email = $("#editUserEmail").val();
      const role = $("#editUserRole").val();
      const status = $("#editUserStatus").val();

      $.ajax({
        url: apiUrl + '/' + userId,
        type: "PUT",
        contentType: "application/json",
        data: JSON.stringify({
          name: name,
          email: email,
          role: role,
          status: status
        }),
        success: function (response) {
          $("#userEditModal").modal("hide");
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
              $(`#editUser${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
              $(`#error-edit-${field}`).text(errors[field][0]);
            }

            $('#userEditModal').modal('show');
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

    $(document).on('click', '#userDelete', function () {
      let userId = $(this).data('id');
      Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Data pengguna ini akan dihapus!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: apiUrl + '/' + userId,
                  type: 'DELETE',
                  success: function () {
                    Swal.fire({
                        title: 'Dihapus!',
                        text: 'Data pengguna telah dihapus.',
                        icon: 'success',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#loading').show(),
                        location.reload();
                    });

                  },
                  error: function () {
                      Swal.fire('Error', 'Gagal menghapus data pengguna.', 'error');
                  }
              });
          }
      });
    });

  })
</script>

@endsection
