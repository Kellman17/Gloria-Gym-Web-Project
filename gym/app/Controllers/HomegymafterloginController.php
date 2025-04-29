<?php

namespace App\Controllers;

use App\Models\MembershipModel;
use App\Models\PersonalTrainerModel;
use App\Models\PersonalTrainingModel;
use App\Models\JadwalPTModel;
use App\Models\MemberModel;
use App\Models\MembershipRecordModel;
use App\Models\ClassModel;
use App\Models\BookingClassModel;
use App\Models\InstrukturModel; // Tambahkan model untuk instruktur
use App\Models\TambahPTModel;


class HomegymafterloginController extends BaseController
{
    protected $personalTrainerModel;
    protected $memberModel;
    protected $jadwalModel;
    protected $personalTrainingModel;
    protected $recordModel;
    protected $classModel;
    protected $bookingModel;
    protected $instrukturModel;
    protected $addonptModel;




    public function __construct()
    {
        // Inisialisasi model
        $this->personalTrainerModel = new PersonalTrainerModel();
        $this->personalTrainingModel = new PersonalTrainingModel();
        $this->memberModel = new MemberModel();
        $this->jadwalModel = new JadwalPTModel();
        $this->recordModel = new MembershipRecordModel();
        $this->classModel = new ClassModel();
        $this->bookingModel = new BookingClassModel();
        $this->instrukturModel = new InstrukturModel(); 
        $this->addonptModel = new TambahPTModel(); 

    }

    protected function validateTrainerAndMember($trainerId, $memberId) {
        
        $trainer = $this->personalTrainerModel->find($trainerId);
        $member = $this->memberModel->find($memberId);
    
        if (!$trainer) {
            return ["message" => "Trainer tidak ditemukan.", "success" => false];
        }
    
        if (!$member) {
            return ["message" => "Member tidak ditemukan.", "success" => false];
        }
    
        return ["success" => true];
    }
    
