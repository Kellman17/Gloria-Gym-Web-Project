<?php

namespace App\Controllers;

use App\Models\PersonalTrainingModel;
use App\Models\MemberModel;
use App\Models\MembershipModel;
use App\Models\MembershipRecordModel;
use App\Models\PersonalTrainerModel;
use App\Models\InstrukturModel; // Tambahkan model untuk instruktur
use App\Models\ClassModel;
use App\Models\TambahPTModel;
use App\Models\BookingClassModel;

class DashboardController extends BaseController
{
    protected $memberModel;
    protected $membershipModel;
    protected $membershipRecordModel;
    protected $personalTrainerModel;
    protected $instrukturModel;
    protected $classModel;
    protected $tambahptModel;
    protected $trainingModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->membershipModel = new MembershipModel();
        $this->membershipRecordModel = new MembershipRecordModel();
        $this->personalTrainerModel = new PersonalTrainerModel();
        $this->instrukturModel = new InstrukturModel(); 
        $this->classModel = new ClassModel();
        $this->tambahptModel = new TambahPTModel();
        $this->trainingModel = new PersonalTrainingModel();
        $this->bookingModel = new BookingClassModel();
    }

    public function index()
    {
        // Ambil data dari tabel `member`
        $members = $this->memberModel->orderBy('ID_Member', 'DESC')->findAll();

        // Ambil data dari tabel `membership`
        $memberships = $this->membershipModel->findAll();

        // Ambil data dari tabel `membership_record`
        $membershipRecords = $this->membershipRecordModel->where('Jenis_Membership','Bulanan_Gym')->orderBy('ID_Record', 'DESC')->findAll();

        // Ambil data dari tabel `membership_record`
        $membershipRecordsClass = $this->membershipRecordModel->where('Jenis_Membership','Bulanan_Class')->orderBy('ID_Record', 'DESC')->findAll();

        // Ambil data dari tabel `membership_record`
        $membershipRecordsHarian = $this->membershipRecordModel->where('Jenis_Membership','Harian')->orderBy('ID_Record', 'DESC')->findAll();

        // Cek dan update status membership_record bulanan gym jika tanggal berakhirnya adalah hari ini
        $today = date('Y-m-d'); // Tanggal hari ini
        foreach ($membershipRecords as &$record) {
            if ($record['Tgl_Berakhir'] <= $today && $record['Status'] !== 'Selesai') {
                // Update status di database
                $this->membershipRecordModel->update($record['ID_Record'], ['Status' => 'Selesai']);
                // Update status pada array data yang akan dikirim ke view
                $record['Status'] = 'Selesai';
            }
        }
        // Cek dan update status membership_record bulanan class jika tanggal berakhirnya adalah hari ini
        $today = date('Y-m-d'); // Tanggal hari ini
        foreach ($membershipRecordsClass as &$recordClass) {
            if ($recordClass['Tgl_Berakhir'] <= $today && $recordClass['Status'] !== 'Selesai') {
                // Update status di database
                $this->membershipRecordModel->update($recordClass['ID_Record'], ['Status' => 'Selesai']);
                // Update status pada array data yang akan dikirim ke view
                $recordClass['Status'] = 'Selesai';
            }
        }
        // Cek dan update status membership_record harian gym jika tanggal berakhirnya adalah hari ini
        $today = date('Y-m-d'); // Tanggal hari ini
        foreach ($membershipRecordsHarian as &$recordHarian) {
            if ($recordHarian['Tgl_Berakhir'] <= $today && $recordHarian['Status'] !== 'Selesai') {
                // Update status di database
                $this->membershipRecordModel->update($recordHarian['ID_Record'], ['Status' => 'Selesai']);
                // Update status pada array data yang akan dikirim ke view
                $recordHarian['Status'] = 'Selesai';
            }
        }

        
        // Ambil data dari tabel personal_trainer
        $personaltrainer = $this->personalTrainerModel->findAll();
        
        // Ambil data dari tabel instruktur
        $instrukturs = $this->instrukturModel->findAll();
        
        $class = $this->classModel->orderBy('ID_Class', 'ASC')->findAll();
        
        // Ambil data dari model TambahPTModel dengan join
        $tambah = $this->tambahptModel->orderBy('ID_Tambah_PT', 'DESC')->getAddonPTWithDetails();
        
        // Cek dan update status add on pt jika tanggal berakhirnya adalah hari ini
        $today = date('Y-m-d'); // Tanggal hari ini
        foreach ($tambah as &$tambahpt) {
            if ($tambahpt['Tgl_Berakhir'] <= $today && $tambahpt['StatusPT'] !== 'Selesai') {
                // Update status di database
                $this->tambahptModel->update($tambahpt['ID_Tambah_PT'], ['StatusPT' => 'Selesai']);
                // Update status pada array data yang akan dikirim ke view
                $tambahpt['StatusPT'] = 'Selesai';
            }
        }

        // Kirim data ke view
        return view('Dashboard', [
            'members' => $members,
            'memberships' => $memberships,
            'membershipRecords' => $membershipRecords,
            'membershipRecordsClass' => $membershipRecordsClass,
            'membershipRecordsHarian' => $membershipRecordsHarian,
            'trainers' => $personaltrainer,
            'instrukturs' => $instrukturs,
            'kelas' => $class,
            'tambahpt' => $tambah,
        ]);
    }

    public function createMember()
    {
        $memberModel = new MemberModel();
    
        // Ambil data dari form
        $data = [
            'Nama_Member' => $this->request->getPost('Nama_Member'),
            'NoHP' => $this->request->getPost('NoHP'),
            'Email' => $this->request->getPost('Email'),
            'Password' => password_hash($this->request->getPost('Password'), PASSWORD_BCRYPT),
        ];
    
        // Cek apakah nama sudah ada
        $existingName = $memberModel->where('Nama_Member', $data['Nama_Member'])->first();
        if ($existingName) {
            session()->setFlashdata('error_members', 'Nama Member sudah terdaftar.');
            return redirect()->to('/dashboard');
        }
    
        // Cek apakah email sudah ada
        $existingEmail = $memberModel->where('Email', $data['Email'])->first();
        if ($existingEmail) {
            session()->setFlashdata('error_members', 'Email sudah terdaftar.');
            return redirect()->to('/dashboard');
        }
    
        // Simpan ke database jika tidak ada duplikasi
        $memberModel->insert($data);
    
        // Menyimpan success flash message
        session()->setFlashdata('success_members', 'Member berhasil ditambahkan.');
    
        // Redirect kembali ke halaman dashboard
        return redirect()->to('/dashboard');
    }
    

    public function updateMember()
    {
        // Ambil ID_Member yang dikirim dari form
        $id = $this->request->getPost('ID_Member');
        
        // Pastikan ID_Member ada
        if (!$id) {
            session()->setFlashdata('error_members', 'id member tidak ada.');
            return redirect()->to('/dashboard');
        }
    
        // Ambil data lainnya yang dikirimkan
        $data = $this->request->getPost();
    
        // Hapus ID_Member dari data sebelum mengirimnya ke database
        unset($data['ID_Member']);
    
    
        // Validasi input sebelum update (contoh: pastikan nama dan email tidak kosong)
        if (empty($data['Nama_Member']) || empty($data['Email'])) {
            session()->setFlashdata('error_members', 'Nama dan Email harus diisi.');
            return redirect()->to('/dashboard');
        }
    
        // Update data member di database
        if ($this->memberModel->update($id, $data)) {
            session()->setFlashdata('success_members', 'Data member berhasil di update.');
            return redirect()->to('/dashboard');
        } else {
            session()->setFlashdata('error_members', 'gagal edit member.');
            return redirect()->to('/dashboard');
        }
    }
    
