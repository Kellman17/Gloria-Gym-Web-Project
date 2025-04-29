<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\MembershipRecordModel; // Tambahkan ini
use App\Config\Database;
use CodeIgniter\Controller;

class Portal extends BaseController {
    public function test() {
        $memberModel = new MemberModel();

        // Ambil semua data member dari model
        $members = $memberModel->findAll(); // findAll() akan mengambil semua data dari tabel

        // Kirimkan data ke view 'Member'
        return view('Member', ['members' => $members]);
    }

    
    public function index() {
        return view('Portal'); // Mengarahkan ke view Portal.php
    }
    public function signup()
    {
        $MemberModel = new MemberModel();
    
        // Ambil data dari form
        $Name = $this->request->getPost('Nama_Member');
        $NoHP = $this->request->getPost('NoHP');
        $Email = $this->request->getPost('Email');
        $Password = $this->request->getPost('Password');
        $RePassword = $this->request->getPost('RePassword');
    
        // Validasi password dan repassword
        if ($Password !== $RePassword) {
            session()->setFlashdata('error', 'Password dan Re-Password tidak sesuai.');
            return redirect()->back();
        }
    
        // Cek apakah email sudah ada
        $existingUser = $MemberModel->getUserByEmail($Email);
        if ($existingUser) {
            session()->setFlashdata('error', 'Email sudah terdaftar.');
            return redirect()->back();
        }
    
        // Proses upload foto
        $foto = $this->request->getFile('Foto_Member');
        $fotoName = null;
    
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            if (in_array($foto->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg']) && $foto->getSize() <= 2097152) {
                // Menghasilkan nama acak untuk file
                $fotoName = $foto->getRandomName();
                // Menyimpan file bukti pembayaran
                $foto->move(FCPATH . 'uploads/member', $fotoName);
            } else {
                // Jika format file atau ukuran tidak valid
                session()->setFlashdata('error', 'Format atau ukuran file tidak valid. Hanya JPEG, PNG dengan ukuran max 2MB.');
                return redirect()->back();
            }
        }
    
        // Hash password
        $HashedPassword = password_hash($Password, PASSWORD_DEFAULT);
    
        // Simpan ke database di tabel (member)
        $saved = $MemberModel->save([
            'Nama_Member' => $Name,
            'Foto_Member' => $fotoName,
            'NoHP' => $NoHP,
            'Email' => $Email,
            'Password' => $HashedPassword,
        ]);

        if (!$saved) {
            session()->setFlashdata('error', 'Gagal mendaftar.');
            return redirect()->back();
        }
    
        session()->setFlashdata('success', 'Akun berhasil terdaftar. Silakan login.');
        return redirect()->to('/Portal');
    }
    
    public function login() {
        $MemberModel = new MemberModel();
    
        // Ambil data dari form
        $Email = $this->request->getPost('Email');
        $Password = $this->request->getPost('Password');
    
        // Cek pengguna berdasarkan email
        $Member = $MemberModel->getUserByEmail($Email);
    
        if ($Member) {
            // Email ditemukan, cek password
            if (password_verify($Password, $Member['Password'])) {
                // Login sukses
                $session = session();
                // Set session hanya untuk Member
                if ($Email !== 'admin@gmail.com' && $Email !== 'trainer@gmail.com') {
                    $session->set([
                        'ID_Member' => $Member['ID_Member'],
                        'Nama_Member' => $Member['Nama_Member'],
                        'Foto_Member' => $Member['Foto_Member'],
                        'Email' => $Member['Email'],
                        'NoHP' => $Member['NoHP']
                    ]);
                }
                // Cek role berdasarkan email
                if ($Email === 'admin@gmail.com' && $Password === 'admin') {
                    return redirect()->to('/dashboard'); // Arahkan ke halaman dashboard untuk admin
                } elseif ($Email === 'trainer@gmail.com' && $Password === 'trainer') {
                    return redirect()->to('/loginpt'); // Arahkan ke halaman PT untuk trainer
                } else {
                    return redirect()->to('/berhasillogin'); // Arahkan ke halaman default setelah login
                }
            } else {
                // Email benar tetapi password salah
                return redirect()->to('/Portal')->with('error', 'Password salah. Silakan coba lagi.');
            }
        } else {
            // Email tidak ditemukan
            return redirect()->to('/Portal')->with('error', 'Email tidak ditemukan.');
        }
    }
    