    public function login()
    {
        // Ambil data dari model membership
        $membershipModel = new MembershipModel();
        $data['memberships'] = $membershipModel->findAll();  // Mengambil semua membership
    
        // Ambil data dari model membership record
        $recordModel = new MembershipRecordModel();
        $data['daftar_membership'] = $recordModel->findAll();  // Mengambil semua membership
    
        // Ambil data dari model member yang sedang login
        $memberId = session()->get('ID_Member');  // ID member dari session
        $member = $this->memberModel->find($memberId);

        // Cek membership yang pending dan aktif dalam satu query
        // Cek membership yang pending dan aktif dalam satu query
        $membershipStatuses = $recordModel
        ->where('ID_Member', $memberId)
        ->whereIn('Status', ['Pending', 'Aktif']) // Filter hanya yang pending atau aktif
        ->orderBy('Tgl_Berlaku', 'DESC') // Urutkan dari yang terbaru
        ->findAll();

        // Proses data membership statuses
        $data['membershipStatuses'] = []; // Default kosong
        if (!empty($membershipStatuses)) {
        foreach ($membershipStatuses as $status) {
            // Jangan ambil data dari membership default, fokus pada daftar_membership
            $status['Jenis_Membership'] = $status['Jenis_Membership'];
            $status['Durasi'] = $status['Tgl_Berlaku'] . " - " . $status['Tgl_Berakhir'];
            $status['Harga'] = $status['Harga']; // Harga dari daftar_membership
            $data['membershipStatuses'][] = $status;
        }
        }

        $membershipHistory = $recordModel
        ->where('ID_Member', $memberId)
        ->get()
        ->getResultArray();

        $data['membershipHistory'] = $membershipHistory;
        
        // --- FITUR SELECT DATE UNTUK KELAS ---
        $classModel = new ClassModel();  // Model untuk kelas
        
        // Kirim data kelas ke view
        $data['classes'] = $classModel->findAll();

        // Ambil data booking kelas untuk member ini
        $bookedClasses = $this->bookingModel->where('ID_Member', $memberId)->findAll();
        $data['bookedClasses'] = $bookedClasses;

        // Ambil data dari model personal trainer
        $trainerModel = new PersonalTrainerModel();
        $data['trainers'] = $trainerModel->findAll();  // Mengambil semua personal trainer
        
        // Ambil data dari model instruktur
        $instrukturModel = new InstrukturModel();
        $data['instrukturs'] = $instrukturModel->findAll();  // Mengambil semua personal trainer
        
        // Ambil jadwal PT jika ada
        $personalTrainingModel = new PersonalTrainingModel();
        $personalTraining = $personalTrainingModel
            ->where('ID_Member', $memberId)
            ->orderBy('ID_Sesi', 'DESC')
            ->limit(8) // Urutkan berdasarkan tanggal lebih dulu
            ->findAll();

        if (!empty($personalTraining)) {
            // Urutkan berdasarkan tanggal, lalu jam pertama dari session_time
            usort($personalTraining, function ($a, $b) {
                // Urutkan berdasarkan tanggal terlebih dahulu
                $dateA = strtotime($a['date']);
                $dateB = strtotime($b['date']);
                if ($dateA != $dateB) {
                    return $dateA - $dateB;
                }

                // Jika tanggal sama, urutkan berdasarkan jam awal dari session_time
                $timeA = strtotime(substr($a['session_time'], 0, 5)); // Ambil jam pertama, contoh: '07:00'
                $timeB = strtotime(substr($b['session_time'], 0, 5));

                return $timeA - $timeB;
            });

            $data['personalTraining'] = $personalTraining;
        } else {
            $data['personalTraining'] = []; // Kosongkan jika tidak ada data
        }

        $data['trainHistory'] = $personalTrainingModel->where('ID_Member', $memberId)
        ->where('status', 'paid')
         // Cek jika kolom Latihan tidak kosong
        ->where('review !=', '')  // Cek jika kolom Review tidak kosong
        ->where('rating !=', '')  // Cek jika kolom Rating tidak kosong
        ->orderBy('ID_Sesi', 'DESC')
        ->findAll();
    
        log_message('debug', 'PT History Data: ' . print_r($data['trainHistory'], true));  // Log data to confirm


        $historyClasses = $this->bookingModel
        ->select('booking_class.ID_Booking, booking_class.ID_Class, booking_class.ID_Member, jadwal_class.Tanggal, jadwal_class.Jam, booking_class.Status, jadwal_class.Nama_Class, jadwal_class.Nama_Instruktur')
        ->join('jadwal_class', 'jadwal_class.ID_Class = booking_class.ID_Class')
        ->where('booking_class.ID_Member', $memberId)  // Filter by member's ID
        ->findAll();
        $data['historyClasses'] = $historyClasses;

        // --- Tambahkan History Add-On PT ---
        $tambahPTModel = new TambahPTModel();
        $addOnPtStatuses = $tambahPTModel
            ->getAddonPTWithDetails(); // Menggunakan metode di model untuk join data

        // Filter hanya data untuk member yang sedang login
        $filteredAddOnPtStatuses = [];
        foreach ($addOnPtStatuses as $ptStatus) {
            if ($ptStatus['Nama_Member'] == $member['Nama_Member']) {
                $filteredAddOnPtStatuses[] = $ptStatus;
            }
        }
        

        $data['addOnPtStatuses'] = $filteredAddOnPtStatuses;

    
        return view('homegymafterlogin', $data);
    }
    



    public function PersonalTraining()
    {
        // Inisialisasi model
        $trainerModel = new PersonalTrainerModel();
        $jadwalModel = new JadwalPTModel();

        // Ambil data personal trainer
        $trainers = $trainerModel->findAll();

        // Ambil jadwal untuk setiap trainer
        $trainerSchedules = [];
        foreach ($trainers as $trainer) {
            $trainerSchedules[$trainer['ID_PT']] = $jadwalModel->where('ID_PT', $trainer['ID_PT'])
                                                                ->findAll();
        }

        // Kirim data ke view
        $data = [
            'trainers' => $trainers,
            'trainerSchedules' => $trainerSchedules
        ];

        return view('homegymafterlogin', $data);
    }


