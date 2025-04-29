<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model {
    protected $table = 'member'; // Nama tabel di database
    protected $primaryKey = 'ID_Member'; // Primary key
    protected $allowedFields = ['Nama_Member', 'Foto_Member', 'NoHP', 'Email', 'Password', 'Reset_Token']; // Kolom yang bisa diisi
 
    
    // Metode untuk mendapatkan pengguna berdasarkan email
    public function getUserByEmail($email) {
        return $this->where('Email', $email)->first();
    }
}
