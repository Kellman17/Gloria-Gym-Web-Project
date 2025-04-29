<?php

namespace App\Controllers;

use App\Models\PersonalTrainerModel;
use App\Models\JadwalPTModel;
use App\Models\PersonalTrainingModel;

use CodeIgniter\Controller;

class PersonalTrainerController extends Controller
{   
    public function index()
    {
        $session = session();
        $trainerId = $session->get('ID_PT'); // Ambil ID_PT dari session
        // Jika session ID_PT tidak ada, redirect ke halaman login
        if (!$trainerId) {
            return redirect()->to('/loginpt');
        }
        $personalTrainerModel = new PersonalTrainerModel();
        $data['trainer'] = $personalTrainerModel->find($trainerId); // Ambil data PT yang sesuai dengan ID_PT

        // Ambil jadwal PT berdasarkan ID_PT
        $jadwalPTModel = new JadwalPTModel();
        $data['jadwal'] = $jadwalPTModel->where('ID_PT', $trainerId)->findAll(); // Ambil jadwal PT yang sesuai dengan ID_PT

        // Ambil data personal training yang sudah ada
        $personalTrainingModel = new PersonalTrainingModel();
        $data['training'] = $personalTrainingModel->where('ID_PT', $trainerId)->where('date >=', date('Y-m-d'))->orderBy('date', 'ASC')->findAll(); // Ambil data personal training yang sesuai dengan ID_PT

        $data['hari'] = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('PersonalTrainer', $data); // Tampilkan halaman dengan data PT
    }


    public function create()
    {
        return view('PT/create'); // Tampilkan form tambah PT
    }
    public function store()
    {
        // Validasi input
        if (!$this->validate([
            'Nama_PT' => 'required',
            'Foto_PT' => 'uploaded[Foto_PT]|max_size[Foto_PT,1024]|is_image[Foto_PT]',
            'Prestasi' => 'required',
            'Spesialisasi' => 'required',
            'Harga_Sesi' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangkap file foto
        $file = $this->request->getFile('Foto_PT');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName(); // Generate nama acak untuk file
            $file->move(FCPATH . 'uploads/pt_photos', $newName); // Pindahkan file ke folder uploads
        }

        // Simpan data ke database
        $model = new PersonalTrainerModel();
        $model->save([
            'Nama_PT' => $this->request->getPost('Nama_PT'),
            'Foto_PT' => $newName,
            'Prestasi' => $this->request->getPost('Prestasi'),
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Harga_Sesi' => $this->request->getPost('Harga_Sesi')
        ]);

        return redirect()->to('/PT')->with('success', 'Data PT berhasil disimpan!');
    }
    public function delete($id)
    {
        $model = new PersonalTrainerModel();
        $model->delete($id); // Hapus data PT berdasarkan ID

        return redirect()->to('/PT')->with('success', 'Data PT berhasil dihapus!');
    }
    public function update()
    {
        $model = new PersonalTrainerModel();

        // Validasi input
        $validationRules = [
            'Nama_PT' => 'required',
            'Password' => 'required',
            'Prestasi' => 'required',
            'Spesialisasi' => 'required',
            'Harga_Sesi' => 'required|numeric',
        ];

        // Jika ada upload file foto baru
        if ($this->request->getFile('Foto_PT')->isValid()) {
            $validationRules['Foto_PT'] = 'uploaded[Foto_PT]|max_size[Foto_PT,1024]|is_image[Foto_PT]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangkap file foto jika ada yang diupload
        $newName = $this->request->getPost('currentFoto'); // Default to current foto
        $file = $this->request->getFile('Foto_PT');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pt_photos', $newName);
        }

        // Update data PT
        $model->update($this->request->getPost('ID_PT'), [
            'Password' => $this->request->getPost('Password'),
            'Nama_PT' => $this->request->getPost('Nama_PT'),
            'Foto_PT' => $newName,
            'Prestasi' => $this->request->getPost('Prestasi'),
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Harga_Sesi' => $this->request->getPost('Harga_Sesi'),
        ]);

        return redirect()->to('/PT')->with('sukses', 'Data PT berhasil diupdate!');
    }
    public function saveJadwal()
    {
        $jadwalModel = new JadwalPTModel();
    
        // Ambil data dari request
        $session = session();
        $trainerId = $session->get('ID_PT');
        $date = $this->request->getVar('date');
        $namaPt = $this->request->getVar('nama_pt');
        $slots = $this->request->getVar('slots'); // Slots dalam bentuk array
    
        // Validasi data yang diperlukan
        if (!$trainerId || !$date || !$namaPt) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Trainer ID, tanggal, atau nama PT tidak ditemukan'
            ]);
        }
    
        // Inisialisasi data default untuk setiap sesi
        $data = [
            'ID_PT' => $trainerId,
            'Nama_PT' => $namaPt,
            'Tanggal' => $date,
            'Sesi1' => 'tidak tersedia',
            'Sesi2' => 'tidak tersedia',
            'Sesi3' => 'tidak tersedia',
            'Sesi4' => 'tidak tersedia',
            'Sesi5' => 'tidak tersedia'
        ];
    
