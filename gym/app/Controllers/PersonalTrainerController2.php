<?php

namespace App\Controllers;

use App\Models\PersonalTrainerModel;
use App\Models\JadwalPTModel;
use CodeIgniter\Controller;

class PersonalTrainerController2 extends Controller
{
    public function index()
    {
        $model = new PersonalTrainerModel();
        $data['trainers'] = $model->findAll(); // Ambil semua data PT
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
            'Nama_PT' => $this->request->getPost('Nama_PT'),
            'Foto_PT' => $newName,
            'Prestasi' => $this->request->getPost('Prestasi'),
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Harga_Sesi' => $this->request->getPost('Harga_Sesi'),
        ]);

        return redirect()->to('/PT')->with('success', 'Data PT berhasil diupdate!');
    }

    public function saveJadwal()
    {
        $jadwalModel = new JadwalPTModel();
    
        // Ambil data dari request
        $trainerId = $this->request->getVar('trainer_id');
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

public function login()
{
    $Email = $this->request->getPost('Email');
    $Password = $this->request->getPost('Password');
    $model = new PersonalTrainerModel();

    // Cek jika email dan password cocok
    $trainer = $model->getTrainerByEmail($Email); // Ambil data trainer berdasarkan email

    if ($trainer && password_verify($Password, $trainer['Password'])) {
        $session = session();
        $session->set([
            'ID_PT' => $trainer['ID_PT'], // Menyimpan ID_PT ke dalam session
            'Nama_PT' => $trainer['Nama_PT'],
            'Foto_PT' => $trainer['Foto_PT'],
            'Spesialisasi' => $trainer['Spesialisasi'],
            // Data lain jika perlu
        ]);
        
        // Redirect ke dashboard trainer
        return redirect()->to('/dashboard');
    } else {
        return redirect()->to('/login')->with('error', 'Invalid email or password');
    }
}




}
?>