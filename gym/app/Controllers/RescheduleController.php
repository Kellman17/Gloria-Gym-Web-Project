<?php

namespace App\Controllers;

use App\Models\JadwalPTModel;
use App\Models\PersonalTrainingModel;
use App\Models\MembershiprecordModel;

class RescheduleController extends BaseController
{
    // 1. Generate Kalender (Tanggal Tersedia)
    public function getAvailableDates()
    {
        $jadwalModel = new JadwalPTModel();
        $personalTrainingModel = new PersonalTrainingModel();
        $membershipModel = new MembershiprecordModel();
    
        $idMember = $this->request->getGet('idMember');
        log_message('debug', 'ID Member yang diterima: ' . $idMember);
    
        if (!$idMember) {
            return $this->response->setJSON(['error' => 'ID Member tidak dikirim', 'availableDates' => []]);
        }
    
        // Ambil rentang membership
        $membership = $membershipModel->where('ID_Member', $idMember)
            ->where('Status', 'Aktif')
            ->first();
    
        if (!$membership) {
            return $this->response->setJSON(['error' => 'Membership tidak aktif', 'availableDates' => []]);
        }
    
        $membershipStart = date('Y-m-d');
        $membershipEnd = date('Y-m-d', strtotime($membership['Tgl_Berakhir']));
    
        $jadwal = $jadwalModel->findAll();
        $availableDates = [];
    
        foreach ($jadwal as $item) {
            $tanggal = $item['Tanggal'];
            $idPT = $item['ID_PT'];
    
            if ($tanggal >= $membershipStart && $tanggal <= $membershipEnd) {
                for ($i = 1; $i <= 5; $i++) {
                    $sesiStatus = !empty($item["Sesi$i"]) ? strtolower($item["Sesi$i"]) : '';
                    $sessionTime = $this->mapSessionTime($i);
    
                    $isBooked = $personalTrainingModel
                        ->where('ID_PT', $idPT)
                        ->where('date', $tanggal)
                        ->where('session_time', $sessionTime)
                        ->countAllResults();
    
                    if ($sesiStatus === 'tersedia' && $isBooked == 0) {
                        $availableDates[] = $tanggal;
                        break;
                    }
                }
            }
        }
    
        log_message('debug', 'Available Dates: ' . json_encode($availableDates));
        return $this->response->setJSON(['availableDates' => array_values(array_unique($availableDates))]);
    }
    
    private function mapSessionTime($index)
    {
        $times = [
            1 => "07:00 - 09:00",
            2 => "09:00 - 11:00",
            3 => "11:00 - 13:00",
            4 => "15:00 - 17:00",
            5 => "19:00 - 21:00"
        ];
        return $times[$index];
    }
    
    

    // Mendapatkan Sesi Tersedia
    public function getAvailableSessions()
    {
        $tanggal = $this->request->getGet('date');
        $idPT = $this->request->getGet('idPT');

        if (!$tanggal || !$idPT) {
            return $this->response->setJSON(['success' => false, 'message' => 'Parameter invalid']);
        }

        $jadwalModel = new JadwalPTModel();
        $personalTrainingModel = new PersonalTrainingModel();

        $jadwal = $jadwalModel->where('ID_PT', $idPT)->where('Tanggal', $tanggal)->first();
        $bookedSessions = $personalTrainingModel->where('ID_PT', $idPT)->where('date', $tanggal)->findAll();

        $bookedTimes = array_column($bookedSessions, 'session_time');
        $sessions = [];

        if ($jadwal) {
            for ($i = 1; $i <= 5; $i++) {
                $time = $this->mapSessionTime($i);
                if (!in_array($time, $bookedTimes) && $jadwal["Sesi$i"] === 'tersedia') {
                    $sessions[] = ['time' => $time, 'status' => 'tersedia'];
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'sessions' => $sessions]);
    }


    // 3. Proses Reschedule
    public function rescheduleSession()
    {
        $personalTrainingModel = new PersonalTrainingModel();
    
        // Ambil data POST
        $idSesiLama = $this->request->getPost('ID_Sesi');
        $idPT = $this->request->getPost('ID_PT');
        $tanggalBaru = $this->request->getPost('tanggal');
        $sesiBaru = $this->request->getPost('sesi');
    
        // Validasi data
        if (!$idSesiLama || !$idPT || !$tanggalBaru || !$sesiBaru) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap!']);
        }
    
        // Ambil sesi lama
        $sesiLama = $personalTrainingModel->find($idSesiLama);
        if (!$sesiLama) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi lama tidak ditemukan!']);
        }
    
        // Hapus sesi lama
        $personalTrainingModel->delete($idSesiLama);
    
        // Simpan sesi baru
        $data = [
            'ID_PT' => $idPT,
            'Nama_PT' => $sesiLama['Nama_PT'],
            'ID_Member' => $sesiLama['ID_Member'],
            'Nama_Member' => $sesiLama['Nama_Member'],
            'date' => $tanggalBaru,
            'session_time' => $sesiBaru,
            'status' => 'paid',
            'rating' => null,
            'review' => null,
            'Latihan' => null
        ];
    
        $personalTrainingModel->insert($data);
    
        return $this->response->setJSON(['success' => true, 'message' => 'Reschedule berhasil']);
    }
    

}