        // Set status 'tersedia' berdasarkan checkbox yang dipilih
        if (is_array($slots) && !empty($slots)) {
            foreach ($slots as $slot) {
                if ($slot === 'Sesi1') $data['Sesi1'] = 'tersedia';
                if ($slot === 'Sesi2') $data['Sesi2'] = 'tersedia';
                if ($slot === 'Sesi3') $data['Sesi3'] = 'tersedia';
                if ($slot === 'Sesi4') $data['Sesi4'] = 'tersedia';
                if ($slot === 'Sesi5') $data['Sesi5'] = 'tersedia';
            }
        }
    
        // Cek apakah data sudah ada di database untuk PT dan tanggal yang sama
        $existing = $jadwalModel->where('ID_PT', $trainerId)
                                ->where('Tanggal', $date)
                                ->first();
    
        if ($existing) {
            // Update jadwal yang sudah ada
            $jadwalModel->update($existing['ID_Jadwal'], $data);
        } else {
            // Simpan data baru
            $jadwalModel->save($data);
        }
    
        // Respon JSON
        return $this->response->setJSON(['success' => true, 'message' => 'Jadwal berhasil disimpan!']);
    }
    public function getJadwal($trainerId, $date)
    {
        $jadwalModel = new JadwalPTModel();
        $jadwal = $jadwalModel->where('ID_PT', $trainerId)
                              ->where('Tanggal', $date)
                              ->first(); // Mengambil satu hasil saja
    
        if ($jadwal) {
            return $this->response->setJSON($jadwal);
        } else {
            return $this->response->setJSON([
                'ID_Jadwal' => null,
                'ID_PT' => $trainerId,
                'Nama_PT' => '',
                'Tanggal' => $date,
                'Sesi1' => 'tidak tersedia',
                'Sesi2' => 'tidak tersedia',
                'Sesi3' => 'tidak tersedia',
                'Sesi4' => 'tidak tersedia',
                'Sesi5' => 'tidak tersedia',
                'success' => true,
            ]);
        }
    }
    public function clearJadwal()
{
    $jadwalModel = new JadwalPTModel();

    $trainerId = $this->request->getVar('trainer_id');
    $date = $this->request->getVar('date');

    if (!$trainerId || !$date) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Trainer ID atau tanggal tidak ditemukan'
        ]);
    }

    // Hapus jadwal berdasarkan ID_PT dan Tanggal
    $deleted = $jadwalModel->where('ID_PT', $trainerId)
                           ->where('Tanggal', $date)
                           ->delete();

    if ($deleted) {
        return $this->response->setJSON(['success' => true, 'message' => 'Jadwal berhasil dihapus!']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus jadwal']);
    }
}
public function getTrainerSchedule($trainerId)
{
    $jadwalModel = new JadwalPTModel();
    $schedule = $jadwalModel->where('ID_PT', $trainerId)->findAll();

    // Kembalikan array kosong jika tidak ada jadwal
    if (!$schedule) {
        return $this->response->setJSON([]);
    }

    return $this->response->setJSON($schedule);
}

public function updateLatihan()
{
    $personalTrainingModel = new PersonalTrainingModel();

    // Debug: Periksa apakah data diterima
    log_message('debug', 'ID_Sesi: ' . $this->request->getVar('ID_Sesi'));
    log_message('debug', 'Latihan: ' . $this->request->getVar('Latihan'));

    $idSesi = $this->request->getVar('ID_Sesi');
    $latihan = $this->request->getVar('Latihan');

    if (!$idSesi || !$latihan) {
        log_message('error', 'Data tidak lengkap. ID_Sesi: ' . $idSesi . ', Latihan: ' . $latihan);
        return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
    }

    // Update Latihan
    $updated = $personalTrainingModel->update($idSesi, ['Latihan' => $latihan]);

    if ($updated) {
        log_message('debug', 'Update berhasil. ID_Sesi: ' . $idSesi);
        return $this->response->setJSON(['success' => true, 'message' => 'Latihan berhasil diperbarui']);
    } else {
        log_message('error', 'Gagal update. ID_Sesi: ' . $idSesi);
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui latihan']);
    }
}

public function confirmSession($id_sesi)
{
    $personalTrainingModel = new PersonalTrainingModel();

    // Update field Confirm menjadi 'done'
    $personalTrainingModel->update($id_sesi, ['Confirm' => 'done']);

    return redirect()->to('/PT')->with('sukses_selesai', 'Sesi telah selesai.');

}
public function requestReschedule()
{
    $model = new PersonalTrainingModel();

    // Ambil data dari form
    $idSesi = $this->request->getPost('ID_Sesi');
    $pesan = $this->request->getPost('pesan');

    // Update data sesi dengan status reschedule dan pesan
    $model->update($idSesi, [
        'Confirm' => 'request_reschedule',
        'Pesan' => $pesan
    ]);

    return redirect()->to('/PT')->with('sukses_reschedule', 'Permintaan reschedule berhasil dikirim.');
}

public function requestReschedule1()
{
    $model = new PersonalTrainingModel();

    // Ambil data dari form
    $idSesi = $this->request->getPost('ID_Sesi1');
    $pesan = $this->request->getPost('pesan1');

    // Update data sesi dengan status reschedule dan pesan
    $model->update($idSesi, [
        'Confirm' => 'request_reschedule',
        'Pesan' => $pesan
    ]);

    return redirect()->to('/PT')->with('sukses_reschedule', 'Permintaan reschedule berhasil dikirim.');
}

public function logoutpt()
    {
        // Menghancurkan session
        session()->destroy(); // Menghapus semua session yang ada

        // Redirect ke halaman login
        return $this->response->setJSON(['success' => true]);
    }

}
?>