    public function submitReview() {
        $personalTraining = new PersonalTrainingModel();

        // Ambil data dari request POST
        $sessionId = $this->request->getPost('ID_Sesi');
        $idPT = $this->request->getPost('ID_PT');
        $namaPT = $this->request->getPost('Nama_PT');
        $idMember = $this->request->getPost('ID_Member');
        $namaMember = $this->request->getPost('Nama_Member');
        $date = $this->request->getPost('date');
        $sessionTime = $this->request->getPost('session_time');
        $status = $this->request->getPost('status');
        $rating = $this->request->getPost('rating');
        $review = $this->request->getPost('review');
        
        // Validasi data (bisa ditambahkan jika perlu)
        if (empty($rating) || empty($review)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Rating dan Review harus diisi']);
        }

        // Update atau simpan review di database
        $data = [

            'rating' => $rating,
            'review' => $review,
        ];

        // Simpan data ke database
        $result = $personalTraining->update($sessionId,$data);

        // Kembali ke halaman atau memberikan response sukses
        if ($result) {
            // Jika berhasil, kirimkan pesan sukses ke JavaScript
            $this->updateTrainerRating($sessionId, $idPT);
            echo "<script>alert('Review dan Rating berhasil diberikan!');</script>";
            return redirect()->to('/berhasillogin');

        } else {
            // Jika gagal, kirimkan pesan error
            echo "<script>alert('Gagal memberikan Review dan Rating. Silakan coba lagi.');</script>";
            return redirect()->to('/berhasillogin');

        }
    }
    
    private function updateTrainerRating($sessionId, $idPT)
    {
        $personalTrainingModel = new PersonalTrainingModel();
        $trainerModel = new PersonalTrainerModel();
    
        // Ambil ID PT dari sesi
        $session = $personalTrainingModel->find($sessionId);
    
        // Ambil semua sesi PT tersebut
        $sessions = $personalTrainingModel->where('ID_PT', $idPT)->findAll();
    
        // Hitung rata-rata rating
        $totalRating = 0;
        $count = 0;
    
        foreach ($sessions as $session) {
            if (!empty($session['rating'])) {
                $totalRating += $session['rating'];
                $count++;
            }
        }
    
        $averageRating = ($count > 0) ? ($totalRating / $count) : 0;
    
        // Update rating PT
        $trainerModel->update($idPT, ['Rating' => $averageRating]);
    }
    
    // Fungsi untuk menangani pemesanan kelas
    public function bookClass()
{
    // Ambil data member yang sedang login
    $memberId = session()->get('member_id'); // Asumsi member_id disimpan di session saat login
    $member = $this->memberModel->find($memberId);

    if (!$member) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Member tidak ditemukan.'
        ]);
    }

    // Ambil ID kelas yang dipilih dari request JSON
    $data = $this->request->getJSON();
    $classId = $data->class_id; // Mengambil class_id dari JSON yang dikirim
    $memberId = $data->member_id; // Mengambil member_id dari JSON

    $class = $this->classModel->find($classId);

    if (!$class) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Kelas tidak ditemukan.'
        ]);
    }

    // Cek apakah kuota kelas masih tersedia
    if ($class['Kuota'] <= 0) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Kuota kelas sudah penuh.'
        ]);
    }

    // Buat pemesanan kelas baru
    $dataBooking = [
        'ID_Class' => $classId,
        'ID_Member' => $memberId,
        'Tanggal_Booking' => date('Y-m-d H:i:s'), // Waktu pemesanan
    ];

    // Simpan pemesanan ke tabel booking_class
    $this->bookingModel->save($dataBooking);

    // Kurangi kuota kelas
    $this->classModel->update($classId, [
        'Kuota' => $class['Kuota'] - 1, // Mengurangi kuota
    ]);

    // Mengirimkan response sukses
    return $this->response->setJSON(['success' => true, 'message' => 'CLass Berhasil di Booking.']);
}

    public function getBookingDetails()
    {
        $classId = $this->request->getPost('class_id');
        $memberId = $this->request->getPost('member_id');
        
        // Cari booking yang sesuai dengan classId dan memberId
        $booking = $this->bookingModel->where('ID_Class', $classId)
                                    ->where('ID_Member', $memberId)
                                    ->first();
        
        if ($booking) {
            // Ambil data kelas untuk detail
            $class = $this->classModel->find($classId);
            $response = [
                'success' => true,
                'booking' => [
                    'Nama_Class' => $class['Nama_Class'],
                    'Nama_Instruktur' => $class['Nama_Instruktur'],
                    'Jam' => $class['Jam'],
                    'Tanggal_Pemesanan' => $booking['Tanggal_Pemesanan'],
                ]
            ];
        } else {
            $response = ['success' => false];
        }

        return $this->response->setJSON($response);
    }

    public function cancelBooking($classId)
{
    // Get logged-in member ID
    $memberId = session()->get('ID_Member');
    
    // Log the values of classId and memberId to verify they are correct
    log_message('debug', "Canceling booking for Class ID: $classId and Member ID: $memberId");

    // Check if the booking exists
    $booking = $this->bookingModel->where('ID_Class', $classId)
                                  ->where('ID_Member', $memberId)
                                  ->first();
    
    // Log the booking result to verify if it's found
    log_message('debug', 'Booking found: ' . print_r($booking, true));

    if (!$booking) {
        return $this->response->setJSON(['success' => false, 'message' => 'Booking tidak ditemukan.']);
    }

    // Proceed with deletion and update
    $this->bookingModel->delete($booking['ID_Booking']);

    // Update the class quota
    $class = $this->classModel->find($classId);
    $this->classModel->update($classId, [
        'Kuota' => $class['Kuota'] + 1
    ]);

    return $this->response->setJSON(['success' => true, 'message' => 'Booking berhasil dibatalkan.']);
}


    public function reschedulePT()
{
    $personalTrainingModel = new PersonalTrainingModel();

    // Ambil data dari form
    $idSesi = $this->request->getPost('ID_Sesi'); // ID sesi yang akan diubah
    $tanggalBaru = $this->request->getPost('tanggal');
    $sesiBaru = $this->request->getPost('sesi');

    // Validasi input
    if (empty($tanggalBaru) || empty($sesiBaru)) {
        echo "<script>
            alert('Tanggal dan sesi harus dipilih!');
            window.history.back();
        </script>";
        return redirect()->to('/berhasillogin');
    }

    // Update jadwal personal training
    $data = [
        'date' => $tanggalBaru,
        'session_time' => $sesiBaru,
    ];

    $update = $personalTrainingModel->update($idSesi, $data);
    if ($update) {
        echo "<script>
            alert('Jadwal berhasil direschedule!');
            window.location.href = '/berhasillogin';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mereschedule jadwal!');
            window.history.back();
        </script>";
    }

    return redirect()->to('/berhasillogin');
}

