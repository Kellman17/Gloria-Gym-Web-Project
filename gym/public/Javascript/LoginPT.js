// Show Forgot Password Form
function openForgotPasswordForm() {
    document.getElementById('formlogin').style.display = 'none';
    document.getElementById('forgotPasswordContainer').style.display = 'block';
}

// Hide Forgot Password Form
function closeForgotPasswordForm() {
    document.getElementById('forgotPasswordContainer').style.display = 'none';
    document.getElementById('formlogin').style.display = 'block';

}