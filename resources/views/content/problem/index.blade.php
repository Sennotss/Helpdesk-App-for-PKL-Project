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
      <tbody class="table-border-bottom-0" id='userTable'>
        @forelse($problems as $index => $problem)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $problem['name'] }}</td>
          <td>
            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#problemEditModal" data-id="{{$problem['_id']}}" id="problemEdit"><i class="bx bx-edit-alt"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada data Masalah</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
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
              <input type="text" id="editName" class="form-control" placeholder="Enter Name">
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
  
</script>
@endsection