public function updateProfile()
{
    $session = session();

    // Ambil data dari form
    $id_member = $this->request->getPost('ID_Member1');
    $nama = $this->request->getPost('Nama_Member1');
    $email = $this->request->getPost('Email1');
    $noHP = $this->request->getPost('NoHP1');
    $foto = $this->request->getFile('Foto_Member1');

    // Load model
    $memberModel = new MemberModel();

    // Ambil data lama dari database
    $currentData = $memberModel->find($id_member);

    if (!$currentData) {
        return redirect()->back()->with('error', 'Data anggota tidak ditemukan.');
    }

    // Validasi input
    if (!$nama || !$email || !$noHP) {
        return redirect()->back()->with('error', 'Semua data harus diisi!');
    }

    // Siapkan data untuk diupdate
    $data = [
        'Nama_Member' => $nama,
        'Email' => $email,
        'NoHP' => $noHP
    ];

    // Validasi foto jika diunggah
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = $foto->getClientExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'Format foto tidak valid! Hanya jpg, jpeg, atau png yang diperbolehkan.');
        }

        // Simpan foto dengan nama unik
        $newName = $foto->getRandomName();
        $foto->move(FCPATH . 'uploads/member', $newName);

        // Masukkan path foto baru ke dalam data
        $data['Foto_Member'] = $newName;

        // Hapus foto lama jika ada
        if (!empty($currentData['Foto_Member'])) {
            $oldFilePath = FCPATH . 'uploads/member/' . $currentData['Foto_Member'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    } else {
        // Jika tidak ada foto baru yang diunggah, gunakan foto lama
        $data['Foto_Member'] = $currentData['Foto_Member'];
    }

    // Update data
    $update = $memberModel->update($id_member, $data);

    if ($update) {
        // Perbarui session
        $session->set([
            'Nama_Member' => $nama,
            'Email' => $email,
            'NoHP' => $noHP,
            'Foto_Member' => $data['Foto_Member']
        ]);

        return redirect()->to('/berhasillogin')->with('sukses_update', 'Profile berhasil di update.');
    } else {
        return redirect()->to('/berhasillogin')->with('gagal_update', 'Profile gagal di update.');
    }
}


    
}
