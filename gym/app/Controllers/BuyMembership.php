<?php

namespace App\Controllers;

use App\Models\MembershipRecordModel;
use App\Models\MembershipModel;
use App\Models\PersonalTrainingModel;
use App\Models\TambahPTModel;

use CodeIgniter\HTTP\ResponseInterface;

class BuyMembership extends BaseController
{
    public function submit()
    {   
        // Tangkap data form
        $id_member = $this->request->getPost('ID_Member');
        $nama_member = $this->request->getPost('Nama_Member');
        $id_membership = $this->request->getPost('ID_Membership');
        $jenis_membership = $this->request->getPost('Jenis_Membership');
        $durasi = $this->request->getPost('Durasi');
        $harga = (int) $this->request->getPost('Total_Harga'); // Pastikan harga diterima sebagai integer
        $pakai_pt = $this->request->getPost('Pakai_PT'); // Status Pakai PT (ya/tidak)
        $tgl_berlaku = $this->request->getPost('Tgl_Berlaku');
        $tgl_berakhir = $this->request->getPost('Tgl_Berakhir');
        $status = 'Pending'; // Status awal, bisa berubah setelah pembayaran terverifikasi
    
        // Tangani file upload bukti pembayaran
        $file = $this->request->getFile('Bukti_Pembayaran');
        $bukti_pembayaran = '';
        
        if ($file->isValid() && !$file->hasMoved()) {
            if (in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg']) && $file->getSize() <= 2097152) {
                // Menghasilkan nama acak untuk file
                $bukti_pembayaran = $file->getRandomName();
                // Menyimpan file bukti pembayaran
                $file->move(FCPATH . 'uploads/bukti', $bukti_pembayaran);
            } else {
                // Jika format file atau ukuran tidak valid
                echo "<script>alert('Format atau ukuran file tidak valid. Hanya JPEG, PNG'); window.history.back();</script>";
                return;
            }
        }
    
        // Proses pengecekan data membership yang dipilih
        $MembershipModel = new MembershipModel();
        $membershipData = $MembershipModel->find($id_membership); // Mengambil data membership berdasarkan ID
    
        if (!$membershipData) {
            // Jika data membership tidak ditemukan
            echo "<script>alert('Jenis membership tidak valid. Silakan pilih paket membership yang tersedia.'); window.history.back();</script>";
            return;
        }

        // Jika membership adalah Bulanan_Gym dan menggunakan PT
        if ($membershipData['Jenis_Membership'] === 'Bulanan_Gym' && $pakai_pt === 'ya') {
            // Update sesi personal training yang statusnya "booked" menjadi "paid"
            $PersonalTrainingModel = new PersonalTrainingModel();
            $data = $PersonalTrainingModel->where('ID_Member', $id_member)
                                        ->where('status', 'booked')
                                        ->countAllResults();

            if ($data > 0) {
                // Jika data ditemukan, lanjutkan ke proses update
                $PersonalTrainingModel->set('status', 'paid')
                ->where('ID_Member', $id_member)
                ->where('status', 'booked')
                ->update();
                echo 'Sesi berhasil diupdate!';
            } else {
                echo 'Data tidak ditemukan atau sudah tidak berstatus booked.';
            }

            

        }
    
        // Menyiapkan data transaksi untuk dimasukkan ke dalam tabel MembershipRecord
        $datatransaksi = [
            'ID_Member' => $id_member,
            'Nama_Member' => $nama_member,
            'ID_Membership' => $id_membership,
            'Jenis_Membership' => $membershipData['Jenis_Membership'],
            'Harga' => $harga, // Harga total sebagai integer
            'Pakai_PT' => $pakai_pt, // Menyimpan status penggunaan PT
            'Tgl_Berlaku' => $tgl_berlaku,
            'Tgl_Berakhir' => $tgl_berakhir,
            'Bukti_Pembayaran' => $bukti_pembayaran,
            'Status' => $status
        ];
    
        // Insert data transaksi ke tabel MembershipRecord
        $MembershipRecordModel = new MembershipRecordModel();
        $insertSuccess = $MembershipRecordModel->insert($datatransaksi);
        
        if ($insertSuccess) {
            // Redirect atau tampilkan pesan sukses
            log_message('info', 'Data yang diterima: ' . json_encode($this->request->getPost()));
            session()->setFlashdata('success', 'Pembelian membership berhasil!');
            return redirect()->to('/berhasillogin');
        } else {
            // Menangani error saat insert data
            echo "<script>alert('Terjadi kesalahan saat memproses transaksi. Silakan coba lagi.'); window.history.back();</script>";
        }
    }

