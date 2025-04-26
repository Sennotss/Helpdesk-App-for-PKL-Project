@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')
@include('layouts.page-title')
<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift Pagi</th>
                <th>Shift Sore</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="schedule-table">
          @foreach ($schedules as $schedule)
            @php
              $carbonDate = \Carbon\Carbon::parse($schedule->date);
              $isWeekend = $carbonDate->isWeekend(); // Sabtu atau Minggu
            @endphp
            <tr data-id="{{ $schedule->id }}" class="{{ $isWeekend ? 'table-danger' : '' }}">
              <td><input type="date" class="form-control schedule-date" value="{{ $schedule->date }}"></td>
                <td>
                    <select class="form-control shift-pagi-select">
                      @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $schedule->shift_pagi_user_id ? 'selected' : '' }}>
                          {{ $user->name }}
                        </option>
                    @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control shift-sore-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $schedule->shift_sore_user_id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control description" value="{{ $schedule->description }}"></td>
                <td><button class="btn btn-success save-schedule">Save</button></td>
              </tr>
          @endforeach
        </tbody>
    </table>
    <button class="btn btn-primary my-3" id="add-schedule-row">+ Tambah Jadwal</button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
      const authToken = localStorage.getItem('auth_token');

      function addAuthorizationHeader(xhr) {
          if (authToken) {
              xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
          }
      }

      let apiUrl = "{{ config('api.base_url') }}/schedules";
        $('#schedule-table').on('click', '.save-schedule', function () {
          let row = $(this).closest('tr');
          let id = row.data('id');
          let date = row.find('.schedule-date').val();
          let shiftPagiUserId = row.find('.shift-pagi-select').val();
          let shiftSoreUserId = row.find('.shift-sore-select').val();
          let notes = row.find('.description  ').val();

          $.ajax({
              url: apiUrl + (id ? '/' + id : ''),
              method: id ? 'PUT' : 'POST',
              beforeSend: function(xhr){
                  addAuthorizationHeader(xhr)
              },
              data: {
                date: date,
                shift_pagi_user_id: shiftPagiUserId,
                shift_sore_user_id: shiftSoreUserId,
                description: notes
              },
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Jadwal berhasil diupdate!',
                  text: response.message,
                  confirmButtonText: 'OK'
                }).then(() =>
                  location.reload());

                if (!id && response.data && response.data.id) {
                    row.attr('data-id', response.data.id);
                }
              },
              error: function() {
                alert('Terjadi kesalahan saat menyimpan jadwal');
              }
          });
      });


      $('#add-schedule-row').click(function () {
        let newRow = `
            <tr>
              <td><input type="date" class="form-control schedule-date" value=""></td>
                <td>
                    <select class="form-control shift-pagi-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control shift-sore-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control schedule-notes" value=""></td>
                <td><button class="btn btn-success save-schedule">Save</button></td>
            </tr>
        `;
        $('#schedule-table').append(newRow);
    });

    });
</script>
@endsection