    public function afterlogin(){

        return redirect()->to('/Homegymafterlogin'); // Mengarahkan ke view Homegymafterlogin.php
    }

    public function addMembershipRecord($memberID) {
        $MembershipRecordModel = new MembershipRecordModel();
    
        // Simulasi data membership
        $MembershipID = 1; // ID membership yang dibeli
        $MembershipType = "Harian"; // Tipe membership
        $StartDate = date('Y-m-d H:i:s'); // Tanggal mulai membership
        $Duration = 1; // Durasi 1 bulan
        $EndDate = date('Y-m-d H:i:s', strtotime("+{$Duration} months")); // Tanggal berakhir
    
        // Simpan data ke tabel Membership_Record
        $MembershipRecordModel->save([
            'ID_Member' => $memberID,
            'ID_Membership' => $MembershipID,
            'Jenis_Membership' => $MembershipType,
            'Tgl_Berlaku' => $StartDate,
            'Tgl_Berakhir' => $EndDate,
        ]);
    
        return redirect()->to('/membership_success'); // Redirect ke halaman sukses membership
    }

    public function resetPasswordForm($token)
    {
        $memberModel = new MemberModel();
    
        // Periksa apakah token valid
        $user = $memberModel->where('Reset_Token', $token)->first();
    
        if ($user) {
            // Kirim token ke view
            return view('Resetpassword', ['token' => $token]);
        } else {
            return redirect()->to('/Portal')->with('error', 'Invalid or expired token.');
        }
    }
    
    

    public function resetPasswordRequest()
    {
        $email = $this->request->getPost('Email');
        $memberModel = new MemberModel();
    
        // Cek apakah email ada di database
        $user = $memberModel->getUserByEmail($email);
        if ($user) {
            // Generate token reset password
            $token = bin2hex(random_bytes(32));
    
            // Simpan token ke database
            $memberModel->update($user['ID_Member'], ['Reset_Token' => $token]);
    
            // Kirim email ke pengguna
            $this->sendResetEmail($email, $token);
    
            return redirect()->back()->with('success', 'A reset password link has been sent to your email.');
        } else {
            return redirect()->back()->with('error', 'Email not found.');
        }
    }
    
    private function sendResetEmail($email, $token)
    {
        $resetLink = base_url('/resetPasswordForm/' . $token);
    
        $message = "
            <p>Hi,</p>
            <p>You requested to reset your password. Click the link below to reset your password:</p>
            <a href='$resetLink'>Reset Password</a>
            <p>If you did not request this, please ignore this email.</p>
        ";
    
        $emailService = \Config\Services::email();
        $emailService->setFrom('boypunyaemail@gmail.com', 'Gloria GYM Karawaci');
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password');
        $emailService->setMessage($message);
    
        if ($emailService->send()) {
            log_message('info', 'Reset password email sent successfully to ' . $email);
        } else {
            log_message('error', 'Failed to send reset password email. Error: ' . $emailService->printDebugger(['headers']));
        }
    }
    
    
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('Password');
        $confirmPassword = $this->request->getPost('ConfirmPassword');
    
        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }
    
        $memberModel = new MemberModel();
        $user = $memberModel->where('Reset_Token', $token)->first();
    
        if ($user) {
            // Hash password baru
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Update password dan hapus token
            $memberModel->update($user['ID_Member'], [
                'Password' => $hashedPassword,
                'Reset_Token' => null
            ]);
    
            return redirect()->to('/Portal')->with('success', 'Your password has been updated.');
        } else {
            return redirect()->back()->with('error', 'Invalid reset token.');
        }
    }
    

}
