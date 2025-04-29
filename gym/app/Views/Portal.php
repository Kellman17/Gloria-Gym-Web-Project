
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login</title>
    <link rel="stylesheet" href="/css/Portal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="auth-container">
  <!-- Form Login -->
    <div class="auth-form login-form">
    <h2>Login</h2>
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="Email" placeholder="Enter your email" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="Password" placeholder="Enter your password" required />
            </div>
            <button type="submit" class="btn-auth">Login</button>
            <p>Don't have an account? <a href="#" id="goSignup">Sign up</a></p>
            <p><a href="#" id="goForgetPassword">Forget Password?</a></p>

        </form>
    </div>

  <!-- Form Signup -->
        <div class="auth-form signup-form" style="display: none;">
        <h2>Sign Up</h2>
            <form action="/signup" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="Nama_Member" placeholder="Isi Nama Lengkap" required />
                </div>
                <div class="form-group">
                    <label for="foto">Upload Photo</label>
                    <input type="file" id="foto" name="Foto_Member" accept="image/*" />
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="NoHP" placeholder="Isi Nomor HP" required />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="signup-email" name="Email" placeholder="Isi Alamat Email" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="signup-password" name="Password" placeholder="Buatlah Password" required />
                </div>
                <div class="form-group">
                    <label for="repassword">Re-Password</label>
                    <input type="password" id="signup-repassword" name="RePassword" placeholder="Ulangi Password" required />
                </div>
                <button type="submit" class="btn-auth">Sign Up</button>
                <p>Already have an account? <a href="#" id="goLogin">Login</a></p>
            </form>
        </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="modal-container">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reset Password</h3>
        </div>
        <form id="resetPasswordForm" action="/resetPassword" method="POST">
            <div class="modal-body">
                <input type="email" id="resetEmail" name="Email" placeholder="Enter your email" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-auth1">Submit</button>
                <button type="button" class="cancel-btn" onclick="closeResetPasswordModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- JavaScript untuk beralih antara Login dan Signup -->
<script>
  document.getElementById('goSignup').addEventListener('click', function() {
    document.querySelector('.login-form').style.display = 'none';
    document.querySelector('.signup-form').style.display = 'block';
  });

  document.getElementById('goLogin').addEventListener('click', function() {
    document.querySelector('.signup-form').style.display = 'none';
    document.querySelector('.login-form').style.display = 'block';
  });

  document.getElementById('goForgetPassword').addEventListener('click', function () {
    document.getElementById('resetPasswordModal').style.display = 'flex';
    });

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').style.display = 'none';
    }

</script>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= session()->getFlashdata('error'); ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= session()->getFlashdata('success'); ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
</body>
</html>