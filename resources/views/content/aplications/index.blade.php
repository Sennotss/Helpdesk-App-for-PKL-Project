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
      <tbody class="table-border-bottom-0" id='userTable'>
        @forelse($applications as $index => $application)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $application['name'] }}</td>
          <td>{{ $application['description'] }}</td>
          <td>
            @if ($application['status'] == 'active')
              <span class="badge bg-label-primary me-1">Active</span>
            @elseif ($application['status'] == 'maintenance')
              <span class="badge bg-label-warning me-1">Maintenance</span>
            @else
              <span class="badge bg-label-danger me-1">Non Active</span>
            @endif
          </td>
          <td>
            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#applicationEditModal" data-id="{{$application['_id']}}" id="applicationEdit"><i class="bx bx-edit-alt"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada data Aplikasi</td>
        </tr>
        @endforelse
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
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea type="text" id="description" class="form-control"></textarea>
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
              <input type="text" id="editName" class="form-control" placeholder="Enter Name">
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea type="text" id="editDescription" class="form-control"></textarea>
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
  $('document').ready(function(){
    let apiUrl = "{{config('api.base_url')}}/applications";

    // CREATE DATA
    $("#saveApplication").click(function (e) {
      e.preventDefault();

      let name = $("#name").val();
      let description = $("#description").val();

      // if (!name || !email || !password || !role) {
      //   alert("Semua field harus diisi!");
      //   return;
      // }

      $.ajax({
        url: apiUrl,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
          name: name,
          description: description
        }),
        success: function (response) {
          alert(response.message);
          $("#applicationModal").modal("hide");
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error("Error Response:", xhr);
          alert("Error: " + xhr.status + " - " + xhr.responseText);
        }
      });
    });

    $(document).on("click", "#applicationEdit", function () {
      let appId = $(this).data("id");
      console.log('id:' + appId)

      if (!appId) {
          alert("Gagal mendapatkan ID Aplikasi!");
          return;
      }

      $.ajax({
          url: apiUrl + '/' + appId,
          type: "GET",
          success: function (response) {
              console.log("Aplikasi Data:", response);

              $("#editAppId").val(appId);
              $("#editName").val(response.name);
              $("#editDescription").val(response.description);
              $("#editAppStatus").val(response.status);

              $("#applicationEditModal").modal("show");
          },
          error: function (xhr) {
              alert("Gagal mengambil data: " + xhr.responseText);
          }
      });
    });

    // UPDATE DATA
    $(document).on("click", "#saveApplicationEdit", function () {
      let appId = $("#editAppId").val();
      let name = $("#editName").val();
      let description = $("#editDescription").val();
      let status = $("#editAppStatus").val();

      console.log("appId :" + appId);

      // if (!userId || !name || !email || !role || !status) {
      //     alert("Semua field harus diisi!");
      //     return;
      // }

      $.ajax({
          url: apiUrl + '/' + appId,
          type: "PUT",
          contentType: "application/json",
          data: JSON.stringify({
              name: name,
              description: description,
              status: status
          }),
          success: function (response) {
              alert(response.message);
              $("#editApplicationModal").modal("hide");
              location.reload();
          },
          error: function (xhr) {
              console.error("Error:", xhr);
              alert("Gagal mengupdate data: " + xhr.responseText);
          }
      });
    });
  })
</script>
@endsection
