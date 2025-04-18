<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const user = JSON.parse(localStorage.getItem('auth_user'));
  const allowedRoles = ['admin']; // role yang boleh akses halaman ini

  if (!user || !allowedRoles.includes(user.role)) {
    Swal.fire({
      icon: 'warning',
      title: 'Akses Ditolak!',
      text: 'Anda tidak memiliki akses ke halaman ini.',
      showConfirmButton: false,
      timer: 2000
    }).then(() => {
      window.location.href = '/dashboard';
    });
  }
</script>
