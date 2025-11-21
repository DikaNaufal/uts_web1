// assets/js/validate.js
function validateLogin() {
  var email = document.getElementById('email').value.trim();
  var password = document.getElementById('password').value;

  // validasi menggunakan if
  if (email === '') {
    alert('Email harus diisi!');
    return false;
  }
  if (password === '') {
    alert('Password harus diisi!');
    return false;
  }
  // cek sederhana format email
  if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
    alert('Format email tidak valid.');
    return false;
  }
  // jika lolos semua, kembalikan true agar form submit (server-side juga memverifikasi)
  return true;
}
