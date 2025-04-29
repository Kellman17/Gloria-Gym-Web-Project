<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PersonalTrainingModel;
use App\Models\MemberModel;
use App\Models\PersonalTrainerModel;
use App\Models\JadwalPTModel;

class PersonalTrainingController extends Controller
{
    protected $personalTrainingModel;
    protected $personalTrainerModel;
    protected $memberModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->personalTrainingModel = new PersonalTrainingModel();
        $this->personalTrainerModel = new PersonalTrainerModel();
        $this->memberModel = new MemberModel();
        $this->jadwalModel = new JadwalPTModel();

    }

    // Save selected sessions to the `personal_training` table
    public function saveSessions()
    {
        try {
            $data = $this->request->getJSON(true);
    
            log_message('debug', 'Data yang diterima di saveSessions: ' . json_encode($data));
    
            $trainerId = $data['trainer_id'] ?? null;
            $memberId = $data['member_id'] ?? null;
            $trainerName = $data['Nama_PT'] ?? null;
            $memberName = $data['Nama_Member'] ?? null;
            $sessions = $data['sessions'] ?? [];
    
            if (!$trainerId || !$memberId || !$trainerName || !$memberName || empty($sessions)) {
                throw new \InvalidArgumentException("Data tidak lengkap. Pastikan semua data sudah dikirim.");
            }
    
            foreach ($sessions as $session) {
                $existingSession = $this->personalTrainingModel
                    ->where('ID_PT', $trainerId)
                    ->where('date', $session['date'])
                    ->where('session_time', $session['time']) // Periksa format waktu, bukan kode sesi
                    ->where('status !=', 'paid') // Filter sesi yang sudah dibayar
                    ->first();
    
                if ($existingSession) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Sesi {$session['time']} sudah dibooking untuk tanggal {$session['date']}.",
                    ]);
                }
    
                // Simpan sesi dengan format waktu yang benar
                $this->personalTrainingModel->insert([
                    'ID_PT' => $trainerId,
                    'Nama_PT' => $trainerName,
                    'ID_Member' => $memberId,
                    'Nama_Member' => $memberName,
                    'date' => $session['date'],
                    'session_time' => $session['time'], // Format waktu langsung dari frontend
                    'status' => 'booked',
                ]);
            }
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sesi berhasil disimpan.',
            ]);
        } catch (\InvalidArgumentException $e) {
            log_message('error', 'Input tidak valid di saveSessions: ' . $e->getMessage());
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error di saveSessions: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ]);
        }
    }
    

    // Reset all sessions for a member
    public function resetSessions()
    {
        try {
            $data = $this->request->getJSON(true);
            $memberId = $data['member_id'] ?? null;

            if (!$memberId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID Member tidak valid.',
                ]);
            }

            $this->personalTrainingModel->where('ID_Member', $memberId)
                                        ->where('status !=', 'paid') // Mengabaikan sesi dengan status 'paid'
                                        ->delete();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Semua sesi berhasil direset.',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in resetSessions: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Silakan Coba Lagi.',
            ]);
        }
    }

    // Retrieve saved sessions for a specific date and trainer
    public function getSavedSessions($trainerId, $memberId)
{
    try {
        if (empty($trainerId) || empty($memberId)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Trainer ID atau Member ID tidak valid.',
            ]);
        }

        $sessions = $this->personalTrainingModel
            ->where('ID_PT', $trainerId)
            ->where('ID_Member', $memberId)
            ->where('status !=', 'paid') // Mengabaikan sesi dengan status 'paid'
            ->findAll();

        $formattedSessions = array_map(function ($session) {
            return [
                'time' => $session['session_time'],
                'date' => $session['date'],
                'status' => $session['status'],
            ];
        }, $sessions);

        return $this->response->setJSON([
            'success' => true,
            'sessions' => $formattedSessions,
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error in getSavedSessions: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memuat sesi yang sudah dibooking.',
        ]);
    }
}

    

    public function getTotalSessions($memberId)
    {
        try {
            $totalSessions = $this->personalTrainingModel
                ->where('ID_Member', $memberId)
                ->where('status !=', 'paid') // Mengabaikan sesi dengan status 'paid'
                ->countAllResults();
    
            return $this->response->setJSON([
                'success' => true,
                'total_sessions' => $totalSessions,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getTotalSessions: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Silakan Coba Lagi.',
            ]);
        }
    }

    // Function untuk membatalkan sesi
