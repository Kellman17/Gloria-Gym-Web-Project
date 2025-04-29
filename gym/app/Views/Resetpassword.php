<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="/css/Portal.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-form reset-password-form">
        <h2>Reset Password</h2>
        <form action="/updatePassword" method="POST">
            <input type="hidden" name="token" value="<?= esc($token) ?>">
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="Password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="ConfirmPassword" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn-auth">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
