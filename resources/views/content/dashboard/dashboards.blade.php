@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
<div class="row">
  <!-- Total Tickets -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-primary rounded d-flex align-items-center justify-content-center">
          <i class="bx bx-notepad bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">Total Tickets</small>
          <h6 class="mb-0" id="ticket-total">{{$total}}</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- Open -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-warning rounded  d-flex align-items-center justify-content-center">
          <i class="bx bx-envelope-open bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">Open</small>
          <h6 class="mb-0" id="ticket-open">{{$open}}</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- On Progress -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-info rounded d-flex align-items-center justify-content-center">
          <i class="bx bx-loader bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">On Progress</small>
          <h6 class="mb-0" id="ticket-onprogress">{{ $onProgress }}</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- Revisi -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-danger rounded  d-flex align-items-center justify-content-center">
          <i class="bx bx-edit bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">Revisi</small>
          <h6 class="mb-0" id="ticket-revisi">{{ $revisi }}</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- Resolved -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-success rounded  d-flex align-items-center justify-content-center">
          <i class="bx bx-check-circle bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">Resolved</small>
          <h6 class="mb-0" id="ticket-resolved">{{ $resolved }}</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- Jadwal Helpdesk -->
  <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3 bg-label-secondary rounded  d-flex align-items-center justify-content-center">
          <i class="bx bx-calendar bx-sm"></i>
        </div>
        <div class="d-flex flex-column">
          <small class="text-muted mb-1">Jadwal Helpdesk</small>
          <h6 class="mb-0" id="jadwal-helpdesk">1. {{ $shiftPagiUser->name }} - 2. {{$shiftSoreUser->name}}</h6>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const loginSuccess = localStorage.getItem('login_success');

  if (loginSuccess === 'true') {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Login berhasil!',
    });

    localStorage.removeItem('login_success');
  }
</script>
@endsection