public function cancelSession($trainerId, $memberId, $date, $sessionTime)
{
    try {
        $this->personalTrainingModel
            ->where('ID_PT', $trainerId)
            ->where('ID_Member', $memberId)
            ->where('date', $date)
            ->where('session_time', $sessionTime)
            ->where('status !=', 'paid') // Mengabaikan sesi dengan status 'paid'
            ->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Sesi berhasil dibatalkan.',
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error in cancelSession: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Terjadi kesalahan saat membatalkan sesi.',
        ]);
    }
}


public function getTrainerScheduleMonthly($trainerId, $month, $year)
{
    if (!$trainerId || !$month || !$year) {
        return $this->response->setStatusCode(400)->setJSON([
            'message' => 'Parameter tidak lengkap.',
        ]);
    }

    try {
        $jadwalPTModel = new JadwalPTModel();

        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $jadwal = $jadwalPTModel
            ->where('ID_PT', $trainerId)
            ->where('Tanggal >=', $startDate)
            ->where('Tanggal <=', $endDate)
            ->findAll();

        // Pastikan jadwal yang diambil sesuai dengan membership
        foreach ($jadwal as &$item) {
            $item['Status'] = 'tersedia';
        }

        log_message('debug', 'Jadwal dari database: ' . json_encode($jadwal));

        return $this->response->setJSON($jadwal);
    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'message' => 'Terjadi kesalahan pada server.',
            'error' => $e->getMessage(),
        ]);
    }
}

public function getTrainerScheduleDaily($trainerId, $date)
{
    try {
        log_message('debug', "Request jadwal harian: Trainer ID: {$trainerId}, Tanggal: {$date}");

        if (empty($trainerId) || empty($date)) {
            throw new \InvalidArgumentException("ID Trainer atau tanggal tidak valid.");
        }

        $jadwal = $this->jadwalModel
            ->select('Tanggal, Sesi1, Sesi2, Sesi3, Sesi4, Sesi5')
            ->where('ID_PT', $trainerId)
            ->where('Tanggal', $date)
            ->first();

        log_message('debug', 'Data jadwal dari database: ' . json_encode($jadwal));

        if (!$jadwal) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada jadwal untuk tanggal yang dipilih.',
            ]);
        }

        $bookedSessions = $this->personalTrainingModel
            ->where('ID_PT', $trainerId)
            ->where('date', $date)
            ->findAll();

        log_message('debug', 'Sesi yang sudah dibooking: ' . json_encode($bookedSessions));

        $bookedMap = [];
        foreach ($bookedSessions as $session) {
            $bookedMap[$session['session_time']] = true; // Gunakan format waktu sebagai key
        }

        $sessions = [];
        $timeMapping = [
            'Sesi1' => '07:00 - 09:00',
            'Sesi2' => '09:00 - 11:00',
            'Sesi3' => '11:00 - 13:00',
            'Sesi4' => '15:00 - 17:00',
            'Sesi5' => '19:00 - 21:00',
        ];

        foreach (['Sesi1', 'Sesi2', 'Sesi3', 'Sesi4', 'Sesi5'] as $key) {
            $time = $timeMapping[$key]; // Ambil waktu dari timeMapping
            if (!isset($bookedMap[$time]) && $jadwal[$key] === 'tersedia') {
                $sessions[] = [
                    'time' => $time,
                    'code' => $key,
                    'status' => 'tersedia',
                ];
            }
        }

        if (empty($sessions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sesi full di booking untuk tanggal ini.',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'sessions' => $sessions,
        ]);
    } catch (\InvalidArgumentException $e) {
        log_message('error', 'Input tidak valid di getTrainerScheduleDaily: ' . $e->getMessage());
        return $this->response->setStatusCode(400)->setJSON([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error di getTrainerScheduleDaily: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server. Periksa log untuk detail lebih lanjut.',
        ]);
    }
}


    
}
