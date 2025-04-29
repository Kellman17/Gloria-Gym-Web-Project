<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Personal Trainer</title>
    <link rel="stylesheet" href="/css/LoginPT.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Javascript/LoginPT.js"></script>
</head>
<body>


    <div class="auth-container" id="formlogin">
        <div class="auth-form login-form">
            <h2>Personal Trainer Login</h2>
            <form id="loginform" action="/loginpt/login" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="Email" placeholder="Enter your email" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="Password" placeholder="Enter your password" required />
                </div>
                <button type="submit" class="btn-auth">Login</button>
                <p style="margin-top: 10px;"><a href="#" onclick="openForgotPasswordForm()">Forgot Password?</a></p>
            </form>
        </div>
    </div>

    <!-- Form Forgot Password -->
<div class="auth-container" id="forgotPasswordContainer" style="display: none;">
    <div class="auth-form login-form">
        <h2>Reset Password</h2>
        <form id="forgotPasswordForm" action="/pt/forgotPassword" method="POST">
            <div class="form-group">
                <label for="forgotEmail">Email:</label>
                <input type="email" id="forgotEmail" name="Email" placeholder="Enter your email" required />
            </div>
            <div class="button-container">
                <button type="submit" class="btn-auth1">Submit</button>
                <button type="button" class="cancel-btn" onclick="closeForgotPasswordForm()">Cancel</button>
            </div>
        </form>
    </div>
</div>


</body>
<script>
    <?php if (session()->getFlashdata('error')): ?>
        document.getElementById('formlogin').style.display = 'none';
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= session()->getFlashdata('error'); ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FFD700',
        }).then(() => {
            document.getElementById('formlogin').style.display = 'block';
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        document.getElementById('formlogin').style.display = 'none';
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= session()->getFlashdata('success'); ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#FFD700',
        }).then(() => {
            document.getElementById('formlogin').style.display = 'block';
        });
    <?php endif; ?>
</script>

</html>
