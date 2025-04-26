@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');

@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
        </a>
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="bx bx-menu bx-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-medium d-block">{{session('auth_user')['name']}}</span>
                      <small class="text-muted">Admin</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#profileModal">
                  <i class="bx bx-user me-2"></i>
                  <span class="align-middle">My Profile</span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" id="logout-btn">
                  <i class='bx bx-power-off me-2'></i>
                  <span class="align-middle">Log Out</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>
  <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1"><i class="bx bx-user"></i>Profile User<h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">
              <div class="card h-100">
                <div class="card-header">
                  <span class="font-weight-bold">Informasi Akun</span>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col mb-3">
                      <label for="nameBasic" class="form-label">Name</label>
                      <input type="text" id="name-profile" class="form-control"">
                      <div class="invalid-feedback" id="error-name-profile"></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col mb-3">
                      <label for="nameBasic" class="form-label">Email</label>
                      <input type="text" id="email-profile" class="form-control"">
                      <div class="invalid-feedback" id="error-email-profile"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id='saveProfile'>Save</button>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="card h-100">
                <div class="card-header">
                  <span class="font-weight-bold">Change Password</span>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col mb-3">
                      <label for="nameBasic" class="form-label">Old Password</label>
                      <input type="text" id="old-password" class="form-control"">
                      <div class="invalid-feedback" id="error-old-password"></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col mb-3">
                      <label for="nameBasic" class="form-label">New Password</label>
                      <input type="text" id="new-password" class="form-control"">
                      <div class="invalid-feedback" id="error-new-password"></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col mb-3">
                      <label for="nameBasic" class="form-label">Confirm New Password</label>
                      <input type="text" id="confirm-new-password" class="form-control"">
                      <div class="invalid-feedback" id="error-confirm-new-password"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id='saveProfile'>Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / Navbar -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const authTokenNavbar = localStorage.getItem('auth_token');

    function addAuthorizationHeader(xhr) {
      if (authTokenNavbar) {
          xhr.setRequestHeader('Authorization', 'Bearer ' + authTokenNavbar);
      }
    }

    $('#logout-btn').click(function () {
      const authToken = localStorage.getItem('auth_token');
      const authUser = localStorage.getItem('auth_user');
      const userRole = localStorage.getItem('user_role');

      if (!authToken) {
        alert('Kamu sudah logout!');
        return;
      }

      Swal.fire({
        title: 'Apakah kamu yakin?',
        text: 'Kamu akan keluar dari akun ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '{{ config("api.base_url") }}/logout',
            method: 'POST',
            beforeSend: function(xhr){
              addAuthorizationHeader(xhr);
            },
            success: function () {
              localStorage.removeItem('auth_token');
              localStorage.removeItem('auth_user');
              localStorage.removeItem('user_role');
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kamu telah berhasil logout.',
              }).then(() => {
                window.location.href = '/login';
              });
            },
            error: function () {
              alert('Gagal logout.');
            }
          });
        } else {
          Swal.fire({
            icon: 'info',
            title: 'Dibatalkan',
            text: 'Logout dibatalkan!',
          });
        }
      });
    });


    $(document).ready(function () {
      function loadProfile() {
        $.ajax({
          url: '/api/profile',
          type: 'GET',
          beforeSend: function(xhr){
            addAuthorizationHeader(xhr)
          },
          success: function (response) {
            let data = response.data;
            $('#name-profile').val(data.name);
            $('#email-profile').val(data.email);
          },
          error: function (xhr) {
            console.log(xhr.responseText);
          }
        });
      }

      $('#profileModal').on('show.bs.modal', function () {
        loadProfile();
      });

      $('#saveProfile').on('click', function () {
        let name = $('#name-profile').val();
        let email = $('#email-profile').val();

        $.ajax({
          url: '{{ config("api.base_url")}}/profile/update',
          type: 'POST',
          beforeSend: function(xhr){
            addAuthorizationHeader(xhr)
          },
          data: {
            name: name,
            email: email
          },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Profile berhasil diperbarui!',
            });
            $('#profileModal').modal('hide');
          },
          error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            if (errors) {
              if (errors.name) {
                $('#name-profile').addClass('is-invalid');
                $('#error-name-profile').text(errors.name[0]);
              }
              if (errors.email) {
                $('#email-profile').addClass('is-invalid');
                $('#error-email-profile').text(errors.email[0]);
              }
            }
          }
        });
      });

      $('#savePassword').on('click', function () {
        let oldPassword = $('#old-password').val();
        let newPassword = $('#new-password').val();
        let confirmPassword = $('#confirm-new-password').val();

        $.ajax({
          url: '{{ config("api.base_url")}}/profile/update/profile/password',
          type: 'POST',
          beforeSend: function(xhr){
            addAuthorizationHeader(xhr)
          },
          data: {
            old_password: oldPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
          },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Password berhasil diubah!',
            });
            $('#profileModal').modal('hide');
          },
          error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            if (errors) {
              if (errors.old_password) {
                $('#old-password').addClass('is-invalid');
                $('#error-old-password').text(errors.old_password[0]);
              }
              if (errors.new_password) {
                $('#new-password').addClass('is-invalid');
                $('#error-new-password').text(errors.new_password[0]);
              }
              if (errors.confirm_password) {
                $('#confirm-new-password').addClass('is-invalid');
                $('#error-confirm-new-password').text(errors.confirm_password[0]);
              }
            }
          }
        });
      });
    });

  </script>
