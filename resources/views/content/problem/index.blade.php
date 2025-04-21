@extends('layouts/contentNavbarLayout')

@section('title', 'Problem')

@section('content')
@include('layouts.page-title')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    {{-- <div class="demo-inline-spacing"> --}}
      <h5>Data Problem</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#problemModal">Add Data</button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" id='problemTable'>
      </tbody>
    </table>
  </div>
  @include('components.loading')
  <div class="modal fade" id="problemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Add Data Problem<h5>
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveProblem'>Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="problemEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Edit data Problem<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editProblemId">
          {{-- <div class="row">
            <div class="col mb-3">
              <label for="id" class="form-label">ID</label>
              <input type="number" id="editUserId" class="form-control" readonly>
            </div>
          </div> --}}
          <div class="row">
            <div class="col mb-3">
              <label for="nameBasic" class="form-label">Name</label>
              <input type="text" id="editProblemName" class="form-control" placeholder="Enter Name">
              <div class="invalid-feedback" id="error-edit-name"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id='saveProblemEdit'>Save changes</button>
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
    let apiUrl = "{{config('api.base_url')}}/problems";
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
        html = `<tr><td colspan="6" class="text-center">Tidak ada data Masalah</td></tr>`;
      } else {
        data.forEach((problem, index) => {
          html += `
            <tr>
              <td>${index + 1}</td>
              <td>${problem.name}</td>
              <td>
                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#problemEditModal" class='problemEdit' data-id="${ problem.id }" id="problemEdit"><i class="bx bx-edit-alt"></i></button>
              </td>
            </tr>`;
        });
      }

      $('#problemTable').html(html);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire('Error', 'Gagal memuat data Masalah.', 'error');
    }
  });

    $("#saveProblem").click(function (e) {
      e.preventDefault();

      let name = $("#name").val();

      $.ajax({
        url: apiUrl,
        type: "POST",
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        contentType: "application/json",
        data: JSON.stringify({
          name: name,
        }),
        success: function (response) {
          $("#problemModal").modal("hide");
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

    $(document).on('click', '#problemEdit', function () {
      let problemId = $(this).data('id');
      $.ajax({
        url: apiUrl + '/' + problemId,
        method: 'GET',
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        success: function (response) {
          const problem = response.data;

          $('#editProblemId').val(problem.id);
          $('#editProblemName').val(problem.name);

          $('#applicationEditModal').modal('show');
        },
        error: function () {
          Swal.fire('Error', 'Gagal mengambil data Masalah.', 'error');
        }
      });
    });

    $("#saveProblemEdit").click(function (e) {
      e.preventDefault();

      const problemId = $("#editProblemId").val();
      const name = $("#editProblemName").val();

      $.ajax({
        url: apiUrl + '/' + problemId,
        type: "PUT",
        contentType: "application/json",
        beforeSend: function(xhr){
          addAuthorizationHeader(xhr)
        },
        data: JSON.stringify({
          name: name,
        }),
        success: function (response) {
          $("#problemEditModal").modal("hide");
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
              $(`#editProblem${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
              $(`#error-edit-${field}`).text(errors[field][0]);
            }

            $('#problemEditModal').modal('show');
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
@include('layouts.userAccess')
@endsection
