<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="/css/loginPT.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="auth-container">
    <div class="auth-form login-form">
        <h2>Reset Password</h2>
        <form id="resetPasswordForm" action="/pt/resetPasswordSubmit" method="POST">
            <input type="hidden" name="token" value="<?= esc($token); ?>">
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="repassword">Konfirmasi Password Baru</label>
                <input type="password" id="repassword" name="repassword" required>
            </div>
            <button type="submit" class="btn-auth">Reset Password</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        
        const password = document.getElementById('password').value;
        const repassword = document.getElementById('repassword').value;

        if (password !== repassword) {
            // Password and repassword do not match
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Password dan Konfirmasi Password tidak cocok.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#FFD700'
            });
        } else {
            // Show SweetAlert before form submission
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Password berhasil diubah.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#FFD700'
            }).then(() => {
                // Submit the form programmatically after SweetAlert
                document.getElementById('resetPasswordForm').submit();
            });
        }
    });
</script>
</body>
</html>
