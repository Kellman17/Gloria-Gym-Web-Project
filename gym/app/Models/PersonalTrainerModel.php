<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalTrainerModel extends Model
{
    protected $table = 'personal_trainer'; // Nama tabel
    protected $primaryKey = 'ID_PT'; // Primary key

    // Kolom-kolom yang diizinkan untuk diisi
    protected $allowedFields = [
        'Email', 'Password', 'Nama_PT', 'Foto_PT', 'Prestasi', 'Spesialisasi', 'Harga_Sesi', 'Rating', 'Reset_Token'
    ];

    // Jika kamu ingin menggunakan timestamps
    protected $useTimestamps = false;

    public function getUserByEmail($email) {
        return $this->where('Email', $email)->first();
    }
}
