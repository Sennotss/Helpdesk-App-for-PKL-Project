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
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            @php
              $user = session('auth_user');
            @endphp
            <div class="col-sm-12 mb-3">
              <h5 class="card-title text-primary m-0">Selamat Datang, {{ $user['name'] ?? 'User' }}!</h5>
              <small>{{$user['role']}}</small>
            </div>
            <p class="mb-4">
              Hai {{ $user['name'] }}, kamu bisa membuat laporan kendala, memantau progres tiket, dan berkomunikasi langsung dengan tim IT.
              Silakan gunakan fitur helpdesk jika ada masalah ya!
            </p>
            <a href="{{ route('tickets') }}" class="btn btn-sm btn-outline-primary">Buat Tiket</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <h5 class="card-header">Riwayat Laporan Tiket Kamu</h5>
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead class="table-light">
            <tr>
              <th>Code</th>
              <th>Issue</th>
              <th>Status</th>
              <th>Created at</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($tickets as $ticket)
              <tr>
                <td>{{ $ticket->ticket_code }}</td>
                <td>{{ $ticket->issue ?? 'Tidak ada judul' }}</td>
                <td>
                  <span class="badge bg-label-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'onprogress' ? 'warning' : 'primary') }}">
                    {{ ucfirst($ticket->status) }}
                  </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                <td>
                  <a href="{{ url( 'tickets/detail/' . urlencode($ticket->ticket_code)) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">Belum ada tiket yang kamu buat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
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
