<?php

namespace App\Controllers;

use App\Models\PersonalTrainerModel;
use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class LoginPTController extends Controller
{
    // Halaman utama untuk login
    public function index()
    {
        // Ambil semua data PT
        $model = new PersonalTrainerModel();
        $data['trainers'] = $model->findAll();  // Ambil semua data PT

        return view('LoginPT', $data);  // Kirim data PT ke view LoginPT
    }
 // Proses login dengan email dan password
 public function login()
 {
     $PTModel = new PersonalTrainerModel();
 
     // Ambil data dari form
     $email = $this->request->getPost('Email');
     $password = $this->request->getPost('Password');
 
     // Cari data personal trainer berdasarkan email
     $PT = $PTModel->where('Email', $email)->first();
 
     if ($PT) {
         // Jika email ditemukan, cek password
         if (password_verify($password, $PT['Password'])) {
             // Login sukses, set session untuk personal trainer
             $session = session();
             $session->set([
                 'ID_PT' => $PT['ID_PT'],
                 'Nama_PT' => $PT['Nama_PT'],
                 'Email' => $PT['Email'],
                 'logged_in' => true
             ]);
 
             // Redirect ke halaman dashboard PT
             return redirect()->to('/PT');
         } else {
             // Password salah
             session()->setFlashdata('error', 'Password Anda salah. Silakan coba lagi.');
             return redirect()->to('/loginpt');
         }
     } else {
         // Email tidak ditemukan
         session()->setFlashdata('error', 'Email tidak ditemukan.');
         return redirect()->to('/loginpt');
     }
 }
 
 public function forgotPassword()
 {
     $email = $this->request->getPost('Email'); // Pastikan nama parameter sesuai dengan permintaan fetch
     $PTModel = new PersonalTrainerModel();
     $PT = $PTModel->getUserByEmail($email);
 
     if (!$PT) {
        return redirect()->back()->with('error', 'Email not found.');
     }
 
     // Generate reset token
     $token = bin2hex(random_bytes(16));
     $PTModel->update($PT['ID_PT'], ['Reset_Token' => $token]);
 
     // Kirim email dengan token
     $emailService = \Config\Services::email();
     $emailService->setFrom('boypunyaemail@example.com', 'Gloria GYM Karawaci');
     $emailService->setTo($email);
     $emailService->setSubject('Reset Password Personal Trainer');
     $emailService->setMessage("
         <p>Anda telah meminta untuk mereset password Anda.</p>
         <p>Klik link berikut untuk mereset password Anda:</p>
         <a href='" . base_url("/pt/resetPasswordForm/$token") . "'>Reset Password</a>
         <p>Jika Anda tidak meminta ini, abaikan email ini.</p>
     ");
 
     if ($emailService->send()) {
        return redirect()->back()->with('success', 'A reset password link has been sent to your email.');
     } else {
        return redirect()->back()->with('error', 'Email not found.');
     }
 }
 

 public function resetPasswordForm($token)
 {
     $PTModel = new PersonalTrainerModel();
     $PT = $PTModel->where('Reset_Token', $token)->first();
 
     if (!$PT) {
         return view('errors/custom_error', ['message' => 'Token tidak valid atau sudah kadaluarsa.']);
     }
 
     return view('ResetPasswordPT', ['token' => $token]);
 }
 

 public function resetPasswordSubmit()
 {
     $token = $this->request->getPost('token');
     $password = $this->request->getPost('password');
     $repassword = $this->request->getPost('repassword');
 
     if ($password !== $repassword) {
         session()->setFlashdata('error', 'Password dan Konfirmasi Password tidak cocok.');
         return redirect()->back();
     }
 
     // Validasi token
     $PTModel = new PersonalTrainerModel();
     $PT = $PTModel->where('Reset_Token', $token)->first();
 
     if (!$PT) {
         session()->setFlashdata('error', 'Token tidak valid atau sudah kadaluarsa.');
         return redirect()->to('/loginpt');
     }
 
     // Hash password baru dan hapus token
     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
     $PTModel->update($PT['ID_PT'], [
         'Password' => $hashedPassword,
         'Reset_Token' => null
     ]);
 
     session()->setFlashdata('success', 'Password berhasil direset. Silakan login.');
     return redirect()->to('/loginpt');
 }
 
 public function logout()
 {
     // Hapus semua session
     session()->destroy();
 
     // Redirect ke halaman login PT
     return redirect()->to('/loginpt')->with('success', 'Anda telah berhasil logout.');
 }
 
 

}