public function deleteMember($id)
{
    $memberModel = new MemberModel();
    $membershiprecordModel = new MembershipRecordModel(); // Atau model lain untuk tabel daftar_membership

    // Hapus data di tabel daftar_membership yang terkait dengan ID_Member
    $membershiprecordModel->where('ID_Member', $id)->delete();

    // Hapus data member
    $member = $memberModel->find($id);
    if (!$member) {
        return $this->response->setJSON(['success_members' => false, 'message' => 'ID member tidak ditemukan.']);
    }

    // Hapus data member
    $memberModel->delete($id);

    return $this->response->setJSON(['success' => true, 'message' => 'Member deleted']);

}


    public function createMembership()
    {
        $membershipModel = new MembershipModel();

        $data = [
            'Jenis_Membership' => $this->request->getPost('Jenis_Membership'),
            'Durasi' => $this->request->getPost('Durasi'),
            'Harga' => $this->request->getPost('Harga'),
        ];

        $membershipModel->insert($data);

        session()->setFlashdata('success_membership', 'Membership berhasil ditambahkan.');
        return redirect()->to('/dashboard');
    }
    public function editMembership()
    {
        $membershipModel = new MembershipModel();

        $data = [
            'Jenis_Membership' => $this->request->getPost('Jenis_Membership'),
            'Durasi' => $this->request->getPost('Durasi'),
            'Harga' => $this->request->getPost('Harga'),
        ];

        $id = $this->request->getPost('ID_Membership');
        $membershipModel->update($id, $data);


        session()->setFlashdata('success_membership', 'Membership berhasil diupdate.');
        return redirect()->to('/dashboard');
    }

    public function deleteMembership($id)
    {
        $membershipModel = new MembershipModel();
        // Cek apakah data ada di database
        $membership = $membershipModel->find($id);
        if (!$membership) {
            return $this->response->setJSON(['success_membership' => false, 'message' => 'Membership tidak ditemukan.']);
        }

        // Hapus data
        $membershipModel->delete($id);

        session()->setFlashdata('success_membership', 'Membership berhasil dihapus.');
        return redirect()->to('/dashboard'); // Kembali ke dashboard setelah delete
    }

    public function createTrainer()
    {

        // Validasi input
        if (!$this->validate([
            'Nama_PT' => 'required',
            'Email' => 'required|valid_email|is_unique[personal_trainer.Email]',
            'Password' => 'required',
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

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($this->request->getPost('Password'), PASSWORD_DEFAULT);


        // Simpan data ke database
        $model = new PersonalTrainerModel();
        $model->save([
            'Email' => $this->request->getPost('Email'),
            'Password' => $hashedPassword,
            'Nama_PT' => $this->request->getPost('Nama_PT'),
            'Foto_PT' => $newName,
            'Prestasi' => $this->request->getPost('Prestasi'),
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Harga_Sesi' => $this->request->getPost('Harga_Sesi')
        ]);

        session()->setFlashdata('success_trainer', 'Data Personal Trainer Berhasil Ditambahkan.');
        return redirect()->to('/dashboard'); 
    }
    
    public function updateTrainer()
    {
        $model = new PersonalTrainerModel();
    
        // Validasi input
        $validationRules = [
            'Nama_PT' => 'required',
            'Email' => 'required|valid_email',
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
        $file = $this->request->getFile('Foto_PT');
        $newName = $this->request->getPost('currentFoto'); // Gunakan nama file lama sebagai default
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName(); // Generate nama file baru
            $file->move(FCPATH . 'uploads/pt_photos', $newName); // Simpan file ke folder uploads
        }
    
        // Hash password jika diisi
        $hashedPassword = $this->request->getPost('Password') 
            ? password_hash($this->request->getPost('Password'), PASSWORD_DEFAULT) 
            : null;
    
        // Data yang akan diupdate
        $updateData = [
            'Nama_PT' => $this->request->getPost('Nama_PT'),
            'Email' => $this->request->getPost('Email'),
            'Foto_PT' => $newName, // Hanya simpan nama file, bukan URL
            'Prestasi' => $this->request->getPost('Prestasi'),
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Harga_Sesi' => $this->request->getPost('Harga_Sesi'),
        ];
    
        if ($hashedPassword) {
            $updateData['Password'] = $hashedPassword;
        }
    
        $model->update($this->request->getPost('ID_PT'), $updateData);
    
        session()->setFlashdata('success_trainer', 'Data Personal Trainer berhasil di update.');
        return redirect()->to('/dashboard');
    }
    
    

    public function deleteTrainer($id)
    {
        $trainerModel = new PersonalTrainerModel();
        // Cek apakah data ada di database
        $trainer = $trainerModel->find($id);
        if (!$trainer) {
            return $this->response->setJSON(['success_trainer' => false, 'message' => 'Trainer tidak ditemukan.']);
        }

        // Hapus data
        $trainerModel->delete($id);
        echo "<script>alert('Trainer berhasil dihapus.');</script>";

        session()->setFlashdata('success_trainer', 'Data Personal Trainer Berhasil Dihapus.');
        return redirect()->to('/dashboard');

    }
    public function getTrainerDetails($id_pt)
{
    $model = new PersonalTrainingModel(); // Sesuaikan nama model
    $data = $model->where('ID_PT', $id_pt)
    ->where('rating IS NOT NULL')
    ->where('review IS NOT NULL')->findAll();

    return $this->response->setJSON($data);
}

public function updateMembershipStatus() {
    $postData = $this->request->getJSON(true); // Mengambil data JSON dari request
    $idRecord = $postData['ID_Record'];
    $status = $postData['Status'];
    $reason = $postData['Reason']; // Ambil alasan dari data JSON

    // Model Membership
    $membershipModel = new MembershipRecordModel();
    
    // Update status dan alasan
    $update = $membershipModel->update($idRecord, [
        'Status' => $status,
        'Alasan' => $reason // Sesuaikan dengan nama kolom 'Alasan' di database
    ]);

    // Berikan respon JSON untuk mengetahui apakah proses berhasil
    if ($update) {
        
        // Tambahkan flashdata berdasarkan status
        if ($status === 'Aktif') {
            session()->setFlashdata('success_record', [
                'message' => 'Membership telah diaktifkan',
            ]);
        } elseif ($status === 'Non-Aktif') {

            // Ambil ID Member berdasarkan ID_Record
            $membershipData = $membershipModel->find($idRecord); // Ambil data membership berdasarkan ID_Record
            $idMember = $membershipData['ID_Member']; // ID Member
            
            // Jika member menggunakan PT (Pakai_PT = 'ya')
            if ($membershipData['Pakai_PT'] == 'ya') {
                // Model untuk tabel Personal Training
                $ptModel = new PersonalTrainingModel();
    
                // Hapus 8 sesi terakhir untuk member ini
                $ptModel->where('ID_Member', $idMember)
                        ->orderBy('ID_Sesi', 'desc') // Urutkan berdasarkan tanggal sesi
                        ->limit(8)
                        ->delete();
            }

            session()->setFlashdata('error_record', [
                'message' => 'Membership ditolak karena',
                'reason' => $reason,
            ]);
        }elseif ($status === 'Pending') {
            session()->setFlashdata('error_record', [
                'message' => 'Verifikasi Membership berhasil dibatalkan',
                'reason' => $reason,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
        
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Update failed']);
    }
}

public function updateMembershipStatusHarian() {
    $postData = $this->request->getJSON(true); // Mengambil data JSON dari request
    $idRecord = $postData['ID_Record'];
    $status = $postData['Status'];
    $reason = $postData['ReasonHarian']; // Ambil alasan dari data JSON

    // Model Membership
    $membershipModel = new MembershipRecordModel();
    
    // Update status dan alasan
    $update = $membershipModel->update($idRecord, [
        'Status' => $status,
        'Alasan' => $reason // Sesuaikan dengan nama kolom 'Alasan' di database
    ]);

    // Berikan respon JSON untuk mengetahui apakah proses berhasil
    if ($update) {
        // Tambahkan flashdata berdasarkan status
        if ($status === 'Aktif') {
            session()->setFlashdata('success_harian', [
                'message' => 'Membership telah diaktifkan.',
            ]);
        } elseif ($status === 'Non-Aktif') {
            session()->setFlashdata('error_harian', [
                'message' => 'Membership ditolak karena',
                'reason' => $reason,
            ]);
        } elseif ($status === 'Pending') {
            session()->setFlashdata('error_harian', [
                'message' => 'Verifikasi Membership berhasil dibatalkan',
                'reason' => $reason,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
        
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Update failed']);
    }
}

public function updateMembershipStatusClass() {
    $postData = $this->request->getJSON(true); // Mengambil data JSON dari request
    $idRecord = $postData['ID_Record'];
    $status = $postData['Status'];
    $reason = $postData['ReasonClass']; // Ambil alasan dari data JSON

    // Model Membership
    $membershipModel = new MembershipRecordModel();
    
    // Update status dan alasan
    $update = $membershipModel->update($idRecord, [
        'Status' => $status,
        'Alasan' => $reason // Sesuaikan dengan nama kolom 'Alasan' di database
    ]);

    // Berikan respon JSON untuk mengetahui apakah proses berhasil
    if ($update) {
        // Tambahkan flashdata berdasarkan status
        if ($status === 'Aktif') {
            session()->setFlashdata('success_kelas', 'Membership telah diaktifkan.');

        } elseif ($status === 'Non-Aktif') {
            session()->setFlashdata('error_kelas', [
                'message' => 'Membership ditolak karena',
                'reason' => $reason,
            ]);

        }elseif ($status === 'Pending') {
            session()->setFlashdata('error_kelas', [
                'message' => 'Verifikasi Membership berhasil dibatalkan',
                'reason' => $reason,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
        
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Update failed']);
    }
}



    public function updateStatusMembershipRecord()
    {
        $model = new MembershipRecordModel();

        // Tangkap ID Record yang dikirim dari form
        $ID_Record = $this->request->getPost('ID_Record');
        $Status = $this->request->getPost('Status');

        // Validasi input (optional)
        if (!$Status) {
            return redirect()->back()->with('error', 'Status tidak boleh kosong!');
        }

        // Update status membership record di database
        $data = [
            'Status' => $Status
        ];

        $updateStatus = $model->update($ID_Record, $data);

        if ($updateStatus) {
            // Berhasil update status
            session()->setFlashdata('success_record', 'Status membership berhasil diperbarui!');
        } else {
            // Gagal update status
            session()->setFlashdata('error_record', 'Gagal memperbarui status membership!');
        }

        return redirect()->to('/dashboard'); // Mengarahkan kembali ke halaman dashboard

    }

    // Fungsi untuk menambah instruktur
    public function createInstruktur()
    {
        // Validasi input
        if (!$this->validate([
            'Nama_Instruktur' => 'required',
            'Foto' => 'uploaded[Foto]|max_size[Foto,1024]|is_image[Foto]',
            'Spesialisasi' => 'required',
            'Status' => 'required|in_list[Aktif,Non-Aktif]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangkap file foto
        $file = $this->request->getFile('Foto');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName(); // Generate nama acak untuk file
            $file->move(FCPATH . 'uploads/instruktur_photos', $newName); // Pindahkan file ke folder uploads
        }

        // Simpan data ke database
        $this->instrukturModel->save([
            'Nama_Instruktur' => $this->request->getPost('Nama_Instruktur'),
            'Foto' => $newName,
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Status' => $this->request->getPost('Status'),
        ]);

        session()->setFlashdata('success_instruktur', 'Instruktur Berhasil Ditambahkan.');
        return redirect()->back();
    }

    // Fungsi untuk mengupdate instruktur
    public function updateInstruktur()
    {
        $model = new InstrukturModel();

        // Validasi input
        if (!$this->validate([
            'Nama_Instruktur' => 'required',
            'Spesialisasi' => 'required',
            'Status' => 'required|in_list[Aktif,Non-Aktif]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

       // Jika ada upload file foto baru
        if ($this->request->getFile('Foto')->isValid()) {
            $validationRules['Foto'] = 'uploaded[Foto]|max_size[Foto,1024]|is_image[Foto]';
        }

        // Tangkap file foto jika ada yang diupload
        $file = $this->request->getFile('Foto');
        $newName = $this->request->getPost('currentFotoIns'); // Gunakan nama file lama sebagai default
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/instruktur_photos', $newName);
        }

        // Update data instruktur di database
        $model->update($this->request->getPost('ID_Instruktur'), [
            'Nama_Instruktur' => $this->request->getPost('Nama_Instruktur'),
            'Foto' => $newName,
            'Spesialisasi' => $this->request->getPost('Spesialisasi'),
            'Status' => $this->request->getPost('Status'),
        ]);

        session()->setFlashdata('success_instruktur', 'Data Instruktur Berhasil Diperbarui.');
        return redirect()->to('/dashboard'); // Redirect ke halaman instruktur
    }


    // Fungsi untuk menghapus instruktur
    public function deleteInstruktur($id)
    {
        // Mengambil data instruktur untuk mendapatkan nama file foto
        $instrukturModel = new InstrukturModel();
        $instruktur= $instrukturModel->find($id);
        if (!$instruktur) {
            return $this->response->setJSON(['success_instruktur' => false, 'message' => 'Instruktur tidak ditemukan.']);
        }
        // Jika foto ada, hapus file foto dari server
        if ($instruktur['Foto']) {
            unlink(FCPATH . 'uploads/instruktur_photos/' . $instruktur['Foto']);
        }

        // Hapus data instruktur dari database
        $instrukturModel->delete($id);
        echo "<script>alert('Instruktur berhasil dihapus.');</script>";

        session()->setFlashdata('success_instruktur', 'Instruktur Berhasil Dihapus.');
        return redirect()->to('/dashboard'); // Redirect ke halaman instruktur
    }

    public function createClass()
    {
        // Validasi input
        if (!$this->validate([
            'Nama_Class' => 'required',
            'ID_Instruktur' => 'required',
            'Tanggal' => 'required|valid_date',
            'Jam' => 'required',
            'Kuota' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil ID_Instruktur yang dipilih
        $idInstruktur = $this->request->getPost('ID_Instruktur');

        // Ambil data Nama_Instruktur berdasarkan ID_Instruktur
        $instrukturModel = new InstrukturModel();
        $instruktur = $instrukturModel->find($idInstruktur);

        // Pastikan instruktur ditemukan
        if (!$instruktur) {
            return redirect()->back()->with('error', 'Instruktur tidak ditemukan!');
        }

        // Ambil Nama_Instruktur
        $namaInstruktur = $instruktur['Nama_Instruktur'];

        // Simpan data ke database
        $this->classModel->save([
            'Nama_Class' => $this->request->getPost('Nama_Class'),
            'ID_Instruktur' => $idInstruktur,
            'Nama_Instruktur' => $namaInstruktur,  // Menyimpan Nama_Instruktur
            'Tanggal' => $this->request->getPost('Tanggal'),
            'Jam' => $this->request->getPost('Jam'),
            'Kuota' => $this->request->getPost('Kuota'),
        ]);

        // Simpan state section di Flashdata
        session()->setFlashdata('activeSection', 'jadwal-class-section');


        return redirect()->to('/dashboard')->with('success_class', 'Jadwal kelas berhasil ditambahkan!');
    }

    public function getUnavailableTimes()
{
    $idInstruktur = $this->request->getGet('ID_Instruktur');
    $tanggal = $this->request->getGet('Tanggal');

    // Ambil daftar jam yang sudah digunakan
    $unavailableTimes = $this->classModel->where('ID_Instruktur', $idInstruktur)
        ->where('Tanggal', $tanggal)
        ->select('Jam')
        ->findAll();

    // Kembalikan daftar jam dalam format JSON
    return $this->response->setJSON(array_column($unavailableTimes, 'Jam'));
}


    public function updateClass()
    {
        // Validasi input
        if (!$this->validate([
            'Nama_Class' => 'required',
            'ID_Instruktur' => 'required',
            'Tanggal' => 'required|valid_date',
            'Jam' => 'required',
            'Kuota' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil ID_Instruktur yang dipilih
        $idInstruktur = $this->request->getPost('ID_Instruktur');

        // Ambil data Nama_Instruktur berdasarkan ID_Instruktur
        $instrukturModel = new InstrukturModel();
        $instruktur = $instrukturModel->find($idInstruktur);

        // Pastikan instruktur ditemukan
        if (!$instruktur) {
            return redirect()->back()->with('error', 'Instruktur tidak ditemukan!');
        }

        // Ambil Nama_Instruktur
        $namaInstruktur = $instruktur['Nama_Instruktur'];

        // Update data di database
        $idClass = $this->request->getPost('ID_Class');
        $this->classModel->update($idClass, [
            'Nama_Class' => $this->request->getPost('Nama_Class'),
            'ID_Instruktur' => $this->request->getPost('ID_Instruktur'),
            'Nama_Instruktur' => $namaInstruktur,
            'Tanggal' => $this->request->getPost('Tanggal'),
            'Jam' => $this->request->getPost('Jam'),
            'Kuota' => $this->request->getPost('Kuota'),
        ]);

        return redirect()->to('/dashboard')->with('success_class', 'Jadwal kelas berhasil diperbarui!');
    }

    public function getUnavailableTimesEdit()
{
    $idInstruktur = $this->request->getGet('ID_Instruktur');
    $tanggal = $this->request->getGet('Tanggal');
    $idClass = $this->request->getGet('ID_Class'); // ID kelas yang sedang diedit

    // Ambil daftar jam yang sudah digunakan oleh kelas lain
    $unavailableTimes = $this->classModel->where('ID_Instruktur', $idInstruktur)
        ->where('Tanggal', $tanggal)
        ->where('ID_Class !=', $idClass) // Kecualikan kelas yang sedang diedit
        ->select('Jam')
        ->findAll();

    // Kembalikan daftar jam dalam format JSON
    return $this->response->setJSON(array_column($unavailableTimes, 'Jam'));
}


    public function deleteClass($id)
    {
        $this->classModel->delete($id);
        return redirect()->to('/dashboard')->with('success_class', 'Jadwal kelas berhasil dihapus!');
    }

    public function getBookingMembers($classID)
{
    $bookingModel = new BookingClassModel();
    $memberModel = new MemberModel(); // Pastikan model member sudah ada

    // Ambil data booking berdasarkan ID_Class
    $bookings = $bookingModel->where('ID_Class', $classID)->findAll();

    if ($bookings) {
        $members = [];
        foreach ($bookings as $booking) {
            $member = $memberModel->find($booking['ID_Member']);
            $members[] = [
                'Foto_Member' => base_url('uploads/member/' . $member['Foto_Member']),
                'Nama_Member' => $member['Nama_Member'],
                'Tanggal_Booking' => $booking['Tanggal_Booking'],
            ];
        }
        return $this->response->setJSON(['success' => true, 'members' => $members]);
    }

    return $this->response->setJSON(['success' => false, 'message' => 'No members found.']);
}


    public function updateAddonStatus()
{
    $requestData = $this->request->getJSON(true);

    $idTambahPT = $requestData['ID_Tambah_PT'];
    $idRecord = $requestData['ID_Record'];
    $status = $requestData['Status'];
    $reason = $requestData['Reason'];

    $tambahPTModel = new TambahPTModel();
    $membershipRecordModel = new MembershipRecordModel();
    $personalTrainingModel = new PersonalTrainingModel();

    // Update status di tabel tambah PT
    $updateTambahPT = $tambahPTModel->update($idTambahPT, [
        'StatusPT' => $status,
        'Reason' => $reason,
    ]);

    if ($updateTambahPT) {
        // Jika status Aktif, perbarui status di Membership Record
        if ($status === 'Non-Aktif') {
            
            // Cari ID_Member berdasarkan ID_Record dari daftar_membership
            $membership = $membershipRecordModel->where('ID_Record', $idRecord)->first();
            if ($membership) {
                $idMember = $membership['ID_Member'];

                // Hapus 8 sesi terakhir untuk ID_Member
                $personalTrainingModel->where('ID_Member', $idMember)
                                      ->orderBy('ID_Sesi', 'DESC')
                                      ->limit(8)
                                      ->delete();
            }

            $membershipRecordModel->update($idRecord, [
                'Pakai_PT' => 'tidak', // Set kolom Pakai_PT menjadi tidak
                'Status' => 'Aktif',  // Pastikan membership tetap Aktif
            ]);
            session()->setFlashdata('error_addon', [
                'message' => 'Addon PT ditolak karena',
                'reason' => $reason,
            ]);
        } elseif ($status === 'Aktif') {
            // Jika Add-on PT diterima, set Membership Record ke Pakai PT = ya
            $membershipRecordModel->update($idRecord, [
                'Pakai_PT' => 'ya',
                'Status' => 'Aktif',
            ]);
            session()->setFlashdata('success_addon', 'Addon PT telah diaktifkan.');
        } elseif ($status === 'Pending') {
            // Jika status diubah ke Pending, kembalikan Membership Record ke Pending
            $membershipRecordModel->update($idRecord, ['Status' => 'Pending']);
            session()->setFlashdata('error_addon', 'Addon PT telah dibatalkan.');
        }

        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status']);
    }
}


    public function logout() {
        // Menghancurkan session
        session()->destroy();
        
        // Redirect ke halaman login
        return redirect()->to('/');
    }
    

}