    public function addPTToMembership() {
        $recordModel = new MembershipRecordModel();
        $tambahPTModel = new TambahPTModel();
        $PersonalTrainingModel = new PersonalTrainingModel();

        $id_member = $this->request->getPost('ID_Member1');
        $idPT = $this->request->getPost('ID_PT1');
        $idRecord = $this->request->getPost('ID_Record');
        $hargaPT = $this->request->getPost('Harga_PT');
        $buktiPT = $this->request->getFile('Bukti_TambahPT');
        $filePath = ''; 

        // Tangani file upload bukti pembayaran
        
        if ($buktiPT->isValid() && !$buktiPT->hasMoved()) {
            if (in_array($buktiPT->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg']) && $buktiPT->getSize() <= 2097152) {
                // Menghasilkan nama acak untuk b$buktiPT
                $filePath = $buktiPT->getRandomName();
                // Menyimpan b$buktiPT bukti pembayaran
                $buktiPT->move(FCPATH . 'uploads/buktitambah', $filePath);
            } else {
                // Jika format file atau ukuran tidak valid
                echo "<script>alert('Format atau ukuran file tidak valid. Hanya JPEG, PNG'); window.history.back();</script>";
                return;
            }
        }
        // Ambil data sesi terbaru berdasarkan ID Member
            $latestSession = $PersonalTrainingModel->where('ID_Member', $id_member)
            ->orderBy('ID_Sesi', 'DESC')
            ->first();

        if ($latestSession) {
            $idPT = $latestSession['ID_PT']; // Ganti ID_PT dengan yang ditemukan
            $namaPT = $latestSession['Nama_PT']; // Ambil nama PT dari data sesi
        } else {
            log_message('error', 'Tidak ditemukan data sesi terbaru untuk ID_Member: ' . $id_member);
            echo "<script>alert('Tidak ditemukan sesi terbaru untuk member ini.'); window.history.back();</script>";
            return;
        }
    
        // Menyimpan data PT ke tabel tambah_pt
        $dataTambahPT = [
            'ID_Record' => $idRecord,
            'ID_PT' => $idPT,
            'Harga_PT' => $hargaPT,
            'Bukti_TambahPT' => $filePath,
            'Status' => 'Pending',
            'Reason' => '',
        ];
        log_message('debug', 'Uploaded file path: ' . $filePath);

        
        // Insert data ke tabel tambah_pt
        $tambahPTModel->insert($dataTambahPT);
        
        log_message('debug', 'Data for tambahPTModel: ' . print_r($dataTambahPT, true));
        
                $PersonalTrainingModel = new PersonalTrainingModel();
                $countBooked = $PersonalTrainingModel->where('ID_Member', $id_member)
                ->where('status', 'booked')
                ->countAllResults();
        
        
                    if ($countBooked > 0) {
                        // Jika data ditemukan, lanjutkan ke proses update
                        $PersonalTrainingModel->set('status', 'paid')
                        ->where('ID_Member', $id_member)
                        ->where('status', 'booked')
                        ->update();
                        echo 'Sesi berhasil diupdate!';
                    } else {
                        echo 'Data tidak ditemukan atau sudah tidak berstatus booked.';
                    }

        
        // Debug untuk memastikan nilai ID_Record diterima
        log_message('debug', 'ID_Record diterima: ' . $idRecord);

        // Validasi ID_Record
        if (empty($idRecord)) {
            log_message('error', 'ID_Record tidak valid atau tidak dikirim.');
            throw new \RuntimeException('ID_Record tidak valid atau tidak dikirim dalam request.');
        }
        $existingData = $recordModel->where('ID_Record', $idRecord)->first();

        // Update status membership dan Pakai_PT
        if ($existingData) {
            // Jika data ditemukan, lakukan update menggunakan set()
            $recordModel->set('Pakai_PT', 'ya')
                        ->where('ID_Record', $idRecord)
                        ->update();
        
            log_message('info', 'Data berhasil diupdate untuk ID_Record: ' . $idRecord);
            echo 'Data berhasil diupdate!';
        } else {
            // Jika data tidak ditemukan
            log_message('error', 'Data dengan ID_Record ' . $idRecord . ' tidak ditemukan.');
            echo 'Data dengan ID_Record tidak ditemukan.';
        }

        log_message('info', 'Data yang diterima: ' . json_encode($this->request->getPost()));
            session()->setFlashdata('success', 'Pembelian Addon PT berhasil!');
        return redirect()->to('/berhasillogin');  // Redirect ke halaman membership
    }
    


   
}
